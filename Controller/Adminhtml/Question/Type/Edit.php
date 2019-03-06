<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Question\Type;


class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Buildateam\Quiz\Model\QuestionTypeRepository
     */
    protected $questionTypeRepository;
    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Buildateam\Quiz\Model\QuestionTypeRepository $questionTypeRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Buildateam\Quiz\Model\QuestionTypeRepository $questionTypeRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->questionTypeRepository = $questionTypeRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $questionType = $this->questionTypeRepository->getById($id);
                $this->registry->register('buildateam_quiz_question_type', $questionType);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('This quiz question type not exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/question_type/');
            }
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Quiz Question Type'));
        $resultPage->getConfig()->getTitle()
            ->prepend($id ? __('Edit Quiz Question Type') : __('New Quiz Question Type'));
        return $resultPage;
    }
}