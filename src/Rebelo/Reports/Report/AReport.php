<?php

/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 */

declare(strict_types=1);

namespace Rebelo\Reports\Report;

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\Datasource\ADatasource;
use Rebelo\Reports\Report\Parameter\Parameter;

/**
 *
 * Class representing RReport (Rebelo Report)
 * @since 1.0.0
 *
 */
abstract class AReport implements IAReport
{

    /**
     * Schema (XDS) file location
     * @since 1.0.0
     */
    const SCHEMA_LOCATION = "https://raw.githubusercontent.com"
                            ."/joaomfrebelo/reports_core/master/src/main/resources/schema_1_1.xsd";

    /**
     * Schema namespace
     * @since 1.0.0
     */
    const SCHEMA_NS = "urn:rebelo.reports.core.parse.pojo";

    /**
     * Node name for the outputfilepath
     * @since 1.0.0
     */
    const NODE_OUT_FILE = "outputfile";

    /**
     * The full path for the Jasper reports file or a relative path to
     * the jasperFileBaseDir
     *
     * @var \Rebelo\Reports\Report\JasperFile|null $jasperfile
     * @since 1.0.0
     */
    protected ?JasperFile $jasperfile = null;

    /**
     * Define the datasource for the report
     *
     * @var \Rebelo\Reports\Report\Datasource\ADatasource|null $datasource
     * @since 1.0.0
     */
    protected ?Datasource\ADatasource $datasource = null;

    /**
     * The parameters to be passed to the jasper report
     *
     * @var \Rebelo\Reports\Report\Parameter\Parameter[] $parameters
     * @since 1.0.0
     */
    protected array $parameters = array();

    public function __construct()
    {
        Config::configLog4Php();
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
    }

