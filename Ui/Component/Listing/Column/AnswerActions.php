<?php

namespace Buildateam\Quiz\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class AnswerActions
 * @package Buildateam\Quiz\Ui\Component\Listing\Column
 */
class AnswerActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * QuizIndexActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'quiz/answer/edit',
                        [
                            'answer_id' => $item['entity_id'],
                            'question_id' => $item['question_id'],
                            'quiz_id' => $item['quiz_id']
                        ]
                    ),
                    'label' => __('Edit'),
                    'hidden' => false,
                ];
                $item[$this->getData('name')]['delete'] = [
                    'href' => $this->urlBuilder->getUrl('quiz/answer/delete', ['answer_id' => $item['entity_id']]),
                    'label' => __('Delete'),
                    'hidden' => false,
                ];
            }
        }
        return $dataSource;
    }
}
