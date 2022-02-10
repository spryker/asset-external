<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Dependency\Service;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Service\StoreReference\StoreReferenceServiceInterface;

class AssetExternalToStoreReferenceBridge implements AssetExternalToStoreReferenceInterface
{
    /**
     * @var \Spryker\Service\StoreReference\StoreReferenceServiceInterface
     */
    protected $storeReferenceService;

    /**
     * @param \Spryker\Service\StoreReference\StoreReferenceServiceInterface $storeReferenceService
     */
    public function __construct($storeReferenceService)
    {
        $this->storeReferenceService = $storeReferenceService;
    }

    /**
     * @param string $storeReference
     *
     * @throws \Spryker\Service\StoreReference\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer
    {
        return $this->storeReferenceService->getStoreByStoreReference($storeReference);
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Service\StoreReference\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer
    {
        return $this->storeReferenceService->getStoreByStoreName($storeName);
    }
}
