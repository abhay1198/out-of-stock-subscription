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

use Magento\Framework\App\Action\Context;

class AddSubscriber extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJson,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\CustomerFactory $customerModel,
        \Magento\Catalog\Model\Product $product,
        \Abhay\OutOfStockSubscription\Model\SubscriptionFactory $subscriptionFactory
    ) {
        $this->resultJson = $resultJson;
        $this->scopeConfig = $scopeConfig;
        $this->customerModel = $customerModel;
        $this->subscription = $subscriptionFactory;
        $this->product = $product;
        parent::__construct($context);
    }

    public function execute()
    {
        $id=$this->getRequest()->getParam('productId');
        $email = $this->getRequest()->getParam('email');
        $productName = $this->getRequest()->getParam('productName');

        $model = $this->subscription->create();
        if($email) {
            $model->setEmail($email);
            $model->setProductId($id);
            $model->setProductName($productName);
            $model->setStatus('0');
            $model->setModel($model)->save();
        }
    }
}
