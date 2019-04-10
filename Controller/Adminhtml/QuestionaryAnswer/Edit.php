<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 10-04-19
 * Time: 10:27 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\QuestionaryAnswer;


class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry ;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var \Buildateam\Quiz\Model\AnswerRepository
     */
    protected $answerRepository;
    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Buildateam\Quiz\Model\AnswerRepository $answerRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Buildateam\Quiz\Model\AnswerRepository $answerRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->answerRepository = $answerRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('answer_id');
        if ($id) {
            try {
                $answerModel = $this->answerRepository->getById($id);
                $this->registry->register('buildateam_answer', $answerModel);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error loading the answer'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('quiz/questionary/index');
            }
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Buildateam_Quiz::quiz_index');
        $resultPage->getConfig()->getTitle()->prepend(__('Answer'));
        $resultPage->getConfig()->getTitle()
            ->prepend($id ? __('Edit Answer') : __('New Answer'));
        return $resultPage;
    }

}
