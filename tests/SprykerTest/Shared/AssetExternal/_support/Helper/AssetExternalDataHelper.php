<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AssetExternal\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AssetAddedMessageBuilder;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\AssetExternal\Business\AssetExternalBusinessFactory;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\AssetExternal\AssetExternalConfigMock;

class AssetExternalDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param string $uuid
     * @param string $content
     * @param int $cmsSlotId
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function haveAssetExternal(string $uuid, string $content, int $cmsSlotId, string $name): AssetExternalTransfer
    {
        $assetExternalEntity = SpyAssetExternalQuery::create()
            ->filterByAssetUuid($uuid)
            ->findOneOrCreate();

        if ($assetExternalEntity->isNew()) {
            $assetExternalEntity = (new SpyAssetExternal())
                ->setFkCmsSlot($cmsSlotId)
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

        return (new AssetExternalTransfer())->fromArray($assetExternalEntity->toArray(), true);
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
}
