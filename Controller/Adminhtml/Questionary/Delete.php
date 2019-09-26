<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 09-04-19
 * Time: 11:12 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\Questionary;

/**
 * Class Delete
 * @package Buildateam\Quiz\Controller\Adminhtml\Questionary
 */
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
            $this->messageManager->addErrorMessage(__('Questionary does not exist'));
            return $resultRedirect->setPath('*/*/');
        }
        try {
            $this->quizRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('Questionary deleted'));
            return $resultRedirect->setPath('*/*/');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }
    }
}
