<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.01.2018
 * Time: 13:45
 */

namespace PM\Bundle\ToolBundle\Components\Helper;

use Doctrine\ORM\UnitOfWork;
use ReflectionClass;


/**
 * Class DoctrineHelper
 *
 * @package PM\Bundle\ToolBundle\Components\Helper
 */
class DoctrineHelper
{
    /**
     * Set Entity Change Set
     *
     * @param object     $entity
     * @param array      $changeSet
     * @param UnitOfWork $unitOfWork
     */
    public static function setUnitOfWorkEntityChangeSet($entity, $changeSet, $unitOfWork)
    {
        $reflectionClass = new ReflectionClass(UnitOfWork::class);

        $reflectionProperty = $reflectionClass->getProperty('entityChangeSets');
        $reflectionProperty->setAccessible(true);

        $changeSetFull = $reflectionProperty->getValue($unitOfWork);
        $changeSetFull[$oid = spl_object_hash($entity)] = $changeSet;

        $reflectionProperty->setValue($unitOfWork, $changeSetFull);
    }
}