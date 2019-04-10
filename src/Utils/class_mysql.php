<?php
namespace src\Utils;


class class_mysql
{
	public $link;
	public $flag;
	
	/*
	public function __construct($host = 'localhost', $user = 'rawjet_u1', $pass = 'PqM1wWTclS', $name = 'rawjet_db1', $charset = 'utf8')
	*/
	public function __construct($host = 'localhost', $user = 'root', $pass = '', $name = 'example_database', $charset = 'utf8')
	{
		$this->link = mysqli_connect  ($host, $user, $pass)or $this->class_on_error(true, mysqli_errno()); mysqli_query($this->link, 'set names '. $charset);
		$this->flag = mysqli_select_db($this->link, $name) or $this->class_on_error(true, mysqli_errno());
	}
	
	
	public function class_mysql_kill()
	{
		mysql_close($this->link);
	}
	
	
	public function class_mysql_read($sql, $mode = 'MYSQLI_ASSOC', $i = 0, $table = array())
	{
		$result = mysqli_query($this->link, $sql); 
		
		switch(str_replace('MYSQL_', 'MYSQLI_', $mode))
		{
			case 'MYSQLI_NUM':	{while($row = mysqli_fetch_array($result, MYSQLI_NUM))		if($row == null) break; else $table[$i++] = $row; break;}
			case 'MYSQLI_BOTH':	{while($row = mysqli_fetch_array($result, MYSQLI_BOTH))		if($row == null) break; else $table[$i++] = $row; break;}
			case 'MYSQLI_ASSOC':{while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))	if($row == null) break; else $table[$i++] = $row; break;}
			default:			{while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))	if($row == null) break; else $table[$i++] = $row;}
		}

		return $table;
	}
	
	
	function class_mysql_write($sql)
	{
		$result = mysqli_query($this->link, $sql) or print('Error! Unable write data: '. mysqli_error());
	}
	
	
	function class_on_error($show_errno_url = true, $sql_errno)
	{
		print_r('Application is serviced '. $sql_errno. ' !!');
	}
}
?>