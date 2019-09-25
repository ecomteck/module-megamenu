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

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Ecomteck\Megamenu\Api\Data\MenuInterface;

/**
 * Class Menu
 * @package Ecomteck\Megamenu\Model
 */
class Menu extends AbstractModel implements MenuInterface, IdentityInterface
{
    /**
     * Cache tag
     * @var string
     */
    const CACHE_TAG = 'ecomteck_megamenu_menu';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Ecomteck\Megamenu\Model\ResourceModel\Menu::class);
    }

    /**
     * Get Identities
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Get stores
     *
     * @return array
     */
    public function getStores()
    {
        $connection = $this->getResource()->getConnection();
        $select = $connection->select()
            ->from($this->getResource()->getTable('ecomteck_menu_store'), ['store_id'])
            ->where('menu_id = ?', $this->getId());

        return $connection->fetchCol($select);
    }

    /**
     * Save stores
     *
     * @param array $stores
     */
    public function saveStores(array $stores)
    {
        $connection = $this->getResource()->getConnection();
        $connection->beginTransaction();
        $table = $this->getResource()->getTable('ecomteck_menu_store');
        $connection->delete($table, ['menu_id = ?' => $this->getId()]);
        foreach ($stores as $store) {
            $connection->insert($table, ['menu_id' => $this->getId(), 'store_id' => $store]);
        }
        $connection->commit();
    }

    /**
     * @inheritdoc
     */
    public function getMenuId()
    {
        return $this->_getData(MenuInterface::MENU_ID);
    }

    /**
     * @inheritdoc
     */
    public function setMenuId($menuId)
    {
        return $this->setData(MenuInterface::MENU_ID, $menuId);
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->_getData(MenuInterface::TITLE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        return $this->setData(MenuInterface::TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function getIdentifier()
    {
        return $this->_getData(MenuInterface::IDENTIFIER);
    }

    /**
     * @inheritdoc
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(MenuInterface::IDENTIFIER, $identifier);
    }

    /**
     * @inheritdoc
     */
    public function getCreationTime()
    {
        return $this->_getData(MenuInterface::CREATION_TIME);
    }

    /**
     * @inheritdoc
     */
    public function setCssClass($cssClass)
    {
        return $this->setData(MenuInterface::CSS_CLASS, $cssClass);
    }

    /**
     * @inheritdoc
     */
    public function getCssClass()
    {
        return $this->_getData(MenuInterface::CSS_CLASS);
    }

    /**
     * @inheritdoc
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(MenuInterface::CREATION_TIME, $creationTime);
    }

    /**
     * @inheritdoc
     */
    public function getUpdateTime()
    {
        return $this->_getData(MenuInterface::UPDATE_TIME);
    }

    /**
     * @inheritdoc
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(MenuInterface::UPDATE_TIME, $updateTime);
    }

    /**
     * @inheritdoc
     */
    public function getIsActive()
    {
        return $this->_getData(MenuInterface::IS_ACTIVE);
    }

    /**
     * @inheritdoc
     */
    public function setIsActive($isActive)
    {
        return $this->setData(MenuInterface::IS_ACTIVE, $isActive);
    }
}
