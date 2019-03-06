<?php
namespace Buildateam\Quiz\Model\ResourceModel\CustomerAnswer\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\App\RequestInterface;

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
        $mainTable = 'buildateam_quiz_customer_answers',
        $resourceModel = \Buildateam\Quiz\Model\ResourceModel\CustomerAnswer::class
    ) {
        $this->request =  $request;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * {@inheritdoc}
     */
    public function _beforeLoad()
    {
        $quizAliasName = 'quiz';
        $customerAliasName = 'customer';
        $this->getSelect()->joinLeft(
            [$quizAliasName => $this->getTable('buildateam_quiz')],
            $quizAliasName.'.entity_id = main_table.quiz_id',
            ['quiz_title' => $quizAliasName.'.title' ]
        )->joinLeft(
            [$customerAliasName => $this->getTable('customer_entity')],
            $customerAliasName.'.entity_id = main_table.customer_id',
            [
                'customer_name' =>
                    new \Zend_Db_Expr("CONCAT({$customerAliasName}.firstname, ' ', {$customerAliasName}.lastname)"),
                'customer_email' => $customerAliasName.'.email'
            ]
        );
        return parent::_beforeLoad();
    }
}
