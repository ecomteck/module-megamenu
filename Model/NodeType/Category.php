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

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Profiler;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

/**
 * Class Category
 * @package Ecomteck\Megamenu\Model\NodeType
 */
class Category extends AbstractNode
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var CollectionFactory
     */
    private $categoryCollection;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\Megamenu\Model\ResourceModel\NodeType\Category::class);
        parent::_construct();
    }

    /**
     * Category constructor.
     *
     * @param Profiler $profiler
     * @param MetadataPool $metadataPool
     * @param CollectionFactory $categoryCollection
     */
    public function __construct(
        Profiler $profiler,
        MetadataPool $metadataPool,
        CollectionFactory $categoryCollection
    ) {
        $this->metadataPool = $metadataPool;
        $this->categoryCollection = $categoryCollection;
        parent::__construct($profiler);
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function fetchConfigData()
    {
        $this->profiler->start(__METHOD__);
        $metadata = $this->metadataPool->getMetadata(CategoryInterface::class);
        $identifierField = $metadata->getIdentifierField();

        $data = $this->getResource()->fetchConfigData();
        $labels = [];

        foreach ($data as $row) {
            if (isset($labels[$row['parent_id']])) {
                $label = $labels[$row['parent_id']];
            } else {
                $label = [];
            }
            $label[] = $row['name'];
            $labels[$row[$identifierField]] = $label;
        }
        
        $fieldOptions = [];
        foreach ($labels as $id => $label) {
            $fieldOptions[] = [
                'label' => $label = implode(' > ', $label),
                'value' => $id
            ];
        }

        $data = [
            'ecomteckMenuAutoCompleteField' => [
                'type'    => 'category',
                'options' => $fieldOptions,
                'message' => __('Category not found'),
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
        $categoryIds = [];

        foreach ($nodes as $node) {
            $localNodes[$node->getId()] = $node;
            $categoryIds[] = (int)$node->getContent();
        }

        $categoryUrls = $this->getResource()->fetchData($storeId, $categoryIds);
        $categories = $this->getCategories($storeId, $categoryIds);

        $this->profiler->stop(__METHOD__);

        return [$localNodes, $categoryUrls, $categories];
    }

    /**
     * @param $store
     * @param array $categoryIds
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategories($store, array $categoryIds)
    {
        $return = [];
        $categories = $this->categoryCollection->create()
            ->addAttributeToSelect('*')
            ->setStoreId($store)
            ->addFieldToFilter(
                'entity_id',
                ['in' => $categoryIds]
            );

        foreach ($categories as $category) {
            $return[$category->getId()] = $category;
        }

        return $return;
    }
}
