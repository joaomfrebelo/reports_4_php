<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\IAReport;

/**
 * Class representing Rectangle
 * @since 1.0.0
 */
class Rectangle implements IAReport
{
    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_VISIBLE = "visible";

    /**
     * Api rectangle property name
     * @since 3.0.0
     */
    const API_N_RECTANGLE = "signRectangle";

    /**
     * Api rectangle property name
     * @since 3.0.0
     */
    const API_P_X = "x";

    /**
     * Api rectangle property name
     * @since 3.0.0
     */
    const API_P_Y = "y";

    /**
     * Api rectangle property name
     * @since 3.0.0
     */
    const API_P_WIDTH = "width";

    /**
     * Api rectangle property name
     * @since 3.0.0
     */
    const API_P_HEIGHT = "height";

    /**
     * Api rectangle property name
     * @since 3.0.0
     */
    const API_P_ROTATION = "rotation";

    /**
     * Define if the signature is visible or not
     *
     * @var bool $visible
     * @since 1.0.0
     */
    private bool $visible = false;

    /**
     * axial coordinate (x)
     *
     * @var int|null $x
     * @since 1.0.0
     */
    private ?int $x = null;

    /**
     * ordinate coordinate (y)
     *
     * @var int|null $y
     * @since 1.0.0
     */
    private ?int $y = null;

    /**
     * the rectangle with
     *
     * @var int|null $width
     * @since 1.0.0
     */
    private ?int $width = null;

    /**
     * The rectangle height
     *
     * @var int|null $height
     * @since 1.0.0
     */
    private ?int $height = null;

    /**
     * The rectangle rotation: 0 , 90 , 180 or 270
     *
     * @var int $rotation
     * @since 1.0.0
     */
    private int $rotation = 0;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        Config::configLog4Php();
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
    public function getVisible(): bool
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->visible
                        ? "true"
                : "false"
            ));
        return $this->visible;
    }

    /**
     * Sets a new visible
     *
     * Define if the signature is visible or not
     *
     * @param bool $visible
     * @return static
     * @since 1.0.0
     */
    public function setVisible(bool $visible): static
    {
        $this->visible = $visible;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s'",
                $this->visible
                        ? "true"
                : "false"
            ));
        return $this;
    }

    /**
     * Gets as x
     *
     * axial coordinate (x)
     *
     * @return int|null
     * @since 1.0.0
     */
    public function getX(): ?int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->x === null
                        ? "null"
                : \strval($this->x)
            ));
        return $this->x;
    }

    /**
     * Sets a new x
     *
     * axial coordinate (x)
     *
     * @param int $x
     * @return static
     * @throws SignException
     * @since 1.0.0
     */
    public function setX(int $x): static
    {
        if ($x < 0) {
            $msg = "the rectangle coordinate X must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->x = $x;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", \strval($this->x)));
        return $this;
    }

    /**
     * Gets as y
     *
     * ordinate coordinate (y)
     *
     * @return int|null
     * @since 1.0.0
     */
    public function getY(): ?int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->y === null
                        ? "null"
                : \strval($this->y)
            ));
        return $this->y;
    }

    /**
     * Sets a new y
     *
     * ordinate coordinate (y)
     *
     * @param int $y
     * @return static
     * @throws SignException
     * @since 1.0.0
     */
    public function setY(int $y): static
    {
        if ($y < 0) {
            $msg = "the rectangle coordinate Y must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->y = $y;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", \strval($this->y)));
        return $this;
    }

    /**
     * Gets as width
     *
     * the rectangle with
     *
     * @return int|null
     * @since 1.0.0
     */
    public function getWidth(): ?int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->width === null
                        ? "null"
                : \strval($this->width)
            ));
        return $this->width;
    }

    /**
     * Sets a new width
     *
     * the rectangle with
     *
     * @param int $width
     * @return static
     * @throws SignException
     * @since 1.0.0
     */
    public function setWidth(int $width): static
    {
        if ($width < 0) {
            $msg = "the rectangle width must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->width = $width;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s'",
                \strval($this->width)
            ));
        return $this;
    }

    /**
     * Gets as height
     *
     * The rectangle height
     *
     * @return int|null
     * @since 1.0.0
     */
    public function getHeight(): ?int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->height === null
                        ? "null"
                : \strval($this->height)
            ));
        return $this->height;
    }

    /**
     * Sets a new height
     *
     * The rectangle height
     *
     * @param int $height
     * @return static
     * @throws SignException
     * @since 1.0.0
     */
    public function setHeight(int $height): static
    {
        if ($height < 0) {
            $msg = "the rectangle height must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->height = $height;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s'",
                \strval($this->height)
            ));
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
    public function getRotation(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " get '%s'", strval($this->rotation)));
        return $this->rotation;
    }

    /**
     * Sets a new rotation
     *
     * The rectangle rotation: 0 , 90 , 180 or 270
     *
     * @param int $rotation
     * @return static
     * @throws SignException
     * @since 1.0.0
     */
    public function setRotation(int $rotation): static
    {
        if ($rotation < 0) {
            $msg = "the rectangle rotation must be a positive integer";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }

        $this->rotation = $rotation;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s'",
                \strval($this->rotation)
            ));
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
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $rectNode = $node->addChild("rectangle");
        $rectNode->addChild(
            "visible",
            $this->getVisible()
                ? "true"
            : "false"
        );
        $posNode  = $rectNode->addChild("position");

        $posNode->addChild(
            "x",
            strval($this->getX() === null
                    ? 0
            : $this->getX())
        );

        $posNode->addChild(
            "y",
            strval($this->getY() === null
                    ? 0
            : $this->getY())
        );

        $posNode->addChild(
            "width",
            strval($this->getWidth() === null
                    ? 0
            : $this->getWidth())
        );

        $posNode->addChild(
            "height",
            strval($this->getHeight() === null
                    ? 0
            : $this->getHeight())
        );

        $posNode->addChild(
            "rotation",
            strval($this->getRotation() === null
                    ? 0
            : $this->getRotation())
        );

        return $rectNode;
    }

    /**
     * Fill the array that will be used to make the request to the Rest API
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $data[static::API_P_VISIBLE] = $this->getVisible();
        $data[static::API_N_RECTANGLE] = [];

        $rect = &$data[static::API_N_RECTANGLE];

        $rect[static::API_P_X]     = $this->getX();
        $rect[static::API_P_Y]     = $this->getY();
        $rect[static::API_P_WIDTH] = $this->getWidth();
        $rect[static::API_P_HEIGHT] = $this->getHeight();
        $rect[static::API_P_ROTATION] = $this->getRotation();
    }
}
