<?php
namespace Buildateam\Quiz\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface QuestionRepositoryInterface
{
    /**
     * Create or update the value
     * @param \Buildateam\Quiz\Model\Question $question
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(\Buildateam\Quiz\Model\Question $question);

    /**
     * @param  int $questionId
     * @return \Buildateam\Quiz\Model\Question $question
     * @throws NoSuchEntityException
     */
    public function getById($questionId);

    /**
     * Delete given the model
     * @param \Buildateam\Quiz\Model\Question $question
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(\Buildateam\Quiz\Model\Question $question);
    /**
     * Delete by Id
     * @param $questionId
     * @return void
     * @throws CouldNotDeleteException | NoSuchEntityException
     */
    public function deleteById($questionId);
}