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
namespace Abhay\OutOfStockSubscription\Controller\Product;
 
use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;

class Delete extends AbstractAccount
{
    public $productFactory;
 
    public function __construct(
        Context $context,
        \Abhay\OutOfStockSubscription\Model\SubscriptionFactory $subscriptionFactory,
        \Magento\Customer\Model\Session $customerSession,
    ) {
        $this->_subscription= $subscriptionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }
 
    public function execute()
    {
        $subscriptionId = $this->getRequest()->getParam('id');
        $customerId = $this->customerSession->getCustomerId();
        $model = $this->_subscription->create()->load($subscriptionId);
        $model->delete();
        $this->messageManager->addSuccess(__('Subscription(s) deleted successfully.'));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
