<?php
namespace Buildateam\Quiz\Block\Adminhtml;

class GenericButton
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Backend\Block\Widget\Context
     */
    protected $context;
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * GenericButton constructor.
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        $this->context = $context;
        $this->registry = $registry;
        $this->request = $context->getRequest();
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}