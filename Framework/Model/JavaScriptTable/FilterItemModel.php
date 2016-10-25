<?php
/**
 * Created by PhpStorm.
 * User: sjoder
 * Date: 24.10.2016
 * Time: 10:56
 */

namespace PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable;

use JMS\Serializer\Annotation\Type;

/**
 * Class FilterItemModel
 *
 * @package PM\Bundle\ToolBundle\Framework\Model\JavaScriptTable
 */
class FilterItemModel
{
    /**
     * @var int
     *
     * @Type("string")
     */
    private $id;

    /**
     * @var string
     *
     * @Type("string")
     */
    private $text;

    /**
     * FilterItemModel constructor.
     *
     * @param int    $id
     * @param string $text
     */
    public function __construct($id, $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return FilterItemModel
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return FilterItemModel
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }


}