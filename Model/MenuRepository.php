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

use Ecomteck\Megamenu\Api\MenuRepositoryInterface;
use Ecomteck\Megamenu\Api\Data\MenuInterface;
use Ecomteck\Megamenu\Model\ResourceModel\Menu\CollectionFactory;
use Ecomteck\Megamenu\Api\Data\MenuSearchResultsInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class MenuRepository implements MenuRepositoryInterface
{
    /**
     * @var MenuFactory
     */
    protected $objectFactory;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var MenuSearchResultsInterfaceFactory
     */
    private $menuSearchResultsFactory;

    /**
     * MenuRepository constructor.
     * @param MenuFactory $objectFactory
     * @param CollectionFactory $collectionFactory
     * @param MenuSearchResultsInterfaceFactory $menuSearchResults
     */
    public function __construct(
        MenuFactory $objectFactory,
        CollectionFactory $collectionFactory,
        MenuSearchResultsInterfaceFactory $menuSearchResults
    ) {
        $this->objectFactory = $objectFactory;
        $this->collectionFactory = $collectionFactory;
        $this->menuSearchResultsFactory = $menuSearchResults;
    }

    /**
     * @param MenuInterface $object
     * @return MenuInterface
     * @throws CouldNotSaveException
     */
    public function save(MenuInterface $object)
    {
        try {
            $object->save();
        } catch (\Exception $e) {
            throw new CouldNotSaveException($e->getMessage());
        }
        return $object;
    }

    /**
     * @param int $id
     * @return Menu
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
     * @param MenuInterface $object
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(MenuInterface $object)
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
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->menuSearchResultsFactory->create();
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
     * @param string $identifier
     * @param int $storeId
     * @return Menu
     */
    public function get($identifier, $storeId)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFilter('identifier', $identifier);
        $collection->addFilter('is_active', 1);
        $collection->join(['stores' => 'ecomteck_menu_store'], 'main_table.menu_id = stores.menu_id', 'store_id');
        $collection->addFilter('store_id', $storeId);
        return $collection->getFirstItem();
    }
}
