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

namespace Ecomteck\Megamenu\Model\NodeType;

/**
 * Class CmsPage
 * @package Ecomteck\Megamenu\Model\NodeType
 */
class CmsPage extends AbstractNode
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\Megamenu\Model\ResourceModel\NodeType\CmsPage::class);
        parent::_construct();
    }

    /**
     * @inheritDoc
     */
    public function fetchConfigData()
    {
        $this->profiler->start(__METHOD__);

        $options = $this->getResource()->fetchConfigData();
        $fieldOptions = [];

        foreach ($options as $label => $value) {
            $fieldOptions[] = [
                'label' => $label,
                'value' => $value
            ];
        }

        $data = [
            'ecomteckMenuAutoCompleteField' => [
                'type' => 'cms_page',
                'options' => $fieldOptions,
                'message' => __('CMS Page not found'),
            ],
        ];

        $this->profiler->stop(__METHOD__);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function fetchData(array $nodes, $storeId)
    {
        $this->profiler->start(__METHOD__);

        $localNodes = [];
        $pagesCodes = [];

        foreach ($nodes as $node) {
            $localNodes[$node->getId()] = $node;
            $pagesCodes[] = $node->getContent();
        }

        /** @var \Ecomteck\Megamenu\Model\ResourceModel\NodeType\CmsPage $resource */
        $resource = $this->getResource();
        $pageIds = $resource->getPageIds($storeId, $pagesCodes);
        $pageUrls = $resource->fetchData($storeId, $pageIds);


        $this->profiler->stop(__METHOD__);

        return [$localNodes, $pageIds, $pageUrls];
    }
}
