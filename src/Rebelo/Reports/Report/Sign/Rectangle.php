<?php

namespace Rebelo\Reports\Report\Sign;

/**
 * Class representing Rectangle
 * @since 1.0.0
 */
class Rectangle
    implements \Rebelo\Reports\Report\IAReport
{

    /**
     * Define if the signature is visible or not
     *
     * @var string $visible
     * @since 1.0.0
     */
    private $visible = false;

    /**
     * axial coordinate (x)
     *
     * @var int $x
     * @since 1.0.0
     */
    private $x = null;

    /**
     * ordinate coordinate (y)
     *
     * @var int $y
     * @since 1.0.0
     */
    private $y = null;

    /**
     * the rectangle with
     *
     * @var int $width
     * @since 1.0.0
     */
    private $width = null;

    /**
     * The rectangle height
     *
     * @var int $height
     * @since 1.0.0
     */
    private $height = null;

    /**
     * The rectangle rotation: 0 , 90 , 180 or 270
     *
     * @var int $rotation
     * @since 1.0.0
     */
    private $rotation = 0;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        \Rebelo\Reports\Config\Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Gets as visible
     *
     * Define if the signature is visible or not
     *
     * @return bool
     * @since 1.0.0
     */
    public function getVisible()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->visible
                        ? "true"
                        : "false"));
        return $this->visible;
    }

    /**
     * Sets a new visible
     *
     * Define if the signature is visible or not
     *
     * @param bool $visible
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setVisible($visible)
    {
        if (\is_bool($visible) === false)
        {
            $msg = "visible must be boolean";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }
        $this->visible = $visible;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->visible
                        ? "true"
                        : "false"));
        return $this;
    }

    /**
     * Gets as x
     *
     * axial coordinate (x)
     *
     * @return int
     * @since 1.0.0
     */
    public function getX()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->x === null
                        ? "null"
                        : \strval($this->x)));
        return $this->x;
    }

    /**
     * Sets a new x
     *
     * axial coordinate (x)
     *
     * @param int $x
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setX($x)
    {
        if (\is_int($x) === false || $x < 0)
        {
            $msg = "the rectangle cordenate X must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->x = $x;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", \strval($this->x)));
        return $this;
    }

    /**
     * Gets as y
     *
     * ordinate coordinate (y)
     *
     * @return int
     * @since 1.0.0
     */
    public function getY()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->y === null
                        ? "null"
                        : \strval($this->y)));
        return $this->y;
    }

    /**
     * Sets a new y
     *
     * ordinate coordinate (y)
     *
     * @param int $y
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setY($y)
    {
        if (\is_int($y) === false || $y < 0)
        {
            $msg = "the rectangle cordenate Y must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->y = $y;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", \strval($this->y)));
        return $this;
    }

    /**
     * Gets as width
     *
     * the rectangle with
     *
     * @return int
     * @since 1.0.0
     */
    public function getWidth()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->width === null
                        ? "null"
                        : \strval($this->width)));
        return $this->width;
    }

    /**
     * Sets a new width
     *
     * the rectangle with
     *
     * @param int $width
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setWidth($width)
    {
        if (\is_int($width) === false || $width < 0)
        {
            $msg = "the rectangle width must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->width = $width;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             \strval($this->width)));
        return $this;
    }

    /**
     * Gets as height
     *
     * The rectangle height
     *
     * @return int
     * @since 1.0.0
     */
    public function getHeight()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->height === null
                        ? "null"
                        : \strval($this->height)));
        return $this->height;
    }

    /**
     * Sets a new height
     *
     * The rectangle height
     *
     * @param int $height
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setHeight($height)
    {
        if (\is_int($height) === false || $height < 0)
        {
            $msg = "the rectangle height must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->height = $height;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             \strval($this->height)));
        return $this;
    }

    /**
     * Gets as rotation
     *
     * The rectangle rotation: 0 , 90 , 180 or 270
     *
     * @return int
     * @since 1.0.0
     */
    public function getRotation()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", strval($this->rotation)));
        return $this->rotation;
    }

    /**
     * Sets a new rotation
     *
     * The rectangle rotation: 0 , 90 , 180 or 270
     *
     * @param int $rotation
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setRotation($rotation)
    {
        if (\is_int($rotation) === false || $rotation < 0)
        {
            $msg = "the rectangle rotation must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->rotation = $rotation;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             \strval($this->rotation)));
        return $this;
    }

    /**
     *
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        return \serialize($this);
    }

    /**
     *
     * @param \SimpleXMLElement $node
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        $rectNode = $node->addChild("rectangle");
        $rectNode->addChild("visible",
                            $this->getVisible()
                ? "true"
                : "false");
        $posNode  = $rectNode->addChild("position");

        $posNode->addChild("x",
                           strval($this->getX() === null
                    ? 0
                    : $this->getX() ));

        $posNode->addChild("y",
                           strval($this->getY() === null
                    ? 0
                    : $this->getY() ));

        $posNode->addChild("width",
                           strval($this->getWidth() === null
                    ? 0
                    : $this->getWidth()));

        $posNode->addChild("height",
                           strval($this->getHeight() === null
                    ? 0
                    : $this->getHeight()));

        $posNode->addChild("rotation",
                           strval($this->getRotation() === null
                    ? 0
                    : $this->getRotation()));
    }

}
