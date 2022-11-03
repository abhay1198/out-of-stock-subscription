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

class MassDelete extends \Magento\Backend\App\Action
{
    protected $_filter;
    protected $_blog;

    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        Action\Context $context,
        \Abhay\OutOfStockSubscription\Model\SubscriptionFactory $subscriptionFactory
    ) {
        $this->_filter = $filter;
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
        $subscriptionModel = $this->_subscription->create();
        $collection = $this->_filter->getCollection($subscriptionModel->getCollection());
        foreach ($collection as $customerSubscription) {
            $customerSubscription->delete();
        }
        $this->messageManager->addSuccess(__('Subscription(s) deleted successfully.'));
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
