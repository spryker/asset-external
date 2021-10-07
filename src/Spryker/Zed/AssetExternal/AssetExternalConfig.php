<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class AssetExternalConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getCurrentTenantUuid(): string
    {
        return (string) (getenv('TENANT_UUID') ?? getenv('SPRYKER_BE_HOST'));
    }
}
