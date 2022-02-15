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
use Rebelo\Reports\Config\Config;
use Rebelo\Reports\Report\Datasource\Database;
use Rebelo\Reports\Report\JasperFile;
use Rebelo\Reports\Report\Report;
use Rebelo\Reports\Report\Pdf;
use Rebelo\Reports\Report\ReportPathType;
use Rebelo\Reports\Report\Sign\Level;
use Rebelo\Reports\Report\Sign\Sign;
use Rebelo\Reports\Report\Sign\Type;
use Rebelo\Reports\Report\Sign\Rectangle;
use Rebelo\Reports\Report\Sign\Keystore;
use Rebelo\Reports\Report\Sign\Certificate;
use Rebelo\Reports\Report\Parameter\Parameter;
use Rebelo\Reports\Report\Parameter\Type as ParameterType;

/**
 * ReportTest
 *
 * @author João Rebelo
 */
class ReportTest extends TestCase
{

    protected function setUp(): void
    {
        Config::$iniPath = TEST_CONFIG_PROP;
        $refClass = new \ReflectionClass(Config::class);
        $refProp = $refClass->getProperty("config");
        $refProp->setAccessible(true);
        $refProp->setValue(null);
    }

    /**
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\ExecException
     * @throws \Rebelo\Reports\Report\ReportException
     */
    public function testSetGet()
    {

        Config::getInstance()->setTempDirectory(TEST_RESOURCES_DIR);

        $inst   = "\Rebelo\Reports\Report\Report";
        $report = new Report();
        $this->assertInstanceOf($inst, $report);
        $this->assertTrue(\is_string($report->getTmpDir()));
        $this->assertTrue($report->getDeleteFile());
        $this->assertTrue($report->getDeleteDirectory());
        $this->assertNull($report->getPathType());
        $this->assertNull($report->getOutputBaseDir());
        $this->assertNull($report->getJasperFileBaseDir());

        $tmpDir = "tmp dir";
        $this->assertInstanceOf($inst, $report->setTmpDir($tmpDir));
        $this->assertEquals($tmpDir, $report->getTmpDir());

        $boolStk = [
            true,
            false];
        foreach ($boolStk as $bool) {
            $this->assertInstanceOf($inst, $report->setDeleteFile($bool));
            $this->assertEquals($bool, $report->getDeleteFile());
            $this->assertInstanceOf($inst, $report->setDeleteDirectory($bool));
            $this->assertEquals($bool, $report->getDeleteDirectory());
        }

        $outBaseDir = "out base dir";
        $this->assertInstanceOf($inst, $report->setOutputBaseDir($outBaseDir));
        $this->assertEquals($outBaseDir, $report->getOutputBaseDir());

        $jasBaseDir = "jasper base dir";
        $this->assertInstanceOf($inst, $report->setOutputBaseDir($jasBaseDir));
        $this->assertEquals($jasBaseDir, $report->getOutputBaseDir());

        $this->assertInstanceOf(
            $inst,
            $report->setPathType(
                new ReportPathType(ReportPathType::PATH_DIR)
            )
        );
        $this->assertEquals(
            ReportPathType::PATH_DIR,
            $report->getPathType()->get()
        );

        $this->assertInstanceOf(
            $inst,
            $report->setPathType(
                new ReportPathType(ReportPathType::PATH_FILE)
            )
        );
        $this->assertEquals(
            ReportPathType::PATH_FILE,
            $report->getPathType()->get()
        );

        $this->assertTrue(\is_string($report->getBaseCmd()));
    }

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     * @throws \Rebelo\Reports\Report\ExecException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     */
    public function testGenerate()
    {
        $genDir    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $outFile   = $genDir . DIRECTORY_SEPARATOR . "report_" . date("Ymd\THis") . ".pdf";
        $dsConStr  = "jdbc:mysql://localhost/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = "rebelo";
        $dsPwd     = "password";
        $jsPath    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";
        $jsCopies  = 1;
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

        $report = new Report();
        $report->setTmpDir($genDir . DIRECTORY_SEPARATOR . uniqid());
        $exit = $report->generate($pdf);
        $this->assertEquals(0, $exit->getCode());
        if (\is_dir($report->getTmpDir())) {
            \rmdir($report->getTmpDir());
        }
        $this->assertTrue(\is_file($outFile));
        if (\extension_loaded("fileinfo")) {
            $mime = \mime_content_type($outFile);
            $this->assertEquals("application/pdf", $mime);
        } else {
            \Logger::getLogger(\get_class($this))
                   ->warn(__METHOD__ . " mime type not checked in the the test because fileinfo ext is not active");
        }
    }

    /**
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     * @throws \Rebelo\Reports\Report\ExecException
     */
    public function testMultipeInOne()
    {
        $genDir    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $outFile   = $genDir . DIRECTORY_SEPARATOR . "report_" . date("Ymd\THis") . ".pdf";
        $dsConStr  = "jdbc:mysql://localhost/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = "rebelo";
        $dsPwd     = "password";
        $jsPath    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";
        $jsCopies  = 1;
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

        $pdf_2 = clone $pdf;
        foreach ($pdf_2->getParameters() as $key => $param2) {
            if ($param2->getType()->get() === "string") {
                $parmCl = clone $param2;
                $parmCl->setValue("Report 2 cloned from 1");
                $pdf_2->unsetParameters($key);
                $pdf_2->addToParameter($parmCl);
            }
        }

        $report = new Report();
        $report->setTmpDir($genDir . DIRECTORY_SEPARATOR . uniqid());
        $exit = $report->generateMultipleInOne([
                                                   $pdf,
                                                   $pdf_2]);

        $this->assertEquals(0, $exit->getCode());

        $this->assertTrue(\is_file($outFile));
        if (extension_loaded("fileinfo")) {
            $mime = \mime_content_type($outFile);
            $this->assertEquals("application/pdf", $mime);
        } else {
            \Logger::getLogger(\get_class($this))
                   ->warn(__METHOD__ . " mime type not checked in the the test because fileinfo ext is not active");
        }
    }
}
