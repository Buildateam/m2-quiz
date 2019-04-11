<?php
namespace Buildateam\Quiz\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AnswerRepository implements \Buildateam\Quiz\Api\AnswerRepositoryInterface
{
    /**
     * @var ResourceModel\Answer
     */
    protected $resource;

    /**
     * @var AnswerFactory
     */
    protected $answerFactory;
    /**
     * QuestionRepository constructor.
     * @param ResourceModel\Answer $resource
     * @param AnswerFactory $answerFactory
     */
    public function __construct(ResourceModel\Answer $resource, AnswerFactory $answerFactory)
    {
        $this->resource = $resource;
        $this->answerFactory = $answerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(Answer $answer)
    {
        try {
            $this->resource->save($answer);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $answer;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($answerId)
    {
        /** @var \Buildateam\Quiz\Model\Answer $answer */
        $answer = $this->answerFactory->create();
        $this->resource->load($answer, $answerId);
        if (!$answer->getId()) {
            throw new NoSuchEntityException(__(' The Answer id "%1" does not exist.', $answerId));
        }
        return $answer;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Buildateam\Quiz\Model\Answer $answer)
    {
        try {
            $this->resource->delete($answer);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($answerId)
    {
        return $this->delete($this->getById($answerId));
    }
}