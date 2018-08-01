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

    const ACTION_EDIT = 'edit';
    const ACTION_DELETE = 'delete';

    /**
     * @var int
     */
    private $version;

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
     * Key=>Value Array for filters
     *
     * @var array|FilterItemModel[]
     */
    private $filters;


    /**
     * TableModel constructor.
     *
     * @param SortingModel $sorting
     * @param null|int     $limit
     * @param int          $version
     */
    public function __construct(SortingModel $sorting, $limit = null, $version = 161110)
    {
        $this->sorting = $sorting;
        $this->limit = $limit;

        $this->version = $version;
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
     * @return array|FilterItemModel[]|mixed
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }


    /**
     * Add Filter
     *
     * @param string          $key
     * @param FilterItemModel $filter
     *
     * @return $this
     */
    public function addFilter($key, $filter)
    {
        if (false === isset($this->filters[$key])) {
            $this->filters[$key] = [];
        }

        $this->filters[$key][] = $filter;

        return $this;
    }

    /**
     * Get Filters Array
     *
     * @return array
     */
    public function getFiltersArray()
    {
        $result = [];

        if (false === is_array($this->getFilters())) {
            return [];
        }

        foreach ($this->getFilters() as $filterIndex => $items) {
            $resultItems = [];
            foreach ($items as $item) {
                $resultItems[] = $item->getId();
            }

            $result[$filterIndex] = implode(',', $resultItems);
        }

        return $result;
    }


    /**
     * Get Filters as JSON
     *
     * @return string
     */
    public function getFiltersJson()
    {
        $result = [];

        if (false === is_array($this->getFilters())) {
            return '{}';
        }

        foreach ($this->getFilters() as $filterIndex => $items) {
            foreach ($items as $item) {
                $result[$filterIndex][] = [
                    'id'   => $item->getId(),
                    'text' => $item->getText(),
                ];
            }
        }

        return json_encode($result);
    }

    /**
     * Reset Filter
     *
     * @param string $key
     *
     * @return $this
     */
    public function resetFilter($key)
    {
        if (true === isset($this->filters[$key])) {
            unset($this->filters[$key]);
        }

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