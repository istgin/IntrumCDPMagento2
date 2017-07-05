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

        $paymentMethod = $event->getMethodInstance();
        if (!$paymentMethod || !($paymentMethod instanceof MethodInterface)) {
            return;
        }

        $code = $paymentMethod->getCode();
        $status = $this->_dataHelper->CDPRequest($quote);
        if ($status == null) {
            return;
        }

        $show = false;
        if ($status == 12) {
            $show = true;
        }
        $result->setData('is_available', $show);
    }
}