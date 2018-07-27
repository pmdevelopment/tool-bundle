<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.10.2016
 * Time: 11:12
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Controller;

use JMS\Serializer\Serializer;
use PM\Bundle\ToolBundle\Constants\HttpStatusCode;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SerializationTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Controller
 *
 * @deprecated Use SerializeJsonResponse instead.
 */
trait SerializationTrait
{

    /**
     * Get Serializer
     *
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get("jms_serializer");
    }

    /**
     * Get Serialized JSON Response
     *
     * @param mixed $object
     *
     * @return Response
     */
    public function getSerializedJsonResponse($object)
    {
        return new Response($this->getSerializer()->serialize($object, "json"), HttpStatusCode::OK, [
            "Content-Type" => "text/json",
        ]);
    }

}