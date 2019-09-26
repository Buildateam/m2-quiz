<?php
namespace Buildateam\Quiz\Api;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Interface QuestionTypeRepositoryInterface
 * @package Buildateam\Quiz\Api
 */
interface QuestionTypeRepositoryInterface
{
    /**
     * Create or update the value
     * @param \Buildateam\Quiz\Model\Question\Type $questionType
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(\Buildateam\Quiz\Model\Question\Type $questionType);

    /**
     * @param  int $questionTypeId
     * @return \Buildateam\Quiz\Model\Question\Type $question
     * @throws NoSuchEntityException
     */
    public function getById($questionTypeId);

    /**
     * Delete given the model
     * @param \Buildateam\Quiz\Model\Question\Type $questionType
     * @return void
     * @throws \Exception
     */
    public function delete(\Buildateam\Quiz\Model\Question\Type $questionType);
    /**
     * Delete by Id
     * @param int $questionTypeId
     * @return void
     */
    public function deleteById($questionTypeId);
}
