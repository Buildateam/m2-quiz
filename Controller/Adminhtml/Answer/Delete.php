<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Answer;

use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var \Buildateam\Quiz\Model\AnswerFactory
     */
    protected $answerFactory;
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Answer
     */
    protected $resourceAnswer;
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param \Buildateam\Quiz\Model\AnswerFactory $answerFactory
     * @param \Buildateam\Quiz\Model\ResourceModel\Answer $resourceAnswer
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        Action\Context $context,
        \Buildateam\Quiz\Model\AnswerFactory $answerFactory,
        \Buildateam\Quiz\Model\ResourceModel\Answer $resourceAnswer,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->answerFactory = $answerFactory;
        $this->resourceAnswer = $resourceAnswer;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('answer_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id === null || empty($id)) {
            $this->messageManager->addErrorMessage(__('Answer does not exist.'));
            return $resultRedirect->setPath('*/index/');
        }
        try {
            /** @var \Buildateam\Quiz\Model\Answer $answerModel */
            $answerModel = $this->answerFactory->create();
            $answerModel = $this->resourceAnswer->load($answerModel, $id);
            $questionId = $answerModel->getData('question_id');
            if ($answerModel->getData('image')) {
                $this->filesystem
                    ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
                    ->delete('quiz/'.$answerModel->getData('image'));
            }
            $this->resourceAnswer->delete($answerModel);
            $this->messageManager->addSuccessMessage(__('Answer deleted'));
            return $resultRedirect->setPath('*/question/edit', ['id' => $questionId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/answer/edit', ['id' => $id]);
        }
    }
}