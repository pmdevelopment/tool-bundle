<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 26.07.2016
 * Time: 15:09
 */

namespace PM\Bundle\ToolBundle\Framework\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Optional
 *
 * @package PM\Bundle\ToolBundle\Framework\Annotations
 *
 * @Annotation
 * @Annotation\Target({"PROPERTY"})
 */
final class Optional extends Annotation
{
    /**
     * @var mixed
     */
    public $default = '';

}