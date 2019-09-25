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

namespace Ecomteck\Megamenu\Model\ResourceModel\NodeType;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Store\Model\Store;

/**
 * Class AbstractNode
 * @package Ecomteck\Megamenu\Model\ResourceModel\NodeType
 */
abstract class AbstractNode extends AbstractResource
{
    /**
     * @var ResourceConnection
     */
    private $_resources;

    /**
     * @var array
     */
    private $_tables = [];

    /**
     * AbstractNode constructor.
     *
     * @param ResourceConnection    $resource
     */
    public function __construct(\Magento\Framework\App\ResourceConnection $resource)
    {
        $this->_resources = $resource;
        parent::__construct();
    }

    /**
     * Fetch additional data required for rendering nodes.
     *
     * @param int $storeId
     * @param array $params
     *
     * @return mixed
     */
    abstract public function fetchData($storeId = Store::DEFAULT_STORE_ID, $params = []);

    /**
     * @inheritDoc
     */
    abstract public function fetchConfigData();

    /**
     * Get real table name for db table, validated by db adapter
     *
     * @param string $tableName
     *
     * @return string
     * @api
     */
    public function getTable($tableName)
    {
        if (is_array($tableName)) {
            list($tableName, $entitySuffix) = $tableName;
        } else {
            $entitySuffix = null;
        }

        if ($entitySuffix !== null) {
            $tableName .= '_' . $entitySuffix;
        }

        if (!isset($this->_tables[$tableName])) {
            $this->_tables[$tableName] = $this->_resources->getTableName(
                $tableName,
                ResourceConnection::DEFAULT_CONNECTION
            );
        }

        return $this->_tables[$tableName];
    }

    /**
     * Get connection
     *
     * @param string $resourceName
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    public function getConnection($resourceName = ResourceConnection::DEFAULT_CONNECTION)
    {
        return $this->_resources->getConnection($resourceName);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
    }
}
