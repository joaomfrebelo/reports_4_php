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
 *
 * @since 3.0.0
 */
class PdfProperties implements IAReport
{

    /**
     * Api node
     * @since 3.0.0
     */
    const API_N_PDF_PROPERTIES = "pdfProperties";

    /**
     * Api property
     * @since 3.0.0
     */
    const API_P_USER_PASSWORD = "userPassword";

    /**
     * Api property
     * @since 3.0.0
     */
    const API_P_OWNER_PASSWORD = "ownerPassword";

    /**
     * Api property
     * @since 3.0.0
     */
    const API_P_JAVASCRIPT = "javascript";

    /**
     * Api property
     * @since 3.0.0
     */
    const API_P_PERMISSIONS = "permissions";


    /**
     * The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_PRINTING = 4 + 2048;

    /** The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_MODIFY_CONTENTS = 8;

    /** The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_COPY = 16;

    /** The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_MODIFY_ANNOTATIONS = 32;

    /** The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_FILL_IN = 256;

    /** The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_SCREENREADERS = 512;

    /** The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_ASSEMBLY = 1024;

    /**
     * The operation permitted when the document is opened with the user password
     *
     * @since 3.0.0
     */
    const ALLOW_DEGRADED_PRINTING = 4;

    /**
     * @var \Logger
     * @since 3.0.0
     */
    private \Logger $logger;

    /**
     * Pdf user password
     * @since 3.0.0
     */
    private string $userPassword;

    /**
     * Pdf owner password
     * @since 3.0.0
     */
    private string $ownerPassword;

    /**
     * PDF javascript
     * @since 3.0.0
     */
    private string $javascript;

    /**
     * Permissions. Bitwise defined in com.lowagie.text.pdf.PdfWriter
     * self::ALLOW_...
     * @since 3.0.0
     */
    private int $permissions;

    /**
     * Pdf properties.
     * Only in API
     * @since 3.0.0
     */
    public function __construct()
    {
        $this->logger = \Logger::getLogger(\get_class($this));
        $this->logger->debug(\sprintf("New instance of '%s'", \get_class($this)));
        $this->setPermissions(
            self::ALLOW_ASSEMBLY
            | self::ALLOW_COPY
            | self::ALLOW_DEGRADED_PRINTING
            | self::ALLOW_FILL_IN
            | self::ALLOW_MODIFY_ANNOTATIONS
            | self::ALLOW_MODIFY_CONTENTS
            | self::ALLOW_PRINTING
            | self::ALLOW_SCREENREADERS
        );
    }

    /**
     * @return String
     * @since 3.0.0
     */
    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    /**
     * @param String $userPassword
     * @return \Rebelo\Reports\Report\PdfProperties
     * @since 3.0.0
     */
    public function setUserPassword(string $userPassword): PdfProperties
    {
        $this->userPassword = $userPassword;
        $this->logger->debug(
            \sprintf(
                "User password set to '%s'",
                \str_pad("*", \strlen($this->userPassword), "*")
            )
        );
        return $this;
    }

    /**
     * @return String
     * @since 3.0.0
     */
    public function getOwnerPassword(): string
    {
        return $this->ownerPassword;
    }

    /**
     * @param String $ownerPassword
     * @return \Rebelo\Reports\Report\PdfProperties
     * @since 3.0.0
     */
    public function setOwnerPassword(string $ownerPassword): PdfProperties
    {
        $this->ownerPassword = $ownerPassword;
        $this->logger->debug(
            \sprintf(
                "Owner password set to '%s'",
                \str_pad("*", \strlen($this->ownerPassword), "*")
            )
        );
        return $this;
    }

    /**
     * @return String
     * @since 3.0.0
     */
    public function getJavascript(): string
    {
        return $this->javascript;
    }

    /**
     * @param String $javascript
     * @return \Rebelo\Reports\Report\PdfProperties
     * @since 3.0.0
     */
    public function setJavascript(string $javascript): PdfProperties
    {
        $this->javascript = $javascript;
        $this->logger->debug("Javascript was set");
        return $this;
    }

    /**
     * @return int
     * @since 3.0.0
     */
    public function getPermissions(): int
    {
        return $this->permissions;
    }

    /**
     * @param int $permissions
     * @return \Rebelo\Reports\Report\PdfProperties
     * @since 3.0.0
     */
    public function setPermissions(int $permissions): PdfProperties
    {
        $this->permissions = $permissions;
        $this->logger->debug(\sprintf("Permissions set to '%s'", $this->permissions));
        return $this;
    }

    /**
     * @param \SimpleXMLElement $node
     * @return \SimpleXMLElement
     * @throws \Rebelo\Reports\Report\ReportException
     * @since 3.0.0
     */
    public function createXmlNode(\SimpleXMLElement $node): \SimpleXMLElement
    {
        throw new ReportException("Not implemented");
    }

    /**
     * @param array $data
     * @return void
     * @since 3.0.0
     */
    public function fillApiRequest(array &$data): void
    {
        $data[static::API_N_PDF_PROPERTIES] = [];
        $pro = &$data[static::API_N_PDF_PROPERTIES];

        if (isset($this->userPassword)) {
            $pro[static::API_P_USER_PASSWORD] = $this->getUserPassword();
        }

        if (isset($this->ownerPassword)) {
            $pro[static::API_P_OWNER_PASSWORD] = $this->getOwnerPassword();
        }

        if (isset($this->javascript)) {
            $pro[static::API_P_JAVASCRIPT] = $this->getJavascript();
        }

        $pro[static::API_P_PERMISSIONS] = $this->getPermissions();
    }

    /**
     * @return string
     * @since 3.0.0
     */
    public function __toString()
    {
        return \serialize($this);
    }
}
