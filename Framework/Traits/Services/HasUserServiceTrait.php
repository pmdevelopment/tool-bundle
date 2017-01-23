<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 11.10.2016
 * Time: 13:23
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class HasUserServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 */
trait HasUserServiceTrait
{
    /**
     * @var AuthorizationChecker
     */
    private $authorizationChecker;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @return AuthorizationChecker
     */
    public function getAuthorizationChecker()
    {
        return $this->authorizationChecker;
    }

    /**
     * @param AuthorizationChecker $authorizationChecker
     *
     * @return HasUserServiceTrait
     */
    public function setAuthorizationChecker($authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;

        return $this;
    }

    /**
     * @return TokenStorage
     */
    public function getTokenStorage()
    {
        return $this->tokenStorage;
    }

    /**
     * @param TokenStorage $tokenStorage
     *
     * @return HasUserServiceTrait
     */
    public function setTokenStorage($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
    }

    /**
     * Get User
     *
     * @return mixed|null
     */
    public function getUser()
    {
        if (null === $this->getTokenStorage()->getToken()) {
            return null;
        }

        return $this->getTokenStorage()->getToken()->getUser();
    }
}