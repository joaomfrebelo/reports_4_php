# Rebelo Reports for PHP

The Rebelo Reports for PHP is the module to run the Rebelo Reports from PHP.  
You have to have installed Rebelo Reports Cli.
    
# Rebelo Reports

Rebelo Reports is a middleware to use the Jasper Reports Framework in the cases that you can not use the Jasper Reports embedded in your software or in cases that your software is not java or there are incompatibility of licences.

Rebelo Reports project in:  
https://github.com/joaomfrebelo/reports_core  
https://jitpack.io/#joaomfrebelo/reports_core  
  
Rebelo Report CLI project:  
https://github.com/joaomfrebelo/reports_cli  
https://jitpack.io/#joaomfrebelo/reports_cli

## To include Reports for PHP in your PHP project (PHP files)

Via Composer

```bash
$ composer require joaomfrebelo/reports_4_php
```  

The java jar files of Rebelo Reports CLI should be compiled and instaled from github or jitpack.io


## Example using from PHP  
  
First rename the file under Config folder config.properties.example to config.properties  
Then configure the config.properties file with your options.  
For log4php do the same to logconf.xml.example

```
use Rebelo\Reports\Report\Datasource\Database;  
use Rebelo\Test\Reports\Report\Parameter\Parameter;  
use Rebelo\Test\Reports\Report\Parameter\Type;
use Rebelo\Reports\Report\JasperFile;  
use Rebelo\Reports\Report\Pdf;

$ds = new Database();
$ds->setConnectionString("jdbc:mysql://localhost/sakila");
$ds->setDriver("com.mysql.jdbc.Driver");
$ds->setUser("user");
$ds->setPassword("password");

$parameter = new Parameter(new Type(Type::P_STRING),"parameter name", "parameter value");

$jf = new JasperFile("/path/to/report.jasper", 1);

$pdf = new Pdf();
$pdf->setDatasource($ds);
$pdf->setJasperFile($jf);
$pdf->setOutputfile("/path/to/outfile.pdf");

$report = new Report();
$result = $report->generate($pdf);

if($result->getCode() == 0){
    // exported ok
    // pdf is in /path/to/outfile.pdf
}else{
    // see exitcode
    // see messages with  $result->getMessages();   
}

```
  
## Features  
### Export to:  
- PDF  
- Digital Sign PDF
- Csv
- Docx
- Html
- Json
- Ods  
- Odt  
- Pptx  
- Rtf  
- Text  
- Xls  
- Xlsx  
- Xml  
- To printer

### Others
- Export multiple reports as one with the same exporter.
- Export copies of the repoprt at onces with a parametrs of the copy index  
- Pass parameters to the report well typed, parameters types:  
    - const P_STRING     = "string";
    - const P_BOOL       = "bool";
    - const P_BOOLEAN    = "boolean";
    - const P_DOUBLE     = "double";
    - const P_FLOAT      = "float";
    - const P_INTEGER    = "integer";
    - const P_LONG       = "long";
    - const P_SHORT      = "short";
    - const P_BIGDECIMAL = "bigdecimal";
    - const P_DATE       = "date";
    - const P_TIME       = "time";
    - const P_SQL_TIME   = "sqltime";
    - const P_SQL_DATE   = "sqldate";
    - const P_TIMESTAMP  = "timestamp";

## License

Copyright (C) 2019  João M F Rebelo  

MIT License  

Copyright (c) 2019 João M F Rebelo  

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:  
   

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.  
   
   
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.  
   
   
