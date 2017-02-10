<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 10.02.2017
 * Time: 12:16
 */

namespace PM\Bundle\ToolBundle\Framework\Model\ChartJs;

/**
 * Class DataSet
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\ChartJs
 */
class DataSet
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var array|Data[]
     */
    private $data;

    /**
     * DataSet constructor.
     *
     * @param string $label
     */
    public function __construct($label)
    {
        $this->label = $label;

        $this->data = [];
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return DataSet
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array|Data[]
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param Data $data
     *
     * @return DataSet
     */
    public function addData($data)
    {
        $this->data[] = $data;

        return $this;
    }

    /**
     * Export
     *
     * @param null|DataSetConfig $dataSetConfig
     *
     * @return array
     */
    public function export($dataSetConfig = null)
    {
        $export = [
            'label' => $this->getLabel(),
            'data'  => [],
        ];

        foreach ($this->getData() as $data) {
            $export['data'][] = $data->export();
        }

        if (null !== $dataSetConfig) {
            $export = array_merge($export, $dataSetConfig->export());
        }

        return $export;
    }

}