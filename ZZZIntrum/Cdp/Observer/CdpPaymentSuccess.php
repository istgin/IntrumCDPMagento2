<?php
namespace ZZZIntrum\Cdp\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use ZZZIntrum\Cdp\Helper\DataHelper;

class CdpPaymentSuccess implements ObserverInterface
{
    protected $_order;
    /**
     * @var DataHelper
     */
    protected $_dataHelper;

    public function __construct(
        \Magento\Sales\Api\Data\OrderInterface $order,
        DataHelper $helper
    ) {
        $this->_order = $order;
        $this->_dataHelper = $helper;
    }

    /**
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderids = $observer->getEvent()->getOrderIds();
        $order = null;
        foreach ($orderids as $orderid) {
            $order = $this->_order->load($orderid);
        }
        if ($order == null)
        {
            return;
        }
        $this->executeComplete($order);
    }

    public function executeComplete($order)
    {
        $request = $this->_dataHelper->CDPRequestComplete($order);
        $ByjunoRequestName = "Order paid";
        $requestType = 'b2c';
        if ($request->getCompanyName1() != '' && $this->_dataHelper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/businesstobusiness', \Magento\Store\Model\ScopeInterface::SCOPE_STORE) == '1') {
            $ByjunoRequestName = "Order paid for Company";
            $requestType = 'b2b';
            $xml = $request->createRequestCompany();
        } else {
            $xml = $request->createRequest();
        }
        $mode = $this->_dataHelper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/currentmode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($mode == 'live') {
            $this->_dataHelper->_communicator->setServer('live');
        } else {
            $this->_dataHelper->_communicator->setServer('test');
        }
        $response = $this->_dataHelper->_communicator->sendRequest($xml, (int)$this->_dataHelper->_scopeConfig->getValue('intrumcdpcheckoutsettings/intrumcdp_setup/timeout', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        $status = 0;
        if ($response) {
            $this->_dataHelper->_response->setRawResponse($response);
            $this->_dataHelper->_response->processResponse();
            $status = (int)$this->_dataHelper->_response->getCustomerRequestStatus();
            if (intval($status) > 15) {
                $status = 0;
            }
            $this->_dataHelper->saveLog($request, $xml, $response, $status, $ByjunoRequestName);
        } else {
            $this->_dataHelper->saveLog($request, $xml, "empty response", "0", $ByjunoRequestName);
        }
        return array($status, $requestType);
    }

}