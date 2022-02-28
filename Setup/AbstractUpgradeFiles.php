<?php

namespace Creatuity\Base\Setup;

use Creatuity\Base\Setup\Abstracts\AbstractUpgradeFilesImpl;

/**
 * Intention of this installer is to installing files to the project from our modules.
 * This way we can automatically merge our work to pub/media without conflicts.
 *
 * To use it, please create Creatuity/ModuleXxx/Setup/UpgradeFiles class extending this one.
 *
 * @package ygy
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2016 Joshua Warren (https://warrenappliedlabs.com)
 */
abstract class AbstractUpgradeFiles extends AbstractUpgradeFilesImpl
{

//
//    ! WARNING !
//    Do NOT perform any action other than those given by $this->filesInstaller()
//
//    protected function upgrade_1_0_0(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
//    {
//        $this->filesInstaller()->installByDirs([
//            'pub/media' => [ // << destination
//                // source file should be placed at one of locations:
//                //   1) at module level: {projectRoot}/app/code/Creatuity/ModuleXxx/data/files/sub/test.jpg
//                //   2) at project level: {projectRoot}/sub/test.jpg
//                //   3) absolute: /sub/test.jpg
//                'sub/test1.jpg',
//            ],
//            'pub/media/catalog/' => [
//                'test2.jpg', // this file will be copied to {projectRoot}/pub/media/catalog/test3.jpg
//            ],
//        ]);
//    }


}