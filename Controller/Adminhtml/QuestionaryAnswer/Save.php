<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 10-04-19
 * Time: 11:01 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\QuestionaryAnswer;

/**
 * Class Save
 * @package Buildateam\Quiz\Controller\Adminhtml\QuestionaryAnswer
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * @var \Buildateam\Quiz\Api\AnswerRepositoryInterface
     */
    protected $answerRepository;
    /**
     * @var \Buildateam\Quiz\Model\ImageUploader
     */
    protected $imageUploader;

    /**
     * @var \Buildateam\Quiz\Model\AnswerFactory
     */
    protected $answerFactory;

    /**
     * Save constructor.
     * @param \Buildateam\Quiz\Api\AnswerRepositoryInterface $answerRepository
     * @param \Buildateam\Quiz\Model\AnswerFactory $answerFactory
     * @param \Buildateam\Quiz\Model\ImageUploader $imageUploader
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Buildateam\Quiz\Api\AnswerRepositoryInterface $answerRepository,
        \Buildateam\Quiz\Model\AnswerFactory $answerFactory,
        \Buildateam\Quiz\Model\ImageUploader $imageUploader,
        \Magento\Backend\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $context->getFormKeyValidator();
        $this->answerRepository = $answerRepository;
        $this->imageUploader = $imageUploader;
        $this->answerFactory = $answerFactory;
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
        $image = null;
        if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {
            $image = $data['image'][0]['name'];
            $this->imageUploader->moveFileFromTmp($image);
        } elseif (isset($data['image'][0]['image']) && !isset($data['image'][0]['tmp_name'])) {
            $image = $data['image'][0]['image'];
        }
        $data['image'] = $image;
        $id = $this->getRequest()->getParam('entity_id');
        $questionId = $data['question_id'];
        $quizId = $this->getRequest()->getParam('quiz_id');
        if ($id) {
            try {
                $answer = $this->answerRepository->getById($id);
                $answer->setData($data);
                $this->answerRepository->save($answer);
                $this->messageManager->addSuccessMessage(__('Edited Answer Successfully.'));
            } catch (\Exception $e) {
                $quiz = null;
                $this->messageManager->addErrorMessage(__('Error editing the Answer.'));
            }
        } else {
            unset($data['entity_id']);
            /** @var \Buildateam\Quiz\Model\Answer $answer */
            $answer = $this->answerFactory->create();
            try {
                $answer->setData($data);
                $id = $this->answerRepository->save($answer)->getId();
                $this->messageManager->addSuccessMessage(__('Answer saved successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('Error saving the Answer.'));
            }
        }
        if ($questionId && isset($data['back']) && $data['back'] == 'edit') {
            return $resultRedirect->setPath('*/questionaryanswer/edit', [
                'answer_id' => $id,
                'question_id' => $questionId,
                '_current' => true
            ]);
        } else {
            return $resultRedirect->setPath('*/questionaryquestion/edit', [
                'quiz_id' => $quizId,
                'question_id' => $questionId,
                '_current' => true
            ]);
        }
    }
}
