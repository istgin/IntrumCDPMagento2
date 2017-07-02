<?php

namespace ZZZIntrum\Cdp\Block;


class Threatmetrix extends \Magento\Framework\View\Element\Template
{

    /* @var $_helper \ZZZIntrum\Cdp\Helper\DataHelper */
    private $_helper;
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection
     * @param \Magento\GoogleAnalytics\Helper\Data $googleAnalyticsData
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $salesOrderCollection,
        \ZZZIntrum\Cdp\Helper\DataHelper $helper,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_salesOrderCollection = $salesOrderCollection;
        parent::__construct($context, $data);
    }

    public function isAvailable()
    {
        $tmxSession = $this->_helper->_checkoutSession->getTmxSession();
        if (empty($tmxSession)) {
            $this->_helper->_checkoutSession->setTmxSession($this->_helper->_checkoutSession->getSessionId());
        }
        if ($this->_helper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/tmxenabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == 1 &&
            $this->_helper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/tmxkey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) != '' &&
            empty($tmxSession)) {

            return true;
        }
        return false;
    }

    public function getOrgId()
    {
        return $this->_helper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/tmxkey', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getSessionId()
    {
        return $this->_helper->_checkoutSession->getTmxSession();
    }

    protected function _toHtml()
    {
        return parent::_toHtml();
    }
}
