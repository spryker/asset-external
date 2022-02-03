<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business\Model;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;

interface AssetExternalHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetExternalTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetExternalTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void;
}
