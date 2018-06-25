<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 18.05.2017
 * Time: 11:44
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class HasEventDispatcherServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 *
 * @deprecated Use HasEventDispatcherTrait instead
 */
trait HasEventDispatcherServiceTrait
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        if (null === $this->eventDispatcher) {
            throw new \RuntimeException('EventDispatcher not found. Setter not called?');
        }

        return $this->eventDispatcher;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     *
     * @return HasEventDispatcherServiceTrait
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

}