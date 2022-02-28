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

use Rebelo\Reports\Cache\Filesystem;
use Rebelo\Reports\Config\Config;

/**
 * Class representing JasperFileAType
 * @since 1.0.0
 */
class JasperFile implements IAReport
{

    /**
     * Jasper API request node
     * @since 3.0.0
     */
    const API_N_JASPER = "report";

    /**
     * Copies API request node
     * @since 3.0.0
     */
    const API_N_COPIES = "copies";

    /**
     * @var string|null $path
     * @since 1.0.0
     */
    private ?string $path = null;

    /**
     * @var int $copies
     * @since 1.0.0
     */
    private int $copies;

    /**
     * Only for API request
     * @var \Rebelo\Reports\Report\ReportResources[]
     * @since 3.0.0
     */
    private array $reportResources = [];

    /**
     * Construct
     * The copies will be initialized to 1
     * @param string|null $path the file path
     * @param int         $copies
     * @throws \Rebelo\Reports\Report\ReportException
     * @since 1.0.0
     */
    public function __construct(?string $path = null, int $copies = 1)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($path !== null) {
            $this->setPath($path);
        }
        $this->setCopies($copies);
    }

    /**
     *
     * The jasper file full path or a
     * relative path to the jasperFileBaseDir
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getPath(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " get '%s'", $this->path === null ? "null" : $this->path));
        return $this->path;
    }

    /**
     *
     * Jasper file full path or relative path<br>
     * If is a relative path jasperBaseDir must be configured
     *
     * @param string $path
     * @return static
     * @since 1.0.0
     */
    public function setPath(string $path): static
    {
        $this->path = $path;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->path));
        return $this;
    }

    /**
     * Gets the number of copies
     *
     * @return int
     * @since 1.0.0
     */
    public function getCopies(): int
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " get '%s'", (string)$this->copies));
        return $this->copies;
    }

    /**
     * Sets the number of copies
     *
     * @param int $copies
     * @return static
     * @throws \Rebelo\Reports\Report\ReportException
     * @since 1.0.0
     */
    public function setCopies(int $copies): static
    {
        if ($copies < 1) {
            $msg = "copies must be a integer greater or equal to 1";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }

        $this->copies = $copies;

        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->copies));

        return $this;
    }

    /**
     * Add a Report Resources.
     * Only for API request
     * @param \Rebelo\Reports\Report\ReportResources $reportResources
     * @return int The index
     */
    public function addReportResources(ReportResources $reportResources): int
    {
        $index                         = \count($this->reportResources);
        $this->reportResources[$index] = $reportResources;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf("The Resource '%s' was add with index '%s'", $reportResources->getName(), $index));
        return $index;
    }

    /**
     * Get the ReportResources stack.
     * Only for API request
     * @return \Rebelo\Reports\Report\ReportResources[]
     */
    public function getReportResources(): array
    {
        return $this->reportResources;
    }

    /**
     * Gets a string value
     *
     * @return string
     * @since 1.0.0
     */
    public function __toString()
    {
        return "{path: '" . ($this->path === null
                ? "null"
                : $this->path) . "', copies: " . $this->copies . "}";
    }

    /**
     * Serialize the node
     *
     * @param \SimpleXMLElement $node The node where will be add this child
     * @return \SimpleXMLElement
     * @throws SerializeReportException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($this->getPath() === null) {
            $msg = "Path to jasper file not defined";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        $jasper = AReport::cdata($node->addChild("jasperfile"), $this->getPath());
        $jasper->addAttribute("copies", strval($this->getCopies()));
        return $jasper;
    }


    /**
     * Fill the array that will be used to make the request to the Rest API
     * @param array $data
     * @return void
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        if (Config::getInstance()->getCacheResources()) {
            $jasperBase64 = (new Filesystem($this->getPath()))->getResource();
        } else {
            if (false === \is_file($this->getPath()) || false === $jasper = @\file_get_contents($this->getPath())) {
                $msg = \sprintf("Fail loading file '%s'", $this->getPath());
                \Logger::getLogger(\get_class($this))->error($msg);
                throw new ReportException($msg);
            }
            $jasperBase64 = \base64_encode($jasper);
        }

        $data[static::API_N_JASPER] = $jasperBase64;
        $data[static::API_N_COPIES] = $this->getCopies();

        if (\count($this->reportResources) > 0) {
            $data[ReportResources::API_N_RESOURCES] = [];
            foreach ($this->reportResources as $reportResource) {
                $reportResource->fillApiRequest($data);
            }
        }
    }
}
