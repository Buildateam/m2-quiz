<?php

namespace Buildateam\Quiz\Controller\Adminhtml\Answer;

use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Buildateam\Quiz\Controller\Adminhtml\Answer
 */
class Delete extends Action
{
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory
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
     * @param \Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory $answerFactory
     * @param \Buildateam\Quiz\Model\ResourceModel\Answer $resourceAnswer
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        Action\Context $context,
        \Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory $answerFactory,
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
            $answerCollection = $this->answerFactory->create();
            /** @var \Buildateam\Quiz\Model\Answer $answerModel */
            $answerModel = $answerCollection->addFieldToFilter('entity_id', $id)->getFirstItem();
            $items = $answerModel->getData();
            $questionId = $items['question_id'];
            if ($items['image']) {
                $this->filesystem
                    ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)
                    ->delete('quiz/' . $items['image']);
            }
            $this->resourceAnswer->delete($answerModel);
            $this->messageManager->addSuccessMessage(__('Answer deleted'));
            return $resultRedirect->setPath('*/question/edit', ['question_id' => $questionId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/answer/edit', ['id' => $id]);
        }
    }
}
