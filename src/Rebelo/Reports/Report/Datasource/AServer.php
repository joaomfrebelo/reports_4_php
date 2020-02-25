<?php

namespace Rebelo\Reports\Report\Datasource;

use Rebelo\Reports\Report\AReport;

/**
 * AServer
 * @since 1.0.0
 */
abstract class AServer
    extends ADatasource
{

    /**
     * The server URL
     *
     * @var string $url
     * @since 1.0.0
     */
    protected $url = null;

    /**
     * The type of the request POST or GET
     *
     * @var \Rebelo\Reports\Report\Datasource\RequestType $type
     * @since 1.0.0
     */
    protected $type = null;

    /**
     * The Date pattern in the datasource values
     *
     * @var string $datePattern
     * @since 1.0.0
     */
    protected $datePattern = null;

    /**
     * The number pattern in the datasource values
     *
     * @var string $numberPattern
     * @since 1.0.0
     */
    protected $numberPattern = null;

    /**
     *
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type
     * @since 1.0.0
     */
    public function __construct(RequestType $type = null)
    {
        parent::__construct();
        $this->setType($type === null
                ? new RequestType(RequestType::GET)
                : $type);
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf("Type setted to '%s' in construct",
                            $this->type->get()));
    }

    /**
     * Gets the server url
     *
     * The server URL
     *
     * @return string
     * @since 1.0.0
     */
    public function getUrl()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->url));
        return $this->url;
    }

    /**
     * Gets the request type
     *
     * The type of the request POST or GET
     *
     * @return \Rebelo\Reports\Report\Datasource\RequestType
     * @since 1.0.0
     */
    public function getType()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted to '%s'",
                           $this->type === null
                        ? "null"
                        : $this->type->get()));
        return $this->type;
    }

    /**
     * Set URL in child class must check if
     * the url schema is http or https and if is
     * correct
     * @since 1.0.0
     */
    abstract public function setUrl($url);

    /**
     * Sets the request type
     *
     * The type of the request POST or GET
     *
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type
     * @return self
     * @since 1.0.0
     */
    public function setType(\Rebelo\Reports\Report\Datasource\RequestType $type)
    {
        $this->type = $type;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->type->get()));
        return $this;
    }

    /**
     * Gets the  Date Pattern
     *
     * The Date pattern in the datasource values
     *
     * @return string
     * @since 1.0.0
     */
    public function getDatePattern()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted to '%s'",
                           $this->datePattern === null
                        ? "null"
                        : $this->datePattern));
        return $this->datePattern;
    }

    /**
     * Sets a new Date Pattern
     *
     * The Date pattern in the datasource values
     *
     * @param string $datePattern
     * @return self
     * @since 1.0.0
     */
    public function setDatePattern($datePattern)
    {
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                            $this->datePattern === null
                        ? "null"
                        : $this->datePattern));
        $this->datePattern = $datePattern;
        return $this;
    }

    /**
     * Gets teh Number Pattern
     *
     * The number pattern in the datasource values
     *
     * @return string
     * @since 1.0.0
     */
    public function getNumberPattern()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted to '%s'",
                           $this->numberPattern === null
                        ? "null"
                        : $this->numberPattern));
        return $this->numberPattern;
    }

    /**
     * Sets the  Number Pattern
     *
     * The number pattern in the datasource values
     *
     * @param string $numberPattern
     * @return self
     * @since 1.0.0
     */
    public function setNumberPattern($numberPattern)
    {
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                            $this->numberPattern === null
                        ? "null"
                        : $this->numberPattern));
        $this->numberPattern = $numberPattern;
        return $this;
    }

    /**
     *
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        $str = "{" . $this->url === null
            ? "null"
            : $this->url . "}";
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " getted to '%s'", $str));
        return $str;
    }

    /**
     *
     * Sreialize the node
     *
     * @param \SimpleXMLElement $node
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if (!\is_string($this->getUrl()) || \trim($this->getUrl()) === "")
        {
            $msg = "Url must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if ($this->getType() === null)
        {
            $msg = "Request type must be setted";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        $serverNode = $node->addChild(strtolower(get_class($this)));
        $serverNode->addChild("url", AReport::cdata($this->getUrl()));
        $serverNode->addChild("type", $this->getType()->get());
        if ($this->getDatePattern() !== null)
        {
            $serverNode->addChild("datePattern",
                                  AReport::cdata($this->getDatePattern()));
        }
        if ($this->getNumberPattern() !== null)
        {
            $serverNode->addChild("numberPattern",
                                  AReport::cdata($this->getDatePattern()));
        }
    }

}
