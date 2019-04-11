<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 10-04-19
 * Time: 05:26 PM
 */

namespace Buildateam\Quiz\Ui\Component\Listing\Column\Answer;


class InputType implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array('label' => __('Multi select'), 'value' => 'multiselect'),
            array('label' => __('Select'), 'value' => 'select'),
            array('label' => __('Text'), 'value' => 'text'),
        );
    }
}