<?php

namespace Buildateam\Quiz\Block\Adminhtml\Question\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class Delete
 * @package Buildateam\Quiz\Block\Adminhtml\Question\Edit
 */
class Delete extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->registry->registry('buildateam_quiz_question')
            && $this->registry->registry('buildateam_quiz_question')->getId()) {
            $data = [
                'label' => __('Delete Question'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to do this?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        $questionId = $this->registry->registry('buildateam_quiz_question')->getId();
        return $this->getUrl('*/*/delete', ['id' => $questionId]);
    }
}
