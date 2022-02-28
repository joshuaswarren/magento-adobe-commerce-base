<?php
namespace Creatuity\Base\ImportExport\Catalog\Category;

use Symfony\Component\Console\Output\OutputInterface;


/**
 * @package waltwo
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-2017 Joshua Warren (https://warrenappliedlabs.com)
 */
interface CategoriesImporterInterface
{

    public function import($absFile, OutputInterface $output = null, array $config = []);


}