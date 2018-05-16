<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.04.2018
 * Time: 16:38
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Trait HasLoggerTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasLoggerTrait
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if ($this instanceof ContainerAwareInterface) {
            return $this->get('logger');
        }

        if (null === $this->logger) {
            throw new \RuntimeException('Logger not found. Setter not called?');
        }

        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return HasLoggerServiceTrait
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }
}