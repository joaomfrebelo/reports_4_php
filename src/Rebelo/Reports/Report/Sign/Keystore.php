<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\IAReport;
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Class representing Keystore
 * @since 1.0.0
 */
class Keystore implements IAReport
{

    /**
     * The full path of the key store
     *
     * @var string|null $path
     * @since 1.0.0
     */
    private ?string $path = null;

    /**
     * The key store password
     *
     * @var string|null $password
     * @since 1.0.0
     */
    private ?string $password = null;

    /**
     * The certificate properties
     *
     * @var \Rebelo\Reports\Report\Sign\Certificate|null $certificate
     * @since 1.0.0
     */
    private ?Certificate $certificate = null;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Get the full path of the key store
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * Se the full path of the key store
     *
     * @param string|null $path
     * @return static
     * @since 1.0.0
     */
    public function setPath(?string $path): static
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the key store password
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the key store password
     *
     * @param string|null $password
     * @return static
     * @since 1.0.0
     */
    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get the certificate properties
     *
     * @return \Rebelo\Reports\Report\Sign\Certificate|null
     * @since 1.0.0
     */
    public function getCertificate(): ?Certificate
    {
        return $this->certificate;
    }

    /**
     * Set the certificate properties
     *
     * @param \Rebelo\Reports\Report\Sign\Certificate|null $certificate
     * @return static
     * @since 1.0.0
     */
    public function setCertificate(?Certificate $certificate): static
    {
        $this->certificate = $certificate;
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
        $clone->setPassword("*********");
        return \serialize($clone);
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (!\is_string($this->getPath()) || \trim($this->getPath() ?? "") === "") {
            $msg = "The path of the keystore must be set";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if ($this->getCertificate() === null) {
            $msg = "The keystore certificate must be set";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        $keyStoreNode = $node->addChild("keystore");
        AReport::cdata($keyStoreNode->addChild("path"), $this->getPath());
        AReport::cdata($keyStoreNode->addChild("password"), $this->getPassword());
        $this->getCertificate()->createXmlNode($keyStoreNode);
        return $keyStoreNode;
    }
}
