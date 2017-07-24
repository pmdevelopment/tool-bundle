<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.01.2017
 * Time: 10:16
 */

namespace PM\Bundle\ToolBundle\Framework\Events;


use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CronEvent
 *
 * @package PM\Bundle\ToolBundle\Framework\Events
 */
class CronEvent extends Event
{
    const NAME = 'pm__tool.framework_events.cron_event';

    const REPEATED_DAILY_MORNING = 'daily_morning';
    const REPEATED_EVERY_MINUTE = 'one_minute';
    const REPEATED_EVERY_HOUR = 'one_hour';
    const REPEATED_FIVE_MINUTES = 'five_minutes';

    /**
     * @var string
     */
    private $repeated;

    /**
     * @var SymfonyStyle
     */
    private $helper;

    /**
     * @var null|string
     */
    private $target;

    /**
     * CronEvent constructor.
     *
     * @param string       $repeated
     * @param SymfonyStyle $helper
     * @param null|string  $target
     */
    public function __construct($repeated, SymfonyStyle $helper, $target)
    {
        $this->repeated = $repeated;
        $this->helper = $helper;
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getRepeated()
    {
        return $this->repeated;
    }

    /**
     * @return SymfonyStyle
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return null|string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Is Executing?
     *
     * @param mixed|string $class
     * @param string       $repeated
     *
     * @return bool
     */
    public function isExecuting($class, $repeated)
    {
        if ($repeated === $this->getRepeated()) {
            return true;
        }

        return $this->isTarget($class);
    }

    /**
     * Is Target
     *
     * @param string|object $class
     *
     * @return bool
     */
    public function isTarget($class)
    {
        if (true === is_object($class)) {
            $class = get_class($class);
        }

        $class = explode('\\', $class);
        $className = end($class);
        if ($className === $this->getTarget()) {
            return true;
        }

        return false;
    }

    /**
     * Get Event Names
     *
     * @return array
     */
    public static function getEventNames()
    {
        $names = [];
        $reflection = new \ReflectionClass(self::class);
        foreach ($reflection->getConstants() as $constant => $value) {
            if ('REPEATED_' === substr($constant, 0, 9)) {
                $names[] = $value;
            }
        }

        return $names;
    }
}