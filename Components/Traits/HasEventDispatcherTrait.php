<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 13.09.2018
 * Time: 16:26
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Trait HasEventDispatcherTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasEventDispatcherTrait
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        if (null === $this->eventDispatcher) {
            throw new \LogicException('EventDispatcher missing. Setter not called?');
        }

        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return HaSeventDispatcherTrait
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

}