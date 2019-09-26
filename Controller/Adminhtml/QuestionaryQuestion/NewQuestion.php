<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 09-04-19
 * Time: 11:54 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\QuestionaryQuestion;

use Magento\Backend\App\Action;

/**
 * Class NewQuestion
 * @package Buildateam\Quiz\Controller\Adminhtml\QuestionaryQuestion
 */
class NewQuestion extends Action
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
