<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;

interface AssetExternalRepositoryInterface
{
    /**
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetExternalByAssetUuid(string $assetUuid): ?AssetExternalTransfer;

    /**
     * @param int $idAssetExternal
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetExternalById(int $idAssetExternal): ?AssetExternalTransfer;
}
