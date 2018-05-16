<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.08.2016
 * Time: 16:44
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use JMS\Serializer\Serializer;

/**
 * Class HasSerializerTrait
 *
 * @package PM\CoreBundle\Component\Traits
 *
 * @deprecated Use HasSerializerTrait instead.
 */
trait HasSerializerServiceTrait
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param Serializer $serializer
     *
     * @return $this
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

}