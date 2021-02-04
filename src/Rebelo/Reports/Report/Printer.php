<?php

namespace Rebelo\Reports\Report;

use Rebelo\Reports\Report\AReport;

/**
 * Class representing PrintXsd
 *
 * Print the report (printer)
 */
class Printer
    extends AReport
{

    /**
     * The printer name.
     *
     * @var string $printer
     */
    private $printer = null;

    public function __construct()
    {
        parent::__construct();
        // Sets the defaul printer
        $this->setPrinter("");
    }

    /**
     * Gets as printer<br>
     * If printer name is empty or null will print in the default printer
     *
     * The printer name.
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPrinter() : ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__ . " getted '%s'",
                    $this->printer === "null"
                        ? "null"
                    : $this->printer
                )
            );
        return $this->printer;
    }

    /**
     * Set the printer name.<br>
     * If printer name is empty or null will print in the default printer
     *
     * @param string $printer
     * @return self
     * @since 1.0.0
     */
    public function setPrinter($printer)
    {
        $this->printer = $printer;
        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " setted to '%s'",
                    $this->printer === null
                        ? "null"
                    : $this->printer
                )
            );
        return $this;
    }

    /**
     *
     * @return string     *
     * @since 1.0.0
     */
    public function __toString()
    {
        return \serialize($this);
    }

    /**
     *
     * Create the xml node for printer exporter
     *
     * @param \SimpleXMLElement $node
     * @return void
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $printNode = $node->addChild("print");

        AReport::cdata(
            $printNode->addChild("printer"),
            $this->getPrinter() === null
                ? ""
                : $this->getPrinter()
        );
    }

}
