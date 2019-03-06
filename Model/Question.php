<?php
namespace Buildateam\Quiz\Model;

class Question extends \Magento\Framework\Model\AbstractModel
{
    const QUESTION_ID = 'entity_id';

    protected $_eventPrefix = 'question';
    protected $_eventObject = 'question';
    protected $_idFieldName = self::QUESTION_ID;

    protected function _construct()
    {
        $this->_init('Buildateam\Quiz\Model\ResourceModel\Question');
    }
}