<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PublisherTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\AssetExternal\AssetExternalDependencyProvider;
use Spryker\Zed\AssetExternal\Business\AssetExternalBusinessFactory;
use Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AssetExternal
 * @group Business
 * @group Facade
 * @group AssetExternalFacadeTest
 * Add your own group annotations below this line
 */
class AssetExternalFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const STORE_REFERENCE = 'development_test-DE';

    /**
     * @var string
     */
    protected $assetUuid;

    /**
     * @var \SprykerTest\Zed\AssetExternal\AssetExternalBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->assetUuid = $this->tester->getUuid();
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetAddedTransfer = $this->tester->buildAssetAddedTransfer('1', 'test', $this->assetUuid);
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $assetExternalFacade->addAsset($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenExternalAssetInvalid(): void
    {
        // Arrange
        $assetAddedTransfer = $this->tester->buildAssetAddedTransfer(static::STORE_REFERENCE, 'test', $this->assetUuid);
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $assetExternalFacade->addAsset($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetMessageTransfer = $this->tester->buildAssetAddedTransfer(static::STORE_REFERENCE, 'slt-footer', $this->assetUuid);

        $assetExternalFacade = $this->getAssetExternalFacade();
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setAssetName('test')
            ->setAssetContent('<script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetTransfer = $assetExternalFacade->addAsset($assetMessageTransfer);
        $assetTransfer->setIdAssetExternal(1)->setCmsSlotKey('slt-footer');

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetUpdatedTransfer = $this->tester->buildAssetUpdatedTransfer('1');
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $assetExternalFacade->updateAsset($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetExternalFacade = $this->getAssetExternalFacade();
        $startAssetMessageTransfer = $this->tester->buildAssetAddedTransfer(static::STORE_REFERENCE, 'slt-footer', $this->assetUuid);
        $newAssetMessageTransfer = (new AssetUpdatedTransfer())
            ->setAssetView('<script> </script>')
            ->setAssetIdentifier($this->assetUuid)
            ->setSlotKey('slt-footer')
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher((new PublisherTransfer())->setAppIdentifier($this->tester->getUuid()))
                    ->setStoreReference(static::STORE_REFERENCE),
            );

        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setAssetName('test')
            ->setAssetContent('<script> </script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetExternalFacade->addAsset($startAssetMessageTransfer);

        $assetTransfer = $assetExternalFacade->updateAsset($newAssetMessageTransfer);
        $assetTransfer->setIdAssetExternal(1)->setCmsSlotKey('slt-footer');

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetDeletedTransfer = $this->tester->buildAssetDeletedMessageTransfer('1');
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $assetExternalFacade->deleteAsset($assetDeletedTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetExternalFacade = $this->getAssetExternalFacade();
        $startAssetMessageTransfer = $this->tester->buildAssetAddedTransfer(static::STORE_REFERENCE, 'slt-footer', $this->assetUuid);
        $delAssetMessageTransfer = (new AssetDeletedTransfer())
            ->setAssetIdentifier($this->assetUuid)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher((new PublisherTransfer())->setAppIdentifier($this->tester->getUuid()))
                    ->setStoreReference(static::STORE_REFERENCE),
            );
        $updateCheckMessageTransfer = $this->tester->buildAssetUpdatedTransfer(static::STORE_REFERENCE, 'slt-footer', $this->assetUuid);
        $assetExternalFacade->addAsset($startAssetMessageTransfer);

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $assetExternalFacade->deleteAsset($delAssetMessageTransfer);
        $assetExternalFacade->updateAsset($updateCheckMessageTransfer);
    }

    /**
     * @return void
     */
    public function testFindAssetById(): void
    {
        // Arrange
        $expectedAssetExternal = $this->tester->haveAssetExternal(
            ['cmsSlotKey' => 'external-asset-header'],
        );

        // Act
        $assetExternalFacade = $this->getAssetExternalFacade();
        $assetExternal = $assetExternalFacade->findAssetById($expectedAssetExternal->getIdAssetExternal());

        // Assert
        $this->assertEquals($expectedAssetExternal, $assetExternal);
    }

    /**
     * @return \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface
     */
    protected function getAssetExternalFacade(): AssetExternalFacadeInterface
    {
        /** @var \Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface $assetExternalFacade */
        $assetExternalFacade = $this->tester->getFacade();

        $container = new Container();

        $assetExternalBusinessFactory = new AssetExternalBusinessFactory();
        $dependencyProvider = new AssetExternalDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $assetExternalBusinessFactory->setContainer($container);
        $assetExternalFacade->setFactory($assetExternalBusinessFactory);

        return $assetExternalFacade;
    }

    /**
     * @return string
     */
    protected function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
