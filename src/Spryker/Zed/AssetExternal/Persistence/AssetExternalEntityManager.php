<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalPersistenceFactory getFactory()
 */
class AssetExternalEntityManager extends AbstractEntityManager implements AssetExternalEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function saveAssetExternalAssetExternalWithAssetExternalStore(
        AssetExternalTransfer $assetExternalTransfer
    ): AssetExternalTransfer {
        $assetExternalTransfer->requireAssetUuid()
            ->requireAssetName()
            ->requireAssetContent()
            ->requireIdCmsSlot()
            ->requireStoreNames();

        $assetExternalEntity = $this->getFactory()
            ->createAssetExternalQuery()
            ->filterByAssetUuid($assetExternalTransfer->getAssetUuid())
            ->findOneOrCreate();

        $assetExternalEntity = $assetExternalEntity->setAssetUuid((string)$assetExternalTransfer->getAssetUuid())
            ->setAssetContent((string)$assetExternalTransfer->getAssetContent())
            ->setAssetName((string)$assetExternalTransfer->getAssetName())
            ->setFkCmsSlot((int)$assetExternalTransfer->getIdCmsSlot());

        $assetExternalEntity->save();

        $assetExternalTransfer->setIdAssetExternal($assetExternalEntity->getIdAssetExternal());

        return $this->saveAssetExternalStoreByAssetExternalTransfer($assetExternalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function saveAssetExternalStoreByAssetExternalTransfer(
        AssetExternalTransfer $assetExternalTransfer
    ): AssetExternalTransfer {
        $assetExternalTransfer->requireIdAssetExternal()->requireStoreNames();

        $fkAssetExternal = (int)$assetExternalTransfer->getIdAssetExternal();

        foreach ($assetExternalTransfer->getStoreNames() as $storeName) {
            $this->saveAssetExternalStore($fkAssetExternal, $storeName);
        }

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

        $this->getFactory()
            ->createAssetExternalQuery()
            ->findOneByIdAssetExternal($assetExternalTransfer->getIdAssetExternalOrFail())
            ->delete();
    }

    /**
     * @param int $fkAssetExternal
     * @param string $storeName
     *
     * @return void
     */
    protected function saveAssetExternalStore(int $fkAssetExternal, string $storeName): void
    {
        $storeEntity = $this->getFactory()->createStoreQuery()->findOneByName($storeName);

        if ($storeEntity) {
            $assetExternalStoreEntity = $this->getFactory()
                ->createAssetExternalStoreQuery()
                ->filterByFkAssetExternal($fkAssetExternal)
                ->filterBySpyStore($storeEntity)
                ->findOneOrCreate();

            $assetExternalStoreEntity->setFkAssetExternal($fkAssetExternal)->setSpyStore($storeEntity);
            $assetExternalStoreEntity->save();
        }
    }
}
