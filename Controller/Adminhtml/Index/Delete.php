<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Index;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Buildateam\Quiz\Model\QuizRepository
     */
    protected $quizRepository;
    /**
     * Delete constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Buildateam\Quiz\Model\QuizRepository $quizRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Buildateam\Quiz\Model\QuizRepository $quizRepository
    ) {
        parent::__construct($context);
        $this->quizRepository = $quizRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('quiz_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id === null || empty($id)) {
            $this->messageManager->addErrorMessage(__('Quiz does not exist'));
            return $resultRedirect->setPath('*/*/');
        }
        try {
            $this->quizRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('Quiz deleted'));
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
    }
}