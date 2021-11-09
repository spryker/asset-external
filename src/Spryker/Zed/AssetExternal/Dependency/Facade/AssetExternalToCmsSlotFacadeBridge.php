<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Dependency\Facade;

use Generated\Shared\Transfer\CmsSlotCriteriaTransfer;

class AssetExternalToCmsSlotFacadeBridge implements AssetExternalToCmsSlotFacadeBridgeInterface
{
    /**
     * @var \Spryker\Zed\CmsSlot\Business\CmsSlotFacadeInterface
     */
    protected $cmsSlotFacade;

    /**
     * @param \Spryker\Zed\CmsSlot\Business\CmsSlotFacadeInterface $cmsSlotFacade
     */
    public function __construct($cmsSlotFacade)
    {
        $this->cmsSlotFacade = $cmsSlotFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\CmsSlotTransfer>
     */
    public function getCmsSlotsByCriteria(CmsSlotCriteriaTransfer $cmsSlotCriteriaTransfer): array
    {
        return $this->cmsSlotFacade->getCmsSlotsByCriteria($cmsSlotCriteriaTransfer);
    }
}
