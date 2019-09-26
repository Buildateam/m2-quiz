<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 03-04-19
 * Time: 11:16 AM
 */

namespace Buildateam\Quiz\Controller\Adminhtml\Questionary;

use Magento\Framework\App\ResponseInterface;

/**
 * Class Index
 * @package Buildateam\Quiz\Controller\Adminhtml\Questionary
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('All Questionaries'));
        return $resultPage;
    }
}
