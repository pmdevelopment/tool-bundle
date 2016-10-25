<?php

namespace PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable;

/**
 * Class SortingModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable
 */
class SortingModel
{
    /**
     * @var string
     */
    private $index;

    /**
     * @var string
     */
    private $direction;

    /**
     * @var array|string[]
     */
    private $sortableFields;

    /**
     * SortingModel constructor.
     *
     * @param string $index
     * @param string $direction
     */
    public function __construct($index, $direction)
    {
        $this->index = $index;
        $this->direction = $direction;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param string $index
     *
     * @return SortingModel
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     *
     * @return SortingModel
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return array|\string[]
     */
    public function getSortableFields()
    {
        return $this->sortableFields;
    }

    /**
     * @param array|\string[] $sortableFields
     *
     * @return SortingModel
     */
    public function setSortableFields($sortableFields)
    {
        $this->sortableFields = $sortableFields;

        return $this;
    }

}