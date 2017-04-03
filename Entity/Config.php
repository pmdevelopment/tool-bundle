<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 03.04.2017
 * Time: 13:48
 */

namespace PM\Bundle\ToolBundle\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Image
 *
 * @package PM\Bundle\ToolBundle\Entity
 *
 * @ORM\MappedSuperclass()
 * @ORM\HasLifecycleCallbacks()
 */
abstract class Config
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="k_y", type="string", length=255, unique=true)
     */
    private $key;

    /**
     * @var mixed
     *
     * @ORM\Column(name="val_e", type="string", length=255)
     */
    private $value;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="changed", type="datetime")
     */
    private $changed;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return Config
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * @param DateTime $changed
     *
     * @return Config
     */
    public function setChanged($changed)
    {
        $this->changed = $changed;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return Config
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }


    /**
     * @ORM\PreFlush
     */
    public function update()
    {
        $this->setChanged(new DateTime());
    }

    /**
     * Get Keys Available
     *
     * @return array
     */
    public static function getKeysAvailable()
    {
        $reflection = new \ReflectionClass(get_called_class());

        return $reflection->getConstants();
    }
}