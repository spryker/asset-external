<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\AssetExternal;

use Spryker\Shared\Kernel\AbstractSharedConfig;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class AssetExternalConfig extends AbstractSharedConfig
{
    public const CMS_SLOT_CONTENT_PROVIDER_TYPE = 'SprykerAssetExternal';
}
