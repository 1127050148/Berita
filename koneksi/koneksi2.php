<?php
	/**
	* 
	*/
	class Koneksi
	{
		private $server = "localhost";
		private $username = "root";
		private $password = "";
		private $database = "db";
		function __construct()
		{
			$con = $this->connectDB();
			if (!empty($con)) 
			{
				$this->selectDB($con);
			}
		}

		function connectDB()
		{
			$con = mysql_connect($this->$server, $this->$username, $this->password);
			return $con;
		}

		function selectDB($con)
		{
			mysql_select_db($this->database, $con);
		}

		function query($query)
		{
			$result = mysql_query($query);
			while($data = mysql_fetch_assoc($result))
			{
				$resultset[] = $data;
			}
			if(!empty($resultset))
				return $resultset;
		}

		function numRow($query)
		{
			$result = mysql_query($query);
			$numRow = mysql_num_rows($result);
			return $result;
		}
	}
?>