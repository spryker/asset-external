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
    public function getCurrentTenantIdentifier(): string
    {
        $beHostInsteadOfTenantIdentifier = getenv('SPRYKER_BE_HOST') !== false ? getenv('SPRYKER_BE_HOST') : 'TENANT_IDENTIFIER_UNDEFINED';

        return (string)(getenv('TENANT_IDENTIFIER') !== false ? getenv('TENANT_IDENTIFIER') : $beHostInsteadOfTenantIdentifier);
    }
}
