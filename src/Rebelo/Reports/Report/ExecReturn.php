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
 * Description of ExecReturn
 *
 * @author João Rebelo
 * @since 1.0.0
 */
class ExecReturn
{

    /**
     * no error
     */
    const NO_ERROR = 0;

    /**
     * undefined error
     */
    const UNDEFINED = 1;

    /**
     * When no dir or file option selected
     */
    const NO_DIR_OR_FILE_OPTION = 2;

    /**
     * When dir and file option are selected
     */
    const DIR_AND_FILE_OPTION = 3;

    /**
     * When wrong dir or file path
     */
    const WRONG_PATH = 4;

    /**
     * File or dir is not readable
     */
    const IS_NOT_READABLE = 5;

    /**
     * Unknown option
     */
    const UNKNOWN_OPTION = 6;

    /**
     * Error generating RReport
     */
    const ERROR_GENERATE_REPORT = 7;

    /**
     * Error generating RReport
     */
    const REPORT_PROPERTIES_ERROR = 8;

    /**
     * RRebelo report datasource definitions error
     */
    const REBELO_REPORT_DATASOURCE_ERROR = 9;

    /**
     * Jasper report engine error
     */
    const JASPER_ENGINE_REPORT_ERROR = 10;

    /**
     * SQL error
     */
    const SQL_ERROR = 11;

    /**
     * Parsing error
     */
    const PARSING_ERROR = 12;

    /**
     * Printer error
     */
    const PRINTER_ERROR = 13;

    /**
     * No files in directory
     */
    const NO_FILES_IN_DIR = 14;

    /**
     * Error in Cli Generate
     */
    const CLI_GENERATE_ERROR = 15;

    /**
     * Error deleting file or dir
     */
    const CLI_DEL_FILE_ERROR = 16;

    /**
     * Passing an argument in main method that not exist
     */
    const UNMATCHED_ARGUMENT = 17;

    /**
     * Error signing the pdf
     */
    const PDF_SIGNING_ERROR = 18;

    /**
     * Return code from the Rebelo Report CLI
     * @var int|null
     * @since 1.0.0
     */
    private ?int $code = null;

    /**
     * Return message from the Rebelo Report CLI
     * @var array
     * @since 1.0.0
     */
    private array $messages = array();

    /**
     * The return properties from the cli exec<br>
     * execution of Rebelo_reports CLI
     * @param int    $code
     * @param array $messages
     * @since 1.0.0
     */
    public function __construct(int $code, array $messages)
    {
        \Logger::getLogger(\get_class($this))->debug(__METHOD__);
        $this->setCode($code);
        $this->setMessages($messages);
    }

    /**
     * Get the exit code
     * @return int
     * @since 1.0.0
     */
    public function getCode(): int
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(
                __METHOD__ . " get '%s'",
                $this->code === null
                        ? "null"
                : $this->code
            ));

        return $this->code;
    }

    /**
     * Get the messages
     * @return array
     * @since 1.0.0
     */
    public function getMessages(): array
    {
        \Logger::getLogger(\get_class($this))
            ->info(sprintf(__METHOD__ . " get '%s'", "... String"));
        return $this->messages;
    }

    /**
     * Set the exit code
     *
     * @param int $code
     * @return static
     */
    public function setCode(int $code): static
    {
        $this->code = $code;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(__METHOD__ . " set to '%s'", \strval($this->code)));
        return $this;
    }

    /**
     *
     * Set the cmd messages
     *
     * @param array $messages
     * @return \Rebelo\Reports\Report\ExecReturn
     */
    public function setMessages(array $messages): static
    {
        $this->messages = $messages;
        \Logger::getLogger(\get_class($this))
            ->debug(sprintf(
                __METHOD__ . " set to '%s'",
                \count($this->messages) === 0
                        ? "empty array"
                : "...string"
            ));
        return $this;
    }

    /**
     *
     * Get the messages as a string<br>
     * the array messages will be joined with "; "
     *
     * @return string
     * @since 1.0.0
     */
    public function messagesToString(): string
    {
        return join("; ", $this->messages);
    }
}
