<?php

namespace Buildateam\Quiz\Model;

/**
 * Class Question
 * @package Buildateam\Quiz\Model
 */
class Question extends \Magento\Framework\Model\AbstractModel
{
    const QUESTION_ID = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'question';

    /**
     * @var string
     */
    protected $_eventObject = 'question';

    /**
     * @var string
     */
    protected $_idFieldName = self::QUESTION_ID;

    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\ResourceModel\Question::class);
    }
}
