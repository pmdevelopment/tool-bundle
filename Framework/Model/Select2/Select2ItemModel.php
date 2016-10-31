<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 31.10.2016
 * Time: 14:47
 */

namespace PM\Bundle\ToolBundle\Framework\Model\Select2;

/**
 * Class Select2ItemModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\Select2
 */
class Select2ItemModel
{
    /**
     * @var mixed
     */
    private $id;

    /**
     * @var string
     */
    private $text;

    /**
     * Select2ItemModel constructor.
     *
     * @param mixed  $id
     * @param string $text
     */
    public function __construct($id, $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }


}