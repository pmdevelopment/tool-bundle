<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 31.10.2016
 * Time: 14:47
 */

namespace PM\Bundle\ToolBundle\Framework\Model\Select2;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Select2ResponseModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\Select2
 */
class Select2ResponseModel
{
    /**
     * @var Select2ItemModel[]
     */
    private $items;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $totalCount;

    /**
     * Select2ResponseModel constructor.
     */
    public function __construct()
    {
        $this
            ->setItems([])
            ->setPage(1)
            ->setTotalCount(0);
    }

    /**
     * @return Select2ItemModel[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Select2ItemModel[] $items
     *
     * @return Select2ResponseModel
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     *
     * @return Select2ResponseModel
     */
    public function setPage($page)
    {
        $this->page = $page;

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
     * @return Select2ResponseModel
     */
    public function setTotalCount($totalCount)
    {
        $this->totalCount = $totalCount;

        return $this;
    }

    /**
     * @param Select2ItemModel $item
     *
     * @return $this
     */
    public function addItem(Select2ItemModel $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Set Total Count
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return Select2ResponseModel
     */
    public function setTotalCountByQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->select(sprintf('COUNT(%s)', $queryBuilder->getRootAliases()[0]))
            ->setMaxResults(1);

        return $this->setTotalCount(intval($queryBuilder->getQuery()->getSingleScalarResult()));
    }

    /**
     * Items as Array
     *
     * @return array
     */
    public function getItemsArray()
    {
        $items = [];

        foreach ($this->getItems() as $item) {
            $items[] = [
                'id'   => $item->getId(),
                'text' => $item->getText(),
            ];
        }

        return $items;
    }

    /**
     * Get JSON Response
     *
     * @return JsonResponse
     */
    public function getJsonResponse()
    {
        return new JsonResponse(
            [
                'items'       => $this->getItemsArray(),
                'total_count' => $this->getTotalCount(),
                'page'        => $this->getPage(),
            ]
        );
    }
}