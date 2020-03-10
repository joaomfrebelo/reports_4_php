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

use Rebelo\Reports\Report\ExecException;
use Rebelo\Reports\Config\Config;

/**
 * Generate the report
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class Report
{

    /**
     *
     * @var \Rebelo\Reports\Report\ReportPathType
     */
    private $pathType = null;

    /**
     * The path to pre append to the output file
     * path defined in the xml file
     *
     * @var string
     * @since 1.0.0
     */
    protected $outputBaseDir = null;

    /**
     * The path to pre append to the jasper file
     * path defined in the xml file
     *
     * @var string
     * @since 1.0.0
     */
    protected $jasperFileBaseDir = null;

    /**
     * If is true the xml file will be deleted after report generator<br>
     * Argument -e, --erase
     * @var boolean
     * @since 1.0.0
     */
    protected $deleteFile = true;

    /**
     * If is true the xml directory will be deleted after report generator<br>
     * Argument -E, --Erase
     * @var boolean
     * @since 1.0.0
     */
    protected $deleteDirectory = true;

    /**
     *
     * Temp direactory where the xml file will be written<br>
     * Will be created one per nstance
     *
     * @var string
     */
    protected $tmpDir;

    /**
     * Generate the report
     * @since 1.0.0
     */
    public function __construct()
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $config       = Config::getInstance();
        $this->tmpDir = $config->getTempDirectory()
            . DIRECTORY_SEPARATOR
            . \uniqid("RReports", true) . \strval(rand(9, 9999));
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " temp dir '%s'", $this->tmpDir));
    }

    /**
     *
     * The cli base command
     *
     * @return string
     */
    public function getBaseCmd()
    {
        $conf     = Config::getInstance();
        $cmdStk   = array();
        $cmdStk[] = "\"" . $conf->getJavaPath() . "\"";
        if ($conf->getJavaXsharedClassesName() !== null)
        {
            $cmdStk[] = "-Xshareclasses:name=\"" . $conf->getJavaXsharedClassesName() . "\"";
            if ($conf->getJavaXsharedClassesDir() !== null)
            {
                $cmdStk[] = "-Xshareclasses:cacheDir=\"" . $conf->getJavaXsharedClassesDir() . "\"";
            }
        }
        $cmdStk[] = "-jar \"" . $conf->getJarPath() . "\"";
        $cmdStk[] = "-" . $this->getPathType()->get() . "=\"%1\$s\"";

        if ($this->deleteFile)
        {
            $cmdStk[] = "-e";
        }

        if ($this->deleteDirectory)
        {
            $cmdStk[] = "-E";
        }

        if ($this->getOutputBaseDir() !== null)
        {
            $cmdStk[] = "-o=\"" . $this->getOutputBaseDir() . "\"";
        }

        if ($this->getJasperFileBaseDir() !== null)
        {
            $cmdStk[] = "-j=\"" . $this->getJasperFileBaseDir() . "\"";
        }

        $cmdStk[] = "-v=" . $conf->getVerboseLevel()->get();
        // java -Xcla.. -Xcla -jar cli.jar -f="path.xml"

        $cmd = \join(" ", $cmdStk);

        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " '%s'", $cmd));

        return $cmd;
    }

    /**
     * Get the temporary directory of this instance, where the xml files will be written
     * @return string
     * @since 1.0.0
     */
    public function getTmpDir()
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " getted '%s'", $this->tmpDir));
        return $this->tmpDir;
    }

    /**
     *
     * @param string $tmpDir
     * @return $this
     * @throws ReportException
     * @since 1.0.0
     */
    public function setTmpDir($tmpDir)
    {
        if (!\is_string($tmpDir) || \trim($tmpDir) === "")
        {
            $msg = "Temporary diretory nust be a non empty string";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->tmpDir = $tmpDir;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " setted to '%s'", $this->tmpDir));
        return $this;
    }

    /**
     * If is true the xml file will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getDeleteFile()
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " getted '%s'",
                           $this->deleteFile
                        ? "true"
                        : "false"));
        return $this->deleteFile;
    }

    /**
     * If is true the xml file will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @param type $deleteFile
     * @return $this
     * @since 1.0.0
     */
    public function setDeleteFile($deleteFile)
    {
        if (\is_bool($deleteFile) === false)
        {
            $msg = "deleteFile must be boolean";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->deleteFile = $deleteFile;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " setted to '%s'",
                            $this->deleteFile
                        ?
                        "true"
                        : "false"));
        return $this;
    }

    /**
     * If is true the xml directory will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @return boolean
     * @since 1.0.0
     */
    public function getDeleteDirectory()
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " getted '%s'",
                           $this->deleteDirectory
                        ? "true"
                        : "false"));
        return $this->deleteDirectory;
    }

    /**
     * If is true the xml directory will be deleted after report generator<br>
     * Argument -e, --erase
     *
     * @param type $deleteDirectory
     * @return $this
     * @since 1.0.0
     */
    public function setDeleteDirectory($deleteDirectory)
    {
        if (\is_bool($deleteDirectory) === false)
        {
            $msg = "deleteDirectory must be boolean";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->deleteDirectory = $deleteDirectory;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " setted to '%s'",
                            $this->deleteDirectory
                        ?
                        "true"
                        : "false"));
        return $this;
    }

    /**
     *
     * The report file path file or directory
     *
     * @return \Rebelo\Reports\Report\ReportPathType
     * @since 1.0.0
     */
    public function getPathType()
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " getted '%s'",
                           $this->pathType == null
                        ? "null"
                        : $this->pathType->get()));
        return $this->pathType;
    }

    /**
     * Get the report path type<br>
     * (rebelo reports_cli) argunent -f,--file, -d, --dir)
     * @param \Rebelo\Reports\Report\ReportPathType $pathType
     * @return $this
     * @since 1.0.0
     */
    public function setPathType(\Rebelo\Reports\Report\ReportPathType $pathType)
    {
        $this->pathType = $pathType;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " setted to '%s'",
                            $this->pathType->get()));
        return $this;
    }

    /**
     * The path to pre append to the output file "
     * path defined in the xml file
     *
     * @return string
     * @since 1.0.0
     */
    public function getOutputBaseDir()
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " getted '%s'",
                           $this->outputBaseDir === null
                        ?
                        "null"
                        : $this->outputBaseDir));
        return $this->outputBaseDir;
    }

    /**
     * The path to pre append to the output file "
     * path defined in the xml file
     *
     * @param type $outputBaseDir
     * @return $this
     * @throws ReportException
     * @since 1.0.0
     */
    public function setOutputBaseDir($outputBaseDir)
    {
        if ($outputBaseDir !== null && !\is_string($outputBaseDir))
        {
            $msg = "outputBaseDir must be null or string";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->outputBaseDir = $outputBaseDir;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " setted to '%s'",
                            $this->outputBaseDir === null
                        ? "null"
                        : $this->outputBaseDir));
        return $this;
    }

    /**
     *
     * The path to pre append to the jasper file
     * path defined in the xml file
     *
     * @return self
     * @since 1.0.0
     */
    public function getJasperFileBaseDir()
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " getted '%s'",
                           $this->jasperFileBaseDir === null
                        ?
                        "null"
                        : $this->jasperFileBaseDir));
        return $this->jasperFileBaseDir;
    }

    /**
     * The path to pre append to the jasper file
     * path defined in the xml file
     *
     * @param type $jasperFileBaseDir
     * @return $this
     * @throws ReportException
     * @since 1.0.0
     */
    public function setJasperFileBaseDir($jasperFileBaseDir)
    {
        if ($jasperFileBaseDir !== null && !\is_string($jasperFileBaseDir))
        {
            $msg = "jasperFileBaseDir must be null or string";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ReportException($msg);
        }
        $this->jasperFileBaseDir = $jasperFileBaseDir;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " setted to '%s'",
                            $this->jasperFileBaseDir === null
                        ? "null"
                        : $this->jasperFileBaseDir));
        return $this;
    }

    /**
     * Generate the report
     *
     * @param \Rebelo\Reports\Report\AReport $report
     * @return \Rebelo\Reports\Report\ExecReturn
     * @throws ExecException
     */
    public function generate(AReport $report)
    {
        try
        {
            \Logger::getLogger(\get_class($this))->debug(__METHOD__);
            $xmlFile = $this->getTmpDir()
                . DIRECTORY_SEPARATOR
                . uniqid("report")
                . ".xml";

            $this->setPathType(new ReportPathType(ReportPathType::PATH_FILE));
            $cmd = sprintf($this->getBaseCmd(), $xmlFile);
            if (!\mkdir($this->getTmpDir()))
            {
                $msg = sprintf(" Error creating tmp dir to '%s'",
                               $this->getTmpDir());
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__ . $msg);
                throw new ReportException($msg);
            }
            $report->serializeToFile($xmlFile);
            $exit = $this->invoque($cmd);
            $this->checkTmp($report);
            return $exit;
        }
        catch (\Exception $e)
        {
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " error to '%s'",
                                $this->url === null
                            ? "null"
                            : $this->url));
            $this->deleteInstanceDir();
            throw new ExecException($e->getMessage());
        }
    }

    /**
     *
     * Generate multiple reports in one exporter
     *
     * @param areport[] $stack
     * @return \Rebelo\Reports\Report\ExecReturn
     * @throws ExecException
     * @throws ReportException
     * @since 1.0.0
     */
    public function generateMultipeInOne($stack)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        try
        {
            if (!\is_array($stack) || count($stack) === 0)
            {
                $msg = "Stack array must be an array of AReport";
                \Logger::getLogger(\get_class($this))
                    ->error(sprintf(__METHOD__ . " '%s'", $msg));
                throw new ReportException($msg);
            }

            if (!\mkdir($this->getTmpDir()))
            {
                $msg = sprintf(" Error creating tmp dir to '%s'",
                               $this->getTmpDir());
                \Logger::getLogger(\get_class($this))
                    ->debug(__METHOD__ . $msg);
                throw new ReportException($msg);
            }

            foreach ($stack as $k => $report)
            {
                if (!($report instanceof AReport))
                {
                    $msg = "Stack array must be an array of AReport";
                    \Logger::getLogger(\get_class($this))
                        ->error(sprintf(__METHOD__ . " '%s'", $msg));
                    throw new ReportException($msg);
                }

                $xmlFile = $this->getTmpDir()
                    . DIRECTORY_SEPARATOR
                    . uniqid("report") . "_" . strval($k)
                    . ".xml";
                $report->serializeToFile($xmlFile);
            }
            $this->setPathType(new ReportPathType(ReportPathType::PATH_DIR));
            $cmd  = sprintf($this->getBaseCmd(), $this->getTmpDir());
            $exit = $this->invoque($cmd);
            $this->checkTmp($report);
            return $exit;
        }
        catch (\Exception $e)
        {
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " error to '%s'",
                                $this->url === null
                            ? "null"
                            : $this->url));
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
    public function invoque($cmd)
    {
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " command '%s'", $cmd));
        $out      = null;
        $exitCode = null;
        exec($cmd, $out, $exitCode);

        if ($exitCode === null)
        {
            $msg = "Command didn't return exit code";
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " '%s'", $msg));
            throw new ExecException($msg);
        }

        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " exit code '%s'", $exitCode));

        $execReturn = new ExecReturn($exitCode, $out);

        if (\count($execReturn->getMessages()) > 0)
        {
            foreach ($execReturn->getMessages() as $msg)
            {
                \Logger::getLogger(\get_class($this))
                    ->debug(sprintf(__METHOD__ . " java out '%s' ", $msg));
            }
        }

        return $execReturn;
    }

    /**
     * Delete file and temp dir of this instance
     * @since 1.0.0
     */
    public function deleteInstanceDir()
    {
        try
        {
            $files = \scandir($this->getTmpDir());
            foreach ($files as $f)
            {
                if ($f === "." || $f === "..")
                {
                    continue;
                }
                \unlink($this->getTmpDir() . DIRECTORY_SEPARATOR . $f);
            }
            \rmdir($this->getTmpDir());
        }
        catch (\Exception $e)
        {
            \Logger::getLogger(\get_class($this))
                ->error(sprintf(__METHOD__ . " while deleting file: '%s'",
                                $e->getMessage()));
        }
    }

    /**
     *
     * Check if the tmp file was renamed to the correct file name
     *
     * @param \Rebelo\Reports\Report\AReport $report
     */
    protected function checkTmp(AReport $report)
    {
        // Some times in windows (?? other so) block the deletion in RReports cli
        // even with all stream closed this is only to guaranty that we have the right file
        if ($report instanceof AFileReport)
        {
            if (\is_file($report->getOutputfile() . ".tmp"))
            {
                \unlink($report->getOutputfile());
                \rename($report->getOutputfile() . ".tmp",
                        $report->getOutputfile());
            }
        }
    }

}
