<?php

namespace ZZZIntrum\Cdp\Model;

use Magento\Framework\Model\AbstractModel;

class Logs extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('ZZZIntrum\Cdp\Model\Resource\Logs');
    }
}