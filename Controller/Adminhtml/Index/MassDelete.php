<?php

namespace Buildateam\Quiz\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Buildateam\Quiz\Model\ResourceModel\Quiz\CollectionFactory;

/**
 * Class MassDelete
 * @package Buildateam\Quiz\Controller\Adminhtml\Index
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Filter
     */
    protected $filter;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var \Buildateam\Quiz\Model\QuizRepository
     */
    protected $quizRepository;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param \Buildateam\Quiz\Model\QuizRepository $quizRepository
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        \Buildateam\Quiz\Model\QuizRepository $quizRepository
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->quizRepository = $quizRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Buildateam\Quiz\Model\ResourceModel\Quiz\Collection $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $count = 0;
        /** @var \Buildateam\Quiz\Model\Quiz $item */
        foreach ($collection as $item) {
            $this->quizRepository->delete($item);
            $count++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
