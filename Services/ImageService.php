<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 18.05.15
 * Time: 17:23
 */

namespace PM\Bundle\ToolBundle\Services;


use PM\Bundle\ToolBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ImageService
 *
 * @package PM\Bundle\ToolBundle\Services
 */
class ImageService
{

    /**
     * Get Response
     *
     * @param Image $image
     *
     * @return Response
     */
    public function getResponse($image)
    {
        if (!$image instanceof Image) {
            throw new \LogicException("Not a valid image");
        }

        $core = explode(";", $image->getContent());
        $content = base64_decode(substr($core[1], 7));

        return new Response($content, 200, array(
            'Content-Type' => $image->getMimeType()
        ));
    }

}