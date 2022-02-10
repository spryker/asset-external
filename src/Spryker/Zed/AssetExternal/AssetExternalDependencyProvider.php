<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal;

use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridge;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridge;
use Spryker\Zed\AssetExternal\Dependency\Service\AssetExternalToStoreReferenceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 */
class AssetExternalDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const FACADE_CMS_SLOT = 'FACADE_CMS_SLOT';

    /**
     * @var string
     */
    public const SERVICE_STORE_REFERENCE = 'SERVICE_STORE_REFERENCE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCmsSlotFacade($container);
        $container = $this->addStoreReferenceService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreReferenceService(Container $container): Container
    {
        $container->set(static::SERVICE_STORE_REFERENCE, function (Container $container) {
            return new AssetExternalToStoreReferenceBridge($container->getLocator()->storeReference()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new AssetExternalToStoreBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCmsSlotFacade(Container $container): Container
    {
        $container->set(static::FACADE_CMS_SLOT, function (Container $container) {
            return new AssetExternalToCmsSlotFacadeBridge($container->getLocator()->cmsSlot()->facade());
        });

        return $container;
    }
}
