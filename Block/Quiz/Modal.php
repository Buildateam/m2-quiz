<?php
namespace Buildateam\Quiz\Block\Quiz;

class Modal extends \Magento\Framework\View\Element\Template
{
    const BT_CUSTOMER_REGISTER =  'bt-quiz-customer-register';
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
     * Modal constructor.
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->cookieManager = $cookieManager;
        $this->httpContext = $httpContext;
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
     * @return mixed
     */
    public function getQuizId()
    {
        return $this->_scopeConfig->getValue(self::QUIZ_CONFIG_ID);
    }
}
