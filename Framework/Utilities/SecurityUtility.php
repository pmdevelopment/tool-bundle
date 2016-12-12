<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 15:28
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallContext;
use Symfony\Component\Security\Http\Firewall\AccessListener;

/**
 * Class SecurityUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class SecurityUtility
{

    /**
     * Get Firewall Names
     *
     * @param Container $container
     *
     * @return array
     */
    public static function getFirewallNames(Container $container)
    {
        $names = [];
        foreach ($container->getServiceIds() as $serviceId) {
            if ('security.firewall.map.context.' === substr($serviceId, 0, 30)) {
                $names[] = substr($serviceId, 30);
            }
        }

        return $names;
    }

    /**
     * Get Firewall With Access Listener Names
     *
     * @param Container|ContainerInterface $container
     *
     * @return array
     *
     * @throws \Exception
     * @throws \Throwable
     */
    public static function getFirewallWithAccessListenerNames(Container $container)
    {
        $names = [];

        foreach (self::getFirewallNames($container) as $firewallName) {
            /** @var FirewallContext $firewall */
            $firewall = $container->get(sprintf('security.firewall.map.context.%s', $firewallName));

            if (0 === count($firewall->getContext())) {
                continue;
            }

            $firewallContext = $firewall->getContext();
            if (false === isset($firewallContext[0]) || 0 === count($firewallContext[0])) {
                continue;
            }

            foreach ($firewallContext[0] as $listener) {
                if ($listener instanceof AccessListener) {
                    $names[] = $firewallName;
                }
            }
        }

        return $names;
    }

}