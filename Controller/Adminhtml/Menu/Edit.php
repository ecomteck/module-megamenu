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

namespace Ecomteck\Megamenu\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Ecomteck\Megamenu\Api\MenuRepositoryInterface;

/**
 * Class Edit
 * @package Ecomteck\Megamenu\Controller\Adminhtml\Menu
 */
class Edit extends Action
{
    /**
     * string Registry code
     */
    const REGISTRY_CODE = 'ecomteckmenu_menu';

    /**
     * @var MenuRepositoryInterface
     */
    private $menuRepository;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Edit constructor.
     * @param Action\Context $context
     * @param MenuRepositoryInterface $menuRepository
     * @param Registry $registry
     */
    public function __construct(
        Action\Context $context,
        MenuRepositoryInterface $menuRepository,
        Registry $registry
    ) {
        parent::__construct($context);
        $this->menuRepository = $menuRepository;
        $this->registry = $registry;
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        try {
            $model = $this->menuRepository->getById($id);
            $this->registry->register(self::REGISTRY_CODE, $model);
            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu('Ecomteck_Megamenu::menus');
            $result->getConfig()->getTitle()->prepend(__('Edit Menu %1', $model->getTitle()));
            return $result;
        } catch (NoSuchEntityException $e) {
            $result = $this->resultRedirectFactory->create();
            $result->setPath('*/*/index');
            return $result;
        }
    }

    /**
     * Is allowed
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecomteck_Megamenu::menus');
    }
}
