<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.07.2017
 * Time: 11:30
 */

namespace PM\Bundle\ToolBundle\Services;

use PM\Bundle\ToolBundle\Framework\Events\CronEvent;
use PM\Bundle\ToolBundle\Framework\Interfaces\CronEventListenerInterface;
use PM\Bundle\ToolBundle\Framework\Traits\Services\HasEventDispatcherServiceTrait;
use PM\Bundle\ToolBundle\Framework\Traits\Services\HasLoggerServiceTrait;


/**
 * Class CronjobService
 *
 * @package PM\Bundle\ToolBundle\Services
 */
class CronjobService
{
    use HasEventDispatcherServiceTrait;
    use HasLoggerServiceTrait;

    const FULL_RESULT = true;
    const FLAT_RESULT = false;

    /**
     * Get Listeners
     *
     * @param bool $resultType
     *
     * @return array
     */
    public function getListeners($resultType = self::FULL_RESULT)
    {
        $listeners = [];

        foreach ($this->getEventDispatcher()->getListeners(CronEvent::NAME) as $listener) {
            $listenerClass = get_class($listener[0]);

            if (!$listener[0] instanceof CronEventListenerInterface) {
                $this->getLogger()->addError(sprintf('%s without %s', $listenerClass, CronEventListenerInterface::class));

                continue;
            }

            if (self::FLAT_RESULT === $resultType) {
                $listeners[] = $listenerClass;

                continue;
            }

            $type = $listener[0]::getRepeatedType();
            if (false === isset($listeners[$type])) {
                $listeners[$type] = [];
            }

            $listeners[$type][] = $listenerClass;
        }

        return $listeners;
    }

    /**
     * Get Listeners without type
     *
     * @return array
     */
    public function getListenersWithoutType()
    {
        return $this->getListeners(self::FLAT_RESULT);
    }
}