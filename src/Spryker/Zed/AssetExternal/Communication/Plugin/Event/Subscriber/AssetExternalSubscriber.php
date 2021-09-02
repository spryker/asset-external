<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AssetExternal\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\AssetExternal\Communication\Plugin\Event\Listener\AssetExternalAddListenerPlugin;
use Spryker\Zed\AssetExternal\Communication\Plugin\Event\Listener\AssetExternalDeleteListenerPlugin;
use Spryker\Zed\AssetExternal\Communication\Plugin\Event\Listener\AssetExternalUpdateListenerPlugin;
use Spryker\Zed\AssetExternal\Dependency\AssetExternalEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AssetExternal\Communication\AssetExternalCommunicationFactory getFactory()
 * @method \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface getFacade()
 * @method \Spryker\Zed\AssetExternal\AssetExternalConfig getConfig()
 */
class AssetExternalSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * {@inheritDoc}
     * - Adds listeners for external asserts handling
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addAssetEventListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addAssetEventListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListener(AssetExternalEvents::EVENT_SCRIPT_ADDED, new AssetExternalAddListenerPlugin());
        $eventCollection->addListener(AssetExternalEvents::EVENT_SCRIPT_UPDATED, new AssetExternalUpdateListenerPlugin());
        $eventCollection->addListener(AssetExternalEvents::EVENT_SCRIPT_DELETED, new AssetExternalDeleteListenerPlugin());
    }
}
