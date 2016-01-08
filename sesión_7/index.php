<?php
//MySQL PDO
require_once 'mysql-login.php';
class Database
{
	protected $db;

	public function connect(){	

		try {
			$con = new PDO("mysql:host=$hostname;dbname=GeoIP", $username, $password); 
			$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			print "Conexion exitosa!";
		}
		catch (PDOException $e) {
			print "¡Error, no conecta!: " . $e->getMessage() . "<br/>";
			die();
		}
		$this->db = $con;
	}
	public function getConnection(){
        return $this->db;
    }
	//return $con;
	//print "Llego a conectar";
}

class GeoIP
{	

	public function __CONSTRUCT ($arg){
	

		$data =  new Database();

		$data->connect();

		$resultado = $data->getConnection()->prepare("SELECT * FROM cities_locations c JOIN cities_blocks_ip4 b ON c.geoname_id=b.geoname_id WHERE b.network = :ip");
		

		foreach ( $resultado->execute(array(":ip" => $arg )) as $rows) { 
			print("<tr>");
			print("<td>".$rows["c.city_name"]."</td>");
			print("<td>".$rows["c.country_name"]."</td>");
			print("<td>".$rows["b.latitude"]."</td>");
			print("<td>".$rows["b.longitude"]."</td>");
			print("<td>".$rows["c.locale_code"]."</td>");
			print("</tr>"); 
		}
		
		unset($data); 
      	unset($query);
	}
}


$result = new GeoIP($argv[1]);
print_r($result);

?>
