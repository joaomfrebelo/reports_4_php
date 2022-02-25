<?php

namespace Rebelo\Reports\Report\Datasource;

use Rebelo\Reports\Report\ReportException;

class JsonFile extends ADatasource
{
    /**
     * Api property
     * @since 3.0.0
     */
    const API_P_JSON = "json";

    /**
     * @var string
     * @since 3.0.0
     */
    private string $json;

    /**
     * Only available for API
     * @since 3.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getJson(): string
    {
        return $this->json;
    }

    /**
     * @param string $json
     */
    public function setJson(string $json): void
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->json = $json;
    }

    /**
     * Not implemented
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        throw new ReportException("Not implemented");
    }

    public function fillApiRequest(array &$data): void
    {
        if (!isset($this->json)) {
            return;
        }

        $name = (new \ReflectionClass($this))->getShortName();
        $data[$name] = [];
        $data[$name][static::API_P_JSON] = \base64_encode($this->getJson());
    }


    public function __toString()
    {
        return \serialize($this);
    }
}
