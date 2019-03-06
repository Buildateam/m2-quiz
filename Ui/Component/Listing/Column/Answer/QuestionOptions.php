<?php
namespace Buildateam\Quiz\Ui\Component\Listing\Column\Answer;

use Magento\Framework\Exception\NoSuchEntityException;

class QuestionOptions implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Question\Type\CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;
    /**
     * @var \Buildateam\Quiz\Model\QuestionRepository
     */
    protected $questionRepository;
    /**
     * Options constructor.
     * @param \Buildateam\Quiz\Model\ResourceModel\Question\CollectionFactory $collectionFactory
     * @param \Buildateam\Quiz\Model\QuestionRepository $questionRepository
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Buildateam\Quiz\Model\ResourceModel\Question\CollectionFactory $collectionFactory,
        \Buildateam\Quiz\Model\QuestionRepository $questionRepository,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->questionRepository = $questionRepository;
        $this->collectionFactory = $collectionFactory;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }

    /**
     * Retrieve attribute options
     *
     * @return array
     */
    protected function getOptions()
    {
        $response = [];
        $questionId = $this->request->getParam('question_id');
        $question = null;
        $isMultiple = false;
        $quizId = 0;
        try {
            $question = $this->questionRepository->getById($questionId);
            if ($question->getData('multiple')) {
                $isMultiple = true;
                $quizId = $question->getData('quiz_id');
            }
        } catch (NoSuchEntityException $e) {
            $question = null;
        }
        /** @var \Buildateam\Quiz\Model\ResourceModel\Question\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('quiz_id', ['eq' => $quizId])
                   ->addFieldToFilter('multiple', ['eq' => 1])
                   ->addFieldToFilter('entity_id', ['neq' => $questionId])->load();
        /** @var \Buildateam\Quiz\Model\Answer $item */
        foreach ($collection as $item) {
            $response[] = [
                'label' => $item->getData('title'),
                'value' => $item->getId()
            ];
        }
        if (!$isMultiple) {
            $response[] = [
                'label' => __('The question is not multiple.'),
                'value' => ''
            ];
        } elseif (count($response) == 0) {
            $response[] = [
                'label' => __('There are no more answers.'),
                'value' => ''
            ];
        }
        return $response;
    }
}
