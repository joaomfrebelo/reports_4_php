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
use Rebelo\Reports\Report\Csv;
use Rebelo\Reports\Report\Datasource\Database;
use Rebelo\Reports\Report\Docx;
use Rebelo\Reports\Report\Html;
use Rebelo\Reports\Report\JasperFile;
use Rebelo\Reports\Report\Json;
use Rebelo\Reports\Report\Metadata;
use Rebelo\Reports\Report\Ods;
use Rebelo\Reports\Report\Odt;
use Rebelo\Reports\Report\PdfProperties;
use Rebelo\Reports\Report\Pptx;
use Rebelo\Reports\Report\Report;
use Rebelo\Reports\Report\Pdf;
use Rebelo\Reports\Report\ReportException;
use Rebelo\Reports\Report\ReportPathType;
use Rebelo\Reports\Report\ReportResources;
use Rebelo\Reports\Report\Rtf;
use Rebelo\Reports\Report\Sign\Level;
use Rebelo\Reports\Report\Sign\Sign;
use Rebelo\Reports\Report\Sign\Type;
use Rebelo\Reports\Report\Sign\Rectangle;
use Rebelo\Reports\Report\Sign\Keystore;
use Rebelo\Reports\Report\Sign\Certificate;
use Rebelo\Reports\Report\Parameter\Parameter;
use Rebelo\Reports\Report\Parameter\Type as ParameterType;
use Rebelo\Reports\Report\Text;
use Rebelo\Reports\Report\Xlsx;
use Rebelo\Reports\Report\Xml;
use Smalot\PdfParser\Parser;

/**
 * ReportTest
 *
 * @author João Rebelo
 */
class ReportTest extends TestCase
{

    const DB_USER = "dbuser";

    const DB_PASSWORD = "dbpassword";

    const DB_SERVER = "dbserver";

    public static array $properties;

    /**
     * @beforeClass
     * @return void
     */
    public static function beforeClass(): void
    {
        $genDir = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        if (false === $scanDir = \scandir($genDir)) {
            return;
        }
        foreach ($scanDir as $fileName) {
            if (\in_array($fileName, [".", ".."])) {
                continue;
            }
            \unlink($genDir . DIRECTORY_SEPARATOR . $fileName);
        }

        static::$properties = \parse_ini_file(
            TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "properties"
        );
    }

    protected function setUp(): void
    {
        Config::$iniPath = TEST_CONFIG_PROP;
        $refClass        = new \ReflectionClass(Config::class);
        $refProp         = $refClass->getProperty("config");
        $refProp->setAccessible(true);
        $refProp->setValue(null);
    }

