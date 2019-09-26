<?php

namespace Buildateam\Quiz\Model\Config\Source;

/**
 * Class UsedQuiz
 * @package Buildateam\Quiz\Model\Config\Source
 */
class UsedQuiz implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Quiz\Collection
     */
    private $quizCollection;

    /**
     * UsedQuiz constructor.
     * @param \Buildateam\Quiz\Model\ResourceModel\Quiz\Collection $quizCollection
     */
    public function __construct(\Buildateam\Quiz\Model\ResourceModel\Quiz\Collection $quizCollection)
    {
        $this->quizCollection = $quizCollection;
    }
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $quizzes = $this->quizCollection->addFieldToFilter('is_active', ['eq' => 1]);
        $resultArray = [];
        /** @var  \Buildateam\Quiz\Model\Quiz $quiz */
        foreach ($quizzes as $quiz) {
            $resultArray[$quiz->getId()] = $quiz->getData('title');
        }
        return $resultArray;
    }
}
