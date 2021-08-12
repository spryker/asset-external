<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal;

use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 */
class AssetExternalDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_STORE = 'PROPEL_QUERY_STORE';
    public const PROPEL_QUERY_CMS_SLOT = 'PROPEL_QUERY_CMS_SLOT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addStorePropelQuery($container);
        $container = $this->addCmsSlotPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STORE, $container->factory(function () {
            return SpyStoreQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CMS_SLOT, $container->factory(function () {
            return SpyCmsSlotQuery::create();
        }));

        return $container;
    }
}
