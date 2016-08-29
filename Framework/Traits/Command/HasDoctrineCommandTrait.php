<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 29.08.2016
 * Time: 09:37
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Command;

use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Class HasDoctrineCommandTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Command
 */
class HasDoctrineCommandTrait
{

    /**
     * @return Registry
     */
    public function getDoctrine()
    {
        return $this->getContainer()->get('doctrine');
    }
}