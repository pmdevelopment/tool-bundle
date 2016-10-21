<?php

namespace PM\Bundle\ToolBundle\Framework\Traits\Controller;

use Doctrine\ORM\QueryBuilder;
use PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable\SortingModel;
use PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable\TableModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class JavaScriptTableTrait
 *
 * @package PM\CoreBundle\Component\Traits\Controller
 */
trait JavaScriptTableTrait
{
    /**
     * Add Order By
     *
     * @param QueryBuilder $queryBuilder
     * @param Request      $request
     * @param TableModel   $table
     *
     * @return QueryBuilder
     */
    public function addOrderBy(QueryBuilder $queryBuilder, Request $request, TableModel $table)
    {
        $sorting = $table->getSorting();
        $rootAlias = $this->getRootAlias($queryBuilder);

        $orderByField = $request->query->get('order_by', null);
        $orderDirection = $request->query->get('order_dir', null);

        if (null !== $orderByField && true === in_array($orderByField, $sorting->getSortableFields())) {
            $sorting->setIndex($orderByField);
        }

        if (null !== $orderDirection && true === in_array(strtolower($orderDirection), $this->getOrderDirections())) {
            $sorting->setDirection($orderDirection);
        }

        $queryBuilder
            ->orderBy(sprintf("%s.%s", $rootAlias, $sorting->getIndex()), $sorting->getDirection());

        return $queryBuilder;
    }

    /**
     * Save Table To Session
     *
     * @param Request    $request
     * @param TableModel $table
     */
    public function saveTableToSession(Request $request, TableModel $table)
    {
        $request->getSession()->set(TableModel::buildSessionPath($request), serialize($table));
    }

    /**
     * Load Table From Session
     *
     * @param Request $request
     *
     * @return null|TableModel
     */
    public function loadTableFromSession(Request $request)
    {
        $table = $request->getSession()->get(TableModel::buildSessionPath($request));
        if (true === is_string($table)) {
            return unserialize($table);
        }

        return null;
    }

    /**
     * Get Root alias
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return string
     */
    private function getRootAlias(QueryBuilder $queryBuilder)
    {
        return $queryBuilder->getRootAliases()[0];
    }

    /**
     * Get Available order directions
     *
     * @return array
     */
    private function getOrderDirections()
    {
        return [
            'asc',
            'desc'
        ];
    }
}