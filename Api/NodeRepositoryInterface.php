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

namespace Ecomteck\Megamenu\Api;

use Ecomteck\Megamenu\Api\Data\NodeInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface NodeRepositoryInterface
 * @package Ecomteck\Megamenu\Api
 */
interface NodeRepositoryInterface
{
    /**
     * @param \Ecomteck\Megamenu\Api\Data\NodeInterface $node
     * @return \Ecomteck\Megamenu\Api\Data\NodeInterface
     */
    public function save(NodeInterface $node);

    /**
     * @param int $id
     * @return \Ecomteck\Megamenu\Model\Menu\Node
     */
    public function getById($id);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * @param \Ecomteck\Megamenu\Api\Data\NodeInterface $node
     * @return bool
     */
    public function delete(NodeInterface $node);

    /**
     * @param int $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * Return node by menu id
     *
     * @api
     * @param int $menuId
     * @return \Ecomteck\Megamenu\Api\Data\NodeInterface[]
     */
    public function getByMenu($menuId);

    /**
     * Return node by identifier
     *
     * @api
     * @param string $identifier
     * @return \Ecomteck\Megamenu\Api\Data\NodeInterface[]
     */
    public function getByIdentifier($identifier);
}
