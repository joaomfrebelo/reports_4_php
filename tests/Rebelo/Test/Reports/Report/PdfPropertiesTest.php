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

namespace Rebelo\Test\Reports\Report;

use PHPUnit\Framework\TestCase;
use Rebelo\Reports\Report\PdfProperties;

class PdfPropertiesTest extends TestCase
{

    public function testInstance(): void
    {
        $pdfProperties = new PdfProperties();
        $this->assertInstanceOf(PdfProperties::class, $pdfProperties);

        $this->assertSame(
            PdfProperties::ALLOW_ASSEMBLY
            | PdfProperties::ALLOW_COPY
            | PdfProperties::ALLOW_DEGRADED_PRINTING
            | PdfProperties::ALLOW_FILL_IN
            | PdfProperties::ALLOW_MODIFY_ANNOTATIONS
            | PdfProperties::ALLOW_MODIFY_CONTENTS
            | PdfProperties::ALLOW_PRINTING
            | PdfProperties::ALLOW_SCREENREADERS,
            $pdfProperties->getPermissions()
        );

        $userPassword  = "user password";
        $ownerPassword = "owner password";
        $javascript    = "javascript code";
        $permissions   = 999;

        $pdfProperties->setUserPassword($userPassword);
        $pdfProperties->setOwnerPassword($ownerPassword);
        $pdfProperties->setJavascript($javascript);
        $pdfProperties->setPermissions($permissions);

        $this->assertSame($userPassword, $pdfProperties->getUserPassword());
        $this->assertSame($ownerPassword, $pdfProperties->getOwnerPassword());
        $this->assertSame($javascript, $pdfProperties->getJavascript());
        $this->assertSame($permissions, $pdfProperties->getPermissions());
    }

    public function testFillApiRequest(): void
    {
        $pdfProperties = new PdfProperties();

        $userPassword  = "user password";
        $ownerPassword = "owner password";
        $javascript    = "javascript code";
        $permissions   = 999;

        $pdfProperties->setUserPassword($userPassword);
        $pdfProperties->setOwnerPassword($ownerPassword);
        $pdfProperties->setJavascript($javascript);
        $pdfProperties->setPermissions($permissions);

        $data = [];
        $pdfProperties->fillApiRequest($data);
        $api = $data[PdfProperties::API_N_PDF_PROPERTIES];
        $this->assertSame($userPassword, $api[PdfProperties::API_P_USER_PASSWORD]);
        $this->assertSame($ownerPassword, $api[PdfProperties::API_P_OWNER_PASSWORD]);
        $this->assertSame($javascript, $api[PdfProperties::API_P_JAVASCRIPT]);
        $this->assertSame($permissions, $api[PdfProperties::API_P_PERMISSIONS]);
    }


    public function testFillApiRequestNoSet(): void
    {
        $pdfProperties = new PdfProperties();
        $data          = [];
        $pdfProperties->fillApiRequest($data);
        $api = $data[PdfProperties::API_N_PDF_PROPERTIES];
        $this->assertSame(1, \count($api));
        $this->assertSame($pdfProperties->getPermissions(), $api[PdfProperties::API_P_PERMISSIONS]);
    }
}
