<?php

namespace Rebelo\Reports\Report\Sign;

use Rebelo\Reports\Report\AReport;

/**
 * Class representing Certificate
 * @since 1.0.0
 */
class Certificate
    implements \Rebelo\Reports\Report\IAReport
{

    /**
     * The certificate name (alias) in the keystore<br>
     *
     * @var string $name
     * @since 1.0.0
     */
    private $name = null;

    /**
     * The certificate password
     *
     * @var string $password
     * @since 1.0.0
     */
    private $password = null;

    /**
     * Get the certificate name (alias) in the keystore
     *
     * @return string
     * @since 1.0.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @since 1.0.0
     */
    public function __construct()
    {
        \Rebelo\Reports\Config\Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Set the certificate name (alias) in the keystore
     *
     * @param string $name
     * @return self
     * @throws SignException
     * @since 1.0.0
     */
    public function setName(string $name)
    {
        if (\trim($name) === "")
        {
            $msg = "Certificate name must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SignException($msg);
        }
        $this->name = $name;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " setted to '%s'",
                    $this->name === null
                        ? "null"
                    : $this->name
                )
            );
        return $this;
    }

    /**
     * Gets the certificate password
     *
     * @return string
     * @since 1.0.0
     */
    public function getPassword()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__ . " getted '%s'",
                    $this->password === null
                        ? "null"
                    : md5($this->password)
                )
            );
        return $this->password;
    }

    /**
     * Sets a new password<br>
     *
     * The certificate password
     *
     * @param string $password
     * @return self
     * @since 1.0.0
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        //The passord taht is passed to the log is a md5 hash in order of the
        //password no be not knowed, how ever in memory is the password that
        //has been setted
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " setted to '%s' (MD5) ",
                    md5($password)
                )
            );
        return $this;
    }

    /**
     * Create the xml node
     * @param \SimpleXMLElement $node
     * @return void
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        $certNode = $node->addChild("certificate");
        AReport::cdata($certNode->addChild("name"), $this->getName());
        AReport::cdata(
            $certNode->addChild("password"),
            $this->getPassword() == null
                ? ""
            : $this->getPassword()
        );
    }

    /**
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        $str = clone $this;
        if ($this->password !== null)
        {
            $str->password = md5($this->password);
        }
        return \serialize($str);
    }

}
