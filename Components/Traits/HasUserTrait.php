<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 18.05.2018
 * Time: 13:44
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Trait HasUserTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasUserTrait
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @return TokenStorageInterface
     */
    public function getTokenStorage()
    {
        if (null === $this->tokenStorage) {
            throw new \LogicException('Missing TokenStorage. Setter not called?');
        }

        return $this->tokenStorage;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return $this
     */
    public function setTokenStorage($tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    public function getAuthorizationChecker()
    {
        if (null === $this->authorizationChecker) {
            throw new \LogicException('Missing AuthorizationChecker. Setter not called?');
        }

        return $this->authorizationChecker;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return $this
     */
    public function setAuthorizationChecker($authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;

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