<?php
/**
 * Created by PhpStorm.
 * User: i.sutugins
 * Date: 2017.07.07.
 * Time: 14:29
 */
namespace ZZZIntrum\Cdp\Block\Adminhtml\Customformfield\Edit\Tab;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
    implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param \Magento\Store\Model\System\Store       $systemStore
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, null, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('customfield_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Custom Form Field'), 'class' => 'fieldset-wide']
        );
        $fieldset->addType(
            'mycustomfield',
            '\Webkul\CustomFormField\Block\Adminhtml\Customformfield\Edit\Renderer\CustomRenderer'
        );
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return "getTabLabel";
    }

    public function getTabTitle()
    {
        return "getTabTitle";
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }
}