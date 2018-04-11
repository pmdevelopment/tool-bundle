<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 01.12.2016
 * Time: 16:05
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Controller;

use Doctrine\ORM\EntityManager;

/**
 * Class DoctrineTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Controller
 *
 * @deprecated Use HasDoctrineTrait instead
 */
trait HasDoctrineControllerTrait
{
    /**
     * Persist and Flush
     *
     * @return $this
     */
    public function persistAndFlush()
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
            $this->getDoctrine()->getManager()->persist($persist);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getDoctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }
}