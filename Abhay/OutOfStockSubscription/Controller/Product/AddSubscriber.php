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
    protected $_storeManager;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJson,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Customer\Model\CustomerFactory $customerModel,
        \Magento\Catalog\Model\Product $product,
        \Abhay\OutOfStockSubscription\Model\SubscriptionFactory $subscriptionFactory,
        \Abhay\OutOfStockSubscription\Helper\Email $email,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resultJson = $resultJson;
        $this->scopeConfig = $scopeConfig;
        $this->customerModel = $customerModel;
        $this->subscription = $subscriptionFactory;
        $this->product = $product;
        $this->email = $email;
        $this->_storeManager = $storeManager; 
        parent::__construct($context);
    }

    public function execute()
    {
        $time = date('Y-m-d H:i:s');
        $id=$this->getRequest()->getParam('productId');
        $email = $this->getRequest()->getParam('email');
        $productName = $this->getRequest()->getParam('productName');

        $websiteID = $this->_storeManager->getStore()->getWebsiteId();
        $customer = $this->customerModel->create()->setWebsiteId($websiteID)->loadByEmail($email);
        $customerId = $customer->getId();

        $customer = $this->customerModel->create()->getCollection()->addFieldToFilter('email', ['eq'=>$email]);
        foreach ($customer as $myInfo) {
            $customerName = $myInfo->getFirstname()." ".$myInfo->getLastname();
        }

        $model = $this->subscription->create();
        $subscriptionCollection = $model->getCollection()
            ->addFieldToFilter("email", ['eq' => $email])
            ->addFieldToFilter("product_id", ['eq' => $id]);

        if($subscriptionCollection->getSize() > 0) {
            $this->messageManager->addSuccess(__('You have already subscribed the product with this Email ID.'));
        } else {
            $model->setUserId($customerId);
            $model->setEmail($email);
            $model->setProductId($id);
            $model->setProductName($productName);
            $model->setStatus('0');
            $model->setCreatedAt($time);
            $model->setModel($model)->save();
            $this->sendEmail($customerName,$email,$this->getStoreId());
            $this->messageManager->addSuccess(__('We will notify you when product will be back in stock.'));
        }
    }

    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Send Notification Email
     *
     * @param [string] $customerName
     * @param [string] $customerEmail
     * @param [int]    $storeId
     * @param [int]    $orderId
     * 
     * @return void
     */
    public function sendEmail($customerName, $customerEmail, $storeId)
    {
        $senderInfo = [
            'name' =>$this->email->getConfig('trans_email/ident_general/name'),
            'email' => $this->email->getConfig('trans_email/ident_general/email'),
        ];
        $receiverInfo = [
            'name' => $customerName,
            'email' => $customerEmail,
        ];

        $emailTemplateVariables = [];
        $id = $this->getRequest()->getParam('productId');
        $email = $this->getRequest()->getParam('email');

        $productCollection = $this->product->load($id);
        $customer = $this->customerModel->create()->getCollection()->addFieldToFilter('email', ['eq'=>$email]);
        foreach ($customer as $myInfo) {
            $customerName = $myInfo->getFirstname()." ".$myInfo->getLastname();
        }
        $emailTempVariables['customerName'] = $customerName;
        $emailTempVariables['productName'] = $productCollection->getName();

        $this->email->sendProductSubscriptionMail(
            $emailTemplateVariables,
            $senderInfo,
            $receiverInfo,
            $storeId
        );
    }
}
