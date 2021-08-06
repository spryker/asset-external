<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Communication\Plugin\Event\Listener;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AssetExternal\Communication\AssetExternalCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 */
class AssetExternalAddListenerPlugin extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetAddedMessageTransfer $transfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $transfer, $eventName): void
    {
        $this->getFacade()->addAsset($transfer);
    }
}
