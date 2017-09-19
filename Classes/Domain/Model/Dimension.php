<?php

/**
 * Dimension.
 */

namespace HDNET\Focuspoint\Domain\Model;

/**
 * Dimension.
 *
 * @db
 * @smartExclude EnableFields,Language
 */
class Dimension extends AbstractModel
{
    /**
     * Title.
     *
     * @var string
     * @db
     */
    protected $title;

    /**
     * Identifier.
     *
     * @var string
     * @db
     */
    protected $identifier;

    /**
     * Dimension.
     *
     * @var string
     * @db
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
