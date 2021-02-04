<?php

namespace Rebelo\Reports\Report;

/**
 * Class representing JasperfileAType
 * @since 1.0.0
 */
class JasperFile
    implements IAReport
{

    /**
     * @var string $path
     * @since 1.0.0
     */
    private $path = null;

    /**
     * @var int $copies
     * @since 1.0.0
     */
    private $copies;

    /**
     * Construct
     * The copies will be initialized to 1
     * @param string $path the file path
     * @param int $copies
     * @since 1.0.0
     */
    public function __construct(?string $path = null, $copies = 1)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($path !== null)
        {
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
    function getPath()
    {
        \Logger::getLogger(\get_class($this))
            ->info(
                \sprintf(
                    __METHOD__ . " getted '%s'",
                    $this->path === null
                        ? "null"
                    : $this->path
                )
            );
        return $this->path;
    }

    /**
     *
     * Jasper file full path or relative path<br>
     * If is a relative path jasperBaseDir must be configured
     *
     * @param string $path
     * @return $this
     * @since 1.0.0
     */
    function setPath(string $path)
    {
        $this->path = $path;

        \Logger::getLogger(\get_class($this))
            ->debug(\sprintf(__METHOD__ . " setted to '%s'", $this->path));

        return $this;
    }

    /**
     * Gets the number of copies
     *
     * @return int
     * @since 1.0.0
     */
    public function getCopies()
    {
        \Logger::getLogger(\get_class($this))
            ->info(\sprintf(__METHOD__ . " getted '%s'", (string) $this->copies));
        return $this->copies;
    }

    /**
     * Sets the number of copies
     *
     * @param int $copies
     * @return self
     * @since 1.0.0
     */
    public function setCopies($copies)
    {
        if (!\is_int($copies) || $copies < 1)
        {
            $msg = "copies must be a integer greater or equal to 1";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }

        $this->copies = $copies;

        \Logger::getLogger(\get_class($this))
            ->debug(
                \sprintf(
                    __METHOD__ . " setted to '%s'",
                    (string) $this->copies
                )
            );

        return $this;
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
            : $this->path) . "', copies: " . strval($this->copies) . "}";
    }

    /**
     * Serialize the node
     *
     * @param \SimpleXMLElement $node The node where will be add this child
     * @return \SimpleXMLElement
     * @throws SerializeReportException
     * @since 1.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        if ($this->getPath() === null)
        {
            $msg = "Path to jasper file not defined";
            \Logger::getLogger(\get_class($this))
                ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new SerializeReportException($msg);
        }
        $jasper = AReport::cdata($node->addChild("jasperfile"), $this->getPath());
        $jasper->addAttribute("copies", strval($this->getCopies()));
        return $jasper;
    }

}
