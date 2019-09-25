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

namespace Ecomteck\Megamenu\Model;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\File\Validator;

/**
 * Class TemplateResolver
 * @package Ecomteck\Megamenu\Model
 */
class TemplateResolver
{
    /**
     * @var array
     */
    private $templateMap = [];

    /**
     * @var Validator
     */
    private $validator;

    /**
     * TemplateResolver constructor.
     * @param Validator $validator
     */
    public function __construct(
        Validator $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * @param Template $block
     * @param string $menuId
     * @param string $template
     * @return string
     */
    public function getMenuTemplate($block, $menuId, $template)
    {
        if (isset($this->templateMap[$menuId . '-' . $template])) {
            return $this->templateMap[$menuId . '-' . $template];
        }

        $templateArr = explode('::', $template);
        if (isset($templateArr[1])) {
            $newTemplate = $templateArr[0] . '::' . $menuId . DIRECTORY_SEPARATOR . $templateArr[1];
        } else {
            $newTemplate = $menuId . DIRECTORY_SEPARATOR . $template;
        }

        if (!$this->validator->isValid($block->getTemplateFile($newTemplate))) {
            return $this->setTemplateMap($menuId, $template, $template);
        }

        return $this->setTemplateMap($menuId, $newTemplate, $template);
    }

    /**
     * @param string $menuId
     * @param string $template
     * @param string $oldTemplate
     * @return string
     */
    private function setTemplateMap($menuId, $template, $oldTemplate)
    {
        return $this->templateMap[$menuId . '-' . $oldTemplate] = $template;
    }
}
