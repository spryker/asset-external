<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AssetExternal\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AssetExternalBuilder;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class AssetExternalDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function haveAssetExternalTransfer(array $seed = []): AssetExternalTransfer
    {
        return (new AssetExternalBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function haveAssetExternal(array $seed = []): AssetExternalTransfer
    {
        $assetExternalTransfer = $this->haveAssetExternalTransfer($seed);

        $assetExternalEntity = SpyAssetExternalQuery::create()
            ->filterByAssetUuid($assetExternalTransfer->getAssetUuid())
            ->findOneOrCreate();

        if ($assetExternalEntity->isNew()) {
            $assetExternalEntity = (new SpyAssetExternal())
                ->setCmsSlotKey($assetExternalTransfer->getCmsSlotKey())
                ->setAssetContent($assetExternalTransfer->getAssetContent())
                ->setAssetUuid($assetExternalTransfer->getAssetUuid())
                ->setAssetName($assetExternalTransfer->getAssetName());

            $assetExternalEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($assetExternalEntity): void {
            $assetExternalEntity->delete();
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
            $assetExternalStoreEntity->delete();
        });
    }
}
