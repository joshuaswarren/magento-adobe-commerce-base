<?php

namespace Creatuity\Base\Helpers\Creatuity\Plugin;

use Magento\Cms\Model\ResourceModel\Page as CmsPage;
use Magento\CmsUrlRewrite\Plugin\Cms\Model\ResourceModel\Page;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @license https://warrenappliedlabs.com/license
 * @copyright Copyright (c) 2008-* Joshua Warren (https://warrenappliedlabs.com)
 */
class CmsUrlRewritePlugin extends Page
{
    protected static $enabled = false;

    public static function runWithEnabled($callback, array $params = [])
    {
        try {
            self::$enabled = true;

            return call_user_func_array($callback, $params);
        } finally {
            self::$enabled = false;
        }
    }

    public function beforeSave(CmsPage $subject, AbstractModel $object)
    {
        if (self::$enabled) {
            parent::beforeSave($subject, $object);
        }
    }

    public function aroundDelete(CmsPage $subject, \Closure $proceed, AbstractModel $page)
    {
        //this is for magento before 2.2
        if (self::$enabled && method_exists(parent::class, 'aroundDelete')) {
            return parent::aroundDelete($subject, $proceed, $page);
        }

        return $proceed($page);
    }

    public function afterDelete(
        CmsPage $subject,
        AbstractDb $result,
        AbstractModel $page = null
    ) {
        //this is for magento 2.2 and up
        if (self::$enabled && method_exists(parent::class, 'afterDelete')) {
            return parent::afterDelete($subject, $result, $page);
        }

        return $result;
    }
}
