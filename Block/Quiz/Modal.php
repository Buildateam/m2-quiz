<?php

namespace Buildateam\Quiz\Block\Quiz;

/**
 * Class Modal
 * @package Buildateam\Quiz\Block\Quiz
 */
class Modal extends \Magento\Framework\View\Element\Template
{
    const BT_CUSTOMER_REGISTER = 'bt-quiz-customer-register';
    const BT_QUIZ_MODAL = 'bt-quiz-modal';
    const QUIZ_CONFIG_ID = 'buildateam_quiz/general/used_quiz';
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;
    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Quiz\CollectionFactory
     */
    protected $quizCollectionFactory;

    /**
     * Modal constructor.
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Buildateam\Quiz\Model\ResourceModel\Quiz\CollectionFactory $quizResource
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Buildateam\Quiz\Model\ResourceModel\Quiz\CollectionFactory $quizCollectionFactory,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->cookieManager = $cookieManager;
        $this->httpContext = $httpContext;
        $this->quizCollectionFactory = $quizCollectionFactory;
        $this->_isScopePrivate = true;
        parent::__construct($context, $data);
    }

    /**
     * @return bool
     */
    public function isCustomerLogged()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    /**
     * @return null|string
     */
    public function getCustomerRegisterCookie()
    {
        return $this->cookieManager->getCookie(self::BT_CUSTOMER_REGISTER);
    }

    /**
     * @return null|string
     */
    public function getQuizModalCookie()
    {
        return $this->cookieManager->getCookie(self::BT_QUIZ_MODAL);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getQuizId()
    {
        $id = $this->_scopeConfig->getValue(self::QUIZ_CONFIG_ID);
        if (!$id) {
            // return last inserted id in case if no id in store config
            try {
                $id = $this->quizCollectionFactory->create()->getLastItem()->getId();
            } catch (\Exception $exception) {
                throw new \Exception(__($exception->getMessage()));
            }
        }

        return $id;
    }
}
