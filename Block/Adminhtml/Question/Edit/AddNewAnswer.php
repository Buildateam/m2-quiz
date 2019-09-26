<?php
namespace Buildateam\Quiz\Block\Adminhtml\Question\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class AddNewAnswer
 * @package Buildateam\Quiz\Block\Adminhtml\Question\Edit
 */
class AddNewAnswer extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        if ($this->registry->registry('buildateam_quiz_question')
            && $this->registry->registry('buildateam_quiz_question')->getId()) {
            $questionId = $this->registry->registry('buildateam_quiz_question')->getId();
            return [
                'label' => __('Add New Answer'),
                'class' => 'reset add-new-answer',
                'on_click' => sprintf(
                    "location.href = '%s';",
                    $this->getUrl('*/answer/new', ['question_id' => $questionId])
                ),
                'sort_order' => 60,
            ];
        }
        return [];
    }
}
