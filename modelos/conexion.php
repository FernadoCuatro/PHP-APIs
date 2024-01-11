<?php 
/**
 * Clase de conexion de datos de tipo estatico
 */
class Conexion {
	// metodo estatico de toda la vida
	static public function conectar(){
		try {
			$link = new PDO("mysql:host=localhost;dbname=millennium_calendar_db", "root", "root");

			$link->exec("set names utf8");

			return $link;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
}

?>