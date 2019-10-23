<?php

namespace Buildateam\Quiz\Model;

use Buildateam\Quiz\Model\ResourceModel\Answer\Collection;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class CustomerAnswer
 * @package Buildateam\Quiz\Model
 */
class CustomerAnswer extends AbstractModel
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
    private $answerCollection;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * CustomerAnswer constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ResourceModel\Answer\Collection $answerCollection
     * @param StoreManagerInterface $storeManager
     * @param SerializerInterface $serializer
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Collection $answerCollection,
        StoreManagerInterface $storeManager,
        SerializerInterface $serializer,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->serializer = $serializer;
        $this->answerCollection = $answerCollection;
        $this->storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init(ResourceModel\CustomerAnswer::class);
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
