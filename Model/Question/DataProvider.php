<?php

namespace Buildateam\Quiz\Model\Question;

use Buildateam\Quiz\Model\ResourceModel\Question\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Question\Type\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var $quiz \Buildateam\Quiz\Model\Question */
        foreach ($items as $quiz) {
            $this->loadedData[$quiz->getId()] = $quiz->getData();
        }

        $data = $this->dataPersistor->get('buildateam_quiz_question');
        if (!empty($data)) {
            $quiz = $this->collection->getNewEmptyItem();
            $quiz->setData($data);
            $this->loadedData[$quiz->getId()] = $quiz->getData();
            $this->dataPersistor->clear('buildateam_quiz_question');
        } else {
            $this->loadedData[null] = ['quiz_id' => $this->request->getParam('quiz_id')];
        }
        return $this->loadedData;
    }
}
