<?php

namespace ZZZIntrum\Cdp\Controller\Adminhtml\Logs;
use Magento\Backend\App\Action;

class Grid extends Action
{
    protected $_resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('ZZZIntrum_Cdp::manage_logs');
    }

    public function execute()
    {
        return $this->_resultPageFactory->create();
    }

}