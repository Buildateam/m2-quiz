<?php
namespace Buildateam\Quiz\Block\Adminhtml\Quiz\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

class AddNewQuestion extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        if ($this->registry->registry('buildateam_quiz') && $this->registry->registry('buildateam_quiz')->getId()) {
            $quizId = $this->registry->registry('buildateam_quiz')->getId();
            return [
                'label' => __('Add New Question'),
                'class' => 'reset add-new-question',
                'on_click' => sprintf("location.href = '%s';", $this->getUrl('*/question/new', ['quiz_id' => $quizId])),
                'sort_order' => 60,
            ];
        }
        return [];
    }
}