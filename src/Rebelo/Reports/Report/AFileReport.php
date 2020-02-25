<?php

/*
 * The MIT License
 *
 * Copyright 2020 João Rebelo.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
//declare(strict_types=1);

namespace Rebelo\Reports\Report;

/**
 * Description of AFileReport
 *
 * Abstarct class for reports that will be exported to a file.<br>
 * Ex: pdf, xml, doc, etc.
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AFileReport
    extends AReport
{

    /**
     * Abstarct class for reports that will be exported to a file.<br>
     * Ex: pdf, xml, doc, etc.
     * @since 1.0.0
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * The full or relative path of the output file.
     *
     * @var string $outputfile
     * @since 1.0.0
     */
    private $outputfile = null;

    /**
     * Gets the full or relative path of the output file.
     *
     * @return string
     * @since 1.0.0
     */
    public function getOutputfile()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", $this->outputfile));
        return $this->outputfile;
    }

    /**
     * Sets the full or relative path of the output file.
     *
     * @param string $outputfile
     * @return self
     * @since 1.0.0
     */
    public function setOutputfile($outputfile)
    {
        if (!is_string($outputfile) || trim($outputfile) === "")
        {
            $msg = "the output file path must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->outputfile = $outputfile;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->outputfile));
        return $this;
    }

    /**
     * Create the xml commom nodes of all File exporters
     * @since 1.0.0
     *
     * @param \SimpleXMLElement $node The node whre will be add the node of this class
     * @return \SimpleXMLElement The add node to be possible the manipulation
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($this->getOutputfile() === null)
        {
            $msg = "The output file path must be setted to be possible the "
                . "xml serialization";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        $class     = \strtolower(get_class($this));
        $lastPos   = \strrpos($class, "\\");
        $nodeName  = \substr($class, $lastPos + 1, \strlen($class));
        $AFileNode = $node->addChild($nodeName);
        AReport::cdata($AFileNode->addChild(static::NODE_OUT_FILE),
                                            $this->getOutputfile());
        return $AFileNode;
    }

}
