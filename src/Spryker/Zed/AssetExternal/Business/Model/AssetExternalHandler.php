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
use Spryker\Zed\AssetExternal\AssetExternalConfig;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidTenantUuidException;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface;

class AssetExternalHandler implements AssetExternalHandlerInterface
{
    /**
     * @var string
     */
    protected $currentTenantUuid;

    /**
     * @var \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface
     */
    protected $assetExternalRepository;

    /**
     * @var \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface
     */
    protected $assetExternalEntityManager;

    /**
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface $assetExternalEntityManager
     * @param \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface $assetExternalRepository
     * @param \Spryker\Zed\AssetExternal\AssetExternalConfig $config
     */
    public function __construct(
        AssetExternalEntityManagerInterface $assetExternalEntityManager,
        AssetExternalRepositoryInterface $assetExternalRepository,
        AssetExternalConfig $config
    ) {
        $this->currentTenantUuid = $config->getCurrentTenantUuid();
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
        $assetAddedMessageTransfer->requireAppUuid()
            ->requireAssetContent()
            ->requireAssetName()
            ->requireAssetUuid()
            ->requireSlotKey()
            ->requireStores()
            ->requireTenantUuid();

        $this->validateTenant($assetAddedMessageTransfer->getTenantUuid());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetAddedMessageTransfer->getAssetUuid());

        if ($assetExternalTransfer !== null) {
            throw new InvalidAssetExternalException('This asset already exist in DB.');
        }

        $idCmsSlot = $this->getIdCmsSlotByKey((string)$assetAddedMessageTransfer->getSlotKey());

        $assetExternalTransfer = (new AssetExternalTransfer())->setAssetUuid($assetAddedMessageTransfer->getAssetUuid())
            ->setAssetContent($assetAddedMessageTransfer->getAssetContent())
            ->setAssetName($assetAddedMessageTransfer->getAssetName())
            ->setIdCmsSlot($idCmsSlot)
            ->setStoreNames($assetAddedMessageTransfer->getStores());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStore($assetExternalTransfer);
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
        $assetUpdatedMessageTransfer->requireAppUuid()
            ->requireAssetContent()
            ->requireAssetUuid()
            ->requireSlotKey()
            ->requireStores()
            ->requireTenantUuid();

        $this->validateTenant($assetUpdatedMessageTransfer->getTenantUuid());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetUpdatedMessageTransfer->getAssetUuid());

        if ($assetExternalTransfer === null) {
            throw new InvalidAssetExternalException('This asset not exist in DB.');
        }

        $idCmsSlot = $this->getIdCmsSlotByKey((string)$assetUpdatedMessageTransfer->getSlotKey());

        $assetExternalTransfer->setAssetContent($assetUpdatedMessageTransfer->getAssetContent())
            ->setIdCmsSlot($idCmsSlot)
            ->setStoreNames($assetUpdatedMessageTransfer->getStores());

        return $this->assetExternalEntityManager
            ->saveAssetExternalAssetExternalWithAssetExternalStore($assetExternalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedMessageTransfer $assetDeletedMessageTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedMessageTransfer $assetDeletedMessageTransfer): void
    {
        $assetDeletedMessageTransfer->requireAppUuid()
            ->requireAssetUuid()
            ->requireStores()
            ->requireTenantUuid();

        $this->validateTenant($assetDeletedMessageTransfer->getTenantUuid());

        $assetExternalTransfer = $this->assetExternalRepository
            ->findAssetExternalByAssetUuid((string)$assetDeletedMessageTransfer->getAssetUuid());

        if ($assetExternalTransfer) {
            $this->assetExternalEntityManager->deleteAssetExternal($assetExternalTransfer);
        }
    }

    /**
     * @param string|null $tenantUuid
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidTenantUuidException
     *
     * @return void
     */
    protected function validateTenant(?string $tenantUuid): void
    {
        if (empty($this->currentTenantUuid) || $tenantUuid !== $this->currentTenantUuid) {
            throw new InvalidTenantUuidException('Invalid tenant UUID.');
        }
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException
     *
     * @return int
     */
    protected function getIdCmsSlotByKey(string $key): int
    {
        $cmsSlotKeyId = $this->assetExternalRepository
            ->getIdCmsSlotByKey($key);

        if ($cmsSlotKeyId === null) {
            throw new InvalidAssetExternalException('This asset has invalid cms slot key.');
        }

        return $cmsSlotKeyId;
    }
}
