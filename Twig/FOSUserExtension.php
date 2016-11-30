<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.11.2016
 * Time: 14:50
 */

namespace PM\Bundle\ToolBundle\Twig;

use PM\Bundle\ToolBundle\Framework\Traits\Services\HasDoctrineServiceTrait;
use PM\Bundle\ToolBundle\Framework\Utilities\CollectionUtility;

/**
 * Class FOSUserExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class FOSUserExtension extends \Twig_Extension
{
    use HasDoctrineServiceTrait;

    /**
     * @inheritDoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('fos_user_get_usernames', [
                $this,
                'getUserNames',
            ]),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return "pm.twig.fos_user";
    }

    /**
     * Get Usernames as array
     *
     * @param string $entity User Entity Class Name
     *
     * @return array
     */
    public function getUserNames($entity)
    {
        $users = $this->getDoctrine()->getRepository($entity)->findBy(
            [
                'enabled' => true,
            ],
            [
                'id' => 'asc',
            ]
        );

        return CollectionUtility::get('username', $users);
    }
}