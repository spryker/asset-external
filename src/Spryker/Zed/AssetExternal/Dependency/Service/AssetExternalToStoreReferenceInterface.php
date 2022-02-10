<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Dependency\Service;

use Generated\Shared\Transfer\StoreTransfer;

interface AssetExternalToStoreReferenceInterface
{

    /**
     * @param string $storeReference
     *
     * @throws \Spryker\Service\StoreReference\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer;

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Service\StoreReference\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer;
}
