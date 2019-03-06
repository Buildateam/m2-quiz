<?php
namespace Buildateam\Quiz\Model\ResourceModel\CustomerAnswer;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = \Buildateam\Quiz\Model\CustomerAnswer::ANSWER_ID;

    protected function _construct()
    {
        $this->_init('Buildateam\Quiz\Model\CustomerAnswer',
            'Buildateam\Quiz\Model\ResourceModel\CustomerAnswer');
    }
    /**
     * @param $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'customer_id') {
                $customerName = $column->getFilter()->getValue();
                if (!empty($customerEmail)) {
                    $this->getCollection()->addFieldToFilter('ce.firstname', array('like' => '%' . $customerName . '%'))->addFieldToFilter('ce.lastname', array('like' => '%' . $customerName . '%'));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}