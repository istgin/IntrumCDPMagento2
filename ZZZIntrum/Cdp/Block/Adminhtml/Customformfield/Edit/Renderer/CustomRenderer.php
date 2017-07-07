<?php
/**
 * Created by PhpStorm.
 * User: i.sutugins
 * Date: 2017.07.07.
 * Time: 14:32
 */

namespace ZZZIntrum\Cdp\Block\Adminhtml\Customformfield\Edit\Renderer;

/**
 * CustomFormField Customformfield field renderer
 */
class CustomRenderer extends \Magento\Framework\Data\Form\Element\AbstractElement
{
    /**
     * Get the after element html.
     *
     * @return mixed
     */
    public function getAfterElementHtml()
    {
        // here you can write your code.
        $customDiv = '<div style="width:600px;height:200px;margin:10px 0;border:2px solid #000" id="customdiv"><h1 style="margin-top: 12%;margin-left:40%;">Custom Div</h1></div>';
        return $customDiv;
    }
}