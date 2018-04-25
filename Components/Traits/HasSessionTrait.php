<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 25.04.2018
 * Time: 12:52
 */

namespace PM\Bundle\ToolBundle\Components\Traits;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


/**
 * trait HasSessionTrait
 *
 * @package PM\Bundle\ToolBundle\Components\Traits
 */
trait HasSessionTrait
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @return SessionInterface
     */
    public function getSession()
    {
        if ($this instanceof ContainerAwareInterface) {
            if ($this instanceof ContainerAwareCommand) {
                return $this->getContainer()->get('session');
            }

            return $this->get('session');
        }

        if (null === $this->session) {
            throw new \LogicException('Missing session. Setter not called?');
        }

        return $this->session;
    }

    /**
     * @param SessionInterface $session
     *
     * @return HasSessionTrait
     */
    public function setSession($session)
    {
        $this->session = $session;

        return $this;
    }


}