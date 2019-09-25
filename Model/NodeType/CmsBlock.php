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
 * Class CmsBlock
 * @package Ecomteck\Megamenu\Model\NodeType
 */
class CmsBlock extends AbstractNode
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\Megamenu\Model\ResourceModel\NodeType\CmsBlock::class);
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
                'type'    => 'cms_block',
                'options' => $fieldOptions,
                'message' => __('CMS Block not found'),
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
        $blocksCodes = [];

        foreach ($nodes as $node) {
            $localNodes[$node->getId()] = $node;
            $blocksCodes[] = $node->getContent();
        }

        $codes = $this->getResource()->fetchData($storeId, $blocksCodes);

        $content = [];

        foreach ($codes as $row) {
            $content[$row['identifier']] = $row['content'];
        }

        $this->profiler->stop(__METHOD__);

        return [$localNodes, $content];
    }
}
