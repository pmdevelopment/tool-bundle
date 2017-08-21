<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 30.11.2016
 * Time: 14:50
 */

namespace PM\Bundle\ToolBundle\Twig;

use FOS\UserBundle\Model\User;
use PM\Bundle\ToolBundle\Framework\Traits\Services\HasDoctrineServiceTrait;
use PM\Bundle\ToolBundle\Framework\Utilities\CollectionUtility;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

/**
 * Class FOSUserExtension
 *
 * @package PM\Bundle\ToolBundle\Twig
 */
class FOSUserExtension extends \Twig_Extension
{
    use HasDoctrineServiceTrait;

    /**
     * @var array
     */
    private $roleHierarchyRoles;

    /**
     * @return array
     */
    public function getRoleHierarchyRoles()
    {
        return $this->roleHierarchyRoles;
    }

    /**
     * @param array $roleHierarchyRoles
     *
     * @return FOSUserExtension
     */
    public function setRoleHierarchyRoles($roleHierarchyRoles)
    {
        $this->roleHierarchyRoles = $roleHierarchyRoles;

        return $this;
    }

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
            new \Twig_SimpleFilter('fos_user_is_granted', [
                $this,
                'isGranted',
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

    /**
     * Is Granted
     *
     * @param User   $user
     * @param string $role
     *
     * @return bool
     */
    public function isGranted($user, $role)
    {
        if (true === $user->hasRole($role)) {
            return true;
        }

        $roleHierarchy = new RoleHierarchy($this->getRoleHierarchyRoles());

        $userRoles = array_map(function ($role) {
            return new Role($role);
        }, $user->getRoles());

        $roles = $roleHierarchy->getReachableRoles($userRoles);
        $roles = array_map(function (Role $role) {
            return $role->getRole();
        }, $roles);

        return in_array($role, $roles);
    }
}