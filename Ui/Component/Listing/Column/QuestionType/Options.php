<?php

namespace Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType;

use Buildateam\Quiz\Model\Question\Type;
use Buildateam\Quiz\Model\ResourceModel\Question\Type\Collection;
use Buildateam\Quiz\Model\ResourceModel\Question\Type\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Options
 * @package Buildateam\Quiz\Ui\Component\Listing\Column\QuestionType
 */
class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Options constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
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
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            /** @var Type $item */
            foreach ($collection as $item) {
                $this->options[] = [
                    'label' => $item->getData('title'),
                    'value' => $item->getId()
                ];
            }
            if (empty($this->options)) {
                $this->options[] = [
                    'label' => __('Please add a Question Type First.'),
                    'value' => ''
                ];
            }
        }
        return $this->options;
    }
}
