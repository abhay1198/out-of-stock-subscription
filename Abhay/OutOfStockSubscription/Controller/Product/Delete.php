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
        \Abhay\OutOfStockSubscription\Model\SubscriptionFactory $subscriptionFactory
    ) {
        $this->_subscription= $subscriptionFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
        
    }
}
