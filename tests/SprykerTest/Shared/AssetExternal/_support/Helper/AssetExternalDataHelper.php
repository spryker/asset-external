<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AssetExternal\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery;
use Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManager;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AssetExternalDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param string $uuid
     * @param string $content
     * @param string $cmsSlotKey
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function haveAssetExternal(string $uuid, string $content, string $cmsSlotKey, string $name): AssetExternalTransfer
    {
        $assetExternalEntity = SpyAssetExternalQuery::create()
            ->filterByAssetUuid($uuid)
            ->findOneOrCreate();

        if ($assetExternalEntity->isNew()) {
            $assetExternalEntity = (new SpyAssetExternal())
                ->setCmsSlotKey($cmsSlotKey)
                ->setAssetContent($content)
                ->setAssetUuid($uuid)
                ->setAssetName($name);

            $assetExternalEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($assetExternalEntity): void {
            (new SpyAssetExternalQuery())
                ->findByIdAssetExternal($assetExternalEntity->getIdAssetExternal())
                ->delete();
        });

        $assetExternalTransfer = (new AssetExternalTransfer())->fromArray($assetExternalEntity->toArray(), true);
        $assetExternalTransfer->setCmsSlotKey($assetExternalEntity->getCmsSlotKey());

        return $assetExternalTransfer;
    }

    /**
     * @param int $idAssetExternal
     * @param int $idStore
     *
     * @return void
     */
    public function haveAssetExternalStoreRelation(int $idAssetExternal, int $idStore): void
    {
        $assetExternalStoreEntity = SpyAssetExternalStoreQuery::create()
            ->filterByFkAssetExternal($idAssetExternal)
            ->filterByFkStore($idStore)
            ->findOneOrCreate();

        if ($assetExternalStoreEntity->isNew()) {
            $assetExternalStoreEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($assetExternalStoreEntity): void {
            (new SpyAssetExternalStoreQuery())
                ->findByIdAssetExternalStore($assetExternalStoreEntity->getIdAssetExternalStore())
                ->delete();
        });
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return void
     */
    public function deleteAssetExternal(AssetExternalTransfer $assetExternalTransfer): void
    {
        $assetExternalEntity = (new AssetExternalEntityManager())->deleteAssetExternal($assetExternalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAssetExternal(AssetExternalTransfer $assetExternalTransfer): AssetExternalTransfer
    {
        $assetExternalEntity = (new AssetExternalEntityManager())->saveAssetExternal($assetExternalTransfer);

        return (new AssetExternalTransfer())->fromArray($assetExternalEntity->toArray());
    }
}
