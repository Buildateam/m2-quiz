<?php

namespace Buildateam\Quiz\Model\ResourceModel\Quiz;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Buildateam\Quiz\Model\ResourceModel\Quiz
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = \Buildateam\Quiz\Model\Quiz::QUIZ_ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\Quiz::class, \Buildateam\Quiz\Model\ResourceModel\Quiz::class);
    }
}