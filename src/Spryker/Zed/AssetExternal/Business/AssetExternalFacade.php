<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\ScriptAddedTransfer;
use Generated\Shared\Transfer\ScriptDeletedTransfer;
use Generated\Shared\Transfer\ScriptUpdatedTransfer;
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
     * @param \Generated\Shared\Transfer\ScriptAddedTransfer $scriptAddedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(ScriptAddedTransfer $scriptAddedTransfer): AssetExternalTransfer
    {
        return $this->getFactory()->createAssetExternalHandler()->addAsset($scriptAddedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ScriptUpdatedTransfer $scriptUpdatedTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(ScriptUpdatedTransfer $scriptUpdatedTransfer): AssetExternalTransfer
    {
        return $this->getFactory()->createAssetExternalHandler()->updateAsset($scriptUpdatedTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ScriptDeletedTransfer $scriptDeletedTransfer
     *
     * @return void
     */
    public function deleteAsset(ScriptDeletedTransfer $scriptDeletedTransfer): void
    {
        $this->getFactory()->createAssetExternalHandler()->deleteAsset($scriptDeletedTransfer);
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
