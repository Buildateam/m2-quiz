<?php
namespace Buildateam\Quiz\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class QuestionRepository implements \Buildateam\Quiz\Api\QuestionRepositoryInterface
{
    /**
     * @var ResourceModel\Question
     */
    protected $resource;

    /**
     * @var QuestionFactory
     */
    protected $questionFactory;
    /**
     * QuestionRepository constructor.
     * @param ResourceModel\Question $resource
     * @param QuestionFactory $questionFactory
     */
    public function __construct(ResourceModel\Question $resource, QuestionFactory $questionFactory)
    {
        $this->resource = $resource;
        $this->questionFactory = $questionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Question $question)
    {
        try {
            $this->resource->save($question);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $question;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($questionId)
    {
        $question = $this->questionFactory->create();
        $this->resource->load($question, $questionId);
        if (!$question->getId()) {
            throw new NoSuchEntityException(__(' The Questionwith id "%1" does not exist.', $questionId));
        }
        return $question;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Buildateam\Quiz\Model\Question $question)
    {
        try {
            $this->resource->delete($question);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($questionId)
    {
        return $this->delete($this->getById($questionId));
    }
}