<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 21.10.2016
 * Time: 13:36
 */

namespace PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class TableModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable
 */
class TableModel
{
    const SESSION_KEY = 'java_script_table';

    const DEFAULT_LIMIT = 50;

    /**
     * @var SortingModel
     */
    private $sorting;

    /**
     * @var int|null
     */
    private $limit;

    /**
     * @var int|null
     */
    private $page;

    /**
     * TableModel constructor.
     *
     * @param SortingModel $sorting
     * @param null|int     $limit
     */
    public function __construct(SortingModel $sorting, $limit = null)
    {
        $this->sorting = $sorting;
        $this->limit = $limit;
    }

    /**
     * @return SortingModel
     */
    public function getSorting()
    {
        return $this->sorting;
    }

    /**
     * @param SortingModel $sorting
     *
     * @return TableModel
     */
    public function setSorting($sorting)
    {
        $this->sorting = $sorting;

        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        if (false === is_numeric($this->limit) || 0 === $this->limit) {
            return self::DEFAULT_LIMIT;
        }

        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return TableModel
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPage()
    {
        if (null === $this->page) {
            return 1;
        }

        return $this->page;
    }

    /**
     * @param int|null $page
     *
     * @return TableModel
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }


    /**
     * Build Session Path
     *
     * @param Request $request
     *
     * @return string
     */
    public static function buildSessionPath(Request $request)
    {
        return sprintf('%s_%s', $request->get('_route', 'unknown'), self::SESSION_KEY);
    }
}