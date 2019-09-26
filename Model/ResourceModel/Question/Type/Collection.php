<?php
namespace Buildateam\Quiz\Model\ResourceModel\Question\Type;

use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Buildateam\Quiz\Model\ResourceModel\Question\Type
 */
class Collection extends AbstractCollection
{
    protected $_idFieldName = \Buildateam\Quiz\Model\Question\Type::TYPE_ID;
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Buildateam\Quiz\Model\Question\Type', 'Buildateam\Quiz\Model\ResourceModel\Question\Type');
    }
}
