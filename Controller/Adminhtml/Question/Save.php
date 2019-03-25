<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Question;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Buildateam\Quiz\Model\QuestionRepository
     */
    protected $repository;
    /**
     * @var \Buildateam\Quiz\Model\QuestionFactory
     */
    protected $questionFactory;
    /**
     * Save constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Buildateam\Quiz\Model\QuestionRepository $repository
     * @param \Buildateam\Quiz\Model\QuestionFactory $questionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Buildateam\Quiz\Model\QuestionRepository $repository,
        \Buildateam\Quiz\Model\QuestionFactory $questionFactory
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->questionFactory = $questionFactory;
    }
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }
        $data = $this->getRequest()->getParams();
        $questionId = null;
        $quizId = null;
        if ($id = $this->getRequest()->getParam('entity_id')) {
            try {
                $question = $this->repository->getById($id);
                $question->setData($data);
                $this->repository->save($question);
                $questionId = $question->getId();
                $quizId = $question->getData('quiz_id');
                $this->messageManager->addSuccessMessage(__('Edited Question Successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error editing the question.'));
            }
        } else {
            unset($data['entity_id']);
            /** @var \Buildateam\Quiz\Model\Question $question */
            $question = $this->questionFactory->create();
            try {
                $this->repository->save($question->setData($data));
                $questionId = $question->getId();
                $quizId = $question->getData('quiz_id');
                $this->messageManager->addSuccessMessage(__('Question saved successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error saving the question.'));
            }
        }
        if($questionId && isset($data['back']) && $data['back'] == 'edit') {
            return $resultRedirect->setPath('*/*/edit', ['quiz_id' => $quizId, 'question_id' => $questionId]);
        } else {
            return $resultRedirect->setPath('quiz/index/edit', ['quiz_id' => $quizId]);
        }
    }
}