<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 08-04-19
 * Time: 05:03 PM
 */

namespace Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType;


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
            array("value" => "select", "label" => "select"),
            array("value" => "text", "label" => "text"),
            array("value" => "multiselect", "label" => "multiselect")
        );
    }

}
