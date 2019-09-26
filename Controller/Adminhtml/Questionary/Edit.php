<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 08-04-19
 * Time: 10:51 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\Questionary;

/**
 * Class Edit
 * @package Buildateam\Quiz\Controller\Adminhtml\Questionary
 */
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
     * @var \Buildateam\Quiz\Model\quizRepository
     */
    protected $quizRepository;

    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Buildateam\Quiz\Model\QuizRepository $quizRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Buildateam\Quiz\Model\QuizRepository $quizRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->quizRepository = $quizRepository;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('quiz_id');

        try {
            if ($id) {
                $quizModel = $this->quizRepository->getById($id);
                $this->registry->register('buildateam_quiz', $quizModel);
            }
        } catch (\Exception $e) {
            $this->messageManager->addSuccessMessage(__('The questionary does not exists.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/index/*');
        }
        $resultPage = $this->resultPageFactory->create();
        if (!$id) {
            $block = $resultPage->getLayout()->getBlock('quiz_question_listing');
            if ($block) {
                $resultPage->getLayout()->unsetElement('quiz_question_listing');
            }
        }
        $resultPage->setActiveMenu('Buildateam_Quiz::quiz_index');
        $resultPage->getConfig()->getTitle()->prepend(__('Questionary'));
        $resultPage->getConfig()->getTitle()
            ->prepend($id ? __('Edit Questionary.') : __('New Questionary.'));
        return $resultPage;
    }
}
