<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 15.06.2016
 * Time: 14:24
 */

namespace PM\Bundle\ToolBundle\Framework\Traits\Entities;

use DateTime;

/**
 * Class EditableEntityTrait
 *
 * @package PM\Bundle\ToolBundle\Framework\Traits\Entities
 */
trait EditableEntityTrait
{
    /**
     * @var DateTime
     *
     * @ORM\Column(name="created",type="datetime")
     */
    private $created;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="updated",type="datetime")
     */
    private $updated;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted;

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     *
     * @return EditableEntityTrait
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param DateTime $updated
     *
     * @return EditableEntityTrait
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param boolean $deleted
     *
     * @return EditableEntityTrait
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Set Editable Fields
     *
     * @ORM\PreFlush()
     */
    public function setEditableOnFlush()
    {
        if (null === $this->isDeleted()) {
            $this->setDeleted(false);
        }

        if (null === $this->getCreated()) {
            $this->setCreated(new DateTime());
        }

        $this->setUpdated(new DateTime());
    }
}