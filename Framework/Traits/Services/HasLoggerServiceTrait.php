<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 16.11.2016
 * Time: 09:31
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Symfony\Bridge\Monolog\Logger;

/**
 * Class HasLoggerServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 */
trait HasLoggerServiceTrait
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if (null === $this->logger) {
            throw new \RuntimeException('Logger not found. Setter not called?');
        }

        return $this->logger;
    }

    /**
     * @param Logger $logger
     *
     * @return HasLoggerServiceTrait
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

}