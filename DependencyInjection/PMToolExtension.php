<?php

namespace PM\Bundle\ToolBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PMToolExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        /* Config */
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if (true === isset($config['doctrine']['encryption']) && null !== $config['doctrine']['encryption']) {
            $container->setParameter('pm__tool.configuration.doctrine.encryption', $config['doctrine']['encryption']);
        } else {
            $container->setParameter('pm__tool.configuration.doctrine.encryption', null);
        }

        /* Services */
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
