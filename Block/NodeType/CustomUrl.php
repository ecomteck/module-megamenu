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

use Magento\Framework\View\Element\Template\Context;
use Ecomteck\Megamenu\Model\TemplateResolver;
use Ecomteck\Megamenu\Model\NodeType\CustomUrl as CustomUrlModel;

/**
 * Class CustomUrl
 * @package Ecomteck\Megamenu\Block\NodeType
 */
class CustomUrl extends AbstractNode
{
    const NAME_TARGET = 'node_target';

    /**
     * @var string
     */
    protected $defaultTemplate = 'menu/node_type/custom_url.phtml';

    /**
     * @var string
     */
    protected $nodeType = 'custom_url';

    /**
     * @var array
     */
    protected $nodes;

    /**
     * @var CustomUrlModel
     */
    private $_customUrlModel;

    /**
     * CustomUrl constructor.
     *
     * @param Context $context
     * @param CustomUrlModel $customUrlModel
     * @param TemplateResolver $templateResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        CustomUrlModel $customUrlModel,
        TemplateResolver $templateResolver,
        $data = []
    ) {
        parent::__construct($context, $templateResolver, $data);
        $this->addNodeAttribute(self::NAME_TARGET, 'Node target blank', 'checkbox');
        $this->_customUrlModel = $customUrlModel;
    }

    /**
     * @inheritDoc
     */
    public function getJsonConfig()
    {
        $data = [
            "ecomteckMenuSimpleField" => [
                "type" => "custom_url"
            ]
        ];
        return $data;
    }

    /**
     * @param array $nodes
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function fetchData(array $nodes)
    {
        $storeId = $this->_storeManager->getStore()->getId();

        $this->nodes = $this->_customUrlModel->fetchData($nodes, $storeId);
    }

    /**
     * @param int $nodeId
     * @param int $level
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getHtml($nodeId, $level)
    {
        $classes = $level == 0 ? 'level-top' : '';
        $node = $this->nodes[$nodeId];
        $nodeContent  = $node->getContent();
        $title = $node->getTitle();

        if (!$this->isExternalUrl($nodeContent)) {
            $url = $this->_storeManager->getStore()->getBaseUrl() . $nodeContent;
        } else {
            $url = $nodeContent;
        }

        return <<<HTML
<a href="$url" class="$classes" role="menuitem"><span>$title</span></a>
HTML;
    }

    /**
     * @param string|null $url
     *
     * @return bool
     */
    private function isExternalUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __("Custom Url");
    }
}
