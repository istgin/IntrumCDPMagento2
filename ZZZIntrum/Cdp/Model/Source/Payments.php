<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Locale timezone source
 */
namespace ZZZIntrum\Cdp\Model\Source;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Payment\Model\Config;

class Payments implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    protected $_localeLists;
    /**
     * @var Config
     */
    protected $_paymentModelConfig;
    /**
     * @var ScopeConfigInterface
     */
    protected $_appConfigScopeConfigInterface;

    /**
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     */
    public function __construct(
        \Magento\Framework\Locale\ListsInterface $localeLists,
        ScopeConfigInterface $appConfigScopeConfigInterface,
        Config $paymentModelConfig)
    {
        $this->_localeLists = $localeLists;
        $this->_appConfigScopeConfigInterface = $appConfigScopeConfigInterface;
        $this->_paymentModelConfig = $paymentModelConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $payments = $this->_paymentModelConfig->getActiveMethods();
        $methods = array();
        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->_appConfigScopeConfigInterface->getValue('payment/' . $paymentCode . '/title');
            $payments[$paymentCode] = array(
                'label' => $paymentTitle,
                'value' => $paymentCode
            );
        }
        return $payments;

        var_dump($payments);
        $array = Array();
        $array[0] = Array("label" => "Sunday", "value" => "XXXX");
        $array[1] = Array("label" => "Sunday2", "value" => "XXXX2");
        return $array;
    }
}
