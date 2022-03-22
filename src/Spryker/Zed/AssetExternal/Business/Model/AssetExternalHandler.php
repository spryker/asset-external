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
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreReferenceInterface;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface;

class AssetExternalHandler implements AssetExternalHandlerInterface
{
    /**
     * @var int
     */
    protected const EXPECTED_CMS_SLOT_COUNT_WITH_REQUESTED_KEY = 1;

    /**
     * @var \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @var \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface
     */
    protected $assetExternalRepository;

    /**
     * @var \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface
     */
    protected $assetExternalEntityManager;

    /**
     * @var \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreReferenceInterface
     */
    protected $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface $assetExternalEntityManager
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface $assetExternalRepository
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreReferenceInterface $storeReferenceFacade
     */
    public function __construct(
        AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade,
        AssetExternalEntityManagerInterface $assetExternalEntityManager,
        AssetExternalRepositoryInterface $assetExternalRepository,
        AssetExternalToStoreReferenceInterface $storeReferenceFacade
    ) {
        $this->cmsSlotFacade = $cmsSlotFacade;
        $this->assetExternalRepository = $assetExternalRepository;
        $this->assetExternalEntityManager = $assetExternalEntityManager;
        $this->storeReferenceFacade = $storeReferenceFacade;
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
            ->requireSlotKey();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($assetAddedMessageTransfer->getStoreReferenceOrFail());
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
            ->setCmsSlotKey($assetAddedMessageTransfer->getSlotKey());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, [$storeTransfer]);
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
            ->requireSlotKey();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($assetUpdatedMessageTransfer->getStoreReferenceOrFail());
        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetUpdatedMessageTransfer->getScriptUuid());

        if ($assetExternalTransfer === null) {
            throw new InvalidAssetExternalException('This asset doesn\'t exist in DB.');
        }

        $this->validateCmsSlot((string)$assetUpdatedMessageTransfer->getSlotKey());

        $assetExternalTransfer
            ->setAssetContent($assetUpdatedMessageTransfer->getScriptView())
            ->setCmsSlotKey($assetUpdatedMessageTransfer->getSlotKey());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, [$storeTransfer]);
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
            ->requireScriptUuid();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($assetDeletedMessageTransfer->getStoreReferenceOrFail());
        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetDeletedMessageTransfer->getScriptUuid());

        if (!$assetExternalTransfer) {
            return;
        }

        $this->assetExternalEntityManager->deleteAssetExternal($assetExternalTransfer);
        $this->assetExternalEntityManager->deleteAssetExternalStores(
            $assetExternalTransfer,
            [$storeTransfer],
        );
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
}
