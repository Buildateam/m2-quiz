<?php
/**
 * Created by PhpStorm.
 * User: ws10
 * Date: 09-04-19
 * Time: 03:46 PM
 */

namespace Buildateam\Quiz\Block\Adminhtml\Questionary\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Buildateam\Quiz\Block\Adminhtml\GenericButton;

/**
 * Class AddNewQuestion
 * @package Buildateam\Quiz\Block\Adminhtml\Questionary\Edit
 */
class AddNewQuestion extends GenericButton implements ButtonProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getButtonData()
    {
        if ($this->registry->registry('buildateam_quiz') && $this->registry->registry('buildateam_quiz')->getId()) {
            $quizId = $this->registry->registry('buildateam_quiz')->getId();
            return [
                'label' => __('Add New Question'),
                'class' => 'reset add-new-question',
                'on_click' => sprintf(
                    "location.href = '%s';",
                    $this->getUrl(
                        '*/questionaryQuestion/newQuestion',
                        ['quiz_id' => $quizId]
                    )
                ),
                'sort_order' => 60,
            ];
        }
        return [];
    }
}
