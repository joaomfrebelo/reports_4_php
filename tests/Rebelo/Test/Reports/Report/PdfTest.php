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
use Rebelo\Reports\Report\Pdf;
use Rebelo\Reports\Report\Sign\Level;
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

    public function testSetGet(): void
    {
        $inst = "Rebelo\Reports\Report\Pdf";
        $pdf  = new Pdf();
        $this->assertInstanceOf($inst, $pdf);
        $this->assertNull($pdf->getSign());
        $this->assertNull($pdf->getJasperFile());
        $this->assertNull($pdf->getOutputfile());
        $this->assertEquals(array(), $pdf->getParameters());

        $ds = new \Rebelo\Reports\Report\Datasource\Database();
        $this->assertInstanceOf($inst, $pdf->setDatasource($ds));
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Datasource\Database",
            $pdf->getDatasource()
        );

        $jf = new \Rebelo\Reports\Report\JasperFile("path");
        $this->assertInstanceOf($inst, $pdf->setJasperFile($jf));
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\JasperFile",
            $pdf->getJasperFile()
        );

        $this->assertInstanceOf($inst, $pdf->setOutputfile("path"));
        $this->assertEquals("path", $pdf->getOutputfile());

        $sign = new \Rebelo\Reports\Report\Sign\Sign();
        $this->assertInstanceOf($inst, $pdf->setSign($sign));
        $this->assertInstanceOf(
            "\Rebelo\Reports\Report\Sign\Sign",
            $pdf->getSign()
        );
    }

    public function testXmlNode(): void
    {
        $resource = __DIR__.DIRECTORY_SEPARATOR.".."
            .DIRECTORY_SEPARATOR.".."
            .DIRECTORY_SEPARATOR.".."
            .DIRECTORY_SEPARATOR.".."
            .DIRECTORY_SEPARATOR."Resources";

        $dsConStr  = "jdbc:mysql://localhost/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = "rebelo";
        $dsPwd     = "password";
        $jsPath    = $resource.DIRECTORY_SEPARATOR."sakila.jasper";
        $jsCopies  = 1;
        $outFile   = $resource.DIRECTORY_SEPARATOR
            ."Generate"
            .DIRECTORY_SEPARATOR
            ."report_".uniqid().".pdf";
        $outXml    = $resource.DIRECTORY_SEPARATOR
            ."Generate"
            .DIRECTORY_SEPARATOR
            .uniqid().".xml";
        $sigLocal  = "Sintra";
        $sigLevel  = Level::CERTIFIED_NO_CHANGES_ALLOWED;
        $sigReazon = "Developer test";
        $sigType   = Type::SELF_SIGNED;
        $rectVisi  = true;
        $height    = 100;
        $width     = 100;
        $x         = 100;
        $y         = 100;
        $rot       = 0;
        $ksPwd     = "password";
        $ksPath    = $resource.DIRECTORY_SEPARATOR."keystore.ks";
        $certName  = "rreports";
        $certPwd   = "password";

        $parameters = array(
            array(
                "type" => "string",
                "name" => "P_STRING",
                "value" => "Parameter String",
                "format" => null),
            array(
                "type" => "bool",
                "name" => "P_BOOLEAN",
                "value" => "true",
                "format" => null),
            array(
                "type" => "date",
                "name" => "P_DATE",
                "value" => "1969-10-05",
                "format" => "yyyy-MM-dd"),
        );

        $pdf = new Pdf();

        $ds = new \Rebelo\Reports\Report\Datasource\Database();
        $ds->setConnectionString($dsConStr);
        $ds->setDriver($dsDriver);
        $ds->setUser($dsUser);
        $ds->setPassword($dsPwd);
        $pdf->setDatasource($ds);

        $jf = new \Rebelo\Reports\Report\JasperFile($jsPath, $jsCopies);
        $pdf->setJasperFile($jf);

        $pdf->setOutputfile($outFile);

        $sign = new \Rebelo\Reports\Report\Sign\Sign();
        $sign->setLocation($sigLocal);
        $sign->setLevel(new Level($sigLevel));
        $sign->setReazon($sigReazon);
        $sign->setType(new Type($sigType));

        $rectangle = new Rectangle();
        $rectangle->setVisible($rectVisi);
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
                new ParameterType($paramProp["type"]), $paramProp["name"],
                $paramProp["value"], $paramProp["format"]
            );
            $pdf->addToParameter($param);
        }

        $node = new \SimpleXMLElement("<root></root>", LIBXML_NOCDATA);
        $this->assertInstanceOf("\SimpleXMLElement", $pdf->createXmlNode($node));
        if (false === $xml  = simplexml_load_string($node->asXML())) { /** @phpstan-ignore-line */
            $this->fail("fail load xml string");
        }
        $this->assertEquals($outFile, (string) $xml->pdf->{Pdf::NODE_OUT_FILE});
        $this->assertEquals($ksPath, (string) $xml->pdf->sign->keystore->path);
        $this->assertEquals($ksPwd, (string) $xml->pdf->sign->keystore->password);
        $this->assertEquals(
            $certName, (string) $xml->pdf->sign->keystore->certificate->name
        );
        $this->assertEquals(
            $certPwd, (string) $xml->pdf->sign->keystore->certificate->password
        );
        $this->assertEquals($sigLevel, (string) $xml->pdf->sign->level);
        $this->assertEquals($sigType, (string) $xml->pdf->sign->type);
        $this->assertEquals(
            $rectVisi,
            (string) $xml->pdf->sign->rectangle->visible === "true"
        );
        $this->assertEquals(
            strval($x), (string) $xml->pdf->sign->rectangle->position->x
        );
        $this->assertEquals(
            strval($x), (string) $xml->pdf->sign->rectangle->position->y
        );
        $this->assertEquals(
            strval($width), (string) $xml->pdf->sign->rectangle->position->width
        );
        $this->assertEquals(
            strval($height),
            (string) $xml->pdf->sign->rectangle->position->height
        );
        $this->assertEquals(
            strval($rot),
            (string) $xml->pdf->sign->rectangle->position->rotation
        );

        // Test the full xml report
        $report = $pdf->serializeToSimpleXmlElement();
        $this->assertInstanceOf("\SimpleXMLElement", $report);

        $this->assertEquals($jsPath, $report->jasperfile);
        $this->assertEquals(
            strval($jsCopies),
            (string) $report->jasperfile[0]["copies"]
        );
        $this->assertEquals(
            $outFile, (string) $report->reporttype->pdf->{Pdf::NODE_OUT_FILE}
        );

        $this->assertEquals(
            $ksPath, (string) $report->reporttype->pdf->sign->keystore->path
        );
        $this->assertEquals(
            $ksPwd, (string) $report->reporttype->pdf->sign->keystore->password
        );
        $this->assertEquals(
            $certName,
            (string) $report->reporttype->pdf->sign->keystore->certificate->name
        );
        $this->assertEquals(
            $certPwd,
            (string) $report->reporttype->pdf->sign->keystore->certificate->password
        );
        $this->assertEquals(
            $sigLevel,
            (string) $report->reporttype->pdf->sign->level
        );
        $this->assertEquals(
            $sigType,
            (string) $report->reporttype->pdf->sign->type
        );
        $this->assertEquals(
            $rectVisi,
            (string)$report->reporttype->pdf->sign->rectangle->visible === "true"
        );
        $this->assertEquals(
            strval($x),
            (string) $report->reporttype->pdf->sign->rectangle->position->x
        );
        $this->assertEquals(
            strval($x),
            (string) $report->reporttype->pdf->sign->rectangle->position->y
        );
        $this->assertEquals(
            strval($width),
            (string) $report->reporttype->pdf->sign->rectangle->position->width
        );
        $this->assertEquals(
            strval($height),
            (string) $report->reporttype->pdf->sign->rectangle->position->height
        );
        $this->assertEquals(
            strval($rot),
            (string) $report->reporttype->pdf->sign->rectangle->position->rotation
        );

        // Verify data source
        $this->assertEquals(
            $dsConStr,
            $report->datasource->database->connectionString
        );
        $this->assertEquals(
            $dsDriver,
            (string) $report->datasource->database->driver
        );
        $this->assertEquals(
            $dsPwd,
            (string) $report->datasource->database->password
        );
        $this->assertEquals($dsUser, (string) $report->datasource->database->user);

        // Verify parameters
        $this->assertInstanceOf("\SimpleXMLElement", $report->parameters);
        $pdf->serializeToFile($outXml);

        $xmlDoc = new \DOMDocument();
        $xmlDoc->load($outXml);
        $valide = $xmlDoc->schemaValidate(\Rebelo\Reports\Report\AReport::SCHEMA_LOCATION);
        $this->assertTrue($valide, "Error validating XML");
        if ($valide && \is_file($outXml)) {
            \unlink($outXml);
        }
    }
}