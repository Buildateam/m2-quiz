<?php

namespace Buildateam\Quiz\Ui\Component\Listing\Column;

use \Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

/**
 * Class Answer
 * @package Buildateam\Quiz\Ui\Component\Listing\Column
 */
class Answer extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * Answer constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param SerializerInterface $serializer
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        SerializerInterface $serializer,
        array $components = [],
        array $data = []
    ) {
        $this->serializer = $serializer;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item['answers'] = json_encode($this->serializer->unserialize($item["answers"]));
            }
        }
        return $dataSource;
    }
}
