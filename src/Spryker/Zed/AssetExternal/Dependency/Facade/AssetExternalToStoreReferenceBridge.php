<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Dependency\Facade;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface;

class AssetExternalToStoreReferenceBridge implements AssetExternalToStoreReferenceInterface
{
    /**
     * @var \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface
     */
    protected $storeReferenceFacade;

    /**
     * @param \Spryker\Zed\StoreReference\Business\StoreReferenceFacadeInterface $storeReferenceFacade
     */
    public function __construct($storeReferenceFacade)
    {
        $this->storeReferenceFacade = $storeReferenceFacade;
    }

    /**
     * @param string $storeReference
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        return $this->storeReferenceFacade->getStoreByStoreReference($storeReference);
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer
    {
        return $this->storeReferenceFacade->getStoreByStoreName($storeName);
    }
}
