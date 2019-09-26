<?php

namespace Buildateam\Quiz\Block\Adminhtml\Question\Edit;

/**
 * Class Answer
 * @package Buildateam\Quiz\Block\Adminhtml\Question\Edit
 */
class Answer extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * Answer constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    public function getGridHtml()
    {
        if ($this->_coreRegistry->registry('buildateam_quiz_question') === null) {
            return false;
        }
        return $this->getChildHtml('buildateam.quiz.question.edit.answers.grid');
    }
}
