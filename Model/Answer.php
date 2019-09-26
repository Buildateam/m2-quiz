<?php

namespace Buildateam\Quiz\Model;

/**
 * Class Answer
 * @package Buildateam\Quiz\Model
 */
class Answer extends \Magento\Framework\Model\AbstractModel
{
    const ANSWER_ID = 'entity_id';
    /**
     * @var string
     */
    protected $_eventPrefix = 'answer';
    /**
     * @var string
     */
    protected $_eventObject = 'answer';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\ResourceModel\Answer::class);
    }
}
