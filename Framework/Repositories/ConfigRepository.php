<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 03.04.2017
 * Time: 13:54
 */

namespace PM\Bundle\ToolBundle\Framework\Repositories;

use Doctrine\ORM\EntityRepository;
use PM\Bundle\ToolBundle\Entity\Config;

/**
 * Class ConfigRepository
 *
 * @package PM\Bundle\ToolBundle\Framework\Repositories
 */
class ConfigRepository extends EntityRepository
{
    /**
     * Find One By Key
     *
     * @param string $key
     *
     * @return null|object|Config
     */
    public function findOneByKey($key)
    {
        return $this->findOneBy(
            [
                'key' => $key,
            ]
        );
    }
}