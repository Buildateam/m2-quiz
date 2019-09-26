<?php

namespace Buildateam\Quiz\Model;

use \Magento\Framework\Serialize\SerializerInterface;

/**
 * Class CustomerAnswer
 * @package Buildateam\Quiz\Model
 */
class CustomerAnswer extends \Magento\Framework\Model\AbstractModel
{
    const ANSWER_ID = 'entity_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'quiz_customer_answer';

    /**
     * @var string
     */
    protected $_eventObject = 'quiz_customer_answer';

    /**
     * @var string
     */
    protected $_idFieldName = self::ANSWER_ID;

    /**
     * @var ResourceModel\Answer\Collection
     */
    protected $answerCollection;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * CustomerAnswer constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ResourceModel\Answer\Collection $answerCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Buildateam\Quiz\Model\ResourceModel\Answer\Collection $answerCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection,
        SerializerInterface $serializer,
        array $data = []
    ) {
        $this->serializer = $serializer;
        $this->answerCollection = $answerCollection;
        $this->storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(\Buildateam\Quiz\Model\ResourceModel\CustomerAnswer::class);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getResultUrl()
    {
        if (!$this->hasData('result_url')) {
            $answers = $this->serializer->unserialize($this->getData('answers'));
            $answersId = [];
            foreach ($answers as $answer) {
                $answersId = array_merge($answersId, $answer);
            }
            $answerCollection = $this->answerCollection->addFieldToFilter('entity_id', $answersId)->load();
            $path = $this->storeManager->getStore()->getBaseUrl();
            $params = [];
            foreach ($answerCollection as $quizAnswer) {
                $params[] = $quizAnswer->getAttribute();
            }
            $this->setResultUrl($path . 'catalogsearch/advanced/result/?' . implode('&', $params));
        }
        return $this->getData('result_url');
    }
}
