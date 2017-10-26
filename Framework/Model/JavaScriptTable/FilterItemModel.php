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
    const ADD_TO_QUERY = true;
    const IGNORE_FOR_QUERY = false;
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
     * @var bool
     *
     * @Type("boolean")
     */
    private $addToQuery;

    /**
     * FilterItemModel constructor.
     *
     * @param int    $id
     * @param string $text
     * @param bool   $addToQuery
     */
    public function __construct($id, $text, $addToQuery = self::ADD_TO_QUERY)
    {
        $this->id = $id;
        $this->text = $text;
        $this->addToQuery = $addToQuery;
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

    /**
     * @return boolean
     */
    public function isAddToQuery()
    {
        return $this->addToQuery;
    }

    /**
     * @param boolean $addToQuery
     *
     * @return FilterItemModel
     */
    public function setAddToQuery($addToQuery)
    {
        $this->addToQuery = $addToQuery;

        return $this;
    }

}