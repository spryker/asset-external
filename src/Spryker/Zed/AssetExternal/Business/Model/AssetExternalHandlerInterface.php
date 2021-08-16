<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business\Model;

use Generated\Shared\Transfer\AssetAddedMessageTransfer;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;

interface AssetExternalHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetAddedMessageTransfer $assetAddedMessageTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(AssetAddedMessageTransfer $assetAddedMessageTransfer): AssetExternalTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedMessageTransfer $assetUpdatedMessageTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedMessageTransfer $assetUpdatedMessageTransfer): AssetExternalTransfer;

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedMessageTransfer $assetDeletedMessageTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedMessageTransfer $assetDeletedMessageTransfer): void;
}
