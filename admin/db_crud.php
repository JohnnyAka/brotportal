<?php


class db_connection{
	//für die Ausführung von db_config.php muss root als user eingestellt sein, danach wie $adminUser in db_config.php
	public $db_username = 'root';
	public $db_password = '';
	/*public $db_username = 'bestellannahme';
	public $db_password = 'password';*/
	public $db_name = 'brotportal';
	public $db_host = 'localhost';

	protected $mysqli;
	
	function __construct(){
		//connect to MySql                     
		$this->mysqli = new mysqli($this->db_host, $this->db_username, $this->db_password,$this->db_name);                       
		if ($this->mysqli->connect_error) {
			die('Error : ('. $this->mysqli->connect_errno .') '. $this->mysqli->connect_error);
		}
	}

	function createData($table_name, $array_args_names, $array_args){
		
		//prepare arguments and names of arguments
		$strArgsNames="";
		$strArgs="";
		$mysqli = $this->mysqli;
		foreach ($array_args_names as $value){
			$value = $mysqli->real_escape_string($value);
			$strArgsNames .= $value . ", ";
		}
		$strArgsNames = chop($strArgsNames, ", ");
		foreach ($array_args as $value){
			if(is_string($value)){
				$value = $mysqli->real_escape_string($value);
			}elseif(is_float($value) or is_int($value) or is_bool($value) or is_null($value)){
				//go on
			}else{
				return "Error: Der Typ einer Variable ist nicht bekannt.";
			}
			$strArgs .= "'" . $value . "'" . ", ";
		}
		$strArgs = chop($strArgs, ", ");
		//put query
		$sql = <<<EOT
		INSERT INTO {$table_name} ({$strArgsNames})
		VALUES ({$strArgs})
EOT;
		
		//execute query
		if ($mysqli->query($sql) === TRUE) {
			return "New record created successfully";
		} else {
			return "Error: " . $sql . "<br>" . $mysqli->error;
		}
	}

	//returns array of items (array of array)
	function getData($table_name, $args_names, $where_condition = NULL, $where_values = NULL, $noDuplicateEntries = NULL){
		$mysqli = $this->mysqli;
		
		//prepare names of arguments
		if(is_array($args_names)){
			$strArgsNames="";
			foreach ($args_names as $value){
				$value = $mysqli->real_escape_string($value);
				$strArgsNames .= $value . ", ";
			}
			$strArgsNames = chop($strArgsNames, ", ");
		}
		else{
			$strArgsNames = $args_names;
		}
		
		//put query
		$distinct= '';
		if (!is_null($noDuplicateEntries)){
			$distinct = 'distinct';
		}
			$sql = <<<EOT
			SELECT {$distinct} {$strArgsNames} FROM {$table_name}
EOT;
		
		//prepare where condition - ?1 (?<number>) in the condition are placeholders for values
		if (!is_null($where_condition)){
			if(!is_array($where_values)){
        $where_condition_final = str_replace('?1',"'".$where_values."'",$where_condition);
			}else{
				$countValues = count($where_values);
				$placeholderArray =array();
				for($x=1;$x<=$countValues;$x++){
					array_push($placeholderArray,"?".$x);
					$value = $where_values[$x-1];
					if(is_string($value)){
						$value = $mysqli->real_escape_string($value);
					}elseif(is_float($value) or is_int($value) or is_bool($value) or is_null($value)){
						//go on
					}else{
						return "Error: Der Typ einer Variable ist nicht bekannt.";
					}
					$where_values[$x-1] = "'".$value."'";
				}
        $where_condition_final = str_replace($placeholderArray,$where_values,$where_condition);
			}
			$sql .= " WHERE " . $where_condition_final;
		}
		//execute query
		
		$result = $this->mysqli->query($sql);
		if($result){
			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		else{
			return $result;
		}
	}
	
	
	function updateData($table_name, $args_names, $args_values, $where_condition = NULL, $where_values = NULL){
		$mysqli = $this->mysqli;
		//prepare name-value pairs for query
		if (is_array($args_names)){
			$strArgs = "";
			
			for($x=0; $x < count($args_names); $x++){
				$args_names[$x] = $mysqli->real_escape_string($args_names[$x]);
				$args_values[$x] = $mysqli->real_escape_string($args_values[$x]);
				$strArgs .= $args_names[$x]." = '".$args_values[$x]."', ";
			}
			$strArgs = chop($strArgs, ", ");
		}
		else
		{
			$args_names = $mysqli->real_escape_string($args_names);
			$args_values = $mysqli->real_escape_string($args_values);
			$strArgs = $args_names." = '".$args_values."'";
		}
		//put query
		$sql = "UPDATE ".$table_name." SET ".$strArgs;
		
		//prepare where condition - ?1 (?<number>) in the condition are placeholders for values
		if (!is_null($where_condition)){
			if(!is_array($where_values)){
        $where_condition_final = str_replace('?1',"'".$where_values."'",$where_condition);
			}else{
				$countValues = count($where_values);
				$placeholderArray =array();
				for($x=1;$x<=$countValues;$x++){
					array_push($placeholderArray,"?".$x);
					$value = $where_values[$x-1];
					if(is_string($value)){
						$value = $mysqli->real_escape_string($value);
					}elseif(is_float($value) or is_int($value) or is_bool($value) or is_null($value)){
						//go on
					}else{
						return "Error: Der Typ einer Variable ist nicht bekannt.";
					}
					$where_values[$x-1] = "'".$value."'";
				}
        $where_condition_final = str_replace($placeholderArray,$where_values,$where_condition);
			}
			$sql .= " WHERE " . $where_condition_final;
		}
		
		//execute query
		if ($this->mysqli->query($sql)=== TRUE) {
			return "Record updated successfully";
		} else {
			return "Error updating record: " . $this->mysqli->error;
		}
	}
	
	function deleteData($table_name, $where_condition, $where_values){
		$mysqli = $this->mysqli;
		//prepare where condition - ?1 (?<number>) in the condition are placeholders for values
		if(!is_array($where_values)){
			$where_condition_final = str_replace('?1',"'".$where_values."'",$where_condition);
		}else{
			$countValues = count($where_values);
			$placeholderArray =array();
			for($x=1;$x<=$countValues;$x++){
				array_push($placeholderArray,"?".$x);
				$value = $where_values[$x-1];
				if(is_string($value)){
					$value = $mysqli->real_escape_string($value);
				}elseif(is_float($value) or is_int($value) or is_bool($value) or is_null($value)){
					//go on
				}else{
					return "Error: Der Typ einer Variable ist nicht bekannt.";
				}
				$where_values[$x-1] = "'".$value."'";
			}
			$where_condition_final = str_replace($placeholderArray,$where_values,$where_condition);
		}
	
		//put query
		$sql = "DELETE FROM ".$table_name." WHERE ".$where_condition_final;
		//execute query
		if ($this->mysqli->query($sql)=== TRUE) {
			return "Record/s deleted successfully";
		} else {
			return "Error deleting record/s: " . $this->mysqli->error;
		}
	}
}


?>























