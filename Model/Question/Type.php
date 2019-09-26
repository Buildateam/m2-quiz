<?php

namespace Buildateam\Quiz\Model\Question;

/**
 * Class Type
 * @package Buildateam\Quiz\Model\Question
 */
class Type extends \Magento\Framework\Model\AbstractModel
{
    const TYPE_ID = 'entity_id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\ResourceModel\Question\Type::class);
    }
}