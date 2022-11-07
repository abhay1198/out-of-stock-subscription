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

use Magento\CatalogInventory\Api\StockRegistryInterface;

class OutOfStockProduct extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        StockRegistryInterface $stockRegistryInterface,
        \Magento\Customer\Model\Session $customer,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->stockRegistryInterface = $stockRegistryInterface;
        $this->customer = $customer;
        parent::__construct($context, $data);
    }

    public function getCurrentProductData()
    {
        $product = $this->registry->registry('current_product');
        $outOfStockProduct['id'] = $product->getId();
        $outOfStockProduct['type'] = $product->getTypeId();
        $outOfStockProduct['name'] = $product->getName();
        $productStock = $this->stockRegistryInterface->getStockItem($outOfStockProduct['id']);
        $outOfStockProduct['stock'] = $productStock->getData()['is_in_stock'];
        return $outOfStockProduct;
    }

    /**
     * @return boolean
     */
    public function isCustomerLoggedIn() {
        return $this->customer->isLoggedIn();
    }
}
