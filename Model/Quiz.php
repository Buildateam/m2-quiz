<?php

namespace Buildateam\Quiz\Model;

/**
 * Class Quiz
 * @package Buildateam\Quiz\Model
 */
class Quiz extends \Magento\Framework\Model\AbstractModel
{
    const QUIZ_ID = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'quiz';

    /**
     * @var string
     */
    protected $_eventObject = 'quiz';

    /**
     * @var string
     */
    protected $_idFieldName = self::QUIZ_ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\ResourceModel\Quiz::class);
    }
}
