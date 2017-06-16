<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 29.08.2016
 * Time: 09:37
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;

/**
 * Class HasDoctrineCommandTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Command
 */
trait HasDoctrineCommandTrait
{

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }

    /**
     * @return EntityManager
     */
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * Persist and Flush
     *
     * @param object|object[] $entities
     *
     * @return $this
     */
    public function persistAndFlush($entities)
    {
        if (0 === func_num_args()) {
            throw new \LogicException('Missing arguments');
        }

        $persists = [];
        $entities = func_get_args();

        foreach ($entities as $entity) {
            if (true === is_array($entity)) {
                $persists = array_merge($persists, $entity);
            } else {
                $persists[] = $entity;
            }
        }

        foreach ($persists as $persist) {
            $this->getDoctrineManager()->persist($persist);
        }

        $this->getDoctrineManager()->flush();

        return $this;
    }
}