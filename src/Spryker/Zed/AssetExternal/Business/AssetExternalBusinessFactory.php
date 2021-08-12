<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business;

use Spryker\Zed\AssetExternal\AssetExternalDependencyProvider;
use Spryker\Zed\AssetExternal\Business\Model\AssetExternalHandler;
use Spryker\Zed\AssetExternal\Business\Model\AssetExternalHandlerInterface;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridgeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface getRepository()
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface getEntityManager()
 */
class AssetExternalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AssetExternal\Business\Model\AssetExternalHandlerInterface
     */
    public function createAssetExternalHandler(): AssetExternalHandlerInterface
    {
        return new AssetExternalHandler(
            $this->getStoreFacade(),
            $this->getCmsSlotStoreFacade(),
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreBridgeInterface
     */
    public function getStoreFacade(): AssetExternalToStoreBridgeInterface
    {
        return $this->getProvidedDependency(AssetExternalDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToCmsSlotFacadeBridgeInterface
     */
    public function getCmsSlotStoreFacade(): AssetExternalToCmsSlotFacadeBridgeInterface
    {
        return $this->getProvidedDependency(AssetExternalDependencyProvider::FACADE_CMS_SLOT);
    }
}
