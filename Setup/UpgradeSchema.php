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

namespace Ecomteck\Megamenu\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Ecomteck\Megamenu\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->changeTitleType($setup);
            $this->addMenuCssClassField($setup);
            $this->addTargetAttribute($setup);
            $this->updateTargetAttribute($setup);
            $this->addForeignKeys($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return $this
     */
    private function addMenuCssClassField(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('ecomteck_menu'),
            'css_class',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'identifier',
                'default' => 'menu',
                'comment' => 'CSS Class'
            ]
        );

        return $this;
    }

    private function changeTitleType(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->modifyColumn(
            $setup->getTable('ecomteck_menu_node'),
            'title',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => false
            ],
            'Demo Title'
        );
    }

    private function addTargetAttribute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable('ecomteck_menu_node'),
            'target',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 10,
                'nullable' => true,
                'after' => 'title',
                'default' => '_self',
                'comment' => 'Link target',
            ]
        );

        return $this;
    }

    private function updateTargetAttribute(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable('ecomteck_menu_node');
        $connection = $setup->getConnection();

        $connection->update(
            $table,
            ['target' => 0],
            "target = '_self'"
        );
        $connection->update(
            $table,
            ['target' => 1],
            "target = '_blank'"
        );
        $connection->modifyColumn(
            $table,
            'target',
            [
                'type' => Table::TYPE_BOOLEAN,
                'default' => 0,
            ]
        );
    }

    private function addForeignKeys(SchemaSetupInterface $setup)
    {
        $menuTable = $setup->getTable('ecomteck_menu');
        $nodeTable = $setup->getTable('ecomteck_menu_node');
        $storeTable = $setup->getTable('ecomteck_menu_store');
        $setup->getConnection()->modifyColumn(
            $nodeTable,
            'menu_id',
            [
                'type' => Table::TYPE_INTEGER,
                'length' => 10,
                'nullable' => false,
                'unsigned' => true,
                'comment' => 'Menu ID'
            ]
        );

        $setup->getConnection()->modifyColumn(
            $storeTable,
            'store_id',
            [
                'type' => Table::TYPE_SMALLINT,
                'length' => 5,
                'nullable' => false,
                'primary' => true,
                'unsigned' => true,
                'comment' => 'Store ID'
            ]
        );

        $setup->getConnection()->addForeignKey(
            $setup->getFkName(
                'ecomteck_menu_node',
                'menu_id',
                'ecomteck_menu',
                'menu_id'
            ),
            $nodeTable,
            'menu_id',
            $menuTable,
            'menu_id',
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->addForeignKey(
            $setup->getFkName(
                'ecomteck_menu_store',
                'menu_id',
                'ecomteck_menu',
                'menu_id'
            ),
            $storeTable,
            'menu_id',
            $menuTable,
            'menu_id',
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->addForeignKey(
            $setup->getFkName(
                'ecomteck_menu_store',
                'store_id',
                'store',
                'store_id'
            ),
            $storeTable,
            'store_id',
            $setup->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        );
    }
}
