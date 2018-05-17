<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 19.04.2018
 * Time: 09:39
 */

namespace PM\Bundle\ToolBundle\Framework\Annotations;

use Doctrine\Common\Annotations\Annotation;
use PM\Bundle\ToolBundle\Components\Helper\OpenSSLHelper;

/**
 * Class Encryption
 *
 * @package PM\Bundle\ToolBundle\Framework\Annotations
 *
 * @Annotation
 * @Annotation\Target({"PROPERTY"})
 */
final class Encryption extends Annotation
{
    /**
     * Cipher
     *
     * @var string
     */
    public $cipher = OpenSSLHelper::CIPHER_AES_256_CBC;
}