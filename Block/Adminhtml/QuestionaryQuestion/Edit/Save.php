<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 09-04-19
 * Time: 06:21 PM
 */

namespace Buildateam\Quiz\Block\Adminhtml\QuestionaryQuestion\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class Save
 * @package Buildateam\Quiz\Block\Adminhtml\QuestionaryQuestion\Edit
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
