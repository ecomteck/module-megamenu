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

namespace Ecomteck\Megamenu\Model;

/**
 * Class NodeTypeProvider
 * @package Ecomteck\Megamenu\Model
 */
class NodeTypeProvider
{
    /**
     * @var array
     */
    private $providers;

    /**
     * NodeTypeProvider constructor.
     *
     * @param array $providers
     */
    public function __construct(array $providers = [])
    {
        $this->providers = $providers;
    }

    /**
     * @param $type
     * @param $nodes
     */
    public function prepareData($type, $nodes)
    {
        $this->providers[$type]->fetchData($nodes);
    }

    /**
     * @param string $type
     * @return \Ecomteck\Megamenu\Api\NodeTypeInterface
     */
    public function getProvider($type)
    {
        return $this->providers[$type];
    }

    /**
     * @param $type
     * @param $id
     * @param $level
     *
     * @return mixed
     */
    public function render($type, $id, $level)
    {
        return $this->providers[$type]->getHtml($id, $level);
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        $result = [];
        foreach ($this->providers as $code => $instance) {
            $result[$code] = $instance->getLabel();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getEditForms()
    {
        return $this->providers;
    }
}
