<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Question\Type;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Buildateam\Quiz\Model\QuestionTypeRepository
     */
    protected $questionTypeRepository;
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;
    /**
     * @var \Buildateam\Quiz\Model\Question\TypeFactory
     */
    protected $questionTypeFactory;
    /**
     * Save constructor.
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Buildateam\Quiz\Model\QuestionTypeRepository $questionTypeRepository
     * @param \Buildateam\Quiz\Model\Question\TypeFactory $questionTypeFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Buildateam\Quiz\Model\QuestionTypeRepository $questionTypeRepository,
        \Buildateam\Quiz\Model\Question\TypeFactory $questionTypeFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->questionTypeRepository = $questionTypeRepository;
        $this->formKeyValidator = $formKeyValidator;
        $this->questionTypeFactory = $questionTypeFactory;
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
                $questionFactory = $this->questionTypeRepository->getById($id);
                $questionFactory->setData($data);
                $this->questionTypeRepository->save($questionFactory);
                $this->messageManager->addSuccessMessage(__('Edited Question Type.'));
            } catch (\Exception $e) {
                $quiz = null;
                $this->messageManager->addErrorMessage(__('Error editing the Question Type.'));
            }
        } else {
            unset($data['entity_id']);
            /** @var \Buildateam\Quiz\Model\Question\Type $questionType */
            $questionType = $this->questionTypeFactory->create();
            try {
                $this->questionTypeRepository->save($questionType->setData($data));
                $this->messageManager->addSuccessMessage(__('Question Type saved'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error saving the Question Type.'));
            }
        }
        return $resultRedirect->setPath('*/*/index');
    }
}