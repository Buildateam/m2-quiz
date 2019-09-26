<?php

namespace Buildateam\Quiz\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class QuestionTypeRepository
 * @package Buildateam\Quiz\Model
 */
class QuestionTypeRepository implements \Buildateam\Quiz\Api\QuestionTypeRepositoryInterface
{
    /**
     * @var ResourceModel\Question\Type
     */
    protected $resource;
    /**
     * @var Question\Type
     */
    protected $questionTypeFactory;

    /**
     * QuestionRepository constructor.
     * @param \Buildateam\Quiz\Model\ResourceModel\Question\Type $resource
     * @param \Buildateam\Quiz\Model\Question\TypeFactory $questionTypeFactory
     */
    public function __construct(
        ResourceModel\Question\Type $resource,
        Question\TypeFactory $questionTypeFactory
    ) {
        $this->resource = $resource;
        $this->questionTypeFactory = $questionTypeFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Question\Type $questionType)
    {
        try {
            $this->resource->save($questionType);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $questionType;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($questionTypeId)
    {
        /** @var \Buildateam\Quiz\Model\Question\Type $questionType */
        $questionType = $this->questionTypeFactory->create();
        $this->resource->load($questionType, $questionTypeId);
        if (!$questionType->getId()) {
            throw new NoSuchEntityException(__(' The Question type with id "%1" does not exist.', $questionTypeId));
        }
        return $questionType;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Question\Type $question)
    {
        // TODO: Implement delete() method.
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($questionId)
    {
        // TODO: Implement deleteById() method.
    }
}
