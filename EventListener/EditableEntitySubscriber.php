<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.01.2018
 * Time: 13:51
 */

namespace PM\Bundle\ToolBundle\EventListener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use PM\Bundle\ToolBundle\Framework\Traits\Entities\EditableEntityTrait;


/**
 * Class EditableEntitySubscriber
 *
 * @package PM\Bundle\ToolBundle\EventListener
 */
class EditableEntitySubscriber implements EventSubscriber
{
    /**
     * Get Subscribed Events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preFlush,
            Events::preUpdate,
        ];
    }

    /**
     * PreFlush
     *
     * @param PreFlushEventArgs $args
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        /** @var object $entity */
        foreach ($args->getEntityManager()->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
            if (false === in_array(EditableEntityTrait::class, class_uses($entity))) {
                continue;
            }

            if (null === $entity->isDeleted()) {
                $entity->setDeleted(false);
            }

            if (null === $entity->getCreated()) {
                $entity->setCreated(new DateTime());
            }

            if (null === $entity->getUpdated()) {
                $entity->setUpdated(new DateTime());
            }
        }

    }

    /**
     * PreUpdate
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        /** @var object $entity */
        if (true === in_array(EditableEntityTrait::class, class_uses($entity))) {
            $entity->setUpdated(new DateTime());
        }
    }
}