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

namespace Abhay\OutOfStockSubscription\Block;

use Abhay\OutOfStockSubscription\Model\ResourceModel\Subscription\CollectionFactory;
use Magento\Customer\Model\Session;

class MySubscribedProductList extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        CollectionFactory $subscriptioncollectionFactory,
        Session $customerSession,
        array $data = []
    ) {       
        $this->subscriptioncollectionFactory = $subscriptioncollectionFactory;
        $this->customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    public function getSubscribedProductCollection()
    {
        $collection = $this->subscriptioncollectionFactory->create();
        $customerEmail= $this->customerSession->getCustomer()->getEmail();
        $collection->addFieldToFilter('email', ['eq'=>$customerEmail]);
        return $collection;
    }
}
