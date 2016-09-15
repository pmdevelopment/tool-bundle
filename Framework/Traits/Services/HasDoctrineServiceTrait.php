<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.08.2016
 * Time: 16:25
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class HasDoctrineTrait
 *
 * @package PM\CoreBundle\Component\Traits
 */
trait HasDoctrineServiceTrait
{
    /**
     * @var Registry
     */
    private $doctrine;

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param Registry $doctrine
     *
     * @return $this
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;

        return $this;
    }

}