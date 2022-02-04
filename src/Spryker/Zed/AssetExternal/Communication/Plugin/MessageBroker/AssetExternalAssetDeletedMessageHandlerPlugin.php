<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Communication\Plugin\MessageBroker;

use Generated\Shared\Transfer\AssetDeletedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface;

/**
 * @method \Spryker\Zed\AssetExternal\Communication\AssetExternalCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 */
class AssetExternalAssetDeletedMessageHandlerPlugin extends AbstractPlugin implements MessageHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function onAssetDeleted(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $this->getFacade()->deleteAsset($assetDeletedTransfer);
    }

    /**
     * {@inheritDoc}
     * - Return an array where the key is the class name to be handled and the value is the callable that handles the message.
     *
     * @api
     *
     * @return array<string, callable>
     */
    public function handles(): iterable
    {
        yield AssetDeletedTransfer::class => [$this, 'onAssetDeleted'];
    }
}
