<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.10.2016
 * Time: 10:55
 */

namespace PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable;

use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Type;

/**
 * Class FilterResultModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable
 *
 * @ExclusionPolicy("none")
 */
class FilterResultModel
{
    /**
     * @var FilterItemModel[]
     *
     * @Type("array<PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable\FilterItemModel>")
     */
    private $items;

    /**
     * @var int
     *
     * @Type("integer")
     */
    private $totalCount;

    /**
     * FilterResultModel constructor.
     */
    public function __construct()
    {
        $this
            ->setItems([])
            ->setTotalCount(0);
    }

    /**
     * @return FilterItemModel[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param FilterItemModel[] $items
     *
     * @return FilterResultModel
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     *
     * @return FilterResultModel
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * Add Item
     *
     * @param FilterItemModel $filterItemModel
     *
     * @return $this
     */
    public function addItem(FilterItemModel $filterItemModel)
    {
        $this->items[] = $filterItemModel;

        return $this;
    }

}