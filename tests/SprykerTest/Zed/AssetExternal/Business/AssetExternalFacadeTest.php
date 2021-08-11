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
use Spryker\Zed\AssetExternal\Business\Exception\InvalidTenantUuidException;
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
    protected $tenantUuid;

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
        $this->tenantUuid = $this->getUuid();
        $this->assetUuid = $this->getUuid();
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenTenantUuidInvalid(): void
    {
        // Arrange
        $assetAddedMessageTransfer = $this->buildAssetAddedMessageTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidTenantUuidException::class);

        // Act
        $assetExternalFacade->addAsset($assetAddedMessageTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenExternalAssetInvalid(): void
    {
        // Arrange
        $assetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantUuid);
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
        $assetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantUuid, 'slt-footer');
        $assetExternalFacade = $this->getAssetExternalFacade();
        $expectedAssetTransfer = (new AssetExternalTransfer())->setIdCmsSlot(8)
            ->setStores(['US', 'DE'])
            ->setAssetName('test')
            ->setAssetContent('<script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetTransfer = $assetExternalFacade->addAsset($assetMessageTransfer);
        $assetTransfer->setIdAssetExternal(1)->setIdCmsSlot(8);

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertThrowsExceptionWhenTenantUuidInvalid(): void
    {
        // Arrange
        $assetUpdatedMessageTransfer = $this->buildAssetUpdatedMessageTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidTenantUuidException::class);

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
        $startAssetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantUuid, 'slt-footer');
        $newAssetMessageTransfer = (new AssetUpdatedMessageTransfer())->setAssetContent('<script> </script>')
            ->setAssetUuid($this->assetUuid)
            ->setTenantUuid($this->tenantUuid)
            ->setAppUuid($this->getUuid())
            ->setSlotKey('slt-footer')
            ->setStores(['US']);
        $expectedAssetTransfer = (new AssetExternalTransfer())->setIdCmsSlot(8)
            ->setStores(['US'])
            ->setAssetName('test')
            ->setAssetContent('<script> </script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetExternalFacade->addAsset($startAssetMessageTransfer);

        $assetTransfer = $assetExternalFacade->updateAsset($newAssetMessageTransfer);
        $assetTransfer->setIdAssetExternal(1)->setIdCmsSlot(8);

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertThrowsExceptionWhenTenantUuidInvalid(): void
    {
        // Arrange
        $assetDeletedMessageTransfer = $this->buildAssetDeletedMessageTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidTenantUuidException::class);

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
        $startAssetMessageTransfer = $this->buildAssetAddedMessageTransfer($this->tenantUuid, 'slt-footer');
        $delAssetMessageTransfer = (new AssetDeletedMessageTransfer())->setAssetUuid($this->assetUuid)
            ->setTenantUuid($this->tenantUuid)
            ->setAppUuid($this->getUuid());
        $updateCheckMessageTransfer = $this->buildAssetUpdatedMessageTransfer($this->tenantUuid, 'slt-footer', $this->assetUuid);
        $assetExternalFacade->addAsset($startAssetMessageTransfer);

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $assetExternalFacade->deleteAsset($delAssetMessageTransfer);
        $assetExternalFacade->updateAsset($updateCheckMessageTransfer);
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

        $assetExternalConfig->method('getCurrentTenantUuid')->willReturn($this->tenantUuid);

        $assetExternalBusinessFactory = new AssetExternalBusinessFactory();
        $dependencyProvider = new AssetExternalDependencyProvider();
        $dependencyProvider->provideBusinessLayerDependencies($container);
        $assetExternalBusinessFactory->setContainer($container);
        $assetExternalBusinessFactory->setConfig($assetExternalConfig);
        $assetExternalFacade->setFactory($assetExternalBusinessFactory);

        return $assetExternalFacade;
    }

    /**
     * @param string $tenantUuid
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetAddedMessageTransfer
     */
    protected function buildAssetAddedMessageTransfer(string $tenantUuid, string $cmsSlotKey = 'test'): AssetAddedMessageTransfer
    {
        return (new AssetAddedMessageTransfer())->setAssetName('test')
            ->setAssetContent('<script>')
            ->setAssetUuid($this->assetUuid)
            ->setTenantUuid($tenantUuid)
            ->setAppUuid($this->getUuid())
            ->setSlotKey($cmsSlotKey)
            ->setStores(['US', 'DE']);
    }

    /**
     * @param string $tenantUuid
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetUpdatedMessageTransfer
     */
    protected function buildAssetUpdatedMessageTransfer(string $tenantUuid, string $cmsSlotKey = 'test', ?string $assetUuid = null): AssetUpdatedMessageTransfer
    {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetUpdatedMessageTransfer())->setAssetContent('<script>')
            ->setAssetUuid($assetUuid)
            ->setTenantUuid($tenantUuid)
            ->setAppUuid($this->getUuid())
            ->setSlotKey($cmsSlotKey)
            ->setStores(['US', 'DE']);
    }

    /**
     * @param string $tenantUuid
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\AssetDeletedMessageTransfer
     */
    protected function buildAssetDeletedMessageTransfer(string $tenantUuid, string $cmsSlotKey = 'test'): AssetDeletedMessageTransfer
    {
        return (new AssetDeletedMessageTransfer())->setAssetUuid($this->getUuid())
            ->setTenantUuid($tenantUuid)
            ->setAppUuid($this->getUuid())
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
