<?php

namespace Buildateam\Quiz\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CustomerAnswerRepository
 * @package Buildateam\Quiz\Model
 */
class CustomerAnswerRepository implements \Buildateam\Quiz\Api\CustomerAnswerRepositoryInterface
{
    /**
     * @var ResourceModel\Answer
     */
    protected $resource;

    /**
     * @var CustomerAnswerFactory
     */
    protected $customerAnswerFactory;

    /**
     * QuestionRepository constructor.
     * @param ResourceModel\CustomerAnswer $resource
     * @param CustomerAnswerFactory $customerAnswerFactory
     */
    public function __construct(ResourceModel\CustomerAnswer $resource, CustomerAnswerFactory $customerAnswerFactory)
    {
        $this->resource = $resource;
        $this->customerAnswerFactory = $customerAnswerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CustomerAnswer $customerAnswer)
    {
        try {
            $this->resource->save($customerAnswer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $customerAnswer;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($customerAnswerId)
    {
        /** @var \Buildateam\Quiz\Model\CustomerAnswer $customerAnswer */
        $customerAnswer = $this->customerAnswerFactory->create();
        $this->resource->load($customerAnswer, $customerAnswerId);
        if (!$customerAnswer->getId()) {
            throw new NoSuchEntityException(__(' The Answer id "%1" does not exist.', $customerAnswerId));
        }
        return $customerAnswer;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Buildateam\Quiz\Model\CustomerAnswer $customerAnswer)
    {
        // TODO: Implement delete() method.
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($customerAnswerId)
    {
        // TODO: Implement deleteById() method.
    }
}
