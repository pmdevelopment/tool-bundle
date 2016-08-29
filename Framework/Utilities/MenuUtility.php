<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 29.08.2016
 * Time: 15:44
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;


use Knp\Menu\ItemInterface;

/**
 * Class MenuUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class MenuUtility
{
    /**
     * @param ItemInterface $parent
     * @param array         $routes
     * @param string|null   $basePath
     */
    public static function addHiddenItems($parent, $routes, $basePath = null)
    {
        $parent->setDisplayChildren(false);

        if (null === $basePath && null !== $parent->getExtra('routes') && isset($parent->getExtra('routes')[0]['route'])) {
            $basePathArray = explode("_", $parent->getExtra('routes')[0]['route']);
            $basePathArray[count($basePathArray) - 1] = '%s';

            $basePath = implode("_", $basePathArray);
        }

        foreach ($routes as $routeIndex => $routeParameters) {
            $parent->addChild($routeIndex, [
                'route'           => sprintf($basePath, $routeIndex),
                'routeParameters' => $routeParameters,
            ]);
        }
    }
}