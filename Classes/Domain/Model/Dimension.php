<?php

/**
 * Dimension.
 */

namespace HDNET\Focuspoint\Domain\Model;

use HDNET\Autoloader\Annotation\DatabaseField;
use HDNET\Autoloader\Annotation\DatabaseTable;
use HDNET\Autoloader\Annotation\SmartExclude;

/**
 * Dimension.
 *
 * @DatabaseTable()
 * @SmartExclude({"EnableFields", "Language"})
 */
class Dimension extends AbstractModel
{
    /**
     * Title.
     *
     * @var string
     * @DatabaseField(type="string")
     */
    protected $title;

    /**
     * Identifier.
     *
     * @var string
     * @DatabaseField(type="string")
     */
    protected $identifier;

    /**
     * Dimension.
     *
     * @var string
     * @DatabaseField(type="string")
     */
    protected $dimension;

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * @param mixed $dimension
     */
    public function setDimension($dimension)
    {
        $this->dimension = $dimension;
    }
}
