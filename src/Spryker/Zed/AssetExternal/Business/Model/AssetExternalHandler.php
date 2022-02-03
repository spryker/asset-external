<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business\Model;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
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
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetExternalTransfer
    {
        $assetAddedTransfer->getMessageAttributesOrFail()
            ->requireAppIdentifier()
            ->requireTenantIdentifier();

        $assetAddedTransfer
            ->requireAssetView()
            ->requireAssetName()
            ->requireAssetIdentifier()
            ->requireSlotKey()
            ->requireStores();

        $this->validateTenant($assetAddedTransfer->getMessageAttributes()->getTenantIdentifier());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetAddedTransfer->getAssetIdentifier());

        if ($assetExternalTransfer !== null) {
            throw new InvalidAssetExternalException('This asset already exists in DB.');
        }

        $this->validateCmsSlot((string)$assetAddedTransfer->getSlotKey());

        $assetExternalTransfer = (new AssetExternalTransfer())
            ->setAssetUuid($assetAddedTransfer->getAssetIdentifier())
            ->setAssetContent($assetAddedTransfer->getAssetView())
            ->setAssetName($assetAddedTransfer->getAssetName())
            ->setCmsSlotKey($assetAddedTransfer->getSlotKey())
            ->setStores($assetAddedTransfer->getStores());

        $storeTransfers = $this->getStoreTransfersByStoreNames($assetExternalTransfer->getStores());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ScriptUpdatedTransfer $scriptUpdatedTransfer
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetExternalTransfer
    {
        $assetUpdatedTransfer->getMessageAttributesOrFail()
            ->requireAppIdentifier()
            ->requireTenantIdentifier();

        $assetUpdatedTransfer
            ->requireAssetView()
            ->requireAssetIdentifier()
            ->requireSlotKey()
            ->requireStores();

        $this->validateTenant($assetUpdatedTransfer->getMessageAttributesOrFail()->getTenantIdentifier());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetUpdatedTransfer->getAssetIdentifier());

        if ($assetExternalTransfer === null) {
            throw new InvalidAssetExternalException('This asset doesn\'t exist in DB.');
        }

        $this->validateCmsSlot((string)$assetUpdatedTransfer->getSlotKey());

        $assetExternalTransfer
            ->setAssetContent($assetUpdatedTransfer->getAssetView())
            ->setCmsSlotKey($assetUpdatedTransfer->getSlotKey())
            ->setStores($assetUpdatedTransfer->getStores());

        $storeTransfers = $this->getStoreTransfersByStoreNames($assetExternalTransfer->getStores());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $assetDeletedTransfer->getMessageAttributesOrFail()
            ->requireAppIdentifier()
            ->requireTenantIdentifier();

        $assetDeletedTransfer
            ->requireAssetIdentifier()
            ->requireStores();

        $this->validateTenant($assetDeletedTransfer->getMessageAttributesOrFail()->getTenantIdentifier());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetDeletedTransfer->getAssetIdentifier());

        if (!$assetExternalTransfer) {
            return;
        }

        if (empty($assetDeletedTransfer->getStores())) {
            $this->assetExternalEntityManager->deleteAssetExternal($assetExternalTransfer);

            return;
        }

        $this->assetExternalEntityManager->deleteAssetExternalStores(
            $assetExternalTransfer,
            $this->getStoreTransfersByStoreNames($assetDeletedTransfer->getStores()),
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
        if (!$this->currentTenantIdentifier || $tenantId !== $this->currentTenantIdentifier) {
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
