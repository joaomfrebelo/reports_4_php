<?php

declare(strict_types=1);

namespace Rebelo\Reports\Report\Datasource;

use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\SerializeReportException;

/**
 * AServer
 * @since 1.0.0
 */
abstract class AServer extends ADatasource
{

    /**
     * Api property name
     * @since 3.0.0
     */
    const API_P_URL = "url";

    /**
     * Api property name
     * @since 3.0.0
     */
    const API_P_TYPE = "type";

    /**
     * Api property name
     * @since 3.0.0
     */
    const API_P_DATE_PATTERN = "datePattern";

    /**
     * Api property name
     * @since 3.0.0
     */
    const API_P_NUMBER_PATTERN = "numberPattern";

    /**
     * The server URL
     *
     * @var string|null $url
     * @since 1.0.0
     */
    protected ?string $url = null;

    /**
     * The type of the request POST or GET
     *
     * @var ?\Rebelo\Reports\Report\Datasource\RequestType $type
     * @since 1.0.0
     */
    protected ?RequestType $type = null;

    /**
     * The Date pattern in the datasource values
     *
     * @var string|null $datePattern
     * @since 1.0.0
     */
    protected ?string $datePattern = null;

    /**
     * The number pattern in the datasource values
     *
     * @var ?string $numberPattern
     * @since 1.0.0
     */
    protected ?string $numberPattern = null;

    /**
     *
     * @param \Rebelo\Reports\Report\Datasource\RequestType|null $type
     * @since 1.0.0
     */
    public function __construct(?RequestType $type = null)
    {
        parent::__construct();

        $this->setType(
            $type === null ? new RequestType(RequestType::GET) : $type
        );

        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   "Type set to '%s' in construct",
                   $this->type->get()
               ));
    }

    /**
     * Gets the server url
     *
     * The server URL
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getUrl(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " get '%s'", $this->url));
        return $this->url;
    }

    /**
     * Gets the request type
     *
     * The type of the request POST or GET
     *
     * @return \Rebelo\Reports\Report\Datasource\RequestType|null
     * @since 1.0.0
     */
    public function getType(): ?RequestType
    {
        \Logger::getLogger(\get_class($this))
               ->info(
                   \sprintf(
                       __METHOD__ . " get to '%s'",
                       $this->type === null ? "null" : $this->type->get()
                   )
               );
        return $this->type;
    }

    /**
     * Set URL in child class must check if
     * the url schema is http or https and if is
     * correct
     * @since 1.0.0
     */
    abstract public function setUrl(?string $url);

    /**
     * Sets the request type
     *
     * The type of the request POST or GET
     *
     * @param \Rebelo\Reports\Report\Datasource\RequestType $type
     * @return static
     * @since 1.0.0
     */
    public function setType(RequestType $type): static
    {
        $this->type = $type;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->type->get()));
        return $this;
    }

    /**
     * Gets the  Date Pattern
     *
     * The Date pattern in the datasource values
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getDatePattern(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(
                   \sprintf(
                       __METHOD__ . " get to '%s'",
                       $this->datePattern === null ? "null" : $this->datePattern
                   )
               );
        return $this->datePattern;
    }

    /**
     * Sets a new Date Pattern
     *
     * The Date pattern in the datasource values
     *
     * @param string|null $datePattern
     * @return static
     * @since 1.0.0
     */
    public function setDatePattern(?string $datePattern): static
    {
        \Logger::getLogger(\get_class($this))
               ->debug(
                   \sprintf(
                       __METHOD__ . " set to '%s'",
                       $this->datePattern === null ? "null" : $this->datePattern
                   )
               );
        $this->datePattern = $datePattern;
        return $this;
    }

    /**
     * Gets teh Number Pattern
     *
     * The number pattern in the datasource values
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getNumberPattern(): ?string
    {
        \Logger::getLogger(\get_class($this))->info(
            \sprintf(
                __METHOD__ . " get to '%s'",
                $this->numberPattern === null ? "null" : $this->numberPattern
            )
        );
        return $this->numberPattern;
    }

    /**
     * Sets the  Number Pattern
     *
     * The number pattern in the datasource values
     *
     * @param string|null $numberPattern
     * @return static
     * @since 1.0.0
     */
    public function setNumberPattern(?string $numberPattern): static
    {
        \Logger::getLogger(\get_class($this))
               ->debug(
                   \sprintf(
                       __METHOD__ . " set to '%s'",
                       $this->numberPattern === null ? "null" : $this->numberPattern
                   )
               );
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
               ->debug(\sprintf(__METHOD__ . " get to '%s'", $str));
        return $str;
    }

    /**
     *
     * Serialize the node
     *
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\Reports\Report\SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if (!\is_string($this->getUrl()) || \trim($this->getUrl()) === "") {
            $msg = "Url must be set";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        if ($this->getType() === null) {
            $msg = "Request type must be set";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        $serverNode = $node->addChild(strtolower(get_class($this)));
        AReport::cdata($serverNode->addChild("url"), $this->getUrl());
        $serverNode->addChild("type", $this->getType()?->get());

        if ($this->getDatePattern() !== null) {
            AReport::cdata(
                $serverNode->addChild("datePattern"),
                $this->getDatePattern()
            );
        }

        if ($this->getNumberPattern() !== null) {
            AReport::cdata(
                $serverNode->addChild("numberPattern"),
                $this->getDatePattern()
            );
        }

        return $serverNode;
    }

    /**
     * Fill the array that will be used to make the request to the Rest API
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $ref                                  = new \ReflectionClass($this);
        $data[$ref->getShortName()]           = [];
        $server                               = &$data[$ref->getShortName()];
        $server[static::API_P_URL]            = $this->getUrl();
        $server[static::API_P_TYPE]           = $this->getType()->get();
        $server[static::API_P_DATE_PATTERN]   = $this->getDatePattern();
        $server[static::API_P_NUMBER_PATTERN] = $this->getNumberPattern();
    }
}
