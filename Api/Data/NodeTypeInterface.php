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

/**
 * Interface NodeTypeInterface
 * @package Ecomteck\Megamenu\Api\Data
 */
interface NodeTypeInterface
{
    /**
     * Fetch additional data required for rendering nodes.
     *
     * @param array $nodes
     * @param int|string $storeId
     *
     * @return mixed
     */
    public function fetchData(array $nodes, $storeId);

    /**
     * Fetch additional data required for config.
     *
     * @return mixed
     */
    public function fetchConfigData();
}
