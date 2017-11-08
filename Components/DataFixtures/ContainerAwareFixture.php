<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 08.11.2017
 * Time: 11:17
 */

namespace PM\Bundle\ToolBundle\Components\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


/**
 * Class ContainerAwareFixture
 *
 * @package PM\Bundle\ToolBundle\Components\DataFixtures
 */
abstract class ContainerAwareFixture extends AbstractFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * Get Kernel Environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->container->get('kernel')->getEnvironment();
    }
}