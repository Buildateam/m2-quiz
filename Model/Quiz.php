<?php
namespace Buildateam\Quiz\Model;

class Quiz extends \Magento\Framework\Model\AbstractModel
{
    const QUIZ_ID = 'entity_id';

    protected $_eventPrefix = 'quiz';
    protected $_eventObject = 'quiz';
    protected $_idFieldName = self::QUIZ_ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Buildateam\Quiz\Model\ResourceModel\Quiz');
    }
}