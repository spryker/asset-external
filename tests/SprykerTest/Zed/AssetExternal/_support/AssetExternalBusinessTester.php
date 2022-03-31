<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal;

use Codeception\Actor;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
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
     * @param string $assetUuid
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
                    ->setPublisher($this->havePublisherTransfer())
                    ->setStoreReference($storeReference),
            );
    }

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     * @param string|null $assetView
     *
     * @return \Generated\Shared\Transfer\AssetUpdatedTransfer
     */
    public function buildAssetUpdatedTransfer(
        string $storeReference,
        string $cmsSlotKey = 'test',
        ?string $assetUuid = null,
        ?string $assetView = '<script>'
    ): AssetUpdatedTransfer {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetUpdatedTransfer())
            ->setAssetView($assetView)
            ->setAssetIdentifier($assetUuid)
            ->setSlotKey($cmsSlotKey)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher($this->havePublisherTransfer())
                    ->setStoreReference($storeReference),
            );
    }

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetDeletedTransfer
     */
    public function buildAssetDeletedMessageTransfer(
        string $storeReference,
        string $cmsSlotKey = 'test',
        ?string $assetUuid = null
    ): AssetDeletedTransfer {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetDeletedTransfer())
            ->setAssetIdentifier($assetUuid)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher($this->havePublisherTransfer())
                    ->setStoreReference($storeReference),
            );
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return \Generated\Shared\Transfer\PublisherTransfer
     */
    public function haveMessageAttributesTransfer(): MessageAttributesTransfer
    {
        return (new PublisherTransfer())->setAppIdentifier($this->getUuid());
    }

    /**
     * @return \Generated\Shared\Transfer\PublisherTransfer
     */
    public function havePublisherTransfer(): PublisherTransfer
    {
        return (new PublisherTransfer())->setAppIdentifier($this->getUuid());
    }

    /**
     * @param string $assetContent
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function buildAssetExternalTransfer(string $assetContent, string $assetUuid): AssetExternalTransfer
    {
        return (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setAssetName('test')
            ->setAssetContent($assetContent)
            ->setIdAssetExternal(1)
            ->setAssetUuid($assetUuid);
    }
}
