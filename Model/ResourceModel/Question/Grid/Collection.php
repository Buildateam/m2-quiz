<?php

namespace Buildateam\Quiz\Model\ResourceModel\Question\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\RequestInterface;

/**
 * Class Collection
 * @package Buildateam\Quiz\Model\ResourceModel\Question\Grid
 */
class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Collection constructor.
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param RequestInterface $request
     * @param string $mainTable
     * @param string $resourceModel
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        RequestInterface $request,
        $mainTable = 'buildateam_quiz_questions',
        $resourceModel = \Buildateam\Quiz\Model\ResourceModel\Question::class
    ) {
        $this->request = $request;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * {@inheritdoc}
     */
    public function _beforeLoad()
    {

        $this->getSelect()->joinLeft(
            ['qqt' => 'buildateam_quiz_questions_types'],
            'qqt.entity_id = main_table.type_id',
            ['type_title' => 'qqt.title']
        );
        return parent::_beforeLoad();
    }
}
