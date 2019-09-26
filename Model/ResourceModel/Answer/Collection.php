<?php

namespace Buildateam\Quiz\Model\ResourceModel\Answer;

/**
 * Class Collection
 * @package Buildateam\Quiz\Model\ResourceModel\Answer
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = \Buildateam\Quiz\Model\Answer::ANSWER_ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\Answer::class, \Buildateam\Quiz\Model\ResourceModel\Answer::class);
    }

    /**
     * @param int $questionId
     * @return $this
     */
    public function getByQuestionId($questionId)
    {
        $this->getSelect()->where('question_id = ?', $questionId)->order('sort ASC');
        return $this;
    }
}
