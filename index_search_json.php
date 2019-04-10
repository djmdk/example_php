<?php
header('Content-type: application/json; charset=utf-8');
 
 
include('class_mysql.php');


function validate_url($t)
{
	$t = urldecode($t);
	$t = strip_tags($t);
	
	$a_src = array('ó', "\xC3",	'ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż', '.', ',', '`', '~', '%', '#', '&', "/", "\\", ' ', '---', '--', '"');
	$a_dst = array('o', '', 	'a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', '',  '',  '',  '',  '',  '',  '',  "",  "", '-', '-',   '-', '' );
	$t = str_replace($a_src, $a_dst, $t);
	
	$a_src = array("\xC3",	'Ą', 'Ć', 'Ę', 'Ł', 'Ń', 'Ó', 'Ś', 'Ź', 'Ż', '|');
	$a_dst = array('', 		'a', 'c', 'e', 'l', 'n', 'o', 's', 'z', 'z', '');
	$t = str_replace($a_src, $a_dst, $t);
	
	$t = strtolower($t);
	return $t;
}


function validate_get($t)
{
	//$t = urldecode($t);
	$t = strip_tags($t);
	
	$a_src = array("'",	'"', ' ');
	$a_dst = array('', 	"",	 '%');
	$t = str_replace($a_src, $a_dst, $t);
	
	$t = strtolower($t);
	return $t;
}


function validate_text($t)
{
	$a_src = array('%');
	$a_dst = array(' ');
	$t = str_replace($a_src, $a_dst, $t);
	
	return $t;
}


if(isset($_GET['search'])) 
{
	$search = urldecode($_GET['search']);
} 
else 
{
	$search = 'search';
}


/*
$sql 
= 
"
SELECT 

kna_gidnumer, 
LTRIM(Klient) AS Klient, 
CASE WHEN SPD = '' THEN '-' ELSE SPD END AS SPD,
REPLACE(LTRIM(Klient)  + ' - ' + Kod + ', ' + Miasto + ', ' + Ulica, '\"', '') + ' - ' + CASE WHEN SPD = '' THEN '-' ELSE SPD END AS Adres

FROM CDN.JG_ZmianaSPDView

WHERE 

(LTRIM(Klient)  + ' - ' + Kod + ', ' + Miasto + ', ' + Ulica + ' - ' + CASE WHEN SPD = '' THEN '-' ELSE SPD END) LIKE '%". validate_text(iconv("UTF-8", "WINDOWS-1250", $search)). "%'

ORDER BY Adres
";

$res = $db->class_mssql_read($sql, 'MSSQL_ASSOC'); //debug($sql); debug($res);

OR
	 
	
	LOWER(ART.content_ext) LIKE '%". addslashes(strip_tags(urldecode(strtolower($search)))). "%' 
	OR
	LOWER(ART.tags) LIKE '%". addslashes(strip_tags(urldecode(strtolower($search)))). "%'
*/

/*
$sql
=
"
SELECT 

ART.id,
ART.name, 
ART.tags, 
ART.id_user, 
USR.nick

FROM ". DB_PREFIX. "articles ART
LEFT OUTER JOIN ". DB_PREFIX. "profiles USR ON (USR.id = id_user)

WHERE 
ART.id_category <> 10 
AND ART.type <> 2 
AND ART.active = 1 
AND 
(
	LOWER(ART.name) LIKE '%". validate_get($search). "%'
)

GROUP BY

ART.id,
ART.name, 
ART.tags, 
ART.id_user, 
USR.nick

ORDER BY ART.date_add DESC

LIMIT 10
";

$res = $db->class_mysql_read($sql, 'MYSQL_ASSOC'); //debug($gallery); debug($sql);

if(count($res) > 0) 
{
	$json_array = array();
	
	foreach($res as $val)
	{
		$json_array[] = '{"id": "'. $val['id']. '", "name": "'. validate_text($val['name']). '", "url": "'. validate_url($val['name']). '"}';
	}
	
	$json_result = '{"items": ['. implode($json_array, ', '). ']}';
}

//echo iconv("WINDOWS-1250", "UTF-8", $json_result);


echo $json_result;
*/


echo 
'
{
	"searchResult": 
	[
		{"id": "id-999-'. $search. '", "name": "name-Cherry-'. $search. '"}
	]
}
';
?>