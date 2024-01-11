<?php 
// requerimos la conexion a la base de datos
require_once 'conexion.php';

/**
 * clase para el modelo de los clientes
 */
class ModeloClientes {

	// metodo para sacar todos los datos de la tabla pasado por argumentos
	static public function index($tabla) {
		$stmt = Conexion::conectar()->prepare("SELECT * FROM {$tabla}");
		$stmt->execute();
		return $stmt->fetchall();

		$stmt->close();
		$stmt = null;
	}

	// el nombre lo dice, no hay que ser cientifico 
	static public function create($tabla, $datos) {
		$stmt = Conexion::conectar()->prepare("INSERT INTO administrador(nombre, apellido, email, id_uso, llave_secreta, create_at, update_at) VALUES (:nombre, :apellido, :email, :id_uso, :llave_secreta, :create_at, :update_at)");
		$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
		$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
		$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
		$stmt->bindParam(":id_uso", $datos["id_cliente"], PDO::PARAM_STR);
		$stmt->bindParam(":llave_secreta", $datos["llave_secreta"], PDO::PARAM_STR);
		$stmt->bindParam(":create_at", $datos["create_at"], PDO::PARAM_STR);
		$stmt->bindParam(":update_at", $datos["update_at"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			return true;
		} else {
			return print_r(Conexion::conectar()->error_info());
		}

		$stmt->close();
		$stmt = null;
	}


}

?>