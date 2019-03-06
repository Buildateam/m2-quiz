<?php
namespace Buildateam\Quiz\Api;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CustomerAnswerRepositoryInterface
{
    /**
     * Create or update the value
     * @param \Buildateam\Quiz\Model\CustomerAnswer $customerAnswer
     * @return void
     * @throws CouldNotSaveException
     */
    public function save(\Buildateam\Quiz\Model\CustomerAnswer $customerAnswer);

    /**
     * @param  int $customerAnswerId
     * @return \Buildateam\Quiz\Model\CustomerAnswer
     * @throws NoSuchEntityException
     */
    public function getById($customerAnswerId);

    /**
     * Delete given the model
     * @param \Buildateam\Quiz\Model\CustomerAnswer $customerAnswer
     * @return void
     * @throws CouldNotDeleteException
     */
    public function delete(\Buildateam\Quiz\Model\CustomerAnswer $customerAnswer);
    /**
     * Delete by Id
     * @param int $customerAnswerId
     * @return void
     * @throws CouldNotDeleteException|NoSuchEntityException
     */
    public function deleteById($customerAnswerId);
}