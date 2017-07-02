<?php

namespace ZZZIntrum\Cdp\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Logs extends Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_logs';
        $this->_blockGroup = 'ZZZIntrum_Cdp';
        parent::_construct();
        $this->removeButton('add');
    }
}