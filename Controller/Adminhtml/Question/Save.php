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
        var_dump($data);
        if ($id = $this->getRequest()->getParam('entity_id')) {
            try {
                $question = $this->repository->getById($id);
                $question->setData($data);
                $this->repository->save($question);
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
                $this->messageManager->addSuccessMessage(__('Question saved successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error saving the question.'));
            }
        }
        return $resultRedirect->setPath('quiz/index/edit', ['quiz_id' => $data['quiz_id']]);
    }
}