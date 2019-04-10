<?php
/**
 * Created by PhpStorm.
 * User: HP test
 * Date: 2019-03-28
 * Time: 17:24
 */
 
 
error_reporting(E_ALL);

ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


function debug($t)
{
	echo '<pre>'; print_r($t); echo '</pre>';
}


$path  = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, realpath(getcwd()));
$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen'); $absolutes = array(); 

foreach($parts as $part)
{
	if('.'  == $part) continue;
	if('..' == $part) array_pop($absolutes); else $absolutes[] = $part;
}

define("__ROOT__",		str_replace(':/', ':\\', implode('\\', $absolutes)));											//debug(__ROOT__);
define('__ROOT_PATH__',	('http://'. str_replace('index.php', '', $_SERVER['SERVER_NAME']. $_SERVER['SCRIPT_NAME'])));	//debug(__ROOT_PATH__);

//require ("src/Utils/class_mysql.php");
//require ("src/Utils/class_load_json_csv.php");
require ("vendor/autoload.php");

use src\Utils\class_mysql;
use src\Utils\class_load_json_csv;
use src\Utils\class_show_json_csv;


$mysql	= new class_mysql();					// debug($mysql);
$load	= new class_load_json_csv($mysql);		// debug($load);
$show	= new class_show_json_csv();

/*
 require 'vendor/autoload.php';
    $indexParams = [
        'index' => 'my_index',
        'body' => [
            'settings' => [
                'number_of_shards' => 5,
                'number_of_replicas' => 1
            ]
        ]
    ];

    $client = Elasticsearch\ClientBuilder::create()
        ->setSSLVerification(false)
        ->setHosts(["127.0.0.1:9200"])->build();  
    $response = ''; 
    try {
        // Create the index 
        $response = $client->indices()->create($indexParams);
        print_r($response);

        print_r($response);

    } catch(Exception $e) {
        echo "Exception : ".$e->getMessage();
    }
    die('End : Elastic Search');
*/





$xsl = new DOMDocument();
$xsl->load("plik_xsl.xml");

$inputdom = new DomDocument();
$inputdom->load("plik_xml.xml");

$proc = new XSLTProcessor();
$xsl = $proc->importStylesheet($xsl);
$proc->setParameter(null, "", "");

$newdom = $proc->transformToDoc($inputdom);
//print $newdom-> saveXML();




$data_csv  = $load->load_csv ('file_csv.csv', false);											//debug($data_csv);

$data_json_data_beg = '2019-03-01';
$data_json_data_end = '2019-03-10';
$data_json = $load->load_json('http://api.nbp.pl/api/cenyzlota/'. $data_json_data_beg. '/'. $data_json_data_end, false);	//debug($data_json);

$points	= $show->points($data_json);

require (dirname(__FILE__) . '/src/Utils/Classes/PHPExcel.php');


$objPHPExcel = new PHPExcel();
$__File_Name = 'file_excel.xlsx';
$objPHPExcel->getProperties()->setCreator("Pawel Drabowicz")
							 ->setLastModifiedBy("Pawel Drabowicz")
							 ->setTitle("Exported Data")
							 ->setSubject("PHPExcel Document")
							 ->setDescription("Excel document created using PHPExcel.")
							 ->setKeywords("Report Data Excel")
							 ->setCategory("Report");

$a_zPHPExcel = array_combine(range(1, 26), range('A', 'Z'));


$rows = $array = $data_csv;
$cols = array(0 => array_keys($array[0]));


for($r = 0; $r < 1; $r++) for($c = 0; $c < count(array_keys($array[0])); $c++) 
{
	$posPHPExcel = $a_zPHPExcel[($c + 1)]. ($r + 1);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($posPHPExcel, $cols[$r][$c]);
}


for($r = 0; $r < count($rows); $r++) for($c = 0; $c < count(array_keys($array[0])); $c++) 
{
	$posPHPExcel = $a_zPHPExcel[($c + 1)]. ($r + 2);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue($posPHPExcel, $rows[$r][$cols[0][$c]]);
}


$objPHPExcel->getActiveSheet()->setTitle('Exported Data');
$objPHPExcel->setActiveSheetIndex(0);


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
//$objWriter->save($__File_Name);


//include(__ROOT__. '/templates/index_html_head.php');
include('templates/index_html_head.php');
?>