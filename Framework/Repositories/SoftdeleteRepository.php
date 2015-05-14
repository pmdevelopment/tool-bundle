<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 14.05.15
 * Time: 14:47
 */

namespace PM\Bundle\ToolBundle\Framework\Repositories;

use Doctrine\ORM\EntityRepository;

/**
 * Class SoftdeleteRepository
 *
 * @package PM\Bundle\ToolBundle\Framework\Repositories
 */
class SoftdeleteRepository extends EntityRepository
{
    /**
     * FindAll
     *
     * @param null|array $orderBy
     *
     * @return array
     */
    public function findAll($orderBy = null)
    {
        return $this->findBy(array("deleted" => false), $orderBy);
    }

    /**
     * Find One By
     *
     * @param array $criteria
     * @param array $orderBy
     *
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $criteria = array_merge($criteria, array("deleted" => false));

        return parent::findOneBy($criteria, $orderBy);
    }

    /**
     * Find By
     *
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $criteria = array_merge($criteria, array("deleted" => false));

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }
}