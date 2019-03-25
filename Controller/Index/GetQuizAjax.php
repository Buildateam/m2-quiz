<?php
namespace Buildateam\Quiz\Controller\Index;

class GetQuizAjax extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Buildateam\Quiz\Api\QuizRepositoryInterface
     */
    protected $quizRepository;
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Question\CollectionFactory
     */
    protected $collectionQuestionFactory;
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory
     */
    protected $collectionAnswerFactory;
    /**
     * GetQuizAjax constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Buildateam\Quiz\Api\QuizRepositoryInterface $quizRepository
     * @param \Buildateam\Quiz\Model\ResourceModel\Question\CollectionFactory $collectionQuestionFactory
     * @param \Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory $collectionAnswerFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Buildateam\Quiz\Api\QuizRepositoryInterface $quizRepository,
        \Buildateam\Quiz\Model\ResourceModel\Question\CollectionFactory $collectionQuestionFactory,
        \Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory $collectionAnswerFactory
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->storeManager = $storeManager;
        $this->quizRepository = $quizRepository;
        $this->collectionQuestionFactory = $collectionQuestionFactory;
        $this->collectionAnswerFactory = $collectionAnswerFactory;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $response = new \Magento\Framework\DataObject();
        $id = $this->getRequest()->getParam('id');
        if ($id === null || empty($id)) {
            $response->setData('success', false);
            $response->setData('message', __('Incorrect Quiz Id.'));
            $resultJson->setJsonData($response->toJson());
            return $resultJson;
        }
        try {
            $quiz = $this->quizRepository->getById($id);
            if (!$quiz->getData('is_active')) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('The Quiz Selected is Disabled.')
                );
            }
            $path = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $path = $path.'quiz/';
            $quizQuestions = [
                'info' => [
                    'name' => $quiz->getData('title'),
                    'main' => $quiz->getData('text')
                ],
                'questions' => []
            ];
            /** @var \Buildateam\Quiz\Model\ResourceModel\Question\Collection $collectionQuestion */
            $collectionQuestion = $this->collectionQuestionFactory->create();
            $collectionQuestion->getByQuizId($id)->load();
            /** @var \Buildateam\Quiz\Model\Question $question */
            foreach ($collectionQuestion as $question) {
                $quizQuestions['questions'][$question->getId()] = [
                    'id'            => $question->getId(),
                    'q'             => $question->getData('title'),
                    'multiple'      => (boolean)$question->getData('multiple'),
                    'sort'          => $question->getData('sort'),
                    'a'             => []
                ];
                /** \Buildateam\Quiz\Model\ResourceModel\Answer\Collection $collectionAnswer */
                $collectionAnswer = $this->collectionAnswerFactory->create();
                /** @var \Buildateam\Quiz\Model\Answer $answer */
                foreach ($collectionAnswer->getByQuestionId($question->getId()) as $answer) {
                    $quizQuestions['questions'][$question->getId()]['a'][] = [
                        'id'        => $answer->getId(),
                        'type'      => $question->getData('type_id'),
                        'title'     => $answer->getData('title'),
                        'img'       => $answer->getData('image') ? $path . $answer->getData('image') : '',
                        'color'     => $answer->getData('color'),
                        'relation'  => $answer->getData('relation_id'),
                        'sort'      => $answer->getData('sort')
                    ];
                }
            }
            $response->setData('success', true);
            $response->setData('quiz', $quizQuestions);
        } catch (\Exception $e) {
            $response->setData('success', false);
            $response->setData('message', __('Incorrect Quiz Id.'));
        }
        $resultJson->setJsonData($response->toJson());
        return $resultJson;
    }
}
