<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 16.12.2016
 * Time: 16:23
 */

namespace PM\Bundle\ToolBundle\Testing\TestCase;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Class BundleExtensionTestCase
 *
 * @package PM\Bundle\ToolBundle\Testing\TestCase
 */
class BundleExtensionTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Get Container.
     *
     * @param Extension $extension
     *
     * @return ContainerBuilder
     */
    public function getContainer($extension)
    {
        $container = new ContainerBuilder();
        $container->registerExtension($extension);

        return $container;
    }
}