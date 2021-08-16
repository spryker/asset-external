<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Business;

use Generated\Shared\Transfer\AssetAddedMessageTransfer;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;

interface AssetExternalFacadeInterface
{
    /**
     * Specification:
     * - Creates a new asset external entity with new assetExternalStoreEntity relations.
     * - Uses incoming transfer to set entity fields.
     * - Persists the entity to DB.
     * - Sets ID to the returning transfer.
     * - Returns asset external response with newly created asset external transfer inside.
     * - Throws InvalidAssetExternalException in case a record is found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetAddedMessageTransfer $assetAddedMessageTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function addAsset(AssetAddedMessageTransfer $assetAddedMessageTransfer): AssetExternalTransfer;

    /**
     * Specification:
     * - Finds an asset external record by ID in DB.
     * - Uses incoming transfer to update entity fields.
     * - Persists the entity to DB.
     * - Updates a new relations assetExternalStoreEntity.
     * - Returns asset external response with updated asset external transfer inside.
     * - Throws InvalidAssetExternalException in case a record is not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetUpdatedMessageTransfer $assetUpdatedMessageTransfer
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function updateAsset(AssetUpdatedMessageTransfer $assetUpdatedMessageTransfer): AssetExternalTransfer;

    /**
     * Specification:
     * - Removes an asset external record by ID in DB.
     * - Removes related entity assetExternalStoreEntity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetDeletedMessageTransfer $assetDeletedMessageTransfer
     *
     * @return void
     */
    public function deleteAsset(AssetDeletedMessageTransfer $assetDeletedMessageTransfer): void;
}
