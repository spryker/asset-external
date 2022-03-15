<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal;

use Codeception\Actor;
use Generated\Shared\Transfer\AssetAddedMessageTransfer;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PublisherTransfer;
use Ramsey\Uuid\Uuid;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AssetExternalBusinessTester extends Actor
{
    use _generated\AssetExternalBusinessTesterActions;

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetAddedTransfer
     */
    public function buildAssetAddedTransfer(string $storeReference, string $cmsSlotKey, string $assetUuid): AssetAddedTransfer
    {
        return (new AssetAddedTransfer())
            ->setAssetName('test')
            ->setAssetView('<script>')
            ->setAssetIdentifier($assetUuid)
            ->setSlotKey($cmsSlotKey)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher((new PublisherTransfer())->setAppIdentifier($this->getUuid()))
                    ->setStoreReference($storeReference));
    }

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetUpdatedTransfer
     */
    public function buildAssetUpdatedTransfer(
        string $storeReference,
        string $cmsSlotKey = 'test',
        ?string $assetUuid = null
    ): AssetUpdatedTransfer {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetUpdatedTransfer())
            ->setAssetView('<script>')
            ->setAssetView($assetUuid)
            ->setAssetIdentifier($assetUuid)
            ->setSlotKey($cmsSlotKey)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher((new PublisherTransfer())->setAppIdentifier($this->getUuid()))
                    ->setStoreReference($storeReference));
    }

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetDeletedTransfer
     */
    public function buildAssetDeletedMessageTransfer(string $storeReference, string $cmsSlotKey = 'test'): AssetDeletedTransfer
    {
        return (new AssetDeletedTransfer())
            ->setAssetIdentifier($this->getUuid())
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher((new PublisherTransfer())->setAppIdentifier($this->getUuid()))
                    ->setStoreReference($storeReference));
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
