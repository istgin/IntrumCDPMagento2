<?php
namespace ZZZIntrum\Cdp\Model\Source;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Fieldset;
use Magento\Framework\Data\Form\Element\AbstractElement;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Payment\Model\Config;

class MethodMapping extends Fieldset
{
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_dummyElement;
    /**
     * @var \Magento\Config\Block\System\Config\Form\Field
     */
    protected $_fieldRenderer;

    public function render(AbstractElement $element)
    {
        $this->setElement($element);
        $header = $this->_getHeaderHtml($element);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $paymentModelConfig = $objectManager->get('\Magento\Payment\Model\Config');
        $payments = $paymentModelConfig->getActiveMethods();
        $html = '';
        foreach ($payments as $paymentCode => $paymentModel) {
            $html .= $this->_getFieldHtml($element, $paymentModel, $paymentCode);
        }
        $footer = $this->_getFooterHtml($element);

        return $header . $html . $footer;
    }

    protected function _getValues()
    {
        if (empty($this->_values)) {
            $this->_values = array(
                'INVOICE'=>'INVOICE',
                'DIRECT'=>'DIRECT-DEBIT',
                'CREDIT-CARD'=>'CREDIT-CARD',
                'PRE-PAY'=>'PRE-PAY',
                'CASH-ON-DELIVERY'=>'CASH-ON-DELIVERY',
                'E-PAYMENT'=>'E-PAYMENT',
                'PAYMENT'=>'PAYMENT'
            );
        }
        return $this->_values;
    }

    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $this->_fieldRenderer = $this->_layout->getBlockSingleton(
                'Magento\Config\Block\System\Config\Form\Field'
            );
        }
        return $this->_fieldRenderer;
    }

    protected function _getDummyElement()
    {
        if (empty($this->_dummyElement)) {
            $this->_dummyElement = new \Magento\Framework\DataObject(['showInDefault' => 1, 'showInWebsite' => 1]);
        }
        return $this->_dummyElement;
    }
    /*
     * @var \Magento\Payment\Model\Method\Free $paymentModel
     */
    protected function _getFieldHtml(AbstractElement $element, $paymentModel, $paymentCode)
    {
        $configData = $this->getConfigData();
        $path = 'intrumcdppaymentconfig/intrumcdp_payment_config/group_'.$paymentCode;

        if (isset($configData[$path])) {
            $data = $configData[$path];
            $inherit = false;
        } else {
            $data = (string)$this->getForm()->getConfigValue($path);
            $inherit = true;
        }

        $elementShow = $this->_getDummyElement();

        $field = $element->addField(
            'groups[intrumcdp_payment_config][fields][group_'.$paymentCode.'][value]',
            'select',
            [
                'label' => __($paymentModel->getTitle()),
                'title' => __($paymentModel->getTitle()),
                'name' => 'groups[intrumcdp_payment_config][fields][group_'.$paymentCode.'][value]',
                'required' => true,
                'values' => $this->_getValues(),
                'value' => $data,
                'inherit' => $inherit,
                'can_use_default_value' => $this->getForm()->canUseDefaultValue($elementShow),
                'can_use_website_value' => $this->getForm()->canUseWebsiteValue($elementShow)
            ]
        )->setRenderer(
            $this->_getFieldRenderer()
        );
        return $field->toHtml();
        //return '<div style="white-space: nowrap; background-color: #ddffdf; padding: 10px 5px 10px 5px">Select disabled payment methods for this code</div>';
    }

}