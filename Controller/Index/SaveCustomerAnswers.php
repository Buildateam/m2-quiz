<?php

namespace Buildateam\Quiz\Controller\Index;

use Buildateam\Quiz\Api\CustomerAnswerRepositoryInterface;
use Buildateam\Quiz\Model\CustomerAnswer;
use Buildateam\Quiz\Model\CustomerAnswerFactory;
use Magento\Backend\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Class SaveCustomerAnswers
 * @package Buildateam\Quiz\Controller\Index
 */
class SaveCustomerAnswers extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var CustomerAnswerFactory
     */
    private $customerAnswerFactory;

    /**
     * @var CustomerAnswerRepositoryInterface
     */
    private $customerAnswerRepository;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * SaveCustomerAnswers constructor.
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param CustomerAnswerFactory $customerAnswerFactory
     * @param CustomerAnswerRepositoryInterface $customerAnswerRepository
     * @param Session $session
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        CustomerAnswerFactory $customerAnswerFactory,
        CustomerAnswerRepositoryInterface $customerAnswerRepository,
        Session $session,
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
        $this->jsonFactory = $jsonFactory;
        $this->customerAnswerFactory = $customerAnswerFactory;
        $this->customerAnswerRepository = $customerAnswerRepository;
        $this->session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $response = new DataObject();
        if ($this->getRequest()->getParam('quiz') && $this->getRequest()->getParam('answers')) {
            $customerId = null;
            if ($this->session->isLoggedIn()) {
                $customerId = $this->session->getCustomerId();
            }
            /** @var CustomerAnswer $customerAnswer */
            $customerAnswer = $this->customerAnswerFactory->create();
            $customerAnswer->setData('customer_id', $customerId);
            $customerAnswer->setData('quiz_id', $this->getRequest()->getParam('quiz'));
            $customerAnswer->setData('answers', $this->serializer->serialize($this->getRequest()->getParam('answers')));
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
