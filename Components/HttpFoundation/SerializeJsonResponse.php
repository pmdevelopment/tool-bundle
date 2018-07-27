<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 27.07.2018
 * Time: 10:54
 */

namespace PM\Bundle\ToolBundle\Components\HttpFoundation;

use JMS\Serializer\SerializerInterface as JMSSerializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class SerializeJsonResponse
 *
 * @package PM\Bundle\ToolBundle\Components\HttpFoundation
 */
class SerializeJsonResponse extends Response
{

    /**
     * SerializeJsonResponse constructor.
     *
     * @param SerializerInterface|JMSSerializer $serializer
     * @param mixed                             $object
     * @param int                               $status
     * @param array                             $headers
     */
    public function __construct($serializer, $object, $status = Response::HTTP_OK, $headers = [])
    {
        $headers = array_merge($headers, [
            'Content-Type' => 'text/json',
        ]);

        $content = $serializer->serialize($object, 'json');

        parent::__construct($content, $status, $headers);
    }

}