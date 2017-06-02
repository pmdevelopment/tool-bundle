<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 02.06.2017
 * Time: 14:25
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Repositories;

/**
 * Class CountableRepositoryTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Repositories
 */
trait CountableRepositoryTrait
{

    /**
     * Get Count
     *
     * @return int
     */
    public function getCount()
    {
        $queryBuilder = $this->createQueryBuilder('entity');

        return $queryBuilder->select('COUNT(entity)')->getQuery()->getSingleScalarResult();
    }

}