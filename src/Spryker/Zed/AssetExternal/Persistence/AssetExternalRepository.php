<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Persistence;

use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternal;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\AssetExternal\Persistence\AssetExternalPersistenceFactory getFactory()
 */
class AssetExternalRepository extends AbstractRepository implements AssetExternalRepositoryInterface
{
    /**
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetExternalByAssetUuid(string $assetUuid): ?AssetExternalTransfer
    {
        $assetExternalEntity = $this->getFactory()
            ->createAssetExternalQuery()
            ->filterByAssetUuid($assetUuid)
            ->findOne();

        if ($assetExternalEntity === null) {
            return null;
        }

        return $this->getAssetExternalTransfer($assetExternalEntity);
    }

    /**
     * @param int $idAssetExternal
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer|null
     */
    public function findAssetExternalById(int $idAssetExternal): ?AssetExternalTransfer
    {
        $assetExternalEntity = $this->getFactory()
            ->createAssetExternalQuery()
            ->filterByIdAssetExternal($idAssetExternal)
            ->findOne();

        if ($assetExternalEntity === null) {
            return null;
        }

        return $this->getAssetExternalTransfer($assetExternalEntity);
    }

    /**
     * @param \Orm\Zed\AssetExternal\Persistence\SpyAssetExternal $assetExternalEntity
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    protected function getAssetExternalTransfer(SpyAssetExternal $assetExternalEntity): AssetExternalTransfer
    {
        /** @var \Generated\Shared\Transfer\AssetExternalTransfer $assetExternalTransfer */
        $assetExternalTransfer = $this->getFactory()
            ->createAssetExternalMapper()
            ->mapAssetExternalEntityToAssetExternalTransfer($assetExternalEntity);

        $assetExternalStoreEntities = $this->getFactory()
            ->createAssetExternalStoreQuery()
            ->joinWithSpyStore(Criteria::LEFT_JOIN)
            ->filterByFkAssetExternal($assetExternalEntity->getIdAssetExternal())
            ->find();

        return $assetExternalTransfer;
    }
}
