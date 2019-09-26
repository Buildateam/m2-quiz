<?php

namespace Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType;

/**
 * Class Options
 * @package Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType
 */
class Options implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var \Buildateam\Quiz\Model\ResourceModel\Question\Type\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Options constructor.
     * @param \Buildateam\Quiz\Model\ResourceModel\Question\Type\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Buildateam\Quiz\Model\ResourceModel\Question\Type\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
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
        if ($this->options === null) {
            /** @var \Buildateam\Quiz\Model\ResourceModel\Question\Type\Collection $collection */
            $collection = $this->collectionFactory->create();
            /** @var \Buildateam\Quiz\Model\Question\Type $item */
            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $item->getData('title'),
                    'value' => $item->getId()
                ];
            }
            if (count($this->options) === 0) {
                $this->options[] = [
                    'label' => __('Please add a Question Type First.'),
                    'value' => ''
                ];
            }
        }
        return $this->options;
    }
}
