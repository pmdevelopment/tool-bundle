<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 31.10.2016
 * Time: 14:43
 */

namespace PM\Bundle\ToolBundle\Framework\Model\Select2;


use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class Select2RequestModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\Select2
 */
class Select2RequestModel
{
    /**
     * @var string
     */
    private $query;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * Select2RequestModel constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this
            ->setQuery($request->query->get('q'))
            ->setPage($request->query->get('page', 1))
            ->setLimit($request->query->get('limit', 30));

    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     *
     * @return Select2RequestModel
     */
    public function setQuery($query)
    {
        $this->query = $query;

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
     * @return Select2RequestModel
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     *
     * @return Select2RequestModel
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get Query Length
     *
     * @return int
     */
    public function getQueryLength()
    {
        return strlen($this->query);
    }

    /**
     * Offset
     *
     * @return int
     */
    public function getFirstResult()
    {
        return ($this->getPage() - 1) * $this->getLimit();
    }

    /**
     * Extend Query Builder
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return $this
     */
    public function extendQueryBuilder(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->setFirstResult($this->getFirstResult())
            ->setMaxResults($this->getLimit());

        return $this;
    }
}