<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 10.02.2017
 * Time: 12:17
 */

namespace PM\Bundle\ToolBundle\Framework\Model\ChartJs;

/**
 * Class Data
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\ChartJs
 */
class Data
{
    /**
     * @var string
     */
    private $x;

    /**
     * @var string
     */
    private $y;

    /**
     * Data constructor.
     *
     * @param string $x
     * @param string $y
     */
    public function __construct($x, $y = null)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return string
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return string
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Export
     *
     * @return array
     */
    public function export()
    {
        if (null === $this->getY()) {
            return $this->getX();
        }

        return [
            'x' => $this->getX(),
            'y' => $this->getY(),
        ];
    }


}