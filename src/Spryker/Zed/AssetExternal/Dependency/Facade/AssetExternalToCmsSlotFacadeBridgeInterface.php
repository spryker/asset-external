<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;
use Generated\Shared\Transfer\CmsSlotTransfer;

interface AssetExternalToCmsSlotFacadeBridgeInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CmsSlotTransfer
     */
    public function getCmsSlot(CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer): CmsSlotTransfer;
}