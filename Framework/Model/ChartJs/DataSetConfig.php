<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 10.02.2017
 * Time: 13:34
 */

namespace PM\Bundle\ToolBundle\Framework\Model\ChartJs;

/**
 * Class DataSetConfig
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\ChartJs
 */
class DataSetConfig
{
    /**
     * e.g. 0 for straight lines
     *
     * @var float
     */
    private $lineTension;

    /**
     * e.g. rgba(255,255,255,0.5)
     *
     * @var string
     */
    private $backgroundColor;

    /**
     * @var float
     */
    private $pointRadius;

    /**
     * @var string
     */
    private $pointBorderColor;

    /**
     * @var float
     */
    private $pointBorderWidth;

    /**
     * @var int
     */
    private $pointHitRadius;

    /**
     * @var float
     */
    private $borderWidth;

    /**
     * e.g. rgb(0,0,0)
     *
     * @var string
     */
    private $borderColor;

    /**
     * @return string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param string $backgroundColor
     *
     * @return DataSetConfig
     */
    public function setBackgroundColor($backgroundColor)
    {
        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return int
     */
    public function getPointRadius()
    {
        return $this->pointRadius;
    }

    /**
     * @param int $pointRadius
     *
     * @return DataSetConfig
     */
    public function setPointRadius($pointRadius)
    {
        $this->pointRadius = $pointRadius;

        return $this;
    }

    /**
     * @return string
     */
    public function getPointBorderColor()
    {
        return $this->pointBorderColor;
    }

    /**
     * @param string $pointBorderColor
     *
     * @return DataSetConfig
     */
    public function setPointBorderColor($pointBorderColor)
    {
        $this->pointBorderColor = $pointBorderColor;

        return $this;
    }

    /**
     * @return float
     */
    public function getPointBorderWidth()
    {
        return $this->pointBorderWidth;
    }

    /**
     * @param float $pointBorderWidth
     *
     * @return DataSetConfig
     */
    public function setPointBorderWidth($pointBorderWidth)
    {
        $this->pointBorderWidth = $pointBorderWidth;

        return $this;
    }

    /**
     * @return int
     */
    public function getPointHitRadius()
    {
        return $this->pointHitRadius;
    }

    /**
     * @param int $pointHitRadius
     *
     * @return DataSetConfig
     */
    public function setPointHitRadius($pointHitRadius)
    {
        $this->pointHitRadius = $pointHitRadius;

        return $this;
    }

    /**
     * @return float
     */
    public function getLineTension()
    {
        return $this->lineTension;
    }

    /**
     * @param float $lineTension
     *
     * @return DataSetConfig
     */
    public function setLineTension($lineTension)
    {
        $this->lineTension = $lineTension;

        return $this;
    }

    /**
     * @return float
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }

    /**
     * @param float $borderWidth
     *
     * @return DataSetConfig
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = $borderWidth;

        return $this;
    }

    /**
     * @return string
     */
    public function getBorderColor()
    {
        return $this->borderColor;
    }

    /**
     * @param string $borderColor
     *
     * @return DataSetConfig
     */
    public function setBorderColor($borderColor)
    {
        $this->borderColor = $borderColor;

        return $this;
    }


    /**
     * Export
     *
     * @return array
     */
    public function export()
    {
        return [
            'lineTension'      => $this->getLineTension(),
            'backgroundColor'  => $this->getBackgroundColor(),
            'pointRadius'      => $this->getPointRadius(),
            'pointBorderColor' => $this->getPointBorderColor(),
            'pointBorderWidth' => $this->getPointBorderWidth(),
            'pointHitRadius'   => $this->getPointHitRadius(),
            'borderWidth'      => $this->getBorderWidth(),
            'borderColor'      => $this->getBorderColor(),
        ];
    }
}