<?php
class db_connection{
	public $db_username = 'root';
	public $db_password = '';
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
			//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!$value = mysqli_real_escape_string($mysqli, $value );
			$strArgsNames .= $value . ", ";
		}
		$strArgsNames = chop($strArgsNames, ", ");
		foreach ($array_args as $value){
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
	function getData($table_name, $args_names, $where_condition = NULL){

		//prepare names of arguments
		if(is_array($args_names)){
			$strArgsNames="";
			foreach ($args_names as $value){
				$strArgsNames .= $value . ", ";
			}
			$strArgsNames = chop($strArgsNames, ", ");
		}
		else{
			$strArgsNames = $args_names;
		}
		
		//put query
		$sql = <<<EOT
		SELECT {$strArgsNames} FROM {$table_name}
EOT;
		if (!is_null($where_condition)){
			$sql .= " WHERE " . $where_condition;
		}
		//execute query
		$result = mysqli_fetch_all($this->mysqli->query($sql), MYSQLI_ASSOC);
		return $result;
	}
	
				
	function updateData($table_name, $args_names, $args_values, $where_condition){
		//prepare name-value pairs for query
		if (is_array($args_names)){
			$strArgs = "";
			
			for($x=0; $x < count($args_names); $x++){
				$strArgs .= $args_names[$x]." = '".$args_values[$x]."', ";
			}
			$strArgs = chop($strArgs, ", ");
		}
		else
		{
			$strArgs = $args_names." = '".$args_values."'";
		}
		//put query
		$sql = "UPDATE ".$table_name." SET ".$strArgs." WHERE ".$where_condition.";";
		
		//execute query
		if ($this->mysqli->query($sql)=== TRUE) {
			return "Record updated successfully";
		} else {
			return "Error updating record: " . $this->mysqli->error;
		}
	}
	
	function deleteData($table_name, $where_condition){
		//put query
		$sql = "DELETE FROM ".$table_name." WHERE ".$where_condition;
		//execute query
		if ($this->mysqli->query($sql)=== TRUE) {
			return "Record deleted successfully";
		} else {
			return "Error deleting record: " . $this->mysqli->error;
		}
	}
}
?>























