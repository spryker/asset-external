<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence\Mapper;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;

class AssetExternalMapper implements AssetExternalMapperInterface
{
    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function mapAssetExternalEntityToAssetExternalTransfer(
        SpyAssetExternal $assetExternalEntity
    ): AssetExternalTransfer {
        $assetExternalTransfer = (new AssetExternalTransfer())->fromArray($assetExternalEntity->toArray(), true);

        return $assetExternalTransfer->setIdCmsSlot($assetExternalEntity->getFkCmsSlot());
    }
}
