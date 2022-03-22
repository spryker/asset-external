<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal;

use Codeception\Actor;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\AssetAddedMessageTransfer;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\AssetExternal\AssetExternalDependencyProvider;
use Spryker\Zed\AssetExternal\Dependency\Facade\AssetExternalToStoreReferenceInterface;

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
     * @var string
     */
    public const STORE_REFERENCE = 'dev-DE';

    /**
     * @var string
     */
    public const STORE_NAME = 'DE';

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetAddedMessageTransfer
     */
    public function buildAssetAddedMessageTransfer(string $storeReference, string $cmsSlotKey, string $assetUuid): AssetAddedMessageTransfer
    {
        return (new AssetAddedMessageTransfer())
            ->setScriptName('test')
            ->setScriptView('<script>')
            ->setScriptUuid($assetUuid)
            ->setAppId($this->getUuid())
            ->setSlotKey($cmsSlotKey)
            ->setStoreReference($storeReference);
    }

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetUpdatedMessageTransfer
     */
    public function buildAssetUpdatedMessageTransfer(
        string $storeReference,
        string $cmsSlotKey = 'test',
        ?string $assetUuid = null
    ): AssetUpdatedMessageTransfer {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetUpdatedMessageTransfer())
            ->setScriptView('<script>')
            ->setScriptUuid($assetUuid)
            ->setAppId($this->getUuid())
            ->setSlotKey($cmsSlotKey)
            ->setStoreReference($storeReference);
    }

    /**
     * @param string $storeReference
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetDeletedMessageTransfer
     */
    public function buildAssetDeletedMessageTransfer(string $storeReference, string $cmsSlotKey = 'test'): AssetDeletedMessageTransfer
    {
        return (new AssetDeletedMessageTransfer())
            ->setScriptUuid($this->getUuid())
            ->setAppId($this->getUuid())
            ->setStoreReference($storeReference);
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return void
     */
    public function mockStoreReferenceFacade(): void
    {
        $store = $this->haveStore(['name' => static::STORE_NAME]);
        $storeTransfer = (new StoreTransfer())
            ->setName(static::STORE_NAME)
            ->setStoreReference(static::STORE_REFERENCE)
            ->setIdStore($store->getIdStore());

        $storeReferenceFacadeMock = Stub::makeEmpty(
            AssetExternalToStoreReferenceInterface::class,
            [
                'getStoreByStoreReference' => $storeTransfer,
                'getStoreByStoreName' => $storeTransfer,
            ],
        );

        $this->setDependency(AssetExternalDependencyProvider::FACADE_STORE_REFERENCE, $storeReferenceFacadeMock);
    }
}
