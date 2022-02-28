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
use Rebelo\Reports\Report\AReport;
use Rebelo\Reports\Report\Datasource\Database;
use Rebelo\Reports\Report\JasperFile;
use Rebelo\Reports\Report\Metadata;
use Rebelo\Reports\Report\Pdf;
use Rebelo\Reports\Report\PdfProperties;
use Rebelo\Reports\Report\Sign\Level;
use Rebelo\Reports\Report\Sign\Sign;
use Rebelo\Reports\Report\Sign\Type;
use Rebelo\Reports\Report\Sign\Rectangle;
use Rebelo\Reports\Report\Sign\Keystore;
use Rebelo\Reports\Report\Sign\Certificate;
use Rebelo\Reports\Report\Parameter\Parameter;
use Rebelo\Reports\Report\Parameter\Type as ParameterType;

/**
 * Class PdfTest
 *
 * @author João Rebelo
 */
class PdfTest extends TestCase
{

    /**
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testSetGet()
    {
        $inst = "Rebelo\Reports\Report\Pdf";
        $pdf  = new Pdf();
        $this->assertInstanceOf($inst, $pdf);
        $this->assertNull($pdf->getSign());
        $this->assertNull($pdf->getJasperFile());
        $this->assertNull($pdf->getOutputFile());
        $this->assertEquals([], $pdf->getParameters());
        $this->assertEquals(0, $pdf->getAfterPrintOperations());
        $this->assertNull($pdf->getMetadata());
        $this->assertNull($pdf->getPdfProperties());

        $ds = new Database();
        $this->assertInstanceOf($inst, $pdf->setDatasource($ds));
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $pdf->getDatasource()
        );

        $jf = new JasperFile("path");
        $this->assertInstanceOf($inst, $pdf->setJasperFile($jf));
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\JasperFile",
            $pdf->getJasperFile()
        );

        $this->assertInstanceOf($inst, $pdf->setOutputFile("path"));
        $this->assertEquals("path", $pdf->getOutputFile());

        $sign = new Sign();
        $this->assertInstanceOf($inst, $pdf->setSign($sign));
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Sign\Sign",
            $pdf->getSign()
        );

        $pdf->setAfterPrintOperations(AReport::AFTER_PRINT_CUT_PAPER);
        $this->assertSame(AReport::AFTER_PRINT_CUT_PAPER, $pdf->getAfterPrintOperations());

        $metada = new Metadata();
        $pdf->setMetadata($metada);
        $this->assertSame($metada, $pdf->getMetadata());

        $pdfProperties = new PdfProperties();
        $pdf->setPdfProperties($pdfProperties);
        $this->assertSame($pdfProperties, $pdf->getPdfProperties());
    }

    /**
     * @throws \Rebelo\Reports\Report\SerializeReportException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     */
    public function testXmlNode()
    {
        $dsConStr  = "jdbc:mysql://localhost/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = "rebelo";
        $dsPwd     = "password";
        $jsPath    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";
        $jsCopies  = 1;
        $outDir    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $outFile   = $outDir . DIRECTORY_SEPARATOR . "report_" . uniqid() . ".pdf";
        $outXml    = $outDir . DIRECTORY_SEPARATOR . uniqid() . ".xml";
        $sigLocal  = "Sintra";
        $sigLevel  = Level::CERTIFIED_NO_CHANGES_ALLOWED;
        $sigReason = "Developer test";
        $sigType   = Type::SELF_SIGNED;
        $height    = 100;
        $width     = 100;
        $x         = 100;
        $y         = 100;
        $rot       = 0;
        $ksPwd     = "password";
        $ksPath    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "keystore.ks";
        $certName  = "rreports";
        $certPwd   = "password";

        $parameters = [
            [
                "type" => "string",
                "name" => "P_STRING",
                "value" => "Parameter String",
                "format" => null],
            [
                "type" => "bool",
                "name" => "P_BOOLEAN",
                "value" => "true",
                "format" => null],
            [
                "type" => "date",
                "name" => "P_DATE",
                "value" => "1969-10-05",
                "format" => "yyyy-MM-dd"],
        ];

        if (\is_dir($outDir) === false) {
            \mkdir($outDir);
        }

        $pdf = new Pdf();

        $ds = new Database();
        $ds->setConnectionString($dsConStr);
        $ds->setDriver($dsDriver);
        $ds->setUser($dsUser);
        $ds->setPassword($dsPwd);
        $pdf->setDatasource($ds);

        $jf = new JasperFile($jsPath, $jsCopies);
        $pdf->setJasperFile($jf);

        $pdf->setOutputFile($outFile);

        $sign = new Sign();
        $sign->setLocation($sigLocal);
        $sign->setLevel(new Level($sigLevel));
        $sign->setReason($sigReason);
        $sign->setType(new Type($sigType));

        $rectangle = new Rectangle();
        $rectangle->setVisible(true);
        $rectangle->setHeight($height);
        $rectangle->setWidth($width);
        $rectangle->setX($x);
        $rectangle->setY($y);
        $rectangle->setRotation($rot);
        $sign->setRectangle($rectangle);

        $cert = new Certificate();
        $cert->setName($certName);
        $cert->setPassword($certPwd);

        $keystore = new Keystore();
        $keystore->setPassword($ksPwd);
        $keystore->setPath($ksPath);
        $keystore->setCertificate($cert);
        $sign->setKeystore($keystore);

        $pdf->setSign($sign);
        foreach ($parameters as $paramProp) {
            $param = new Parameter(
                new ParameterType($paramProp["type"]),
                $paramProp["name"],
                $paramProp["value"],
                $paramProp["format"]
            );
            $pdf->addToParameter($param);
        }

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $this->assertInstanceOf("\SimpleXMLElement", $pdf->createXmlNode($node));
        $xml = simplexml_load_string($node->asXML());
        $this->assertEquals($outFile, $xml->pdf->{AReport::NODE_OUT_FILE});
        $this->assertEquals($ksPath, $xml->pdf->sign->keystore->path);
        $this->assertEquals($ksPwd, $xml->pdf->sign->keystore->password);
        $this->assertEquals(
            $certName,
            $xml->pdf->sign->keystore->certificate->name
        );
        $this->assertEquals(
            $certPwd,
            $xml->pdf->sign->keystore->certificate->password
        );
        $this->assertEquals($sigLevel, $xml->pdf->sign->level);
        $this->assertEquals($sigType, $xml->pdf->sign->type);
        $this->assertTrue((string)$xml->pdf->sign->rectangle->visible === "true");
        $this->assertEquals(strval($x), $xml->pdf->sign->rectangle->position->x);
        $this->assertEquals(strval($x), $xml->pdf->sign->rectangle->position->y);
        $this->assertEquals(
            strval($width),
            $xml->pdf->sign->rectangle->position->width
        );
        $this->assertEquals(
            strval($height),
            $xml->pdf->sign->rectangle->position->height
        );
        $this->assertEquals(
            strval($rot),
            $xml->pdf->sign->rectangle->position->rotation
        );

        // Test the full xml report
        $report = $pdf->serializeToSimpleXmlElement();
        $this->assertInstanceOf("\SimpleXMLElement", $report);

        $this->assertEquals($jsPath, $report->jasperfile);
        $this->assertEquals(strval($jsCopies), $report->jasperfile[0]["copies"]);
        $this->assertEquals(
            $outFile,
            $report->reporttype->pdf->{AReport::NODE_OUT_FILE}
        );

        $this->assertEquals(
            $ksPath,
            $report->reporttype->pdf->sign->keystore->path
        );
        $this->assertEquals(
            $ksPwd,
            $report->reporttype->pdf->sign->keystore->password
        );
        $this->assertEquals(
            $certName,
            $report->reporttype->pdf->sign->keystore->certificate->name
        );
        $this->assertEquals(
            $certPwd,
            $report->reporttype->pdf->sign->keystore->certificate->password
        );
        $this->assertEquals($sigLevel, $report->reporttype->pdf->sign->level);
        $this->assertEquals($sigType, $report->reporttype->pdf->sign->type);
        $this->assertTrue((string)$report->reporttype->pdf->sign->rectangle->visible === "true");
        $this->assertEquals(
            strval($x),
            $report->reporttype->pdf->sign->rectangle->position->x
        );
        $this->assertEquals(
            strval($x),
            $report->reporttype->pdf->sign->rectangle->position->y
        );
        $this->assertEquals(
            strval($width),
            $report->reporttype->pdf->sign->rectangle->position->width
        );
        $this->assertEquals(
            strval($height),
            $report->reporttype->pdf->sign->rectangle->position->height
        );
        $this->assertEquals(
            strval($rot),
            $report->reporttype->pdf->sign->rectangle->position->rotation
        );

        // Verify data source
        $this->assertEquals(
            $dsConStr,
            $report->datasource->database->connectionString
        );
        $this->assertEquals($dsDriver, $report->datasource->database->driver);
        $this->assertEquals($dsPwd, $report->datasource->database->password);
        $this->assertEquals($dsUser, $report->datasource->database->user);

        // Verify parameters
        $this->assertInstanceOf("\SimpleXMLElement", $report->parameters);
        $pdf->serializeToFile($outXml);

        $xmlDoc = new \DOMDocument();
        $xmlDoc->load($outXml);
        $validate = $xmlDoc->schemaValidate(AReport::SCHEMA_LOCATION);
        $this->assertTrue($validate, "Error validating XML");
        if ($validate && \is_file($outXml)) {
            \unlink($outXml);
        }
    }
}
