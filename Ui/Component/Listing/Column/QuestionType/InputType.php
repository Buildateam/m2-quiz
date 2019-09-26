<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 08-04-19
 * Time: 05:03 PM
 */

namespace Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType;

/**
 * Class InputType
 * @package Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType
 */
class InputType implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ["value" => "select", "label" => "select"],
            ["value" => "text", "label" => "text"],
            ["value" => "multiselect", "label" => "multiselect"]
        ];
    }
}
