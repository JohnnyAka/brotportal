<?php

//include($_SERVER['DOCUMENT_ROOT']."/brotportal/admin/db_crud.php");


function exportOrders($name, $password, $dateOriginal){
	//check for name and password
	$authorized = authenticate($name, $password);
	if(!$authorized){
		return "Der Name oder das Passwort ist nicht richtig.";
	}
	else{
		$dateServer= new DateTime($dateOriginal);
		$_POST["day"]=$dateServer->format('d');
		$_POST["month"]=$dateServer->format('m');
		$_POST["year"]=$dateServer->format('Y');
		$csvString = include 'ajax/export_saveDataToCSV.php';
	}
	
	
	return $csvString;
	//return $name." ".$password." ".$date.".";
}

function exportBackup($name, $password){
	//check for name and password
	$authorized = authenticate($name, $password);
	if(!$authorized){
		return "Der Name oder das Passwort ist nicht richtig.";
	}
	else{
		//Code fehlt noch!!!
	}
	return $name." ".$password.".";
}

function authenticate($name, $password){
	$database = new db_connectionServer();
	$data = $database->getData("settings",array("adminName","adminPassword"));
	
	$adminName = $data[0]['adminName'];
	$adminPassword = $data[0]['adminPassword'];
	
	if($name==$adminName and $password==$adminPassword){
		return true;
	}
	else{
		return false;
	}
}

$server = new SoapServer( "brotportal.wsdl" );
$server->addFunction("exportBackup");
$server->addFunction("exportOrders");
$server->handle();

class db_connectionServer{
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
	//returns array of items (array of array)
	/*//ist noch nicht auf sichere Queries angepasst (where_values) fehlt in getData
	function getData($table_name, $args_names, $where_condition = NULL){
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
		$sql = <<<EOT
		SELECT {$strArgsNames} FROM {$table_name}
EOT;
		if (!is_null($where_condition)){
			$sql .= " WHERE " . $where_condition;
		}
		//execute query
		$result = $this->mysqli->query($sql);
		if($result){
			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
		else{
			return $result;
		}
	}*/
} 

?>