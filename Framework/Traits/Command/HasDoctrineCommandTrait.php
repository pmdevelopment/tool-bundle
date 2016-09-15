<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 29.08.2016
 * Time: 09:37
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;

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