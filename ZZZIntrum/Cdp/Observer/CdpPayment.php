<?php
/**
 * Created by PhpStorm.
 * User: i.sutugins
 * Date: 2017.07.01.
 * Time: 14:20
 */

namespace ZZZIntrum\Cdp\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;
use Symfony\Component\Config\Definition\Exception\Exception;
use ZZZIntrum\Cdp\Helper\DataHelper;

class CdpPayment implements ObserverInterface
{
    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    public function __construct(
        DataHelper $helper
    )
    {
        $this->_dataHelper = $helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $isActive = $this->_dataHelper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/active', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!$isActive) {
            return;
        }

        $minAmount = $this->_dataHelper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/minamount', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $maxAmount = $this->_dataHelper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/maxamount', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        $event = $observer->getEvent();
        if (!$event || !($event instanceof Event)) {
            return;
        }
        $result = $event->getResult();
        if (!$result || !($result instanceof DataObject) || !$result->getIsAvailable()) {
            return;
        }

        $quote = $event->getQuote();
        if (!$quote || !($quote instanceof Quote)) {
            return;
        }

        $total = $quote->getGrandTotal();
        if (($minAmount != '' && $total < $minAmount) || ($maxAmount != '' && $total > $maxAmount)) {
            return;
        }

        $paymentMethod = $event->getMethodInstance();
        if (!$paymentMethod || !($paymentMethod instanceof MethodInterface)) {
            return;
        }

        $code = $paymentMethod->getCode();
        $status = $this->_dataHelper->CDPRequest($quote);
        if ($status == null) {
            return;
        }

        $show = true;
        $status = intval($status);
        if ($status < 0 || $status > 15) {
            $status = 0;
        }
        $methodCodes = $this->_dataHelper->_scopeConfig->getValue('intrumcdppayments/intrumcdp_payment_config/status_'.(string)$status, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $methods = explode(",", $methodCodes);
        foreach($methods as $mthd)
        {
            if ($mthd == $code) {
                $show = false;
            }
        }
        $result->setData('is_available', $show);
    }
}