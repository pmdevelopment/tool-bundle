<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 16.05.2018
 * Time: 15:12
 */

namespace PM\Bundle\ToolBundle\Components\Traits;


use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Trait HasSerializerTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasSerializerTrait
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @return SerializerInterface
     */
    public function getSerializer()
    {
        if ($this instanceof ContainerAwareInterface) {
            return $this->get('jms_serializer');
        }

        if (null === $this->serializer) {
            throw new \RuntimeException('Serializer not found. Setter not called?');
        }

        return $this->serializer;
    }

    /**
     * @param SerializerInterface $serializer
     *
     * @return HasSerializerTrait
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

}