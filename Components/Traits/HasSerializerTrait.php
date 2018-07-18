<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.04.2018
 * Time: 12:52
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use JMS\Serializer\SerializerInterface;


/**
 * trait HasSerializerTrait
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
        if (null === $this->serializer) {
            throw new \LogicException('Missing serializer. Setter not called?');
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