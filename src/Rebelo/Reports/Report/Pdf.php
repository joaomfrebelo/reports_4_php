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

use Rebelo\Reports\Report\Sign\Sign;

/**
 * Class representing Pdf
 *
 * Export the report to a PDF file
 * @since 1.0.0
 */
class Pdf extends AFileReport
{

    /**
     * The PDF digital signature properties
     *
     * @var \Rebelo\Reports\Report\Sign\Sign|null $sign
     * @since 1.0.0
     */
    private Sign|null $sign = null;

    /**
     * @var \Rebelo\Reports\Report\PdfProperties|null
     * @since 3.0.0
     */
    private ?PdfProperties $pdfProperties = null;

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
     * @return \Rebelo\Reports\Report\Sign\Sign|null
     * @since 1.0.0
     */
    public function getSign(): ?Sign
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->sign === null
                        ? "null"
                : $this->sign->__toString()
            ));
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * The PDF digital signature properties
     *
     * @param \Rebelo\Reports\Report\Sign\Sign $sign
     * @return static
     * @since 1.0.0
     */
    public function setSign(Sign $sign): static
    {
        $this->sign = $sign;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s'",
                $this->sign === null
                        ? "null"
                : $this->sign->__toString()
            ));
        return $this;
    }

    /**
     * @return \Rebelo\Reports\Report\PdfProperties|null
     * @since 3.0.0
     */
    public function getPdfProperties(): ?PdfProperties
    {
        return $this->pdfProperties;
    }

    /**
     * @param \Rebelo\Reports\Report\PdfProperties|null $pdfProperties
     * @return \Rebelo\Reports\Report\Pdf
     * @since 3.0.0
     */
    public function setPdfProperties(?PdfProperties $pdfProperties): Pdf
    {
        $this->pdfProperties = $pdfProperties;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->pdfProperties === null
                               ? "null"
                               : $this->pdfProperties->__toString()
               ));
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
        return serialize($str);
    }

    /**
     * Create the xml common nodes of pdf exporter
     *
     * @param \SimpleXMLElement $node $node The node where will be add the pdf node
     * @return \SimpleXMLElement The pdf node added to the parent to be possible the manipulation
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $pdfNode = parent::createXmlNode($node);
        if ($this->getSign() instanceof Sign) {
            $this->sign->createXmlNode($pdfNode);
        }
        return $pdfNode;
    }
}
