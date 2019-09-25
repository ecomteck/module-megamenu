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
use Magento\Cms\Api\Data\PageInterface;
use Ecomteck\Megamenu\Model\TemplateResolver;
use Ecomteck\Megamenu\Model\NodeType\CmsPage as CmsPageModel;

/**
 * Class CmsPage
 * @package Ecomteck\Megamenu\Block\NodeType
 */
class CmsPage extends AbstractNode
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'menu/node_type/cms_page.phtml';

    /**
     * @var string
     */
    protected $nodeType = 'cms_page';

    /**
     * @var array
     */
    protected $nodes;

    /**
     * @var array
     */
    protected $pageUrls;

    /**
     * @var array
     */
    protected $pageIds;

    /**
     * @var PageInterface
     */
    private $page;

    /**
     * @var CmsPageModel
     */
    private $_cmsPageModel;

    /**
     * CmsPage constructor.
     *
     * @param Context $context
     * @param PageInterface $page
     * @param CmsPageModel $cmsPageModel
     * @param TemplateResolver $templateResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        PageInterface $page,
        CmsPageModel $cmsPageModel,
        TemplateResolver $templateResolver,
        $data = []
    ) {
        parent::__construct($context, $templateResolver, $data);
        $this->_cmsPageModel = $cmsPageModel;
        $this->page = $page;
    }

    /**
     * @return array
     */
    public function getNodeCacheKeyInfo()
    {
        $info = [];
        $pageId = $this->getRequest()->getParam('page_id');

        if ($pageId) {
            $info[] = 'cms_page_' . $pageId;
        }

        return $info;
    }

    /**
     * @return string
     */
    public function getJsonConfig()
    {
        $data = $this->_cmsPageModel->fetchConfigData();

        return $data;
    }

    /**
     * @param array $nodes
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function fetchData(array $nodes)
    {
        $storeId = $this->_storeManager->getStore()->getId();

        list($this->nodes, $this->pageIds, $this->pageUrls) = $this->_cmsPageModel->fetchData($nodes, $storeId);
    }

    /**
     * @param int $nodeId
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function isCurrentPage($nodeId)
    {
        if (!isset($this->nodes[$nodeId])) {
            throw new \InvalidArgumentException('Invalid node identifier specified');
        }

        $node = $this->nodes[$nodeId];
        $nodeContent = $node->getContent();

        return isset($this->pageIds[$nodeContent])
            ? $this->page->getId() == $this->pageIds[$nodeContent]
            : false;
    }

    /**
     * @param $nodeId
     * @param null $storeId
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPageUrl($nodeId, $storeId = null)
    {
        if (!isset($this->nodes[$nodeId])) {
            throw new \InvalidArgumentException('Invalid node identifier specified');
        }

        $node = $this->nodes[$nodeId];
        $nodeContent = $node->getContent();

        if (isset($this->pageIds[$nodeContent])) {
            $pageId = $this->pageIds[$nodeContent];
            $baseUrl = $this->_storeManager->getStore($storeId)->getBaseUrl();
            $pageUrlPath = (isset($this->pageUrls[$pageId]))
                ? $this->pageUrls[$pageId]
                :'';
            return $baseUrl . $pageUrlPath;
        }

        return false;
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

        if (isset($this->pageIds[$node->getContent()])) {
            $pageId = $this->pageIds[$node->getContent()];
            $url = $this->_storeManager->getStore()->getBaseUrl() . $this->pageUrls[$pageId];
        } else {
            $url = $this->_storeManager->getStore()->getBaseUrl();
        }

        $title = $node->getTitle();

        return <<<HTML
<a href="$url" class="$classes" role="menuitem"><span>$title</span></a>
HTML;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __("Cms Page link");
    }
}
