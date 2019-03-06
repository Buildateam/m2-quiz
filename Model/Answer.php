<?php
namespace Buildateam\Quiz\Model;

class Answer extends \Magento\Framework\Model\AbstractModel
{
    const ANSWER_ID = 'entity_id';

    protected $_eventPrefix = 'answer';
    protected $_eventObject = 'answer';
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Buildateam\Quiz\Model\ResourceModel\Answer');
    }

}