<?php

namespace Rebelo\Reports\Report;

use \Rebelo\Reports\Report\Sign\Sign;

/**
 * Class representing Pdf
 *
 * Export the report to a PDF file
 * @since 1.0.0
 */
class Pdf
    extends AFileReport
{

    /**
     * The PDF digital signature properties
     *
     * @var \Rebelo\Reports\Report\Sign\Sign $sign
     * @since 1.0.0
     */
    private $sign = null;

    /**
     *
     * Properties class to generate a pdf report
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets as sign
     *
     * The PDF digital signature properties
     *
     * @return \Rebelo\Reports\Report\Sign\Sign
     * @since 1.0.0
     */
    public function getSign()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'",
                            $this->sign === null
                        ? "null"
                        : $this->sign->__toString()));
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * The PDF digital signature properties
     *
     * @param \Rebelo\Reports\Report\Sign\Sign $sign
     * @return self
     * @since 1.0.0
     */
    public function setSign(Sign $sign)
    {
        $this->sign = $sign;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'",
                             $this->sign === null
                        ? "null"
                        : $this->sign->__toString()));
        return $this;
    }

    /**
     *
     * @since 1.0.0
     */
    public function __toString()
    {
        $str       = clone $this;
        $str->sign = null;
        return sprintf(serialize($str));
    }

    /**
     * Create the xml commom nodes of pdf exporter
     *
     * @param \SimpleXMLElement $node $node The node whre will be add the pdf node
     * @return \SimpleXMLElement The pdf node added to the parent to be possible the manipulation
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $pdfNode = parent::createXmlNode($node);
        if ($this->getSign() instanceof Sign)
        {
            $this->sign->createXmlNode($pdfNode);
        }
        return $pdfNode;
    }

}
