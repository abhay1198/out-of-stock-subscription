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


namespace Abhay\OutOfStockSubscription\Model\ResourceModel\Subscription;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    
    public function _construct()
    {
        $this->_init(
            \Abhay\OutOfStockSubscription\Model\Subscription::class,
            \Abhay\OutOfStockSubscription\Model\ResourceModel\Subscription::class
        );
        $this->_map['fields']['entity_id'] = 'main_table.id';
    }
}
