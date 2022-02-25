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
use Rebelo\Reports\Report\Metadata;

class MetadataTest extends TestCase
{

    public function testInstance(): void
    {
        $metadata = new Metadata();

        $this->assertInstanceOf(Metadata::class, $metadata);

        $title       = "The title";
        $author      = "The Author";
        $subject     = "The subject";
        $keywords    = "The keywords";
        $application = "The application";

        $metadata->setTitle($title);
        $metadata->setAuthor($author);
        $metadata->setSubject($subject);
        $metadata->setKeywords($keywords);
        $metadata->setApplication($application);

        $this->assertSame($title, $metadata->getTitle());
        $this->assertSame($author, $metadata->getAuthor());
        $this->assertSame($subject, $metadata->getSubject());
        $this->assertSame($keywords, $metadata->getKeywords());
        $this->assertSame($application, $metadata->getApplication());
        $this->assertFalse($metadata->isDisplayMetadataTitle());

        foreach ([true, false, true] as $boll) {
            $metadata->setDisplayMetadataTitle($boll);
            $this->assertSame($boll, $metadata->isDisplayMetadataTitle());
        }
    }

    public function testFillApiRequest(): void
    {

        $title       = "The title";
        $author      = "The Author";
        $subject     = "The subject";
        $keywords    = "The keywords";
        $application = "The application";

        foreach ([true, false, true] as $bool) {
            $metadata = new Metadata();
            $metadata->setTitle($title);
            $metadata->setAuthor($author);
            $metadata->setSubject($subject);
            $metadata->setKeywords($keywords);
            $metadata->setApplication($application);
            $metadata->setDisplayMetadataTitle($bool);

            $data = [];
            $metadata->fillApiRequest($data);
            $api = $data[Metadata::API_N_METADATA];
            $this->assertSame($title, $api[Metadata::API_P_TITLE]);
            $this->assertSame($author, $api[Metadata::API_P_AUTHOR]);
            $this->assertSame($subject, $api[Metadata::API_P_SUBJECT]);
            $this->assertSame($keywords, $api[Metadata::API_P_KEYWORDS]);
            $this->assertSame($application, $api[Metadata::API_P_APPLICATION]);
            $this->assertSame($bool, $api[Metadata::API_P_DISPLAY_METADATA_TITLE]);
        }
    }

    public function testFillApiRequestNotSet(): void
    {
        $data = [];
        $metadata = new Metadata();
        $metadata->fillApiRequest($data);
        $api = $data[Metadata::API_N_METADATA];
        $this->assertSame(1, \count($api));
        $this->assertFalse($api[Metadata::API_P_DISPLAY_METADATA_TITLE]);
    }
}
