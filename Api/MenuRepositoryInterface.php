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

use Ecomteck\Megamenu\Api\Data\MenuInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface MenuRepositoryInterface
 * @package Ecomteck\Megamenu\Api
 */
interface MenuRepositoryInterface
{
    /**
     * Save menu
     *
     * @param \Ecomteck\Megamenu\Api\Data\MenuInterface $menu
     * @return \Ecomteck\Megamenu\Api\Data\MenuInterface
     */
    public function save(MenuInterface $menu);

    /**
     * Get menu by id
     *
     * @param int $id
     * @return \Ecomteck\Megamenu\Model\Menu
     */
    public function getById($id);

    /**
     * Returns menus list
     *
     * @api
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Ecomteck\Megamenu\Api\Data\MenuSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria);

    /**
     * Delete menus
     *
     * @param \Ecomteck\Megamenu\Api\Data\MenuInterface $menu
     * @return bool
     */
    public function delete(MenuInterface $menu);

    /**
     * Delete menu by id
     *
     * @param int $id
     * @return bool
     */
    public function deleteById($id);

    /**
     *  Get menu with identifier
     *
     * @param string $identifier
     * @param int $storeId
     * @return \Ecomteck\Megamenu\Model\Menu
     */
    public function get($identifier, $storeId);
}
