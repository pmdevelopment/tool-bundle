<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 09.12.2016
 * Time: 15:28
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Symfony\Bundle\SecurityBundle\Security\FirewallContext;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
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

    /**
     * Get Random Password
     *
     * @param int|null $length
     * @param int|null $countCharsNum
     * @param int|null $countCharsSpecial
     *
     * @return string
     */
    public static function getRandomPassword($length = null, $countCharsNum = null, $countCharsSpecial = null)
    {
        $chars = [
            'alpha'   => 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ',
            'special' => '!$%&=?*-:;.,+~@_',
        ];

        if (null === $length) {
            $length = mt_rand(9, 13);
        }

        if (null === $countCharsNum) {
            $countCharsNum = mt_rand(2, 3);
        }

        if (null === $countCharsSpecial) {
            $countCharsSpecial = mt_rand(1, 2);
        }

        $countCharsAlpha = $length - $countCharsNum - $countCharsSpecial;
        if (0 >= $countCharsAlpha) {
            throw new \LogicException(sprintf('%d chars are not enough to include %d numbers and %d special chars', $lengthAlpha, $countCharsNum, $countCharsSpecial));
        }

        $result = [];

        for ($index = 0; $index < $countCharsAlpha; $index++) {
            $result[] = substr($chars['alpha'], mt_rand(0, 45), 1);
        }

        for ($index = 0; $index < $countCharsNum; $index++) {
            $result[] = mt_rand(1, 9);
        }

        for ($index = 0; $index < $countCharsSpecial; $index++) {
            $result[] = substr($chars['special'], mt_rand(0, 15), 1);
        }

        shuffle($result);

        return join('', $result);
    }

}