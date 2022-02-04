<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business\Mapper;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;

class AssetExternalMapper implements AssetExternalMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     * @param \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function mapAssetAddedTransferToAssetExternalTransfer(
        AssetAddedTransfer $assetAddedTransfer,
        AssetExternalTransfer $assetExternalTransfer
    ): AssetExternalTransfer {
        return $assetExternalTransfer->setAssetUuid($assetAddedTransfer->getAssetIdentifier())
            ->setAssetContent($assetAddedTransfer->getAssetView())
            ->setAssetName($assetAddedTransfer->getAssetName())
            ->setCmsSlotKey($assetAddedTransfer->getSlotKey())
            ->setStores($assetAddedTransfer->getStores());
    }
}
