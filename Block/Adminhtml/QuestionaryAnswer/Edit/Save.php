<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 10-04-19
 * Time: 10:59 AM
 */

namespace Buildateam\Quiz\Block\Adminhtml\QuestionaryAnswer\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class Save
 * @package Buildateam\Quiz\Block\Adminhtml\QuestionaryAnswer\Edit
 */
class Save extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Answer'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'save']
                ],
            ],
            'sort_order' => 100,
        ];
    }
}
