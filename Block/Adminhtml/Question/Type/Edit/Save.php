<?php

namespace Buildateam\Quiz\Block\Adminhtml\Question\Type\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class Save
 * @package Buildateam\Quiz\Block\Adminhtml\Question\Type\Edit
 */
class Save extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
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
