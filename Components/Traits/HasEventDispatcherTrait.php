<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.06.2018
 * Time: 09:54
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
        if (false === ($this->eventDispatcher instanceof EventDispatcherInterface)) {
            throw new \RuntimeException('EventDispatcher not found. Setter not called?');
        }

        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return HasEventDispatcherTrait
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

}