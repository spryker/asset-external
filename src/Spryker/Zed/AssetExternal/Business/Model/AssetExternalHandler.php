<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business\Model;

use Generated\Shared\Transfer\AssetAddedMessageTransfer;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;
use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;
use Spryker\Zed\AssetExternal\AssetExternalConfig;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidTenantIdentifierException;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridgeInterface;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface;

class AssetExternalHandler implements AssetExternalHandlerInterface
{
    /**
     * @var int
     */
    protected const EXPECTED_CMS_SLOT_COUNT_WITH_REQUESTED_KEY = 1;

    /**
     * @var \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridgeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @var string
     */
    protected $currentTenantIdentifier;

    /**
     * @var \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface
     */
    protected $assetExternalRepository;

    /**
     * @var \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface
     */
    protected $assetExternalEntityManager;

    /**
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridgeInterface $storeFacade
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface $assetExternalEntityManager
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface $assetExternalRepository
     * @param \Spryker\Zed\AssetExternal\AssetExternalConfig $config
     */
    public function __construct(
        AssetExternalToStoreBridgeInterface $storeFacade,
        AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade,
        AssetExternalEntityManagerInterface $assetExternalEntityManager,
        AssetExternalRepositoryInterface $assetExternalRepository,
        AssetExternalConfig $config
    ) {
        $this->storeFacade = $storeFacade;
        $this->cmsSlotFacade = $cmsSlotFacade;
        $this->currentTenantIdentifier = $config->getCurrentTenantIdentifier();
        $this->assetExternalRepository = $assetExternalRepository;
        $this->assetExternalEntityManager = $assetExternalEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetAddedMessageTransfer $assetAddedMessageTransfer
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(AssetAddedMessageTransfer $assetAddedMessageTransfer): AssetExternalTransfer
    {
        $assetAddedMessageTransfer
            ->requireAppId()
            ->requireScriptView()
            ->requireScriptName()
            ->requireScriptUuid()
            ->requireSlotKey()
            ->requireStores()
            ->requireTenantId();

        $this->validateTenant($assetAddedMessageTransfer->getTenantId());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetAddedMessageTransfer->getScriptUuid());

        if ($assetExternalTransfer !== null) {
            throw new InvalidAssetExternalException('This asset already exists in DB.');
        }

        $this->validateCmsSlot((string)$assetAddedMessageTransfer->getSlotKey());

        $assetExternalTransfer = (new AssetExternalTransfer())
            ->setAssetUuid($assetAddedMessageTransfer->getScriptUuid())
            ->setAssetContent($assetAddedMessageTransfer->getScriptView())
            ->setAssetName($assetAddedMessageTransfer->getScriptName())
            ->setCmsSlotKey($assetAddedMessageTransfer->getSlotKey())
            ->setStores($assetAddedMessageTransfer->getStores());

        $storeTransfers = $this->getStoreTransfersByStoreNames($assetExternalTransfer->getStores());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedMessageTransfer $assetUpdatedMessageTransfer
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedMessageTransfer $assetUpdatedMessageTransfer): AssetExternalTransfer
    {
        $assetUpdatedMessageTransfer
            ->requireAppId()
            ->requireScriptView()
            ->requireScriptUuid()
            ->requireSlotKey()
            ->requireStores()
            ->requireTenantId();

        $this->validateTenant($assetUpdatedMessageTransfer->getTenantId());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetUpdatedMessageTransfer->getScriptUuid());

        if ($assetExternalTransfer === null) {
            throw new InvalidAssetExternalException('This asset doesn\'t exist in DB.');
        }

        $this->validateCmsSlot((string)$assetUpdatedMessageTransfer->getSlotKey());

        $assetExternalTransfer
            ->setAssetContent($assetUpdatedMessageTransfer->getScriptView())
            ->setCmsSlotKey($assetUpdatedMessageTransfer->getSlotKey())
            ->setStores($assetUpdatedMessageTransfer->getStores());

        $storeTransfers = $this->getStoreTransfersByStoreNames($assetExternalTransfer->getStores());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedMessageTransfer $assetDeletedMessageTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedMessageTransfer $assetDeletedMessageTransfer): void
    {
        $assetDeletedMessageTransfer
            ->requireAppId()
            ->requireScriptUuid()
            ->requireStores()
            ->requireTenantId();

        $this->validateTenant($assetDeletedMessageTransfer->getTenantId());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetDeletedMessageTransfer->getScriptUuid());

        if (!$assetExternalTransfer) {
            return;
        }

        if (empty($assetDeletedMessageTransfer->getStores())) {
            $this->assetExternalEntityManager->deleteAssetExternal($assetExternalTransfer);

            return;
        }

        $this->assetExternalEntityManager->deleteAssetExternalStores(
            $assetExternalTransfer,
            $this->getStoreTransfersByStoreNames($assetDeletedMessageTransfer->getStores()),
        );
    }

    /**
     * @param string|null $tenantId
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidTenantIdentifierException
     *
     * @return void
     */
    protected function validateTenant(?string $tenantId): void
    {
        if (empty($this->currentTenantIdentifier) || $tenantId !== $this->currentTenantIdentifier) {
            throw new InvalidTenantIdentifierException('Invalid tenant identifier.');
        }
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return void
     */
    protected function validateCmsSlot(string $key): void
    {
        $cmsSlotTransfers = $this->cmsSlotFacade->getCmsSlotsByCriteria(
            (new CmsSlotCriteriaTransfer())->addKey($key),
        );

        if (count($cmsSlotTransfers) != static::EXPECTED_CMS_SLOT_COUNT_WITH_REQUESTED_KEY) {
            throw new InvalidAssetExternalException(
                'This asset has invalid cms slot key.',
            );
        }
    }

    /**
     * @param array<string> $storeNames
     *
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    protected function getStoreTransfersByStoreNames(array $storeNames): array
    {
        return $this->storeFacade->getStoreTransfersByStoreNames($storeNames);
    }
}
