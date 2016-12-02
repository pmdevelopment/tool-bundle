<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 02.12.2016
 * Time: 10:51
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Services;

use Swift_Mailer;

/**
 * Class HasSwiftMailerServiceTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Services
 */
trait HasSwiftMailerServiceTrait
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @return Swift_Mailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * @param Swift_Mailer $mailer
     *
     * @return HasSwiftMailerServiceTrait
     */
    public function setMailer($mailer)
    {
        $this->mailer = $mailer;

        return $this;
    }


}