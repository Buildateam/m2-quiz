<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 10-04-19
 * Time: 10:17 AM
 */

namespace Buildateam\Quiz\Block\Adminhtml\QuestionaryQuestion\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

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
                    $this->getUrl('*/questionaryanswer/new', ['question_id' => $questionId])
                ),
                'sort_order' => 60,
            ];
        }
        return [];
    }
}
