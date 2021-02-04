<?php

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Report\Sign\Certificate;
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * Class representing Keystore
 * @since 1.0.0
 */
class Keystore
    implements \Rebelo\Reports\Report\IAReport
{

    /**
     * The full path of the key store
     *
     * @var string $path
     * @since 1.0.0
     */
    private $path = null;

    /**
     * The key store password
     *
     * @var string $password
     * @since 1.0.0
     */
    private $password = null;

    /**
     * The certificate properties
     *
     * @var \Rebelo\Reports\Report\Sign\Certificate $certificate
     * @since 1.0.0
     */
    private $certificate = null;

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        \Rebelo\Reports\Config\Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Get the full path of the key store
     *
     * @return string
     * @since 1.0.0
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Se the full path of the key store
     *
     * @param string $path
     * @return self
     * @since 1.0.0
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get the key store password
     *
     * @return string
     * @since 1.0.0
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the key store password
     *
     * @param string $password
     * @return self
     * @since 1.0.0
     */
    public function setPassword($password)
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
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * Set the certificate properties
     *
     * @param \Rebelo\Reports\Report\Sign\Certificate $certificate
     * @return self
     * @since 1.0.0
     */
    public function setCertificate(Certificate $certificate)
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
     *
     * Create the xml node
     *
     * @param \SimpleXMLElement $node
     * @throws SerializeReportException
     * @return void
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if (!\is_string($this->getPath()) || \trim($this->getPath()) === "")
        {
            $msg = "The path of the keystore must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if ($this->getCertificate() === null)
        {
            $msg = "The keystore certificate must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        $keyStoreNode = $node->addChild("keystore");
        AReport::cdata($keyStoreNode->addChild("path"), $this->getPath());
        AReport::cdata($keyStoreNode->addChild("password"), $this->getPassword());
        $this->getCertificate()->createXmlNode($keyStoreNode);
    }

}
