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
 * Class representing PrintXsd
 *
 * Print the report (printer)
 */
class Printer extends AReport
{

    /**
     * The printer name.
     *
     * @var string|null $printer
     */
    private ?string $printer = null;

    public function __construct()
    {
        parent::__construct();
        // Sets the default printer
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
    public function getPrinter(): ?string
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(
                __METHOD__ . " get '%s'",
                $this->printer === "null"
                        ? "null"
                : $this->printer
            ));
        return $this->printer;
    }

    /**
     * Set the printer name.<br>
     * If printer name is empty or null will print in the default printer
     *
     * @param string $printer
     * @return static
     * @since 1.0.0
     */
    public function setPrinter(string $printer): static
    {
        $this->printer = $printer;
        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(
                __METHOD__ . " set to '%s'",
                $this->printer === null
                        ? "null"
                : $this->printer
            ));
        return $this;
    }

    /**
     *
     * @return string
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
     * @return \SimpleXMLElement
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $printNode = $node->addChild("print");

        AReport::cdata(
            $printNode->addChild("printer"),
            $this->getPrinter() === null
                ? ""
                : $this->getPrinter()
        );

        return $printNode;
    }
}
