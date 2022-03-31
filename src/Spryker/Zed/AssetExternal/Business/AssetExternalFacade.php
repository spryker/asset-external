<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business;

use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\AssetExternal\Business\AssetExternalBusinessFactory getFactory()
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalRepositoryInterface getRepository()
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalEntityManagerInterface getEntityManager()
 */
class AssetExternalFacade extends AbstractFacade implements AssetExternalFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(AssetAddedTransfer $assetAddedTransfer): AssetExternalTransfer
    {
        return $this->getFactory()->createAssetExternalHandler()->addAsset($assetAddedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedTransfer $assetUpdatedTransfer): AssetExternalTransfer
    {
        return $this->getFactory()->createAssetExternalHandler()->updateAsset($assetUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $this->getFactory()->createAssetExternalHandler()->deleteAsset($assetDeletedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idAssetExternal
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetById(int $idAssetExternal): ?AssetExternalTransfer
    {
        return $this->getRepository()->findAssetExternalById($idAssetExternal);
    }
}
