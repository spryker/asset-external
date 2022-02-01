<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\ScriptAddedTransfer;
use Generated\Shared\Transfer\ScriptDeletedTransfer;
use Generated\Shared\Transfer\ScriptUpdatedTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\AssetExternal\AssetExternalConfig;
use Spryker\Zed\AssetExternal\AssetExternalDependencyProvider;
use Spryker\Zed\AssetExternal\Business\AssetExternalBusinessFactory;
use Spryker\Zed\AssetExternal\Business\AssetExternalFacadeInterface;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidTenantIdentifierException;
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
        $scriptAddedTransfer = $this->buildScriptAddedTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidTenantIdentifierException::class);

        // Act
        $assetExternalFacade->addAsset($scriptAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenExternalAssetInvalid(): void
    {
        // Arrange
        $scriptAddedTransfer = $this->buildScriptAddedTransfer($this->tenantIdentifier);
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $assetExternalFacade->addAsset($scriptAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertSuccessfull(): void
    {
        // Arrange
        $scriptAddedTransfer = $this->buildScriptAddedTransfer($this->tenantIdentifier, 'slt-footer');
        $assetExternalFacade = $this->getAssetExternalFacade();
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setStores(['US', 'DE'])
            ->setAssetName('test')
            ->setAssetContent('<script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetTransfer = $assetExternalFacade->addAsset($scriptAddedTransfer);
        $assetTransfer->setIdAssetExternal(1)->setCmsSlotKey('slt-footer');

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertThrowsExceptionWhenTenantIdentifierInvalid(): void
    {
        // Arrange
        $scriptUpdatedTransfer = $this->buildScriptUpdatedTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidTenantIdentifierException::class);

        // Act
        $assetExternalFacade->updateAsset($scriptUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetExternalFacade = $this->getAssetExternalFacade();
        $scriptAddedTransfer = $this->buildScriptAddedTransfer($this->tenantIdentifier, 'slt-footer');
        $scriptUpdatedTransfer = (new ScriptUpdatedTransfer())
            ->setScriptView('<script> </script>')
            ->setScriptUuid($this->assetUuid)
            ->setSlotKey('slt-footer')
            ->setStores(['US'])
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setTenantIdentifier($this->tenantIdentifier)
                    ->setAppIdentifier($this->getUuid())
            );
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setStores(['US'])
            ->setAssetName('test')
            ->setAssetContent('<script> </script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetExternalFacade->addAsset($scriptAddedTransfer);

        $assetTransfer = $assetExternalFacade->updateAsset($scriptUpdatedTransfer);
        $assetTransfer->setIdAssetExternal(1)->setCmsSlotKey('slt-footer');

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertThrowsExceptionWhenTenantIdentifierInvalid(): void
    {
        // Arrange
        $scriptDeletedTransfer = $this->buildScriptDeletedTransfer($this->getUuid());
        $assetExternalFacade = $this->getAssetExternalFacade();

        // Assert
        $this->expectException(InvalidTenantIdentifierException::class);

        // Act
        $assetExternalFacade->deleteAsset($scriptDeletedTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetExternalFacade = $this->getAssetExternalFacade();
        $scriptAddedTransfer = $this->buildScriptAddedTransfer($this->tenantIdentifier, 'slt-footer');
        $scriptDeletedTransfer = (new ScriptDeletedTransfer())
            ->setScriptUuid($this->assetUuid)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setTenantIdentifier($this->tenantIdentifier)
                    ->setAppIdentifier($this->getUuid())
            );;
        $scriptUpdatedTransfer = $this->buildScriptUpdatedTransfer($this->tenantIdentifier, 'slt-footer', $this->assetUuid);
        $assetExternalFacade->addAsset($scriptAddedTransfer);

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $assetExternalFacade->deleteAsset($scriptDeletedTransfer);
        $assetExternalFacade->updateAsset($scriptUpdatedTransfer);
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

        $assetExternalConfig->method('getCurrentTenantIdentifier')->willReturn($this->tenantIdentifier);

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
     * @return \Generated\Shared\Transfer\ScriptAddedTransfer
     */
    protected function buildScriptAddedTransfer(string $tenantId, string $cmsSlotKey = 'test'): ScriptAddedTransfer
    {
        return (new ScriptAddedTransfer())
            ->setScriptName('test')
            ->setScriptView('<script>')
            ->setScriptUuid($this->assetUuid)
            ->setSlotKey($cmsSlotKey)
            ->setStores(['US', 'DE'])
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setAppIdentifier($this->getUuid())
                    ->setTenantIdentifier($tenantId));
    }

    /**
     * @param string $tenantId
     * @param string $cmsSlotKey
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\ScriptUpdatedTransfer
     */
    protected function buildScriptUpdatedTransfer(string $tenantId, string $cmsSlotKey = 'test', ?string $assetUuid = null): ScriptUpdatedTransfer
    {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new ScriptUpdatedTransfer())
            ->setScriptView('<script>')
            ->setScriptUuid($assetUuid)
            ->setSlotKey($cmsSlotKey)
            ->setStores(['US', 'DE'])
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setAppIdentifier($this->getUuid())
                    ->setTenantIdentifier($tenantId));
    }

    /**
     * @param string $tenantId
     * @param string $cmsSlotKey
     *
     * @return \Generated\Shared\Transfer\ScriptDeletedTransfer
     */
    protected function buildScriptDeletedTransfer(string $tenantId, string $cmsSlotKey = 'test'): ScriptDeletedTransfer
    {
        return (new ScriptDeletedTransfer())
            ->setScriptUuid($this->getUuid())
            ->setStores(['US', 'DE'])
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setAppIdentifier($this->getUuid())
                    ->setTenantIdentifier($tenantId));
    }

    /**
     * @return string
     */
    protected function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }
}
