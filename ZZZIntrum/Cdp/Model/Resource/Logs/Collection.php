<?php

namespace ZZZIntrum\Cdp\Model\Resource\Logs;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'ZZZIntrum\Cdp\Model\Logs',
            'ZZZIntrum\Cdp\Model\Resource\Logs'
        );
    }
}