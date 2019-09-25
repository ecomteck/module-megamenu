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

namespace Ecomteck\Megamenu\Block\Menu\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Ecomteck\Megamenu\Controller\Adminhtml\Menu\Edit;

/**
 * Class Form
 * @package Ecomteck\Megamenu\Block\Menu\Edit
 */
class Form extends Generic
{
    /**
     * @return $this|Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );
        $form->setUseContainer(true);

        $menu = $this->_coreRegistry->registry(Edit::REGISTRY_CODE);
        if ($menu) {
            $form->addField('menu_id', 'hidden', ['name' => 'id']);
        }

        $this->setForm($form);
        return $this;
    }

    /**
     * @return $this|Generic
     */
    protected function _initFormValues()
    {
        $menu = $this->_coreRegistry->registry(Edit::REGISTRY_CODE);

        if ($menu) {
            $this->getForm()->setValues($menu->getData());
        }
        return $this;
    }
}
