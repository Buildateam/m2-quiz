<?php

namespace Buildateam\Quiz\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Question
 * @package Buildateam\Quiz\Model\ResourceModel
 */
class Question extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('buildateam_quiz_questions', 'entity_id');
    }
}
