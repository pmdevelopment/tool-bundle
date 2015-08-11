<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.08.15
 * Time: 11:38
 */

namespace PM\Bundle\ToolBundle\Framework\Utilities;

use Doctrine\Common\Collections\Collection;

/**
 * Class CollectionUtility
 *
 * @package PM\Bundle\ToolBundle\Framework\Utilities
 */
class CollectionUtility
{

    /**
     * Get Ids From Collection
     *
     * @param array|mixed[]|Collection $collection
     *
     * @return array
     */
    public static function getIds($collection)
    {
        $ids = array();

        if (false === is_array($collection) && false === ($collection instanceof Collection)) {
            return array();
        }

        foreach ($collection as $entity) {
            $function = array(
                $entity,
                "getId"
            );

            if (false === is_callable($function)) {
                throw new \LogicException("Missing ID getter");
            }

            $ids[] = $entity->getId();
        }

        return $ids;
    }

}