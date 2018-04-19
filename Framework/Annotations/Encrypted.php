<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 23.03.2017
 * Time: 12:29
 */

namespace PM\Bundle\ToolBundle\Framework\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Encrypted
 *
 * @package PM\Bundle\ToolBundle\Framework\Annotations
 *
 * @deprecated Use "Encryption" Annotation instead. Warning: not compatible!
 *
 * @Annotation
 * @Annotation\Target({"PROPERTY"})
 */
final class Encrypted extends Annotation
{
}