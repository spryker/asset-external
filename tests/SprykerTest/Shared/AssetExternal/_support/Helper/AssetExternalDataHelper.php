<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\AssetExternal\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AssetAddedMessageBuilder;
use Generated\Shared\Transfer\AssetExternalTransfer;
use Orm\Zed\AssetExternal\Persistence\SpyAssetExternalQuery;
use Spryker\Zed\AssetExternal\Business\AssetExternalBusinessFactory;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\AssetExternal\AssetExternalConfigMock;

class AssetExternalDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $assetAddedMessageOverride
     *
     * @return \Generated\Shared\Transfer\AssetExternalTransfer
     */
    public function haveAssetExternal(array $assetAddedMessageOverride = []): AssetExternalTransfer
    {
        $assetAddedMessageTransfer = (new AssetAddedMessageBuilder($assetAddedMessageOverride))->build();

        /** @var \Spryker\Zed\AssetExternal\Business\AssetExternalFacade $assetExternalFacade */
        $assetExternalFacade = $this->getLocator()->assetExternal()->facade();
        $assetExternalFacade->setFactory((new AssetExternalBusinessFactory())->setConfig((new AssetExternalConfigMock())));
        $assetExternalTransfer = $assetExternalFacade->addAsset($assetAddedMessageTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($assetExternalTransfer): void {
            (new SpyAssetExternalQuery())
                ->findByIdAssetExternal($assetExternalTransfer->getIdAssetExternal())
                ->delete();
        });

        return $assetExternalTransfer;
    }
}