    /**
     * Gets as jasperfile
     *
     * The full path or relative path for the Jasper reports file.
     *
     * @return \Rebelo\Reports\Report\JasperFile|null
     * @since 1.0.0
     */
    public function getJasperFile(): ?JasperFile
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        return $this->jasperfile;
    }

    /**
     * Sets a new jasperfile
     *
     * The full or relative path for the Jasper reports file.<br>
     * When use relative path jasperBaseDir mus be set
     *
     * @param \Rebelo\Reports\Report\JasperFile $jasperfile
     * @return static
     * @since 1.0.0
     */
    public function setJasperFile(JasperFile $jasperfile): static
    {
        $this->jasperfile = $jasperfile;
        \Logger::getLogger(\get_class($this))->debug(
            sprintf(
                __METHOD__ . " set to '%s'",
                $this->jasperfile->__toString()
            )
        );
        return $this;
    }

    /**
     * Gets as datasource
     *
     * Define the datasource for the report
     *
     * @return \Rebelo\Reports\Report\Datasource\ADatasource|null
     * @since 1.0.0
     */
    public function getDatasource(): ?Datasource\ADatasource
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        return $this->datasource;
    }

    /**
     * Sets a new datasource
     *
     * Define the datasource for the report
     *
     * @param \Rebelo\Reports\Report\Datasource\ADatasource
     * @return static
     * @since 1.0.0
     */
    public function setDatasource(ADatasource $datasource): static
    {
        $this->datasource = $datasource;
        \Logger::getLogger(\get_class($this))->debug(
            sprintf(
                __METHOD__ . " set to '%s'",
                $this->datasource->__toString()
            )
        );
        return $this;
    }

    /**
     * Add a parameter to the stack<br>
     *
     * The parameters to be passed to the jasper report
     *
     * @return int parameter index
     * @param \Rebelo\Reports\Report\Parameter\Parameter $parameter
     * @since 1.0.0
     */
    public function addToParameter(Parameter $parameter): int
    {
        if (\count($this->parameters) == 0) {
            $index = 0;
        } else {
            // The index if obtaining this way because you can unset a key
            $keys  = \array_keys($this->parameters);
            $index = $keys[\count($keys) - 1] + 1;
        }

        $this->parameters[$index] = $parameter;
        \Logger::getLogger(\get_class($this))->debug(
            sprintf(
                __METHOD__ . " set to '%s' with index '%s'",
                $parameter->__toString(),
                $index
            )
        );
        return $index;
    }

    /**
     * Verify if the index/key is set in the parameters satck
     *
     * @param int $index
     * @return bool
     * @since 1.0.0
     */
    public function issetParameters(int $index): bool
    {
        $isset = isset($this->parameters[$index]);
        \Logger::getLogger(\get_class($this))->debug(
            sprintf(
                __METHOD__ . " get '%s' for index '%s'",
                $isset
                    ? "true"
                : "false",
                $index
            )
        );
        return $isset;
    }

    /**
     * unset parameters
     *
     * The parameters to be passed to the jasper report
     *
     * @param int $index
     * @return void
     * @throws \Rebelo\Reports\Report\ReportException
     * @since 1.0.0
     */
    public function unsetParameters(int $index): void
    {
        if (\array_key_exists($index, $this->parameters)) {
            \Logger::getLogger(\get_class($this))->debug(
                sprintf(__METHOD__ . " parameter index '%s' unset ", $index)
            );
            unset($this->parameters[$index]);
            return;
        }
        $msg = sprintf("Index '%s' is not defined in parameters stack", $index);
        \Logger::getLogger(\get_class($this))->debug($msg);
        throw new ReportException($msg);
    }

    /**
     * Gets as parameters
     *
     * The parameters to be passed to the jasper report
     *
     * @return \Rebelo\Reports\Report\Parameter\Parameter[]
     * @since 1.0.0
     */
    public function getParameters(): array
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        return $this->parameters;
    }

    /**
     *
     * Enclose the string in CDATA
     * @see https://stackoverflow.com/a/33407070/6397645
     * @param \SimpleXMLElement $node
     * @param string            $value
     * @return \SimpleXMLElement
     */
    public static function cdata(\SimpleXMLElement $node, string $value): \SimpleXMLElement
    {
        $base     = \dom_import_simplexml($node);
        $docOwner = $base->ownerDocument;
        $base->appendChild($docOwner->createCDATASection($value));
        return $node;
    }

    /**
     *
     * Serialize the report properties to the xml to be used
     * by tha java rebelo reports_cli
     *
     * @return \SimpleXMLElement
     * @throws SerializeReportException
     * @throws \Exception
     */
    public function serializeToSimpleXmlElement(): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        if ($this->getJasperFile() === null) {
            $msg = "JasperFile class not defined";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }

        \libxml_use_internal_errors(true);

        $base = '<?xml version="1.0" encoding="UTF-8" ?>';
        $base .= '<rreport xmlns="' . static::SCHEMA_NS . '" ';
        $base .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $base .= 'xsi:schemaLocation="urn:RReports_CLI:1.1 ' . static::SCHEMA_LOCATION . '">';
        $base .= '</rreport>';

        $rreportNode    = new \SimpleXMLElement($base);
        $this->getJasperFile()->createXmlNode($rreportNode);
        $reportTypeNode = $rreportNode->addChild("reporttype");
        $this->createXmlNode($reportTypeNode);
        $datasourceNode = $rreportNode->addChild("datasource");
        $this->getDatasource()->createXmlNode($datasourceNode);
        if (\count($this->parameters) > 0) {
            $parametersNode = $rreportNode->addChild("parameters");

            foreach ($this->getParameters() as $param) {
                $param->createXmlNode($parametersNode);
            }
        }

        return $rreportNode;
    }

    /**
     * Validate the xml schema
     *
     * @param \SimpleXMLElement $simpleXMLElement
     * @throws SerializeReportException
     */
    public function validateXml(\SimpleXMLElement $simpleXMLElement)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        libxml_use_internal_errors(true);
        $xml = $simpleXMLElement->asXML();

        $xmlDoc = new \DOMDocument();
        $xmlDoc->loadXML($xml);
        if ($xmlDoc->schemaValidate(static::SCHEMA_LOCATION) === false) {
            $errorStack = \libxml_get_errors();
            $msg = "";
            foreach ($errorStack as $error) {
                $msg .= $error->message .";";
            }

            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " Errors '%s'", $msg));
            throw new SerializeReportException($msg);
        }
    }

    /**
     * Serialize the xml to a string
     * @return string
     * @throws \Rebelo\Reports\Report\SerializeReportException
     */
    public function serializeToString(): string
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        return $this->serializeToSimpleXmlElement()->asXML();
    }

    /**
     * Serialize the xml to a file
     * @param string $path
     * @return void
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @since 1.0.0
     */
    public function serializeToFile(string $path): void
    {
        if ("" === $path = \trim($path)) {
            $msg = "path must be a string";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " file path is '%s'", $path));
        $this->serializeToSimpleXmlElement()->asXML($path);
        if (\is_file($path) === false) {
            $msg = sprintf(__METHOD__ . " File '%s' was not created", $path);
            \Logger::getLogger(\get_class($this))
                ->error($msg);
            throw new SerializeReportException($msg);
        }
    }
}