    /**
     * @return void
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
        $dsConStr  = "jdbc:mysql://" . static::$properties[static::DB_SERVER] . "/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = static::$properties[static::DB_USER];
        $dsPwd     = static::$properties[static::DB_PASSWORD];
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
                "type" => ParameterType::P_STRING(),
                "name" => "P_STRING",
                "value" => "Parameter String",
                "format" => null],
            [
                "type" => ParameterType::P_BOOL(),
                "name" => "P_BOOLEAN",
                "value" => "true",
                "format" => null],
            [
                "type" => ParameterType::P_DATE(),
                "name" => "P_DATE",
                "value" => "1969-10-05",
                "format" => "yyyy-MM-dd",
            ],
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
                $paramProp["type"],
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
    public function testMultipleInOne()
    {
        $genDir    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $outFile   = $genDir . DIRECTORY_SEPARATOR . "report_" . date("Ymd\THis") . ".pdf";
        $dsConStr  = "jdbc:mysql://" . static::$properties[static::DB_SERVER] . "/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = static::$properties[static::DB_USER];
        $dsPwd     = static::$properties[static::DB_PASSWORD];
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
                "type" => ParameterType::P_STRING(),
                "name" => "P_STRING",
                "value" => "Parameter String",
                "format" => null],
            [
                "type" => ParameterType::P_BOOLEAN(),
                "name" => "P_BOOLEAN",
                "value" => "true",
                "format" => null],
            [
                "type" => ParameterType::P_DATE(),
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
                $paramProp["type"],
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
                                                   $pdf_2,
                                               ]);

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

    /**
     * @return array
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     */
    public function getReportStackForGenerator(): array
    {
        $dataStack = [];
        $dsConStr  = "jdbc:mysql://" . static::$properties[static::DB_SERVER] . "/sakila";
        $dsDriver  = "com.mysql.jdbc.Driver";
        $dsUser    = static::$properties[static::DB_USER];
        $dsPwd     = static::$properties[static::DB_PASSWORD];
        $jsPath    = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "sakila.jasper";
        $jsCopies  = 2;
        $sigLocal  = "Sintra";
        $sigLevel  = Level::CERTIFIED_NO_CHANGES_ALLOWED;
        $sigReason = "Developer test";
        $sigType   = Type::SELF_SIGNED;
        $height    = 100;
        $width     = 200;
        $x         = 100;
        $y         = 100;
        $rot       = 0;
        $ksPwd     = "password";
        $certName  = "rreports";
        $certPwd   = "password";

        $parameters = [
            [
                "type" => ParameterType::P_STRING(),
                "name" => "P_STRING",
                "value" => "Parameter String",
                "format" => null],
            [
                "type" => ParameterType::P_BOOLEAN(),
                "name" => "P_BOOLEAN",
                "value" => "true",
                "format" => null],
            [
                "type" => ParameterType::P_DATE(),
                "name" => "P_DATE",
                "value" => "1969-10-05",
                "format" => "yyyy-MM-dd",
            ],
            [
                "type" => ParameterType::P_DOUBLE(),
                "name" => "P_DOUBLE",
                "value" => "9.99",
            ],
            [
                "type" => ParameterType::P_FLOAT(),
                "name" => "P_FLOAT",
                "value" => "0.999",
            ],
            [
                "type" => ParameterType::P_INTEGER(),
                "name" => "P_INTEGER",
                "value" => "999",
            ],
            [
                "type" => ParameterType::P_LONG(),
                "name" => "P_LONG",
                "value" => "999",
            ],
            [
                "type" => ParameterType::P_SHORT(),
                "name" => "P_SHORT",
                "value" => "9",
            ],
            [
                "type" => ParameterType::P_BIGDECIMAL(),
                "name" => "P_BIG_DECIMAL",
                "value" => "9999.49",
            ],
            [
                "type" => ParameterType::P_INTEGER(),
                "name" => "CHARACTER_WIDTH",
                "value" => "9",
            ],
            [
                "type" => ParameterType::P_TIMESTAMP(),
                "name" => "P_TIMESTAMPT",
                "value" => "1969-10-05 09:00:00",
            ],
            [
                "type" => ParameterType::P_SQL_DATE(),
                "name" => "P_SQL_DATE",
                "value" => "1969-10-05",
                "format" => "yyyy-MM-dd",
            ],
            [
                "type" => ParameterType::P_SQL_TIME(),
                "name" => "P_SQL_TIME",
                "value" => "09:19:29",
            ],
        ];

        $metadata = new Metadata();
        $metadata->setTitle("Reports 4 PHP title");
        $metadata->setAuthor("Reports 4 PHP author");
        $metadata->setCreator("The creator");
        $metadata->setKeywords("Reports PHP");
        $metadata->setApplication("Reports 4 PHP");
        $metadata->setSubject("Test subject");

        $instanceStack = [
            new Pdf(),
            new Csv(),
            new Docx(),
            new Html(),
            new Json(),
            new Ods(),
            new Odt(),
            new Pptx(),
            new Rtf(),
            new Text(),
            new Xlsx(),
            new Xlsx(),
            new Xml(),
        ];

        foreach ($instanceStack as $instance) {

            /** @var \Rebelo\Reports\Report\AReport $instance */

            $ds = new Database();
            $ds->setConnectionString($dsConStr);
            $ds->setDriver($dsDriver);
            $ds->setUser($dsUser);
            $ds->setPassword($dsPwd);
            $instance->setDatasource($ds);

            $jf = new JasperFile($jsPath, $jsCopies);
            $instance->setJasperFile($jf);
            $instance->setMetadata($metadata);

            if ($instance instanceof Pdf) {
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
                $keystore->setCertificate($cert);
                $sign->setKeystore($keystore);

                $instance->setSign($sign);
            }

            foreach ($parameters as $paramProp) {
                $param = new Parameter(
                    $paramProp["type"],
                    $paramProp["name"],
                    $paramProp["value"],
                    $paramProp["format"] ?? null
                );
                $instance->addToParameter($param);
            }

            $dataStack[] = $instance;
        }
        return $dataStack;
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testGenerateApi()
    {
        $genDir = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        foreach ($this->getReportStackForGenerator() as $instance) {
            $shortName = \strtolower((new \ReflectionClass($instance))->getShortName());
            $report    = new Report();
            $report->setTmpDir($genDir . DIRECTORY_SEPARATOR . uniqid());
            $generated = $report->invokeApi($instance);

            $outFile = $genDir
                       . DIRECTORY_SEPARATOR
                       . \join("", [
                    "report_api_",
                    \date("Ymd\THis"),
                    ".",
                    $shortName,
                ]);

            \file_put_contents($outFile, \base64_decode($generated));

            $this->assertTrue(\is_file($outFile));

            if ($instance instanceof Pdf) {
                $pdf  = (new Parser())->parseFile($outFile);
                $info = $pdf->getDetails();
                $this->assertSame(
                    $instance->getMetadata()->getTitle(),
                    $info["Title"]
                );

                $this->assertSame(
                    $instance->getMetadata()->getKeywords(),
                    $info["Keywords"]
                );

                $this->assertSame(
                    $instance->getMetadata()->getCreator(),
                    $info["Creator"]
                );

                $this->assertSame(
                    $instance->getMetadata()->getSubject(),
                    $info["Subject"]
                );

                $this->assertSame(
                    $instance->getMetadata()->getAuthor(),
                    $info["Author"]
                );
            }
        }
    }

    /**
     * @return void
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     * @throws \ReflectionException
     */
    public function testGenerateBulkApi()
    {
        $genDir       = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $reportErrors = [];
        $clientErrors = [];
        $dataStack    = $this->getReportStackForGenerator();
        $reportStack  = (new Report())->invokeApiBulk(
            $dataStack,
            $reportErrors,
            $clientErrors
        );

        $this->assertEmpty($reportErrors, \join("; ", $reportErrors));
        $this->assertEmpty($clientErrors, \join("; ", $clientErrors));

        $this->assertSame(\count($dataStack), \count($reportStack));

        foreach ($reportStack as $k => $report) {
            $outFile = $genDir
                       . DIRECTORY_SEPARATOR
                       . \join("", [
                    "report_api_bulk_",
                    \date("Ymd\THis"),
                    ".",
                    \strtolower((new \ReflectionClass($dataStack[$k]))->getShortName()),
                ]);

            \file_put_contents($outFile, \base64_decode($report));
            $this->assertTrue(\is_file($outFile));
        }
    }

    /**
     * @return void
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Reports\Report\Parameter\ParameterException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Reports\Report\Sign\SignException
     */
    public function testGenerateBulkApiSomeWithError()
    {
        $reportErrors = [];
        $clientErrors = [];
        $dataStack    = $this->getReportStackForGenerator();
        $reportStack  = (new Report())->invokeApiBulk(
            [new Pdf(), ...$dataStack, new Pdf()],
            $reportErrors,
            $clientErrors
        );

        $this->assertEquals(2, \count($reportErrors), \join("; ", $reportErrors));
        $this->assertSame(
            [0, \count($dataStack) + 1],
            \array_keys($reportErrors),
            "The key on error not correspond to the report with error"
        );
        $this->assertEmpty(\count($clientErrors), \join("; ", $clientErrors));
        $this->assertSame(\count($dataStack), \count($reportStack));
    }

    public function testApiError(): void
    {
        try {
            (new Report())->invokeApi(new Pdf());
        } catch (\Throwable $e) {
            $this->assertInstanceOf(ReportException::class, $e);
        }
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     * @throws \Rebelo\Reports\Cache\CacheException
     */
    public function testGenerateWithResources(): void
    {
        $genDir   = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $dsConStr = "jdbc:mysql://" . static::$properties[static::DB_SERVER] . "/sakila";
        $dsDriver = "com.mysql.jdbc.Driver";
        $dsUser   = static::$properties[static::DB_USER];
        $dsPwd    = static::$properties[static::DB_PASSWORD];
        $jsPath   = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "TestSubReport",
            "test_sub_report.jasper",
        ]);

        $resSubOne = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "TestSubReport",
            "subreport_1.jasper",
        ]);

        $resSubTwo = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "TestSubReport",
            "subreport_2.jasper",
        ]);

        $resImage = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "TestSubReport",
            "License_icon-mit.png",
        ]);

        $bikeImg = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "TestSubReport",
            "eco_bike.png",
        ]);

        $db = new Database();
        $db->setPassword($dsPwd);
        $db->setUser($dsUser);
        $db->setDriver($dsDriver);
        $db->setConnectionString($dsConStr);

        $pdf = new Pdf();
        $pdf->setDatasource($db);
        $pdf->setJasperFile(new JasperFile($jsPath, 2));
        $pdf->getJasperFile()->addReportResources(
            ReportResources::factoryFromFilePath("subreport_1.jasper", $resSubOne)
        );
        $pdf->getJasperFile()->addReportResources(
            ReportResources::factoryFromFilePath("subreport_2.jasper", $resSubTwo)
        );
        $pdf->getJasperFile()->addReportResources(
            ReportResources::factoryFromFilePath("License_icon-mit.png", $resImage)
        );
        $pdf->getJasperFile()->addReportResources(
            ReportResources::factoryFromFilePath("eco_bike.png", $bikeImg)
        );

        $report = (new Report())->invokeApi($pdf);

        $this->assertNotEmpty($report);

        $outFile = $genDir
                   . DIRECTORY_SEPARATOR
                   . \join("", [
                "report_api_resources_",
                \date("Ymd\THis"),
                ".pdf",
            ]);

        \file_put_contents($outFile, \base64_decode($report));

        $this->assertTrue(\is_file($outFile));
    }

    /**
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Rebelo\Enum\EnumException
     * @throws \Rebelo\Reports\Config\ConfigException
     * @throws \Rebelo\Reports\Report\Datasource\DatasourceException
     * @throws \Rebelo\Reports\Report\ReportException
     * @throws \Rebelo\Test\Reports\Api\RequestException
     */
    public function testGenerateWithProperties(): void
    {
        $genDir   = TEST_RESOURCES_DIR . DIRECTORY_SEPARATOR . "Generate";
        $dsConStr = "jdbc:mysql://" . static::$properties[static::DB_SERVER] . "/sakila";
        $dsDriver = "com.mysql.jdbc.Driver";
        $dsUser   = static::$properties[static::DB_USER];
        $dsPwd    = static::$properties[static::DB_PASSWORD];
        $jsPath   = \join(DIRECTORY_SEPARATOR, [
            TEST_RESOURCES_DIR,
            "sakila.jasper",
        ]);

        $pdfProperties = new PdfProperties();
        $pdfProperties->setPermissions(PdfProperties::ALLOW_PRINTING);
        $pdfProperties->setUserPassword("user password");
        $pdfProperties->setOwnerPassword("owner password");
        $pdfProperties->setJavascript('app.alert({
                                      cMsg: "Test embedded JavaScript",
                                      cTitle: "Reports API tests"
                                      });');

        $db = new Database();
        $db->setPassword($dsPwd);
        $db->setUser($dsUser);
        $db->setDriver($dsDriver);
        $db->setConnectionString($dsConStr);

        $pdf = new Pdf();
        $pdf->setDatasource($db);
        $pdf->setJasperFile(new JasperFile($jsPath, 2));
        $pdf->setPdfProperties($pdfProperties);

        $report = (new Report())->invokeApi($pdf);

        $this->assertNotEmpty($report);

        $outFile = $genDir
                   . DIRECTORY_SEPARATOR
                   . \join("", [
                "report_api_properties_",
                \date("Ymd\THis"),
                ".pdf",
            ]);

        \file_put_contents($outFile, \base64_decode($report));

        $this->assertTrue(\is_file($outFile));
    }
}
