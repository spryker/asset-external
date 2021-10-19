<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Dependency;

class AssetExternalEvents
{
    /**
     * Specification:
     * - This events will be used for spy_asset_external entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_EXTERNAL_CREATE = 'Entity.spy_asset_external.create';

    /**
     * Specification:
     * - This events will be used for spy_asset_external entity changes.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_EXTERNAL_UPDATE = 'Entity.spy_asset_external.update';

    /**
     * Specification:
     * - This events will be used for spy_asset_external entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_EXTERNAL_DELETE = 'Entity.spy_asset_external.delete';

    /**
     * Specification:
     * - This events will be used for spy_asset_external_store entity creation.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_EXTERNAL_STORE_CREATE = 'Entity.spy_asset_external_store.create';

    /**
     * Specification:
     * - This events will be used for spy_asset_external_store entity deletion.
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_ASSET_EXTERNAL_STORE_DELETE = 'Entity.spy_asset_external_store.delete';

    /**
     * @var string
     */
    public const EVENT_SCRIPT_ADDED = 'Script.Added';

    /**
     * @var string
     */
    public const EVENT_SCRIPT_UPDATED = 'Script.Updated';

    /**
     * @var string
     */
    public const EVENT_SCRIPT_DELETED = 'Script.Deleted';
}
