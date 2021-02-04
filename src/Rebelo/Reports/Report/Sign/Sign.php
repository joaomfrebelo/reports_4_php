<?php

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Report\Sign\Type;
use Rebelo\Reports\Report\Sign\Keystore;
use Rebelo\Reports\Report\Sign\Level;
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Class representing Sign
 *
 * The pdf digital signature properties
 */
class Sign implements \Rebelo\Reports\Report\IAReport
{
    /**
     * The java key store where the certificates are
     *
     * @var \Rebelo\Reports\Report\Sign\Keystore|null $keystore
     */
    private $keystore = null;

    /**
     * The siganute certification level
     *
     * @var Level $level
     */
    private $level = null;

    /**
     * The type of certificate
     *
     * @var \Rebelo\Reports\Report\Sign\Type $type
     */
    private $type = null;

    /**
     * The visibility and position of the signature image rectangle
     *
     * @var \Rebelo\Reports\Report\Sign\Rectangle $rectangle
     */
    private $rectangle = null;

    /**
     * The legend 'Location' to be write in the signaute
     *
     * @var string $location
     */
    private $location = null;

    /**
     * The legend 'Reazon' to be write in the signaute
     *
     * @var string $reazon
     */
    private $reazon = null;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        \Rebelo\Reports\Config\Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->setType(new Type(Type::SELF_SIGNED));
    }

    /**
     * Gets as keystore
     *
     * The java key store where the certificates are
     *
     * @return \Rebelo\Reports\Report\Sign\Keystore|null
     * @since 1.0.0
     */
    public function getKeystore()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->keystore === null ? "null" : $this->keystore->__toString()
                )
            );
        return $this->keystore;
    }

    /**
     * Sets a new keystore
     *
     * The java key store where the certificates are
     *
     * @param \Rebelo\Reports\Report\Sign\Keystore $keystore
     * @return self
     * @since 1.0.0
     */
    public function setKeystore(Keystore $keystore)
    {
        $this->keystore = $keystore;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->keystore === null ? "null" : $this->keystore
                )
            );
        return $this;
    }

    /**
     * Gets as level
     *
     * The siganute certification level
     *
     * @return Level
     * @since 1.0.0
     */
    public function getLevel()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->level == null ? "null" : $this->level->get()
                )
            );
        return $this->level;
    }

    /**
     * Sets a new level
     *
     * The siganute certification level
     *
     * @param Level $level
     * @return self
     * @since 1.0.0
     */
    public function setLevel(Level $level)
    {
        $this->level = $level;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->level->get()));
        return $this;
    }

    /**
     * Gets as type
     *
     * The type of certificate
     *
     * @return Type
     * @since 1.0.0
     */
    public function getType()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__." getted '%s'", $this->type->get()));
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * The type of certificate
     *
     * @param Type $type
     * @return self
     * @since 1.0.0
     */
    public function setType(Type $type)
    {
        $this->type = $type;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__." setted to '%s'", $this->type->get()));
        return $this;
    }

    /**
     * Gets as rectangle
     *
     * The visibility and position of the signature image rectangle
     *
     * @return \Rebelo\Reports\Report\Sign\Rectangle
     * @since 1.0.0
     */
    public function getRectangle()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->rectangle === null ? "null" : $this->rectangle->__toString()
                )
            );
        return $this->rectangle;
    }

    /**
     * Sets a new rectangle
     *
     * The visibility and position of the signature image rectangle
     *
     * @param \Rebelo\Reports\Report\Sign\Rectangle $rectangle
     * @return self
     * @since 1.0.0
     */
    public function setRectangle(Rectangle $rectangle)
    {
        $this->rectangle = $rectangle;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->rectangle === null ? "null" : $this->rectangle->__toString()
                )
            );
        return $this;
    }

    /**
     * Gets as location
     *
     * The legend 'Location' to be write in the signaute
     *
     * @return string
     * @since 1.0.0
     */
    public function getLocation()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->location === null ? "null" : $this->location
                )
            );
        return $this->location;
    }

    /**
     * Set the legend 'Location' to be write in the signaute
     *
     * @param string $location
     * @return self
     * @since 1.0.0
     */
    public function setLocation($location)
    {
        $this->location = $location;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->location === null ? "null" : $this->location
                )
            );
        return $this;
    }

    /**
     * Gets as reazon
     *
     * The legend 'Reazon' to be write in the signaute
     *
     * @return string
     * @since 1.0.0
     */
    public function getReazon()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__." getted '%s'",
                    $this->reazon === null ? "null" : $this->reazon
                )
            );
        return $this->reazon;
    }

    /**
     * Sets a new reazon
     *
     * The legend 'Reazon' to be write in the signaute
     *
     * @param string $reazon
     * @return self
     * @since 1.0.0
     */
    public function setReazon($reazon)
    {
        $this->reazon = $reazon;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__." setted to '%s'",
                    $this->reazon === null ? "null" : $this->reazon
                )
            );
        return $this;
    }

    /**
     *
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        $clone = clone $this;
        return \serialize($clone);
    }

    /**
     *
     * Serialize the xml node
     *
     * @param \SimpleXMLElement $node
     * @throws SerializeReportException
     * @return void
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $class    = \strtolower(get_class($this));
        $lastPos  = \strrpos($class, "\\");
        $nodeName = \substr($class, $lastPos + 1, \strlen($class));
        $signNode = $node->addChild($nodeName);
        if ($this->getKeystore() === null) {
            $msg = "To have a sign pdf the keystore must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__." '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        $this->getKeystore()->createXmlNode($signNode);
        $signNode->addChild("level", $this->getLevel()->get());
        $signNode->addChild("type", $this->getType()->get());
        if ($this->getRectangle() !== null) {
            $this->getRectangle()->createXmlNode($signNode);
        }
        AReport::cdata($signNode->addChild("location"), $this->getLocation());
        AReport::cdata($signNode->addChild("reazon"), $this->getReazon());
    }
}