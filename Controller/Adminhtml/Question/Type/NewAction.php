<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Question\Type;

use Magento\Backend\App\Action;

/**
 * Class NewAction
 * @package Buildateam\Quiz\Controller\Adminhtml\Question\Type
 */
class NewAction extends Action
{
    protected $_resultForwardFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
    ) {
        $this->_resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultForward = $this->_resultForwardFactory->create();
        return $resultForward->forward('edit');
    }
}
