<?php

namespace Buildateam\Quiz\Block\Quiz;

use Buildateam\Quiz\Model\QuizRepository;
use Buildateam\Quiz\Model\ResourceModel\Quiz\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\View\Element\Template\Context;

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
     * @var Session
     */
    private $customerSession;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;

    /**
     * @var CollectionFactory
     */
    private $quizCollectionFactory;

    /**
     * @var QuizRepository
     */
    private $quizRepository;

    /**
     * Modal constructor.
     * @param CookieManagerInterface $cookieManager
     * @param Session $customerSession
     * @param Context $context
     * @param HttpContext $httpContext
     * @param CollectionFactory $quizCollectionFactory
     * @param QuizRepository $quizRepository
     * @param array $data
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        Session $customerSession,
        Context $context,
        HttpContext $httpContext,
        CollectionFactory $quizCollectionFactory,
        QuizRepository $quizRepository,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->cookieManager = $cookieManager;
        $this->httpContext = $httpContext;
        $this->quizCollectionFactory = $quizCollectionFactory;
        $this->_isScopePrivate = true;
        $this->quizRepository = $quizRepository;
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
        if ($id = $this->_scopeConfig->getValue(self::QUIZ_CONFIG_ID)) {
            try {
                $this->quizRepository->getById($id);
            } catch (NoSuchEntityException $e) {
                $id = null;
            }
        }

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
