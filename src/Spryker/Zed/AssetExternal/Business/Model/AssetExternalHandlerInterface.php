<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business\Model;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\ScriptAddedTransfer;
use Generated\Shared\Transfer\ScriptDeletedTransfer;
use Generated\Shared\Transfer\ScriptUpdatedTransfer;

interface AssetExternalHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ScriptAddedTransfer $scriptAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(ScriptAddedTransfer $scriptAddedTransfer): AssetExternalTransfer;

    /**
     * @param \Generated\Shared\Transfer\ScriptUpdatedTransfer $scriptUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(ScriptUpdatedTransfer $scriptUpdatedTransfer): AssetExternalTransfer;

    /**
     * @param \Generated\Shared\Transfer\ScriptDeletedTransfer $scriptDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(ScriptDeletedTransfer $scriptDeletedTransfer): void;
}
