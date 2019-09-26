<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 09-04-19
 * Time: 05:16 PM
 */

namespace Buildateam\Quiz\Block\Adminhtml\QuestionaryQuestion\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class BackButton
 * @package Buildateam\Quiz\Block\Adminhtml\QuestionaryQuestion\Edit
 */
class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('quiz/questionary/edit/', ['quiz_id' => $this->request->getParam('quiz_id')]);
    }
}
