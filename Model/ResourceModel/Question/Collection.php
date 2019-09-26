<?php

namespace Buildateam\Quiz\Model\ResourceModel\Question;

/**
 * Class Collection
 * @package Buildateam\Quiz\Model\ResourceModel\Question
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = \Buildateam\Quiz\Model\Question::QUESTION_ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Buildateam\Quiz\Model\Question::class,
            \Buildateam\Quiz\Model\ResourceModel\Question::class
        );
    }

    /**
     * @param int $quizId
     * @return $this
     */
    public function getByQuizId($quizId)
    {
        $this->getSelect()->where('quiz_id = ?', $quizId)->order('sort ASC');
        return $this;
    }
}
