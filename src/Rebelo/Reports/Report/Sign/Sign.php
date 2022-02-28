<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\IAReport;
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Class representing Sign
 *
 * The pdf digital signature properties
 */
class Sign implements IAReport
{

    /**
     * Sign api node name
     * @since 3.0.0
     */
    const API_N_SIGN = "sign";

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_LEVEL = "level";

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_CERTIFICATE_TYPE = "certificateType";

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_LOCATION = "location";

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_REASON = "reason";

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_CONTACT = "contact";

    /**
     * The java key store where the certificates are
     *
     * @var \Rebelo\Reports\Report\Sign\Keystore|null $keystore
     */
    private ?Keystore $keystore = null;

    /**
     * The signature certification level
     *
     * @var Level|null $level
     */
    private ?Level $level = null;

    /**
     * The type of certificate
     *
     * @var \Rebelo\Reports\Report\Sign\Type|null $type
     */
    private ?Type $type = null;

    /**
     * The visibility and position of the signature image rectangle
     *
     * @var \Rebelo\Reports\Report\Sign\Rectangle|null $rectangle
     */
    private ?Rectangle $rectangle = null;

    /**
     * The legend 'Location' to be write in the signature
     *
     * @var string|null $location
     */
    private ?string $location = null;

    /**
     * The legend 'Reason' to be write in the signature
     *
     * @var string|null $reason
     */
    private ?string $reason = null;

    /**
     * The legend 'Contact' to be write in the signature (Only for API)
     *
     * @var string|null $contact
     */
    private ?string $contact = null;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        Config::configLog4Php();
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
    public function getKeystore(): ?Keystore
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->keystore === null
                              ? "null"
                              : $this->keystore->__toString()
               ));
        return $this->keystore;
    }

    /**
     * Sets a new keystore
     *
     * The java key store where the certificates are
     *
     * @param \Rebelo\Reports\Report\Sign\Keystore|null $keystore
     * @return static
     * @since 1.0.0
     */
    public function setKeystore(?Keystore $keystore): static
    {
        $this->keystore = $keystore;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->keystore === null
                               ? "null"
                               : $this->keystore
               ));
        return $this;
    }

    /**
     * Gets as level
     *
     * The signature certification level
     *
     * @return Level|null
     * @since 1.0.0
     */
    public function getLevel(): ?Level
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->level == null
                              ? "null"
                              : $this->level->get()
               ));
        return $this->level;
    }

    /**
     * Sets a new level
     *
     * The signature certification level
     *
     * @param Level $level
     * @return static
     * @since 1.0.0
     */
    public function setLevel(Level $level): static
    {
        $this->level = $level;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->level->get()));
        return $this;
    }

    /**
     * Gets as type
     *
     * The type of certificate
     *
     * @return \Rebelo\Reports\Report\Sign\Type|null
     * @since 1.0.0
     */
    public function getType(): ?Type
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " get '%s'", $this->type?->get() ?? "null"));
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * The type of certificate
     *
     * @param \Rebelo\Reports\Report\Sign\Type $type
     * @return static
     * @since 1.0.0
     */
    public function setType(Type $type): static
    {
        $this->type = $type;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->type->get()));
        return $this;
    }

    /**
     * Gets as rectangle
     *
     * The visibility and position of the signature image rectangle
     *
     * @return \Rebelo\Reports\Report\Sign\Rectangle|null
     * @since 1.0.0
     */
    public function getRectangle(): ?Rectangle
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->rectangle === null
                              ? "null"
                              : $this->rectangle->__toString()
               ));
        return $this->rectangle;
    }

    /**
     * Sets a new rectangle
     *
     * The visibility and position of the signature image rectangle
     *
     * @param \Rebelo\Reports\Report\Sign\Rectangle $rectangle
     * @return static
     * @since 1.0.0
     */
    public function setRectangle(Rectangle $rectangle): static
    {
        $this->rectangle = $rectangle;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->rectangle === null
                               ? "null"
                               : $this->rectangle->__toString()
               ));
        return $this;
    }

    /**
     * Gets as location
     *
     * The legend 'Location' to be write in the signature
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getLocation(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->location === null
                              ? "null"
                              : $this->location
               ));
        return $this->location;
    }

    /**
     * Set the legend 'Location' to be write in the signature
     *
     * @param string|null $location
     * @return static
     * @since 1.0.0
     */
    public function setLocation(?string $location): static
    {
        $this->location = $location;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->location === null
                               ? "null"
                               : $this->location
               ));
        return $this;
    }

    /**
     * Gets as reason
     *
     * The legend 'Reason' to be write in the signature
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getReason(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->reason === null
                              ? "null"
                              : $this->reason
               ));
        return $this->reason;
    }

    /**
     * Sets a new reason
     *
     * The legend 'Reason' to be write in the signature
     *
     * @param string|null $reason
     * @return static
     * @since 1.0.0
     */
    public function setReason(?string $reason): static
    {
        $this->reason = $reason;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->reason === null
                               ? "null"
                               : $this->reason
               ));
        return $this;
    }

    /**
     * Gets as contact
     *
     * The legend 'Contact' to be write in the signature
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getContact(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->contact === null
                              ? "null"
                              : $this->contact
               ));
        return $this->contact;
    }

    /**
     * Sets a new contact
     *
     * The legend 'Contact' to be write in the signature
     *
     * @param string|null $contact
     * @return static
     * @since 1.0.0
     */
    public function setContact(?string $contact): static
    {
        $this->contact = $contact;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->contact === null
                               ? "null"
                               : $this->contact
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
        $clone = clone $this;
        return \serialize($clone);
    }

    /**
     *
     * Serialize the xml node
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $class    = \strtolower(get_class($this));
        $lastPos  = \strrpos($class, "\\");
        $nodeName = \substr($class, $lastPos + 1, \strlen($class));
        $signNode = $node->addChild($nodeName);
        if ($this->getKeystore() === null) {
            $msg = "To have a sign pdf the keystore must be set";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        $this->getKeystore()->createXmlNode($signNode);
        $signNode->addChild("level", $this->getLevel()->get());
        $signNode->addChild("type", $this->getType()->get());
        if ($this->getRectangle() !== null) {
            $this->getRectangle()->createXmlNode($signNode);
        }
        AReport::cdata($signNode->addChild("location"), $this->getLocation());
        AReport::cdata($signNode->addChild("reazon"), $this->getReason());

        return $signNode;
    }

    /**
     * Fill the array that will be used to make the request to the Rest API
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $data[static::API_N_SIGN] = [];
        $sign                     = &$data[static::API_N_SIGN];

        $sign[static::API_P_LEVEL]            = $this->getLevel()?->get();
        $sign[static::API_P_LOCATION]         = $this->getLocation() ?? "";
        $sign[static::API_P_CERTIFICATE_TYPE] = $this->getType()?->get();
        $sign[static::API_P_CONTACT]          = $this->getContact() ?? "";
        $sign[static::API_P_REASON]           = $this->getReason() ?? "";

        $this->getKeystore()?->fillApiRequest($sign);
        $this->getRectangle()?->fillApiRequest($sign);
    }
}
