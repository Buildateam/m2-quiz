<?php
namespace Buildateam\Quiz\Model\Quiz\Answer;

use Buildateam\Quiz\Model\ResourceModel\Answer\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Answer\Collection
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

    protected $storeManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
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
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->request = $request;
        $this->storeManager = $storeManager;
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
     * @throws \Exception
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $baseUrl =  $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $items = $this->collection->getItems();
        /** @var $answer \Buildateam\Quiz\Model\Answer */
        foreach ($items as $answer) {
            $data = $answer->getData();
            $image[0]['image'] = $data['image'];
            $image[0]['url'] = $baseUrl.'quiz/'.$data['image'];
            $data['image'] = $image;
            $this->loadedData[$answer->getId()] = $data;
        }
        $data = $this->dataPersistor->get('buildateam_answer');
        if (!empty($data)) {
            $answer = $this->collection->getNewEmptyItem();
            $answer->setData($data);
            $this->loadedData[$answer->getId()] = $answer->getData();
            $this->dataPersistor->clear('buildateam_answer');
        } else {
            $this->loadedData[null] = ['question_id' => $this->request->getParam('question_id')];
        }
        return $this->loadedData;
    }
}