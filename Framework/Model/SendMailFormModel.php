<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 20.10.2015
 * Time: 14:32
 */

namespace PM\Bundle\ToolBundle\Framework\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Swift_Attachment;
use Swift_Mime_Message;

/**
 * Class SendMailFormModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model
 */
class SendMailFormModel
{
    /**
     * @var string
     */
    private $sender;
    /**
     * @var string
     */
    private $recipient;
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $message;

    /**
     * @var Swift_Attachment[]|Collection
     */
    private $attachments;

    /**
     * MailFormModel constructor.
     */
    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     *
     * @return SendMailFormModel
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     *
     * @return SendMailFormModel
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return SendMailFormModel
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return SendMailFormModel
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Collection|Swift_Attachment[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param Collection|Swift_Attachment[] $attachments
     *
     * @return SendMailFormModel
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;

        return $this;
    }

    /**
     * Get Swift Message
     *
     * @return Swift_Mime_Message
     */
    public function getSwiftMessage()
    {
        $message = \Swift_Message::newInstance();

        $message
            ->setSubject($this->getSubject())
            ->setFrom(array($this->getSender()))
            ->setTo(array($this->getRecipient()))
            ->setBody($this->getMessage());

        if (0 < count($this->getAttachments())) {
            foreach ($this->getAttachments() as $attachment) {
                $message->attach($attachment);
            }
        }

        return $message;
    }

}