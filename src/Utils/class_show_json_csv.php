<?php
namespace src\Utils;


class class_show_json_csv
{
	public $table;
	public $points_x_y;
	public $points_x;
	public $points_y;
	
	public $points_y_min;
	public $points_y_max;
	public $points_y_avg;
	
	public $price_buy;
	public $price_sell;
	public $profit;
	
	public $buy_date;	//x = data - najlepiej kupiæ dnia
	public $sell_date;	//x = data - najlepiej sprzedaæ dnia
	
	public function __construct()
	{
		
	}
	
	
	public function table($array = array())
	{
		if(count($array) > 0)
		{
			$rows = $array;
			$cols = array(0 => array_keys($array[0]));
			
			
			$this->table = '<table class="table_main">';
			for($r = 0; $r < 1; $r++)				{$this->table.= '<tr>'; for($c = 0; $c < count(array_keys($array[0])); $c++) {$this->table.= '<th>'. $cols[$r][$c]. '</th>';} $this->table.= '</tr>';}
			for($r = 0; $r < count($rows); $r++)	{$this->table.= '<tr>'; for($c = 0; $c < count(array_keys($array[0])); $c++) {$this->table.= '<td>'. $rows[$r][$cols[0][$c]]. '</td>';} $this->table.= '</tr>';}
			$this->table.= "</table>"; 
		}
		
		
		return $this->table;
	}
	
	
	public function points($array = array())
	{
		if(count($array) > 0)
		{
			for($r = 0; $r <= count($array) - 1; $r++)
			{
				$points_x_y[]	= "{x: ". $array[$r]['data']. ", y: ". $array[$r]['cena']. "}";
				$points_x[]		= "'". $array[$r]['data']. "'";
				$points_y[]		= $array[$r]['cena'];
			}
		}
		
		$this->points_y_min = min($points_y);
		$this->points_y_max = max($points_y);
		$this->points_y_avg = number_format(array_sum($points_y) / count($points_y), 2, '.', '');
		
		
		$tmp = array_keys($points_y, $this->points_y_min);
		$tmp = $tmp[0];
		
		$this->buy_date = str_replace("'", "", $points_x[$tmp]);
		$this->sell_date = str_replace("'", "", $points_x[array_search(max(array_slice($points_y, $tmp, count($points_y))), $points_y)]);
		
		
		$this->price_sell	= max(array_slice($points_y, $tmp, count($points_y)));
		$this->price_buy	= min($points_y);
		
		$this->profit		= number_format($this->price_sell - $this->price_buy, 2, '.', '');
		
		$this->points_x		= implode(', ', $points_x);
		$this->points_y		= implode(', ', $points_y);
		$this->points_x_y	= implode(', ', $points_x_y);
		
		return implode(', ', $points_x_y);
	}
}
?>