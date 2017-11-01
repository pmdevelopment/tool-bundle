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
 * Class UniqueId
 *
 * @package PM\Bundle\ToolBundle\Framework\Annotations
 *
 * @Annotation
 * @Annotation\Target({"PROPERTY"})
 */
final class UniqueId extends Annotation
{
    /**
     * @var int
     */
    public $length = 32;

    /**
     * @var string
     */
    public $prefix;

    /**
     * String or Integer
     *
     * @var string
     */
    public $type;

}