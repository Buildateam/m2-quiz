<?php

namespace Buildateam\Quiz\Controller\Adminhtml\Question\Type;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Buildateam_Quiz::quiz_question_types');
        $resultPage->getConfig()->getTitle()->prepend(__('Questions Types'));

        return $resultPage;
    }
}
