<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AssetAddedMessageTransfer;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\AssetExternal\AssetExternalConfig;
use Spryker\Zed\AssetExternal\AssetExternalDependencyProvider;
use Spryker\Zed\AssetExternal\Business\AssetExternalBusinessFactory;
use Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidtenantIdentifierException;
use Spryker\Zed\Kernel\Container;

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
    protected $tenantIdentifier;

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
        $this->tenantIdentifier = $this->getUuid();
        $this->assetUuid = $this->getUuid();
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhentenantIdentifierInvalid(): void
    {
        // Arrange
        $assetAddedMessageTransfer = $this->buildAssetAddedMessageTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidtenantIdentifierException::class);

        // Act
        $assetExternalFacade->addAsset($assetAddedMessageTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenExternalAssetInvalid(): void
    {
        // Arrange
        $assetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantIdentifier);
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $assetExternalFacade->addAsset($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantIdentifier, 'slt-footer');
        $assetExternalFacade = $this->getAssetExternalFacade();
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setStores(['US', 'DE'])
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
    public function testUpdateAssetAssertThrowsExceptionWhentenantIdentifierInvalid(): void
    {
        // Arrange
        $assetUpdatedMessageTransfer = $this->buildAssetUpdatedMessageTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidtenantIdentifierException::class);

        // Act
        $assetExternalFacade->updateAsset($assetUpdatedMessageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetExternalFacade = $this->getAssetExternalFacade();
        $startAssetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantIdentifier, 'slt-footer');
        $newAssetMessageTransfer = (new AssetUpdatedMessageTransfer())
            ->setScriptView('<script> </script>')
            ->setScriptUuid($this->assetUuid)
            ->setTenantId($this->tenantIdentifier)
            ->setAppId($this->getUuid())
            ->setSlotKey('slt-footer')
            ->setStores(['US']);
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setStores(['US'])
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
    public function testDeleteAssetAssertThrowsExceptionWhentenantIdentifierInvalid(): void
    {
        // Arrange
        $assetDeletedMessageTransfer = $this->buildAssetDeletedMessageTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidtenantIdentifierException::class);

        // Act
        $assetExternalFacade->deleteAsset($assetDeletedMessageTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetExternalFacade = $this->getAssetExternalFacade();
        $startAssetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantIdentifier, 'slt-footer');
        $delAssetMessageTransfer = (new AssetDeletedMessageTransfer())
            ->setScriptUuid($this->assetUuid)
            ->setTenantId($this->tenantIdentifier)
            ->setAppId($this->getUuid());
        $updateCheckMessageTransfer = $this->buildAssetUpdatedMessageTransfer($this->tenantIdentifier, 'slt-footer', $this->assetUuid);
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
        $assetExternalConfig = $this->getMockBuilder(AssetExternalConfig::class)->getMock();

        $assetExternalConfig->method('getCurrenttenantIdentifier')->willReturn($this->tenantIdentifier);

        $assetExternalBusinessFactory = new AssetExternalBusinessFactory();
        $dependencyProvider = new AssetExternalDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $assetExternalBusinessFactory->setContainer($container);
        $assetExternalBusinessFactory->setConfig($assetExternalConfig);
        $assetExternalFacade->setFactory($assetExternalBusinessFactory);

        return $assetExternalFacade;
    }

    /**
     * @param string $tenantId
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetAddedMessageTransfer
     */
    protected function buildAssetAddedMessageTransfer(string $tenantId, string $cmsSlotKey = 'test'): AssetAddedMessageTransfer
    {
        return (new AssetAddedMessageTransfer())
            ->setScriptName('test')
            ->setScriptView('<script>')
            ->setScriptUuid($this->assetUuid)
            ->setTenantId($tenantId)
            ->setAppId($this->getUuid())
            ->setSlotKey($cmsSlotKey)
            ->setStores(['US', 'DE']);
    }

    /**
     * @param string $tenantId
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetUpdatedMessageTransfer
     */
    protected function buildAssetUpdatedMessageTransfer(string $tenantId, string $cmsSlotKey = 'test', ?string $assetUuid = null): AssetUpdatedMessageTransfer
    {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetUpdatedMessageTransfer())
            ->setScriptView('<script>')
            ->setScriptUuid($assetUuid)
            ->setTenantId($tenantId)
            ->setAppId($this->getUuid())
            ->setSlotKey($cmsSlotKey)
            ->setStores(['US', 'DE']);
    }

    /**
     * @param string $tenantId
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetDeletedMessageTransfer
     */
    protected function buildAssetDeletedMessageTransfer(string $tenantId, string $cmsSlotKey = 'test'): AssetDeletedMessageTransfer
    {
        return (new AssetDeletedMessageTransfer())
            ->setScriptUuid($this->getUuid())
            ->setTenantId($tenantId)
            ->setAppId($this->getUuid())
            ->setStores(['US', 'DE']);
    }

    /**
     * @return string
     */
    protected function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
