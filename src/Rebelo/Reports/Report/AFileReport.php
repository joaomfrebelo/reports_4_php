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

declare(strict_types=1);

namespace Rebelo\Reports\Report;

/**
 * Description of AFileReport
 *
 * Abstract class for reports that will be exported to a file.<br>
 * Ex: pdf, xml, doc, etc.
 *
 * @author João Rebelo
 * @since 1.0.0
 */
abstract class AFileReport extends AReport
{

    /**
     * Abstract class for reports that will be exported to a file.<br>
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
     * @var string|null $outputFile
     * @since 1.0.0
     */
    private ?string $outputFile = null;

    /**
     * Gets the full or relative path of the output file.
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getOutputFile(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " get '%s'", $this->outputFile));
        return $this->outputFile;
    }

    /**
     * Sets the full or relative path of the output file.
     *
     * @param string $outPutFile
     * @return static
     * @throws \Rebelo\Reports\Report\ReportException
     * @since 1.0.0
     */
    public function setOutputFile(string $outPutFile): static
    {
        if ("" === $outPutFile = \trim($outPutFile)) {
            $msg = "the output file path must be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->outputFile = $outPutFile;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->outputFile));
        return $this;
    }

    /**
     * Create the xml common nodes of all File exporters
     * @since 1.0.0
     *
     * @param \SimpleXMLElement $node The node where will be add the node of this class
     * @return \SimpleXMLElement The add node to be possible the manipulation
     * @throws SerializeReportException
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($this->getOutputFile() === null) {
            $msg = "The output file path must be set to be possible the "
                . "xml serialization";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        $class     = \strtolower(get_class($this));
        $lastPos   = \strrpos($class, "\\");
        $nodeName  = \substr($class, $lastPos + 1, \strlen($class));
        $AFileNode = $node->addChild($nodeName);
        AReport::cdata(
            $AFileNode->addChild(static::NODE_OUT_FILE),
            $this->getOutputFile()
        );
        return $AFileNode;
    }
}
