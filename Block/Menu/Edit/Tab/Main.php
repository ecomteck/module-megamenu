<?php
/**
 * Ecomteck
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the ecomteck.com license that is
 * available through the world-wide-web at this URL:
 * https://ecomteck.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Ecomteck
 * @package     Ecomteck_Megamenu
 * @copyright   Copyright (c) 2019 Ecomteck (https://ecomteck.com/)
 * @license     https://ecomteck.com/LICENSE.txt
 */

namespace Ecomteck\Megamenu\Block\Menu\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Ecomteck\Megamenu\Controller\Adminhtml\Menu\Edit;

/**
 * Class Main
 * @package Ecomteck\Megamenu\Block\Menu\Edit\Tab
 */
class Main extends Generic implements TabInterface
{
    /**
     * @return Generic|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('menu_');

        $fieldSet = $form->addFieldset(
            'menu_fieldset',
            ['legend' => __('Menu Data'), 'class' => 'fieldset-wide']
        );

        $fieldSet->addField(
            'title',
            'text',
            [
                'name'  => 'title',
                'label' => __('Title'),
                'class' => 'required',
            ]
        );

        $fieldSet->addField(
            'identifier',
            'text',
            [
                'name'  => 'identifier',
                'label' => __('Identifier'),
                'class' => 'required',
            ]
        );

        $fieldSet->addField(
            'css_class',
            'text',
            [
                'name'  => 'css_class',
                'label' => __('Menu Main CSS Class'),
                'class' => 'required',
            ]
        );

        $values = [];
        foreach ($this->_storeManager->getStores(false) as $storeId => $store) {
            $values[] = [
                'label' => $store->getName(),
                'value' => $storeId,
            ];
        }

        $fieldSet->addField(
            'stores',
            'multiselect',
            [
                'name'   => 'stores',
                'label'  => __('Store View'),
                'class'  => 'required',
                'values' => $values,
            ]
        );

        $this->setForm($form);
    }

    /**
     * @return Generic|void
     */
    protected function _initFormValues()
    {
        $menu = $this->_coreRegistry->registry(Edit::REGISTRY_CODE);
        if ($menu) {
            $menu->setData('stores', $menu->getStores());
            $this->getForm()->setValues($menu->getData());
        }
    }

    /**
     * Return Tab label
     *
     * @return string
     * @api
     */
    public function getTabLabel()
    {
        return __('Main information');
    }

    /**
     * Return Tab title
     *
     * @return string
     * @api
     */
    public function getTabTitle()
    {
        return __('Main information');
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     * @api
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     * @api
     */
    public function isHidden()
    {
        return false;
    }
}
