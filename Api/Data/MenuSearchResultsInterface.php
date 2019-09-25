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

namespace Ecomteck\Megamenu\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Search results interface.
 *
 * @api
 */
interface MenuSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get pages list.
     *
     * @return \Ecomteck\Megamenu\Api\Data\MenuInterface[]
     */
    public function getItems();

    /**
     * Set pages list.
     *
     * @param \Ecomteck\Megamenu\Api\Data\MenuInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
