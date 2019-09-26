<?php
namespace Buildateam\Quiz\Block\Adminhtml\Answer\Edit;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class BackButton
 * @package Buildateam\Quiz\Block\Adminhtml\Answer\Edit
 */
class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @var \Buildateam\Quiz\Model\QuestionRepository
     */
    protected $questionRepository;
    /**
     * BackButton constructor.
     * @param \Buildateam\Quiz\Model\QuestionRepository $questionRepository
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Buildateam\Quiz\Model\QuestionRepository $questionRepository,
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->questionRepository = $questionRepository;
        parent::__construct($context, $registry);
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }
    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        $question = null;
        if ($questionId = $this->request->getParam('question_id')) {
            try {
                $question = $this->questionRepository->getById($questionId);
            } catch (NoSuchEntityException $e) {
                $question = null;
            }
        }
        if ($question) {
            return $this->getUrl('quiz/question/edit/', [
                'question_id' => $question->getId(), 'quiz_id' => $question->getData('quiz_id')]);
        } else {
            return $this->getUrl('quiz/index/index/');
        }
    }
}
