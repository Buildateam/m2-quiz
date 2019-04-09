<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 09-04-19
 * Time: 03:52 PM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\QuestionaryQuestion;


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
     * @var \Buildateam\Quiz\Model\QuestionRepository
     */
    protected $questionRepository;
    /**
     * Edit constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Buildateam\Quiz\Model\QuestionRepository $questionRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Buildateam\Quiz\Model\QuestionRepository $questionRepository
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->questionRepository = $questionRepository;
        parent::__construct($context);
    }
    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $idQuestion = $this->getRequest()->getParam('question_id');

        try {
            if ($idQuestion) {
                $questionModel = $this->questionRepository->getById($idQuestion);
                $this->registry->register('buildateam_quiz_question', $questionModel);
            }
        } catch (\Exception $e) {
            $this->messageManager->addSuccessMessage(__('The question does not exists.'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/index/*');
        }
        $resultPage = $this->resultPageFactory->create();
        if (!$idQuestion) {
            $block = $resultPage->getLayout()->getBlock('quiz_answer_listing');
            if ($block) {
                $resultPage->getLayout()->unsetElement('quiz_answer_listing');
            }
        }
        $resultPage->getConfig()->getTitle()->prepend(__('Quiz'));
        $resultPage->getConfig()->getTitle()
            ->prepend($idQuestion > 0 ? __('Edit Questionary Question') : __('New Questionary Question'));
        return $resultPage;
    }
}