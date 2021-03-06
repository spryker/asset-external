<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalPersistenceFactory getFactory()
 */
class AssetExternalEntityManager extends AbstractEntityManager implements AssetExternalEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function saveAssetExternalAssetExternalWithAssetExternalStores(
        AssetExternalTransfer $assetExternalTransfer,
        array $storeTransfers
    ): AssetExternalTransfer {
        $assetExternalTransfer->requireAssetUuid()
            ->requireAssetName()
            ->requireAssetContent()
            ->requireCmsSlotKey()
            ->requireStores();

        $assetExternalEntity = $this->getFactory()
            ->createAssetExternalQuery()
            ->filterByAssetUuid($assetExternalTransfer->getAssetUuid())
            ->findOneOrCreate();

        $assetExternalEntity = $assetExternalEntity->setAssetUuid((string)$assetExternalTransfer->getAssetUuid())
            ->setAssetContent((string)$assetExternalTransfer->getAssetContent())
            ->setAssetName((string)$assetExternalTransfer->getAssetName())
            ->setCmsSlotKey((string)$assetExternalTransfer->getCmsSlotKey());

        $assetExternalEntity->save();

        $assetExternalTransfer->setIdAssetExternal($assetExternalEntity->getIdAssetExternal());

        return $this->saveAssetExternalStoreByAssetExternalTransfer($assetExternalTransfer, $storeTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function saveAssetExternal(AssetExternalTransfer $assetExternalTransfer): AssetExternalTransfer
    {
        $assetExternalTransfer->requireAssetUuid()
            ->requireAssetName()
            ->requireAssetContent()
            ->requireCmsSlotKey()
            ->requireStores();

        $assetExternalEntity = $this->getFactory()
            ->createAssetExternalQuery()
            ->filterByAssetUuid($assetExternalTransfer->getAssetUuid())
            ->findOneOrCreate();

        $assetExternalEntity = $assetExternalEntity
            ->setAssetUuid((string)$assetExternalTransfer->getAssetUuid())
            ->setAssetContent((string)$assetExternalTransfer->getAssetContent())
            ->setAssetName((string)$assetExternalTransfer->getAssetName())
            ->setCmsSlotKey((string)$assetExternalTransfer->getCmsSlotKey());

        $assetExternalEntity->save();

        $assetExternalTransfer->setIdAssetExternal($assetExternalEntity->getIdAssetExternal());

        return $assetExternalTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    protected function saveAssetExternalStoreByAssetExternalTransfer(
        AssetExternalTransfer $assetExternalTransfer,
        array $storeTransfers
    ): AssetExternalTransfer {
        $assetExternalTransfer->requireIdAssetExternal()->requireStores();

        $storeTransferIds = [];
        foreach ($storeTransfers as $storeTransfer) {
            $this->saveAssetExternalStore(
                (int)$assetExternalTransfer->getIdAssetExternal(),
                (int)$storeTransfer->getIdStore(),
            );

            $storeTransferIds[] = $storeTransfer->getIdStoreOrFail();
        }
        $this->deleteStoresNotInStoreIdList($storeTransferIds, $assetExternalTransfer->getIdAssetExternalOrFail());

        return $assetExternalTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    public function deleteAssetExternal(AssetExternalTransfer $assetExternalTransfer): void
    {
        $this->getFactory()
            ->createAssetExternalStoreQuery()
            ->findByFkAssetExternal($assetExternalTransfer->getIdAssetExternalOrFail())
            ->delete();

        $assetExternalEntity = $this->getFactory()
            ->createAssetExternalQuery()
            ->findOneByIdAssetExternal($assetExternalTransfer->getIdAssetExternalOrFail());

        if ($assetExternalEntity !== null) {
            $assetExternalEntity->delete();
        }
    }

    /**
     * @param int $fkAssetExternal
     * @param int $fkStore
     *
     * @return void
     */
    protected function saveAssetExternalStore(int $fkAssetExternal, int $fkStore): void
    {
        $assetExternalStoreEntity = $this->getFactory()
            ->createAssetExternalStoreQuery()
            ->filterByFkAssetExternal($fkAssetExternal)
            ->filterByFkStore($fkStore)
            ->findOneOrCreate();

        $assetExternalStoreEntity->setFkAssetExternal($fkAssetExternal)->setFkStore($fkStore);
        $assetExternalStoreEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     * @param array<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    public function deleteAssetExternalStores(AssetExternalTransfer $assetExternalTransfer, array $storeTransfers): void
    {
        $assetExternalTransfer->requireIdAssetExternal()->requireStores();

        $storeIds = [];
        foreach ($storeTransfers as $store) {
            $storeIds[] = $store->getIdStore();
        }

        $this->getFactory()->createAssetExternalStoreQuery()
            ->filterByFkAssetExternal($assetExternalTransfer->getIdAssetExternal())
            ->filterByFkStore_In($storeIds)
            ->find()
            ->delete();
    }

    /**
     * @param array<int> $storeTransferIds
     * @param int $idAssetExternal
     *
     * @return void
     */
    protected function deleteStoresNotInStoreIdList(array $storeTransferIds, int $idAssetExternal): void
    {
        $this->getFactory()->createAssetExternalStoreQuery()
            ->filterByFkAssetExternal($idAssetExternal)
            ->filterByFkStore($storeTransferIds, Criteria::NOT_IN)
            ->find()
            ->delete();
    }
}
