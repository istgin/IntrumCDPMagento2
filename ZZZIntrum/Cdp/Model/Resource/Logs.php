<?php
/**
 * Created by PhpStorm.
 * User: Igor
 * Date: 17.10.2016
 * Time: 20:09
 */

namespace ZZZIntrum\Cdp\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Logs extends AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('intrum_log', 'id');
    }
}