<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.01.2017
 * Time: 10:16
 */

namespace PM\Bundle\ToolBundle\Framework\Events;


use DateInterval;
use Symfony\Component\Console\Input\InputInterface;
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

    const REPEATED_EVERY_MINUTE = 'one_minute';
    const REPEATED_TWO_MINUTES = 'two_minutes';
    const REPEATED_FIVE_MINUTES = 'five_minutes';
    const REPEATED_TEN_MINUTES = 'ten_minutes';
    const REPEATED_TWENTY_MINUTES = 'twenty_minutes';
    const REPEATED_EVERY_HOUR = 'one_hour';
    const REPEATED_DAILY_MORNING = 'daily_morning';
    const REPEATED_DAILY_NIGHT = 'daily_night';
    const REPEATED_DAILY_NOON = 'daily_noon';
    const REPEATED_FIRST_DAY_OF_MONTH = 'first_day_of_month';

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
     * @var InputInterface
     */
    private $input;

    /**
     * CronEvent constructor.
     *
     * @param string         $repeated
     * @param SymfonyStyle   $helper
     * @param null|string    $target
     * @param InputInterface $input
     */
    public function __construct($repeated, SymfonyStyle $helper, $target, $input)
    {
        $this->repeated = $repeated;
        $this->helper = $helper;
        $this->target = $target;
        $this->input = $input;
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
     * @return InputInterface
     */
    public function getInput()
    {
        return $this->input;
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

    /**
     * Get DateInterval Names
     *
     * @param string $type
     *
     * @return DateInterval
     */
    public static function getDateInterval($type)
    {
        $types = [
            self::REPEATED_EVERY_MINUTE       => 'PT1M',
            self::REPEATED_TWO_MINUTES        => 'PT2M',
            self::REPEATED_FIVE_MINUTES       => 'PT5M',
            self::REPEATED_TEN_MINUTES        => 'PT10M',
            self::REPEATED_TWENTY_MINUTES     => 'PT20M',
            self::REPEATED_EVERY_HOUR         => 'PT60M',
            self::REPEATED_DAILY_MORNING      => 'P1D',
            self::REPEATED_DAILY_NIGHT        => 'P1D',
            self::REPEATED_DAILY_NOON         => 'P1D',
            self::REPEATED_FIRST_DAY_OF_MONTH => 'P1M',
        ];

        return new DateInterval($types[$type]);
    }
}