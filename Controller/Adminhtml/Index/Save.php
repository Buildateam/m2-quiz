<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Index;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Buildateam\Quiz\Model\QuizRepository
     */
    protected $quizRepository;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var \Buildateam\Quiz\Model\QuizFactory
     */
    protected $quizFactory;

    /**
     * Save constructor.
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Buildateam\Quiz\Model\QuizRepository $quizRepository
     * @param \Buildateam\Quiz\Model\QuizFactory $quizFactory
     */
    public function __construct(
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Backend\App\Action\Context $context,
        \Buildateam\Quiz\Model\QuizRepository $quizRepository,
        \Buildateam\Quiz\Model\QuizFactory $quizFactory
    ) {
        parent::__construct($context);
        $this->quizRepository = $quizRepository;
        $this->formKeyValidator = $formKeyValidator;
        $this->quizFactory = $quizFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setPath('*/*/');
        }
        $data = $this->getRequest()->getParams();

        if ($id = $this->getRequest()->getParam('entity_id')) {
            try {
                $quiz = $this->quizRepository->getById($id);
                $quiz->setData($data);
                $this->quizRepository->save($quiz);
                $this->messageManager->addSuccessMessage(__('Edited Quiz'));
            } catch (\Exception $e) {
                $quiz = null;
                $this->messageManager->addErrorMessage(__('Error editing the quiz.'));
            }
        } else {
            unset($data['entity_id']);
            /** @var \Buildateam\Quiz\Model\Quiz $quiz */
            $quiz = $this->quizFactory->create();
            try {
                $this->quizRepository->save($quiz->setData($data));
                $this->messageManager->addSuccessMessage(__('Quiz saved'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error saving the quiz.'));
            }
        }
        return $resultRedirect->setPath('*/*/index');
    }
}