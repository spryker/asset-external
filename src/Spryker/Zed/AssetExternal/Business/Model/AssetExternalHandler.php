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
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\AssetExternal\Business\Mapper\AssetExternalMapperInterface;
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
     * @var \Spryker\Zed\AssetExternal\Business\Mapper\AssetExternalMapperInterface
     */
    protected $assetExternalMapper;

    /**
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridgeInterface $storeFacade
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface $assetExternalEntityManager
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface $assetExternalRepository
     * @param \Spryker\Zed\AssetExternal\AssetExternalConfig $config
     * @param \Spryker\Zed\AssetExternal\Business\Mapper\AssetExternalMapperInterface $assetExternalMapper
     * @var \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreReferenceInterface
     */
    protected $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface $assetExternalEntityManager
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface $assetExternalRepository
     * @param \Spryker\Zed\AssetExternal\Business\Mapper\AssetExternalMapperInterface $assetExternalMapper
     * @param \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreReferenceInterface $storeReferenceFacade
     */
    public function __construct(
        AssetExternalToCmsSlotFacadeBridgeInterface $cmsSlotFacade,
        AssetExternalEntityManagerInterface $assetExternalEntityManager,
        AssetExternalRepositoryInterface $assetExternalRepository,
        AssetExternalMapperInterface $assetExternalMapper,
        AssetExternalToStoreReferenceInterface $storeReferenceFacade
    ) {
        $this->cmsSlotFacade = $cmsSlotFacade;
        $this->assetExternalEntityManager = $assetExternalEntityManager;
        $this->assetExternalRepository = $assetExternalRepository;
        $this->assetExternalMapper = $assetExternalMapper;
        $this->storeReferenceFacade = $storeReferenceFacade;
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
        $messageAttributes = $assetAddedTransfer->getMessageAttributesOrFail();

        $assetAddedTransfer
            ->requireAssetView()
            ->requireAssetName()
            ->requireAssetIdentifier()
            ->requireSlotKey();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());
        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetAddedTransfer->getAssetIdentifier());

        if ($assetExternalTransfer !== null) {
            throw new InvalidAssetExternalException('This asset already exists in DB.');
        }

        $this->validateCmsSlot((string)$assetAddedTransfer->getSlotKey());

        $assetExternalTransfer = $this->assetExternalMapper->mapAssetAddedTransferToAssetExternalTransfer(
            $assetAddedTransfer,
            new AssetExternalTransfer(),
        );

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, [$storeTransfer]);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetExternalTransfer
    {
        $messageAttributes = $assetUpdatedTransfer->getMessageAttributesOrFail();

        $assetUpdatedTransfer
            ->requireAssetView()
            ->requireAssetIdentifier()
            ->requireSlotKey();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());
        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetUpdatedTransfer->getAssetIdentifier());

        if ($assetExternalTransfer === null) {
            throw new InvalidAssetExternalException('This asset doesn\'t exist in DB.');
        }

        $this->validateCmsSlot((string)$assetUpdatedTransfer->getSlotKey());

        $assetExternalTransfer
            ->setAssetContent($assetUpdatedTransfer->getAssetView())
            ->setCmsSlotKey($assetUpdatedTransfer->getSlotKey());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStores($assetExternalTransfer, [$storeTransfer]);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $messageAttributes = $assetDeletedTransfer->getMessageAttributesOrFail();

        $assetDeletedTransfer
            ->requireAssetIdentifier();

        $storeTransfer = $this->storeReferenceFacade->getStoreByStoreReference($messageAttributes->getStoreReferenceOrFail());
        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetDeletedTransfer->getAssetIdentifier());

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
