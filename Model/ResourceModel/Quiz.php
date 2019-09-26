<?php

namespace Buildateam\Quiz\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Quiz
 * @package Buildateam\Quiz\Model\ResourceModel
 */
class Quiz extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('buildateam_quiz', 'entity_id');
    }
}
