<?php
namespace Buildateam\Quiz\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $quizTable = $installer->getConnection()->newTable(
            $installer->getTable('buildateam_quiz')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Quiz Title'
        )->addColumn(
            'text',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Quiz Text'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Quiz Creation Time'
        )->addColumn(
            'update_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Quiz Modification Time'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '1'],
            'Is Quiz Active'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('buildateam_quiz'),
                ['title'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Buildateam Quiz Table'
        );
        $installer->getConnection()->createTable($quizTable);
        /** Questions Type */
        $questionTypesTable = $installer->getConnection()->newTable(
            $installer->getTable('buildateam_quiz_questions_types')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Quiz Question Type Code'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Quiz Question Type Title'
        )->setComment(
            'Buildateam Quiz Question Type Table'
        );
        $installer->getConnection()->createTable($questionTypesTable);

        /** @var  $questionsTable */
        $questionsTable = $installer->getConnection()->newTable(
            $installer->getTable('buildateam_quiz_questions')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'quiz_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Quiz Questions Quiz ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Quiz Questions Title'
        )->addColumn(
            'type_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Quiz Questions Type'
        )->addColumn(
            'multiple',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            [ 'nullable' => false, 'default' => 0 ],
            'Quiz Questions Multiple'
        )->addColumn(
            'sort',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Quiz Questions Sort'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('buildateam_quiz_questions'),
                ['title'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->addForeignKey(
            $installer->getFkName(
                'buildateam_quiz_questions',
                'quiz_id',
                'buildateam_quiz',
                'entity_id'
            ),
            'quiz_id',
            $installer->getTable('buildateam_quiz'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'buildateam_quiz_questions',
                'type_id',
                'buildateam_quiz_questions_types',
                'entity_id'
            ),
            'type_id',
            $installer->getTable('buildateam_quiz_questions_types'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Buildateam Quiz Questions Table'
        );
        $installer->getConnection()->createTable($questionsTable);

        /** Question Answers */
        $answersTable = $installer->getConnection()->newTable(
            $installer->getTable('buildateam_quiz_answers')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'question_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Quiz Answer Question ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Quiz Answers Title'
        )->addColumn(
            'image',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Quiz Answers Image'
        )->addColumn(
            'color',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Quiz Answers Color'
        )->addColumn(
            'category_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Quiz Answers Category ID'
        )->addColumn(
            'relation_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Quiz Answers Relation Next Question'
        )->addColumn(
            'attribute',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Quiz Answers Attribute Url GET'
        )->addColumn(
            'sort',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'default' => '0'],
            'Quiz Answers Sort'
        )->addIndex(
            $setup->getIdxName(
                $installer->getTable('buildateam_quiz_answers'),
                ['title', 'color'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title', 'color'],
            ['type' => AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->addForeignKey(
            $installer->getFkName(
                'buildateam_quiz_answers',
                'question_id',
                'buildateam_quiz_questions',
                'entity_id'
            ),
            'question_id',
            $installer->getTable('buildateam_quiz_questions'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Buildateam Quiz Answers Table'
        );
        $installer->getConnection()->createTable($answersTable);

        /** Customer Answers Table */
        $customerAnswersTable = $installer->getConnection()->newTable(
            $installer->getTable('buildateam_quiz_customer_answers')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Quiz Customer ID'
        )->addColumn(
            'quiz_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Quiz ID'
        )->addColumn(
            'answers',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Quiz Customer Answers'
        )->addColumn(
            'creation_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Quiz Completed Time'
        )->addForeignKey(
            $installer->getFkName(
                'buildateam_quiz_customer_answers',
                'quiz_id',
                'buildateam_quiz',
                'entity_id'
            ),
            'quiz_id',
            $installer->getTable('buildateam_quiz'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Buildateam Quiz Customer Answers Table'
        );
        $installer->getConnection()->createTable($customerAnswersTable);
        $installer->endSetup();
    }
}
