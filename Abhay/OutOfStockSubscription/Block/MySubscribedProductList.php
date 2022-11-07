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
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Catalog\Helper\Image $imageHelper,
        Session $customerSession,
        array $data = []
    ) {       
        $this->subscriptioncollectionFactory = $subscriptioncollectionFactory;
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
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

    public function getProductDataUsingId($productId)
    {
        return $this->productRepository->getById($productId);
    }

    public function getProductImage($productId)
    {
        try {
            $_product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return 'product not found';
        }
        $image_url = $this->imageHelper->init($_product, 'product_base_image')->getUrl();
        return $image_url;
    }

     /**
     * get pager html
     *
     * @return void
     */

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * _prepareLayout prepare pager list
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getSubscribedProductCollection()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'outofstocksubsciption.product.list.pager'
            )->setAvailableLimit([10=>10,15=>15,20=>20])
            ->setShowPerPage(true)->setCollection($this->getSubscribedProductCollection());
            $this->setChild('pager', $pager);
            $this->getSubscribedProductCollection()->load();
        }
        return $this;
    }
}
