<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.04.2017
 * Time: 15:15
 */

namespace PM\Bundle\ToolBundle\Framework\Model\ChartJs;

/**
 * Class DataObject
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\ChartJs
 */
class DataObject
{

    /**
     * @var array
     */
    private $labels;

    /**
     * @var DataSet[]
     */
    private $dataSets;

    /**
     * DataObject constructor.
     *
     * @param array     $labels
     * @param DataSet[] $dataSets
     */
    public function __construct(array $labels, array $dataSets)
    {
        $this->labels = $labels;
        $this->dataSets = $dataSets;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return DataSet[]
     */
    public function getDataSets()
    {
        return $this->dataSets;
    }

    /**
     * Export Data Sets
     *
     * @param DataSetConfig|null $dataSetConfig
     *
     * @return array
     */
    public function exportDataSets($dataSetConfig = null)
    {
        $response = [];

        foreach ($this->getDataSets() as $set) {
            if ($set instanceof DataSet) {
                $response[] = $set->export($dataSetConfig);
            } else {
                $response[] = $set;
            }
        }

        return $response;
    }

    /**
     * Export
     *
     * @param DataSetConfig|null $dataSetConfig
     *
     * @return array
     */
    public function export($dataSetConfig = null)
    {
        return [
            'labels'   => $this->getLabels(),
            'datasets' => $this->exportDataSets($dataSetConfig),
        ];
    }


}