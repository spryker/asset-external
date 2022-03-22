<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AssetExternal\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AssetDeletedMessageTransfer;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Generated\Shared\Transfer\AssetUpdatedMessageTransfer;
use Spryker\Zed\AssetExternal\Business\Exception\InvalidAssetExternalException;
use SprykerTest\Zed\AssetExternal\AssetExternalBusinessTester;

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
        $this->tester->mockStoreReferenceFacade();
    }

    /**
     * @return void
     */
    public function testAddAssetAssertSuccessfull(): void
    {
        // Arrange
        $assetMessageTransfer = $this->tester->buildAssetAddedMessageTransfer(
            AssetExternalBusinessTester::STORE_REFERENCE,
            'slt-footer',
            $this->assetUuid,
        );
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setAssetName('test')
            ->setAssetContent('<script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid);

        // Act
        $assetTransfer = $this->tester->getFacade()->addAsset($assetMessageTransfer);
        $assetTransfer->setIdAssetExternal(1)->setCmsSlotKey('slt-footer');

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetAssertSuccessfull(): void
    {
        // Arrange
        $startAssetMessageTransfer = $this->tester->buildAssetAddedMessageTransfer(
            AssetExternalBusinessTester::STORE_REFERENCE,
            'slt-footer',
            $this->assetUuid,
        );
        $newAssetMessageTransfer = (new AssetUpdatedMessageTransfer())
            ->setScriptView('<script> </script>')
            ->setScriptUuid($this->assetUuid)
            ->setAppId($this->tester->getUuid())
            ->setSlotKey('slt-footer')
            ->setStoreReference(AssetExternalBusinessTester::STORE_REFERENCE);
        $expectedAssetTransfer = (new AssetExternalTransfer())->setCmsSlotKey('slt-footer')
            ->setAssetName('test')
            ->setAssetContent('<script> </script>')
            ->setIdAssetExternal(1)
            ->setAssetUuid($this->assetUuid)
            ->addStore('DE');

        // Act
        $this->tester->getFacade()->addAsset($startAssetMessageTransfer);

        $assetTransfer = $this->tester->getFacade()->updateAsset($newAssetMessageTransfer);
        $assetTransfer->setIdAssetExternal(1)->setCmsSlotKey('slt-footer');

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertSuccessfull(): void
    {
        // Arrange
        $startAssetMessageTransfer = $this->tester->buildAssetAddedMessageTransfer(
            AssetExternalBusinessTester::STORE_REFERENCE,
            'slt-footer',
            $this->assetUuid,
        );
        $delAssetMessageTransfer = (new AssetDeletedMessageTransfer())
            ->setScriptUuid($this->assetUuid)
            ->setStoreReference(AssetExternalBusinessTester::STORE_REFERENCE)
            ->setAppId($this->tester->getUuid());
        $updateCheckMessageTransfer = $this->tester->buildAssetUpdatedMessageTransfer(
            AssetExternalBusinessTester::STORE_REFERENCE,
            'slt-footer',
            $this->assetUuid,
        );
        $this->tester->getFacade()->addAsset($startAssetMessageTransfer);

        // Assert
        $this->expectException(InvalidAssetExternalException::class);

        // Act
        $this->tester->getFacade()->deleteAsset($delAssetMessageTransfer);
        $this->tester->getFacade()->updateAsset($updateCheckMessageTransfer);
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
        $assetExternal = $this->tester->getFacade()->findAssetById($expectedAssetExternal->getIdAssetExternal());

        // Assert
        $this->assertEquals($expectedAssetExternal, $assetExternal);
    }
}
