<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 01.12.2016
 * Time: 16:05
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Controller;

/**
 * Class DoctrineTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Controller
 */
trait HasDoctrineControllerTrait
{
    /**
     * Persist and Flush
     *
     * @param mixed $entity
     *
     * @return $this
     */
    public function persistAndFlush($entity)
    {
        $this->getDoctrine()->getManager()->persist($entity);
        $this->getDoctrine()->getManager()->flush();

        return $this;
    }
}