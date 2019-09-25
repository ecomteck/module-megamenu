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

namespace Ecomteck\Megamenu\Model\Menu;

use Exception;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Ecomteck\Megamenu\Api\Data\NodeInterface;
use Ecomteck\Megamenu\Api\NodeRepositoryInterface;
use Ecomteck\Megamenu\Model\Menu\NodeFactory;
use Ecomteck\Megamenu\Model\ResourceModel\Menu\Node\CollectionFactory;

/**
 * Class NodeRepository
 * @package Ecomteck\Megamenu\Model\Menu
 */
class NodeRepository implements NodeRepositoryInterface
{
    /**
     * @var NodeFactory
     */
    protected $objectFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var SearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * NodeRepository constructor.
     * @param NodeFactory $objectFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     */
    public function __construct(
        NodeFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->objectFactory = $objectFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @param NodeInterface $object
     * @return NodeInterface
     * @throws CouldNotSaveException
     */
    public function save(NodeInterface $object)
    {
        try {
            $object->save();
        } catch (Exception $e) {
            throw new CouldNotSaveException($e->getMessage());
        }
        return $object;
    }

    /**
     * @param int $id
     * @return Node
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $object = $this->objectFactory->create();
        $object->load($id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;
    }

    /**
     * @param NodeInterface $object
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(NodeInterface $object)
    {
        try {
            $object->delete();
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param int $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }

    /**
     * @param int $menuId
     * @return NodeInterface[]
     */
    public function getByMenu($menuId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFilter('menu_id', $menuId);
        $collection->addFilter('is_active', 1);
        $collection->addOrder('level', AbstractCollection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', AbstractCollection::SORT_ORDER_ASC);
        $collection->addOrder('position', AbstractCollection::SORT_ORDER_ASC);
        return $collection->getItems();
    }

    /**
     * @param string $identifier
     * @return NodeInterface[]
     */
    public function getByIdentifier($identifier)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFilter('main_table.is_active', 1);
        $collection->addOrder('level', AbstractCollection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', AbstractCollection::SORT_ORDER_ASC);
        $collection->addOrder('position', AbstractCollection::SORT_ORDER_ASC);
        $collection->join(['menu' => 'ecomteck_menu_menu'], 'main_table.menu_id = menu.menu_id', 'identifier');
        $collection->addFilter('identifier', $identifier);
        return $collection->getItems();
    }
}
