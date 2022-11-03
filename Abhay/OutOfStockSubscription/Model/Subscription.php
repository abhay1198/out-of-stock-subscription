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


namespace Abhay\OutOfStockSubscription\Model;

class Subscription extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const NOROUTE_ENTITY_ID = 'no-route';
    const ENTITY_ID = 'id';
    const CACHE_TAG = 'out_of_stock_subscription';
    protected $_cacheTag = 'out_of_stock_subscription';
    protected $_eventPrefix = 'out_of_stock_subscription';

    public function _construct()
    {
        $this->_init(\Abhay\OutOfStockSubscription\Model\ResourceModel\Subscription::class);
    }

    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRoute();
        }
        return parent::load($id, $field);
    }
    
    public function noRoute()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

}
