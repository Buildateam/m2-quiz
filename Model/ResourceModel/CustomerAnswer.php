<?php
namespace Buildateam\Quiz\Model\ResourceModel;

use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerAnswer extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('buildateam_quiz_customer_answers', 'entity_id');
    }
}