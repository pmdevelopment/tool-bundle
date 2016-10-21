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

    /**
     * @var SortingModel
     */
    private $sorting;

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