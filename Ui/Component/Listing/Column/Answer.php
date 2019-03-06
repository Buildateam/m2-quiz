<?php
namespace Buildateam\Quiz\Ui\Component\Listing\Column;

class Answer extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item['answers'] = json_encode(unserialize($item["answers"]));
            }
        }
        return $dataSource;
    }
}
