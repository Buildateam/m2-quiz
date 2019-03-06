<?php
namespace Buildateam\Quiz\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface AnswerRepositoryInterface
{
    /**
     * Create or update the value
     * @param \Buildateam\Quiz\Model\Answer $answer
     * @return \Buildateam\Quiz\Model\Answer
     * @throws CouldNotSaveException
     */
    public function save(\Buildateam\Quiz\Model\Answer $answer);

    /**
     * @param  int $answerId
     * @return \Buildateam\Quiz\Model\Answer
     * @throws NoSuchEntityException
     */
    public function getById($answerId);

    /**
     * Delete given the model
     * @param \Buildateam\Quiz\Model\Answer $answer
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(\Buildateam\Quiz\Model\Answer $answer);
    /**
     * Delete by Id
     * @param $answerId
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function deleteById($answerId);
}