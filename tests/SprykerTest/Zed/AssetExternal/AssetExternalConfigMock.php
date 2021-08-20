<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal;

use Spryker\Zed\AssetExternal\AssetExternalConfig;

class AssetExternalConfigMock extends AssetExternalConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getCurrentTenantUuid(): string
    {
        return 'TENANT_UUID';
    }
}
