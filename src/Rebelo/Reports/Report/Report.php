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

use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\Api\Request;
use Rebelo\Reports\Report\Api\Status;

/**
 * Generate the report
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Report
{

    /**
     *
     * @var \Rebelo\Reports\Report\ReportPathType|null
     */
    private ?ReportPathType $pathType = null;

    /**
     * The path to pre append to the output file
     * path defined in the xml file
     *
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $outputBaseDir = null;

    /**
     * The path to pre append to the jasper file
     * path defined in the xml file
     *
     * @var string|null
     * @since 1.0.0
     */
    protected ?string $jasperFileBaseDir = null;

    /**
     * If is true the xml file will be deleted after report generator<br>
     * Argument -e, --erase
     * @var boolean
     * @since 1.0.0
     */
    protected bool $deleteFile = true;

    /**
     * If is true the xml directory will be deleted after report generator<br>
     * Argument -E, --Erase
     * @var boolean
     * @since 1.0.0
     */
    protected bool $deleteDirectory = true;

    /**
     *
     * Temp directory where the xml file will be written<br>
     * Will be created one per instance
     *
     * @var string
     */
    protected string $tmpDir;

    /**
     * Generate the report
     * @throws \Rebelo\Reports\Config\ConfigException
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $config       = Config::getInstance();
        $this->tmpDir = $config->getTempDirectory()
                        . DIRECTORY_SEPARATOR
                        . \uniqid("RReports", true) . \rand(9, 9999);
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " temp dir '%s'", $this->tmpDir));
    }

    /**
     *
     * The cli base command
     *
     * @return string
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\ExecException
     * @throws \Rebelo\Enum\EnumException
     */
    public function getBaseCmd(): string
    {
        $conf     = Config::getInstance();
        $cmdStk   = [];
        $cmdStk[] = "\"" . $conf->getJavaPath() . "\"";
        if ($conf->getJavaXsharedClassesName() !== null) {
            $cmdStk[] = "-Xshareclasses:name=\"" . $conf->getJavaXsharedClassesName() . "\"";
            if ($conf->getJavaXsharedClassesDir() !== null) {
                $cmdStk[] = "-Xshareclasses:cacheDir=\"" . $conf->getJavaXsharedClassesDir() . "\"";
            }
        }
        $cmdStk[] = "-jar \"" . $conf->getJarPath() . "\"";
        $cmdStk[] = "-" . (
                $this->getPathType()?->get() ?? throw new ExecException("Path type not defined")
            ). "=\"%1\$s\"";

        if ($this->deleteFile) {
            $cmdStk[] = "-e";
        }

        if ($this->deleteDirectory) {
            $cmdStk[] = "-E";
        }

        if ($this->getOutputBaseDir() !== null) {
            $cmdStk[] = "-o=\"" . $this->getOutputBaseDir() . "\"";
        }

        if ($this->getJasperFileBaseDir() !== null) {
            $cmdStk[] = "-j=\"" . $this->getJasperFileBaseDir() . "\"";
        }

        $cmdStk[] = "-v=" . $conf->getVerboseLevel()?->get() ??
                    throw new ExecException("Verbose Level nor defined");
        // java -Xcla.. -Xcla -jar cli.jar -f="path.xml"

        $cmd = \join(" ", $cmdStk);

        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " '%s'", $cmd));

        return $cmd;
    }

    /**
     * Get the temporary directory of this instance, where the xml files will be written
     * @return string
     * @since 1.0.0
     */
    public function getTmpDir(): string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " get '%s'", $this->tmpDir));
        return $this->tmpDir;
    }

    /**
     *
     * @param string $tmpDir
     * @return static
     * @throws ReportException
     * @since 1.0.0
     */
    public function setTmpDir(string $tmpDir): static
    {
        if ("" === $tmpDir = \trim($tmpDir)) {
            $msg = "Temporary directory must be a non empty string";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->tmpDir = $tmpDir;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " set to '%s'", $this->tmpDir));
        return $this;
    }

    /**
     * If is true the xml file will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getDeleteFile(): bool
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->deleteFile
                              ? "true"
                              : "false"
               ));
        return $this->deleteFile;
    }

    /**
     * If is true the xml file will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @param bool $deleteFile
     * @return static
     * @since 1.0.0
     */
    public function setDeleteFile(bool $deleteFile): static
    {
        $this->deleteFile = $deleteFile;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->deleteFile
                               ?
                               "true"
                               : "false"
               ));
        return $this;
    }

    /**
     * If is true the xml directory will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getDeleteDirectory(): bool
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->deleteDirectory
                              ? "true"
                              : "false"
               ));
        return $this->deleteDirectory;
    }

    /**
     * If is true the xml directory will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @param bool $deleteDirectory
     * @return static
     * @since 1.0.0
     */
    public function setDeleteDirectory(bool $deleteDirectory): static
    {
        $this->deleteDirectory = $deleteDirectory;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->deleteDirectory
                               ?
                               "true"
                               : "false"
               ));
        return $this;
    }

    /**
     *
     * The report file path file or directory
     *
     * @return \Rebelo\Reports\Report\ReportPathType|null
     * @since 1.0.0
     */
    public function getPathType(): ?ReportPathType
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->pathType == null
                              ? "null"
                              : $this->pathType->get()
               ));
        return $this->pathType;
    }

    /**
     * Get the report path type<br>
     * (rebelo reports_cli) argument -f,--file, -d, --dir)
     * @param \Rebelo\Reports\Report\ReportPathType $pathType
     * @return static
     * @since 1.0.0
     */
    public function setPathType(ReportPathType $pathType): static
    {
        $this->pathType = $pathType;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->pathType->get()
               ));
        return $this;
    }

    /**
     * The path to pre append to the output file "
     * path defined in the xml file
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getOutputBaseDir(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->outputBaseDir === null
                              ?
                              "null"
                              : $this->outputBaseDir
               ));
        return $this->outputBaseDir;
    }

    /**
     * The path to pre append to the output file "
     * path defined in the xml file
     *
     * @param string|null $outputBaseDir
     * @return static
     * @since 1.0.0
     */
    public function setOutputBaseDir(?string $outputBaseDir): static
    {
        $this->outputBaseDir = $outputBaseDir;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->outputBaseDir === null
                               ? "null"
                               : $this->outputBaseDir
               ));
        return $this;
    }

    /**
     *
     * The path to pre append to the jasper file
     * path defined in the xml file
     *
     * @return string|null
     * @since 1.0.0
     */
    public function getJasperFileBaseDir(): ?string
    {
        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(
                   __METHOD__ . " get '%s'",
                   $this->jasperFileBaseDir === null
                              ?
                              "null"
                              : $this->jasperFileBaseDir
               ));
        return $this->jasperFileBaseDir;
    }

    /**
     * The path to pre append to the jasper file
     * path defined in the xml file
     *
     * @param ?string $jasperFileBaseDir
     * @return static
     * @since 1.0.0
     */
    public function setJasperFileBaseDir(?string $jasperFileBaseDir): static
    {
        $this->jasperFileBaseDir = $jasperFileBaseDir;
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(
                   __METHOD__ . " set to '%s'",
                   $this->jasperFileBaseDir === null
                               ? "null"
                               : $this->jasperFileBaseDir
               ));
        return $this;
    }

    /**
     * Generate the report
     *
     * @param \Rebelo\Reports\Report\AReport $report
     * @return \Rebelo\Reports\Report\ExecReturn
     * @throws ExecException
     */
    public function generate(AReport $report): ExecReturn
    {
        try {
            \Logger::getLogger(\get_class($this))->debug(__METHOD__);
            $xmlFile = $this->getTmpDir()
                       . DIRECTORY_SEPARATOR
                       . uniqid("report")
                       . ".xml";

            $this->setPathType(new ReportPathType(ReportPathType::PATH_FILE));
            $cmd = sprintf($this->getBaseCmd(), $xmlFile);
            if (!\mkdir($this->getTmpDir())) {
                $msg = sprintf(
                    " Error creating tmp dir to '%s'",
                    $this->getTmpDir()
                );
                \Logger::getLogger(\get_class($this))
                       ->debug(__METHOD__ . $msg);
                throw new ReportException($msg);
            }
            $report->serializeToFile($xmlFile);
            $exit = $this->invoke($cmd);
            $this->checkTmp($report);
            return $exit;
        } catch (\Exception $e) {
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(
                       __METHOD__ . " error to '%s'",
                       $e->getMessage()
                   ));
            $this->deleteInstanceDir();
            throw new ExecException($e->getMessage());
        }
    }

    /**
     *
     * Generate multiple reports in one exporter
     *
     * @param \Rebelo\Reports\Report\AReport[] $stack
     * @return \Rebelo\Reports\Report\ExecReturn
     * @throws ExecException
     * @since 1.0.0
     */
    public function generateMultipleInOne(array $stack): ExecReturn
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        try {
            if (\count($stack) === 0) {
                $msg = "Stack array must be an array of AReport";
                \Logger::getLogger(\get_class($this))
                       ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                throw new ReportException($msg);
            }

            if (!\mkdir($this->getTmpDir())) {
                $msg = sprintf(
                    " Error creating tmp dir to '%s'",
                    $this->getTmpDir()
                );
                \Logger::getLogger(\get_class($this))
                       ->debug(__METHOD__ . $msg);
                throw new ReportException($msg);
            }

            foreach ($stack as $k => $report) {
                if (!($report instanceof AReport)) {
                    $msg = "Stack array must be an array of AReport";
                    \Logger::getLogger(\get_class($this))
                           ->error(\sprintf(__METHOD__ . " '%s'", $msg));
                    throw new ReportException($msg);
                }

                $xmlFile = $this->getTmpDir()
                           . DIRECTORY_SEPARATOR
                           . uniqid("report") . "_" . $k
                           . ".xml";
                $report->serializeToFile($xmlFile);
            }

            $this->setPathType(new ReportPathType(ReportPathType::PATH_DIR));
            $cmd  = sprintf($this->getBaseCmd(), $this->getTmpDir());
            $exit = $this->invoke($cmd);
            if (isset($report)) {
                $this->checkTmp($report);
            }
            return $exit;
        } catch (\Exception $e) {
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(
                       __METHOD__ . " error to '%s'",
                       $e->getMessage()
                   ));
            $this->deleteInstanceDir();
            throw new ExecException($e->getMessage());
        }
    }

    /**
     *
     * @param string $cmd
     * @return \Rebelo\Reports\Report\ExecReturn
     * @throws ExecException
     * @since 1.0.0
     */
    public function invoke(string $cmd): ExecReturn
    {
        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf(__METHOD__ . " command '%s'", $cmd));
        $out      = null;
        $exitCode = null;
        exec($cmd, $out, $exitCode);

        if ($exitCode === null) {
            $msg = "Command didn't return exit code";
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(__METHOD__ . " '%s'", $msg));
            throw new ExecException($msg);
        }

        \Logger::getLogger(\get_class($this))
               ->info(\sprintf(__METHOD__ . " exit code '%s'", $exitCode));

        $execReturn = new ExecReturn($exitCode, $out ?? []);

        if (\count($execReturn->getMessages()) > 0) {
            foreach ($execReturn->getMessages() as $msg) {
                \Logger::getLogger(\get_class($this))
                       ->debug(\sprintf(__METHOD__ . " java out '%s' ", $msg));
            }
        }

        return $execReturn;
    }

    /**
     * Delete file and temp dir of this instance
     * @since 1.0.0
     */
    public function deleteInstanceDir(): void
    {
        try {
            $files = \scandir($this->getTmpDir());
            foreach ($files as $f) {
                if ($f === "." || $f === "..") {
                    continue;
                }
                \unlink($this->getTmpDir() . DIRECTORY_SEPARATOR . $f);
            }
            \rmdir($this->getTmpDir());
        } catch (\Exception $e) {
            \Logger::getLogger(\get_class($this))
                   ->error(\sprintf(
                       __METHOD__ . " while deleting file: '%s'",
                       $e->getMessage()
                   ));
        }
    }

    /**
     *
     * Check if the tmp file was renamed to the correct file name
     *
     * @param \Rebelo\Reports\Report\AReport $report
     */
    protected function checkTmp(AReport $report): void
    {
        // Sometimes in windows (?? other so) block the deletion in RReports cli
        // even with all stream closed this is only to guaranty that we have the right file
        if ($report instanceof AFileReport) {
            if (\is_file($report->getOutputFile() . ".tmp")) {
                \unlink($report->getOutputFile());
                \rename(
                    $report->getOutputFile() . ".tmp",
                    $report->getOutputFile()
                );
            }
        }
    }

    /**
     * Get the report from the Rest server API
     * @param \Rebelo\Reports\Report\AReport $report
     * @return string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \ReflectionException
     */
    public function invokeApi(AReport $report): ?string
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        $data = [];
        $report->fillApiRequest($data);

        $request = new Request();

        if($data[AReport::API_N_REPORT_TYPE] === "PRINTER"){
            $data[AReport::API_N_REPORT_TYPE] = "PRINT";
        }

        $response = $request->requestReport($data);

        if ($response->getStatus()->isNotEqual(Status::OK)) {
            \Logger::getLogger(\get_class($this))->error($response->getMessage());
            throw new ReportException($response->getMessage());
        }

        \Logger::getLogger(\get_class($this))
               ->debug(\sprintf("Report generated in '%s'", $response->getDuration()));

        return $response->getReport();
    }

    /**
     * Get reports in bulk
     * @param \Rebelo\Reports\Report\AReport[] $reports
     * @param array $reportErrors Get report errors
     * @param array $clientErrors Get request http client errors
     * @return string[] The reposts as base64 encoded string
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Cache\CacheException
     * @throws \ReflectionException
     */
    public function invokeApiBulk(array $reports, array &$reportErrors = [], array &$clientErrors = []): array
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);

        $dataStack = [];

        foreach ($reports as $k => $report) {
            $data = [];
            $report->fillApiRequest($data);
            $dataStack[$k] = $data;
        }

        $responses = (new Request())->bulkReportRequest($dataStack, $clientErrors);

        /** @var string[] $reportStack */
        $reportStack = [];

        foreach ($responses as $k => $response) {
            if ($response->getStatus()->isEqual(Status::ERROR())) {
                $reportErrors[$k] = $response->getMessage();
                continue;
            }
            \Logger::getLogger(\get_class($this))->debug(
                \sprintf("Report of index '%s' generated in '%s'", $k, $response->getStatus())
            );
            $reportStack[] = $response->getReport();
        }

        return $reportStack;
    }
}
