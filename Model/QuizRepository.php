<?php
namespace Buildateam\Quiz\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class QuizRepository implements \Buildateam\Quiz\Api\QuizRepositoryInterface
{
    /**
     * @var ResourceModel\Quiz
     */
    protected $resource;

    /**
     * @var QuizFactory
     */
    protected $quizFactory;

    /**
     * QuizRepository constructor.
     * @param ResourceModel\Quiz $resource
     * @param QuizFactory $quizFactory
     */
    public function __construct(ResourceModel\Quiz $resource, QuizFactory $quizFactory)
    {
        $this->resource = $resource;
        $this->quizFactory = $quizFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Quiz $quiz)
    {
        try {
            $this->resource->save($quiz);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $quiz;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($quizId)
    {
        $quiz = $this->quizFactory->create();
        $this->resource->load($quiz, $quizId);
        if (!$quiz->getId()) {
            throw new NoSuchEntityException(__(' The quiz with id "%1" does not exist.', $quizId));
        }
        return $quiz;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Buildateam\Quiz\Model\Quiz $quiz)
    {
        try {
            $this->resource->delete($quiz);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($quizId)
    {
        $this->delete($this->getById($quizId));
    }
}