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

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Ecomteck\Megamenu\Api\Data\NodeTypeInterface;

abstract class AbstractNode implements NodeTypeInterface
{
    /**
     * Name of the resource model
     *
     * @var string
     */
    private $_resourceName;
    /**
     * Resource model instance
     *
     * @var \Ecomteck\Megamenu\Model\ResourceModel\NodeType\AbstractNode
     */
    private $_resource;
    /**
     * @var \Magento\Framework\Profiler
     */
    protected $profiler;

    /**
     * AbstractNode constructor.
     */
    public function __construct(
        \Magento\Framework\Profiler $profiler
    ) {
        $this->_construct();
        $this->profiler = $profiler;
    }

    /**
     * Model construct that should be used for object initialization
     *
     * @return void
     */
    protected function _construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function fetchConfigData()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->_resourceName;
    }

    /**
     * @inheritDoc
     */
    public function fetchData(array $nodes, $storeId)
    {
        $this->profiler->start(__METHOD__);

        $localNodes = [];

        foreach ($nodes as $node) {
            $localNodes[$node->getId()] = $node;
        }

        $this->profiler->stop(__METHOD__);

        return $localNodes;
    }

    /**
     * Standard model initialization
     *
     * @param string $resourceModel
     * @return void
     */
    protected function _init($resourceModel)
    {
        $this->_resourceName = $resourceModel;
    }

    /**
     * Get resource instance
     *
     * @throws LocalizedException
     * @return \Ecomteck\Megamenu\Model\ResourceModel\NodeType\AbstractNode
     */
    protected function getResource()
    {
        if (empty($this->_resourceName) && empty($this->_resource)) {
            throw new LocalizedException(
                new Phrase('The resource isn\'t set.')
            );
        }

        return $this->_resource ?: ObjectManager::getInstance()->get($this->_resourceName);
    }
}