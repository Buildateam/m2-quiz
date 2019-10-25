<?php

namespace Buildateam\Quiz\Controller\Adminhtml\Question;

/**
 * Class Delete
 * @package Buildateam\Quiz\Controller\Adminhtml\Question
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Buildateam\Quiz\Model\QuestionRepository
     */
    protected $questionRepository;

    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Buildateam\Quiz\Model\QuestionRepository $questionRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Buildateam\Quiz\Model\QuestionRepository $questionRepository
    ) {
        $this->questionRepository = $questionRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('question_id');
        $quizId = $this->getRequest()->getParam('quiz_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id == null || empty($id)) {
            $this->messageManager->addErrorMessage(__('Quiz question does not exist'));
            $resultRedirect->setPath('*/index/');
        }
        try {
            $this->questionRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('Quiz Question deleted'));
            return $resultRedirect->setPath('*/index/edit', ['quiz_id' => $quizId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/question/edit', ['question_id' => $id]);
        }
    }
}
