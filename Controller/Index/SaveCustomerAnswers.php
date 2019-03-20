<?php
namespace Buildateam\Quiz\Controller\Index;

use Magento\Setup\Exception;

class SaveCustomerAnswers extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var \Buildateam\Quiz\Model\CustomerAnswerFactory
     */
    protected $customerAnswerFactory;
    /**
     * @var \Buildateam\Quiz\Api\CustomerAnswerRepositoryInterface
     */
    protected $customerAnswerRepository;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    /**
     * SaveCustomerAnswers constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Buildateam\Quiz\Model\CustomerAnswerFactory $customerAnswerFactory
     * @param \Buildateam\Quiz\Api\CustomerAnswerRepositoryInterface $customerAnswerRepository
     * @param \Magento\Customer\Model\Session $session
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Buildateam\Quiz\Model\CustomerAnswerFactory $customerAnswerFactory,
        \Buildateam\Quiz\Api\CustomerAnswerRepositoryInterface $customerAnswerRepository,
        \Magento\Customer\Model\Session $session
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->customerAnswerFactory = $customerAnswerFactory;
        $this->customerAnswerRepository = $customerAnswerRepository;
        $this->session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $response = new \Magento\Framework\DataObject();
        if (!$this->session->isLoggedIn()) {
            $customerAnswer = $this->customerAnswerFactory->create();
            $customerAnswer->setData('quiz_id', $this->getRequest()->getParam('quiz'));
            $customerAnswer->setData('answers', serialize($this->getRequest()->getParam('answers')));
            $response->setData('url', $customerAnswer->getResultUrl());
            $response->setData('success', false);
            $response->setData('message', __('Customer is not logged'));
            $resultJson->setJsonData($response->toJson());
            return $resultJson;
        }
        if ($this->getRequest()->getParam('quiz') && $this->getRequest()->getParam('answers')) {
            /** @var \Buildateam\Quiz\Model\CustomerAnswer $customerAnswer */
            $customerAnswer = $this->customerAnswerFactory->create();
            $customerAnswer->setData('customer_id', $this->session->getCustomerId());
            $customerAnswer->setData('quiz_id', $this->getRequest()->getParam('quiz'));
            $customerAnswer->setData('answers', serialize($this->getRequest()->getParam('answers')));
            try {
                $this->customerAnswerRepository->save($customerAnswer);
                $response->setData('success', true);
                $response->setData('url', $customerAnswer->getResultUrl());
            } catch (\Exception $e) {
                $response->setData('success', false);
                $response->setData('message', $e->getMessage());
            }
        } else {
            $response->setData('success', false);
            $response->setData('message', __('Incorrect Data.'));
        }
        $resultJson->setJsonData($response->toJson());
        return $resultJson;
    }
}
