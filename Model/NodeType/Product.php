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

use Magento\Customer\Model\Session;
use Magento\Framework\Profiler;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Product
 * @package Ecomteck\Megamenu\Model\NodeType
 */
class Product extends AbstractNode
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\Megamenu\Model\ResourceModel\NodeType\Product::class);
        parent::_construct();
    }

    public function __construct(
        Profiler $profiler,
        StoreManagerInterface $storeManager,
        Session $customerSession
    ) {
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        parent::__construct($profiler);
    }

    /**
     * @inheritDoc
     */
    public function fetchData(array $nodes, $storeId)
    {
        $this->profiler->start(__METHOD__);

        $localNodes = [];
        $productIds = [];

        $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
        $customerGroupId = $this->customerSession->getCustomer()->getGroupId();

        foreach ($nodes as $node) {
            $localNodes[$node->getId()] = $node;
            $productIds[] = (int)$node->getContent();
        }
        
        $resource = $this->getResource();
        $productImages = $resource->fetchImageData($storeId, $productIds);
        $productUrls = $resource->fetchData($storeId, $productIds);
        $productPrices = $resource->fetchPriceData($websiteId, $customerGroupId, $productIds);
        $productTitles = $resource->fetchTitleData($storeId, $productIds);
        $this->profiler->stop(__METHOD__);

        return [
            $localNodes,
            $productUrls,
            $productPrices,
            $productImages,
            $productTitles
        ];
    }
}
