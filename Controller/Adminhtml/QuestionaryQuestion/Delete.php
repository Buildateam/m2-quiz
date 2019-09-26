<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 10-04-19
 * Time: 09:48 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\QuestionaryQuestion;

/**
 * Class Delete
 * @package Buildateam\Quiz\Controller\Adminhtml\QuestionaryQuestion
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
            $this->messageManager->addErrorMessage(__('Questionary question does not exist'));
            $resultRedirect->setPath('*/index/');
        }
        try {
            $this->questionRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('Questionary Question deleted'));
            return $resultRedirect->setPath('*/questionary/edit', ['quiz_id' => $quizId]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/questionaryquestion/edit', ['id' => $id]);
        }
    }
}
