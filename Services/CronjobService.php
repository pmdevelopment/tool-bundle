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

    /**
     * Get Listeners
     *
     * @return array
     */
    public function getListeners()
    {
        $listeners = [];

        foreach ($this->getEventDispatcher()->getListeners(CronEvent::NAME) as $listener) {
            $listenerClass = get_class($listener[0]);

            if (!$listener[0] instanceof CronEventListenerInterface) {
                $this->getLogger()->addWarning(sprintf('%s without %s', $listenerClass, CronEventListenerInterface::class));

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

}