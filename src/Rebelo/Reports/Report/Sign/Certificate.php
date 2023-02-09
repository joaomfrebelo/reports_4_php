<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\IAReport;

/**
 * Class representing Certificate
 * @since 1.0.0
 */
class Certificate implements IAReport
{

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_CERTIFICATE_NAME = "certificateName";

    /**
     * Api sign property name
     * @since 3.0.0
     */
    const API_P_CERTIFICATE_PASS = "certificatePassword";

    /**
     * The certificate name (alias) in the keystore<br>
     *
     * @var string|null $name
     * @since 1.0.0
     */
    private ?string $name = null;

    /**
     * The certificate password
     *
     * @var string|null $password
     * @since 1.0.0
     */
    private ?string $password = null;

    /**
     * Get the certificate name (alias) in the keystore
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Set the certificate name (alias) in the keystore
     *
     * @param string $name
     * @return static
     * @throws SignException
     * @since 1.0.0
     */
    public function setName(string $name): static
    {
        if ("" === $name = \trim($name)) {
            $msg = "Certificate name must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }
        $this->name = $name;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->name));
        return $this;
    }

    /**
     * Gets the certificate password
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPassword(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->password === null
                        ? "null"
                : md5($this->password)
            ));
        return $this->password;
    }

    /**
     * Sets a new password<br>
     *
     * The certificate password
     *
     * @param string|null $password
     * @return static
     * @since 1.0.0
     */
    public function setPassword(?string $password): static
    {
        $this->password = $password;
        //The password that is passed to the log is a md5 hash in order of the
        //password no be not known, however in memory is the password that
        //has been set
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s' (MD5) ",
                md5($password ?? "")
            ));
        return $this;
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        $certNode = $node->addChild("certificate");
        AReport::cdata($certNode->addChild("name"), $this->getName());
        AReport::cdata(
            $certNode->addChild("password"),
            $this->getPassword() == null
                ? ""
            : $this->getPassword()
        );
        return $certNode;
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        $str = clone $this;
        if ($this->password !== null) {
            $str->password = md5($this->password);
        }
        return \serialize($str);
    }

    /**
     * Fill the array that will be used to make the request to the Rest API
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $data[static::API_P_CERTIFICATE_PASS] = $this->getPassword();
        $data[static::API_P_CERTIFICATE_NAME] = $this->getName();
    }

}
