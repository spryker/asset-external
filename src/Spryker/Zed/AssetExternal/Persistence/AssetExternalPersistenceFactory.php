<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence;

use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\AssetExternal\AssetExternalDependencyProvider;
use Spryker\Zed\AssetExternal\Persistence\Mapper\AssetExternalMapper;
use Spryker\Zed\AssetExternal\Persistence\Mapper\AssetExternalMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface getRepository()
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface getEntityManager()
 */
class AssetExternalPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery
     */
    public function createAssetExternalQuery(): SpyAssetExternalQuery
    {
        return SpyAssetExternalQuery::create();
    }

    /**
     * @return \Orm\Zed\AssetExternal\Persistence\SpyAssetExternalStoreQuery
     */
    public function createAssetExternalStoreQuery(): SpyAssetExternalStoreQuery
    {
        return SpyAssetExternalStoreQuery::create();
    }

    /**
     * @return \Spryker\Zed\AssetExternal\Persistence\Mapper\AssetExternalMapperInterface
     */
    public function createAssetExternalMapper(): AssetExternalMapperInterface
    {
        return new AssetExternalMapper();
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(AssetExternalDependencyProvider::PROPEL_QUERY_STORE);
    }

    /**
     * @return \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery
     */
    public function getCmsSlotPropelQuery(): SpyCmsSlotQuery
    {
        return $this->getProvidedDependency(AssetExternalDependencyProvider::PROPEL_QUERY_CMS_SLOT);
    }
}
