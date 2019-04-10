<?php
namespace src\Utils;


class class_load_json_csv
{
	public $mysql;
	
	
	public function __construct($mysql_link)
	{
		$this->mysql = $mysql_link;
	}
	
	
	public function load_csv($file_path = 'file_csv.csv', $insert_to_mysql = false, $array = array())
	{
		$file_data = file($file_path); 
		
		
		if(isset($file_data))
		{
			for($r = 1; $r <= count($file_data) - 1; $r++)
			{
				$rows = explode(';', $file_data[$r]); $cols = explode(';', $file_data[0]); for($c = 0; $c < count($cols); $c++) {$array[$r - 1][trim($cols[$c])] = $rows[$c];}
				
				
				if($insert_to_mysql == true)
				{
					$sql_query 
					= 
					"
					INSERT INTO example_table 
					(
						order_number, 
						item_name, 
						order_price_net, 
						order_date_add
					) 
					VALUES 
					(
						'". $rows[1]. "', 
						'". $rows[3]. "', 
						'". $rows[6]. "',
						'". $rows[9]. "'
					);
					";
					
					//debug($sql_query);
					
					$this->mysql->class_mysql_write($sql_query);
				}
			}
		}
		
		
		return $array;
	}
	
	
	public function load_json($file_path = 'http://api.nbp.pl/api/cenyzlota/2019-03-01/2019-03-08', $insert_to_mysql = false, $array = array())
	{
		$curl_init	 = curl_init($file_path);
		$curl_config = array(CURLOPT_RETURNTRANSFER => true); curl_setopt_array($curl_init, $curl_config);
		$curl_result = curl_exec($curl_init); 
		
		
		$file_data = json_decode($curl_result, true);
		
		
		if(isset($file_data))
		{
			for($r = 0; $r <= count($file_data) - 1; $r++)
			{
				$array[$r] = $file_data[$r]; 
				
				
				if($insert_to_mysql == true)
				{
					$sql_query 
					= 
					"
					INSERT INTO example_table 
					(
						order_number, 
						item_name, 
						order_price_net, 
						order_date_add
					) 
					VALUES 
					(
						'". $file_data[$r]['cena']. "', 
						'". $file_data[$r]['cena']. "', 
						'". $file_data[$r]['cena']. "',
						'". $file_data[$r]['data']. "'
					);
					";
					
					//debug($sql_query);
					
					$this->mysql->class_mysql_write($sql_query);
				}
			}
		}
		
		return $array;
	}
}
?>