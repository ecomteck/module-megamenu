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

namespace Ecomteck\Megamenu\Block\NodeType;

use Magento\Framework\Phrase;
use Ecomteck\Megamenu\Model\TemplateResolver;
use Magento\Framework\View\Element\Template\Context;
use Ecomteck\Megamenu\Model\NodeType\Wrapper as WrapperModel;

/**
 * Class Wrapper
 * @package Ecomteck\Megamenu\Block\NodeType
 */
class Wrapper extends AbstractNode
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'menu/node_type/wrapper.phtml';

    /**
     * @var string
     */
    protected $nodeType = 'wrapper';

    /**
     * @var array
     */
    protected $nodes;

    /**
     * @var WrapperModel
     */
    private $wrapperModel;

    /**
     * Wrapper constructor.
     * @param Context $context
     * @param WrapperModel $wrapperModel
     * @param TemplateResolver $templateResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        WrapperModel $wrapperModel,
        TemplateResolver $templateResolver,
        $data = []
    ) {
        $this->wrapperModel = $wrapperModel;

        parent::__construct($context, $templateResolver, $data);
    }

    /**
     * @inheritDoc
     */
    public function getJsonConfig()
    {
        return [
            "ecomteckMenuSimpleField" => [
                "type" => "wrapper"
            ]
        ];
    }

    /**
     * @param array $nodes
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function fetchData(array $nodes)
    {
        $this->nodes = $this->wrapperModel->fetchData(
            $nodes,
            $this->_storeManager->getStore()->getId()
        );
    }

    /**
     * @param int $nodeId
     * @param int $level
     *
     * @return string
     */
    public function getHtml($nodeId, $level)
    {
        $classes = $level == 0 ? 'level-top' : '';
        $node = $this->nodes[$nodeId];
        $nodeClass = $node->getClasses();

        return <<<HTML
<div class="$classes $nodeClass" role="menuitem"></div>
HTML;
    }

    /**
     * @return Phrase
     */
    public function getLabel()
    {
        return __("Wrapper");
    }
}
