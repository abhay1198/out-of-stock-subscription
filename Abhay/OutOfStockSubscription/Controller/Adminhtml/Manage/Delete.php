<?php
/**
 * Abhay
 * 
 * PHP version 7
 * 
 * @category  Abhay
 * @package   Abhay_OutOfStockSubscription
 * @author    Abhay Agrawal <abhay@gmail.com>
 * @copyright 2022 Copyright © Abhay
 * @license   See COPYING.txt for license details.
 * @link      https://github.com/abhay1198/out-of-stock-subscription
 */

namespace Abhay\OutOfStockSubscription\Controller\Adminhtml\Manage;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Magento\Ui\Component\MassAction\Filter;

class Delete extends \Magento\Backend\App\Action
{
    protected $_subscriptionFactory;

    public function __construct(
        Action\Context $context,
        \Abhay\OutOfStockSubscription\Model\SubscriptionFactory $subscriptionFactory
    ) {
        $this->_subscription= $subscriptionFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Abhay_OutOfStockSubscription::manage');
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id=$this->getRequest()->getParam('id');
        if ($id) {
            $customerSubscription = $this->_subscription->create()->load($id);
            $customerSubscription->delete();
            $this->messageManager->addSuccess(__('Subscription(s) deleted successfully.'));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
