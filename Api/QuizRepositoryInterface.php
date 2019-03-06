<?php
namespace Buildateam\Quiz\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface QuizRepositoryInterface
{
    /**
     * Create or update the value
     * @param \Buildateam\Quiz\Model\Quiz $quiz
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(\Buildateam\Quiz\Model\Quiz $quiz);

    /**
     * @param  int $quizId
     * @return \Buildateam\Quiz\Model\Quiz $quiz
     * @throws NoSuchEntityException
     */
    public function getById($quizId);

    /**
     * Delete given the model
     * @param \Buildateam\Quiz\Model\Quiz $quiz
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(\Buildateam\Quiz\Model\Quiz $quiz);
    /**
     * Delete by Id
     * @param $quizId
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function deleteById($quizId);
}