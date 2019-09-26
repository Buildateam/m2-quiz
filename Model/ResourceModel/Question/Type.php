<?php

namespace Buildateam\Quiz\Model\ResourceModel\Question;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Type
 * @package Buildateam\Quiz\Model\ResourceModel\Question
 */
class Type extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('buildateam_quiz_questions_types', 'entity_id');
    }
}
