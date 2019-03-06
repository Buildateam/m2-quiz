<?php
namespace Buildateam\Quiz\Controller\Adminhtml\Question\Type;

class Delete extends \Magento\Backend\App\Action
{
    protected $_model;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Buildateam\Quiz\Model\Question\Type $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Quiz question type deleted'));
                return $resultRedirect->setPath('*/question_type/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/question_type/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Quiz question type does not exist'));
        return $resultRedirect->setPath('*/question_type/');
    }
}