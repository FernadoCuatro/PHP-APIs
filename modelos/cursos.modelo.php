<?php 
// requerimos la conexion a la base de datos
require_once 'conexion.php';

class modeloCursos {
	// lista todos los datos en HD
	static public function index($tabla) {
		$stmt = Conexion::conectar()->prepare("SELECT * FROM {$tabla}");
		$stmt->execute();
		return $stmt->fetchall(PDO::FETCH_CLASS);

		$stmt->close();
		$stmt = null;
	}
	// fin function index

	// insertamos las actividades obteniendo la tabla y los datos enviados por el array
	static public function create($tabla, $datos) {
		$query  = "INSERT INTO actividad(id_categoria, id_facilitador, nombre_actividad, fecha_inicio, fecha_final, dias_semana, horas_dias, descripcion) ";
		$query .= "VALUES (:id_categoria, :id_facilitador, :nombre_actividad, :fecha_inicio, :fecha_final, :dias_semana, :horas_dias, :descripcion)";
		$stmt = Conexion::conectar()->prepare($query);
		
		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_STR);
		$stmt->bindParam(":id_facilitador", $datos["id_facilitador"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre_actividad", $datos["nombre_actividad"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_inicio", $datos["fecha_inicio"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_final", $datos["fecha_final"], PDO::PARAM_STR);
		$stmt->bindParam(":dias_semana", $datos["dias_semana"], PDO::PARAM_STR);
		$stmt->bindParam(":horas_dias", $datos["horas_dias"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);

		if ($stmt->execute()) {
			return true;
		} else {
			return print_r(Conexion::conectar()->error_info());
		}

		$stmt->close();
		$stmt = null;
	}
	// fin function create

	// evaluamos por la tabla y por el id de la actividad
	static public function show($tabla, $id) {
		$stmt = Conexion::conectar()->prepare("SELECT * FROM {$tabla} WHERE id_actividad=:id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);

		$stmt->execute();
		return $stmt->fetchall(PDO::FETCH_CLASS);

		$stmt->close();
		$stmt = null;
	}
	// fin function show

	// actualizamos las actividades obteniendo la tabla y los datos enviados por el array
	static public function update($tabla, $datos) {
		$query  = "UPDATE actividad SET id_categoria=:id_categoria,id_facilitador=:id_facilitador,nombre_actividad=:nombre_actividad, ";
		$query .= "fecha_inicio=:fecha_inicio,fecha_final=:fecha_final,dias_semana=:dias_semana,horas_dias=:horas_dias,descripcion=:descripcion WHERE id_actividad=:id";
		$stmt = Conexion::conectar()->prepare($query);
		
		$stmt->bindParam(":id_categoria", $datos["id_categoria"], PDO::PARAM_STR);
		$stmt->bindParam(":id_facilitador", $datos["id_facilitador"], PDO::PARAM_STR);
		$stmt->bindParam(":nombre_actividad", $datos["nombre_actividad"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_inicio", $datos["fecha_inicio"], PDO::PARAM_STR);
		$stmt->bindParam(":fecha_final", $datos["fecha_final"], PDO::PARAM_STR);
		$stmt->bindParam(":dias_semana", $datos["dias_semana"], PDO::PARAM_STR);
		$stmt->bindParam(":horas_dias", $datos["horas_dias"], PDO::PARAM_STR);
		$stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
		$stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

		if ($stmt->execute()) {
			return true;
		} else {
			return print_r(Conexion::conectar()->error_info());
		}

		$stmt->close();
		$stmt = null;
	}
	// fin function update

	// evaluamos por la tabla y por el id de la actividad
	static public function delete($tabla, $id) {
		$stmt = Conexion::conectar()->prepare("DELETE FROM actividad WHERE id_actividad=:id");
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);

		if ($stmt->execute()) {
			return true;
		} else {
			return print_r(Conexion::conectar()->error_info());
		}

		$stmt->close();
		$stmt = null;
	}
	// fin function delete 
}
?>