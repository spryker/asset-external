<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence\Mapper;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;

interface AssetExternalMapperInterface
{
    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function mapAssetExternalEntityToAssetExternalTransfer(
        SpyAssetExternal $assetExternalEntity
    ): AssetExternalTransfer;
}
