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
namespace Abhay\OutOfStockSubscription\Helper;

use Magento\Framework\Exception\MailException;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param String
     */
    const XML_PATH_CUSTOMER_SUBSCRIPTION_NOTIFICATION = 'out_of_stock_subscription/general/subscription_email';

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $template;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @param \Magento\Framework\App\Helper\Context              $context
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder  $transportBuilder
     * @param \Magento\Framework\Message\ManagerInterface        $messageManager
     * @param \Magento\Store\Model\StoreManagerInterface         $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
    }
    
    /**
     * Send CustomerCancelMail
     *
     * @param Mixed  $emailTemplateVariables
     * @param Mixed  $senderInfo
     * @param Mixed  $receiverInfo
     * @param string $storeId
     * 
     * @return MailTemplate
     */
    public function sendProductSubscriptionMail(
        $emailTemplateVariables, $senderInfo, $receiverInfo, $storeId = 0
    ) {
        $this->template = $this->getTemplateId(
            self::XML_PATH_CUSTOMER_SUBSCRIPTION_NOTIFICATION, 
            $storeId
        );

        $this->inlineTranslation->suspend();
        $this->generateTemplate(
            $emailTemplateVariables, $senderInfo, $receiverInfo, $storeId
        );
        try {
            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->inlineTranslation->resume();
    }

    /**
     * Recived Template Id
     * 
     * @param string $xmlPath
     * @param string $storeId
     *
     * @return mixed
     */
    public function getTemplateId($xmlPath, $storeId = '')
    {
        if (!empty($storeId)) {
            return $this->getConfigValue($xmlPath, $storeId);
        }
        return $this->getConfigValue($xmlPath, $this->getStore()->getStoreId());
    }

    /**
     * Return store configuration value.
     *
     * @param string $path
     * @param int    $storeId
     *
     * @return mixed
     */
    protected function getConfigValue($path, $storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
    
    /**
     * Return store.
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }
    
    /**
     * Generate Template
     *
     * @param Mixed  $emailTemplateVariables
     * @param Mixed  $senderInfo
     * @param Mixed  $receiverInfo
     * @param string $storeId
     * 
     * @return mixed
     */
    public function generateTemplate(
        $emailTemplateVariables, $senderInfo, $receiverInfo, $storeId = ''
    ) {
        if (!empty($storeId)) {
            $setStoreId = $storeId;
        } else {
            $setStoreId = $this->storeManager->getStore()->getId();
        }
        $senderEmail = $senderInfo['email'];
        $adminEmail = $this->getConfigValue(
            'trans_email/ident_general/email',
            $this->getStore()->getStoreId()
        );
        $senderInfo['email'] = $adminEmail;
        $template = $this->transportBuilder->setTemplateIdentifier($this->template)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $setStoreId,
                ]
            )
            ->setTemplateVars($emailTemplateVariables)
            ->setFrom($senderInfo)
            ->addTo($receiverInfo['email'], $receiverInfo['name'])
            ->setReplyTo($senderEmail, $senderInfo['name']);

        return $this;
    }

    /**
     * Return store configuration value.
     * 
     * @param string $config
     * 
     * @return mixed
     */
    public function getConfig($config)
    {
        return $this->scopeConfig->getValue(
            $config,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}