<?php 
/**
  * 
  */
class ControladorCursos {
	// el index se llama por el metodo GET
	// la lista de los datos
	public function index(){
		// validamos las variables del cliente por el metodo basico de Auth 
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$user = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];

			// source de todos los administradores
			$clientes = ModeloClientes::index("administrador");

			foreach ($clientes as $key => $value) {
				if ($user == $value["id_uso"] && $pass == $value["llave_secreta"]) {
					$validado = true;
				} else {
					$validado = false;
				}
			}

			if ($validado) {
				// es requerido usar el modelo
				$cursos = modeloCursos::index("actividad");

				$json = array(
					"detalle" => $cursos,
				);

				echo json_encode($json, true);
			} else {
				$json = array(
					"detalle" => "Credenciales incorrectas",
				);

				echo json_encode($json, true);
			}

		} else {
			$json = array(
				"detalle" => "Credenciales inexistentes",
			);

			echo json_encode($json, true);
		}
	}
	// fin function index

	// el create se llama desde el metodo POST
	// con el datos recibimos la informacion enviado desde la ruta
	public function create($datos){

		// validamos las variables del cliente por el metodo basico de Auth 
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$user = $_SERVER['PHP_AUTH_USER'];
			$pass = $_SERVER['PHP_AUTH_PW'];

			// la primera validacion es que el usuario exista en la validacion Auth
			// source de todos los administradores
			$clientes = ModeloClientes::index("administrador");

			foreach ($clientes as $key => $value) {
				if ($user == $value["id_uso"] && $pass == $value["llave_secreta"]) {
					$validado = true;
					break;
				} else {
					$validado = false;
				}
			}

			// si en el foreach encuentra un valor valido, la validacion es true
			if ($validado) {

				// validamos los datos entrantes en el array de datos
				foreach ($datos as $key => $valueDatos) {

					// validamos cada uno de los campos que contenga la informacion
					// y que los string contengan datos validos
					if (isset($valueDatos) && !preg_match('/^[\\-\\:\\0-9a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $valueDatos)) {
						// si los datos evualuad contiene informacion no deseada, saltamos alerta y quebramos el codigo
						$json = array(
							"status"  => 404,
							"detalle" => "Error en el campo del ".$key.", no se permiten caracteres especiales."
						);

						echo json_encode($json, true);
						return;
					}
				}

				// si los datos pasan el filtro
				// validamos que el nombre_actividad o descripcion no se repitan
				// source de todos los cursos
				$cursos = modeloCursos::index("actividad");

				foreach ($cursos as $key => $value) {
					if ($datos["nombre_actividad"] == $value->nombre_actividad || $datos["descripcion"] == $value->descripcion) {
						$repetido = true;
						break;
					} else {
						$repetido = false;
					}
				}

				// si esta repetido mandamos una alerta y quebramos el codigo
				if ($repetido) {
						$json = array(
							"detalle" => "Nombre de actividad o descripcion repetido",
						);

						echo json_encode($json, true);

				// si no esta repetido seguimos con los procesos
				} else {
					// procesamos los datos para llevarlos al modelo
					$datos = array(
						"id_categoria"     => $datos["id_categoria"],
						"id_facilitador"   => $datos["id_facilitador"],
						"nombre_actividad" => $datos["nombre_actividad"],
						"fecha_inicio"     => date("Y-m-d", strtotime($datos["fecha_inicio"])),
						"fecha_final"      => date("Y-m-d", strtotime($datos["fecha_final"])),
						"dias_semana"      => $datos["dias_semana"],
						"horas_dias"       => $datos["horas_dias"],
						"descripcion"      => $datos["descripcion"]
					);

					// envio de datos y el insert into
					// vamos al modelo para invocar la funcion de insertar
					$create = modeloCursos::create("actividad", $datos);

					// si el return es true, se inserto correctamente
					if ($create) {
						$json = array("detalle" => "Se inserto la actividad '".$datos["nombre_actividad"]."', en base de datos.");
					} else {
						$json = array("detalle" => $create);
					}

					echo json_encode($json, true);
					return;
				}

			// si las credenciales no son validas, venimos aqui
			} else {
				$json = array(
					"detalle" => "Credenciales incorrectas",
				);

				echo json_encode($json, true);
			}

		// si las credenciables no se presentan, directamente para aca
		} else {
			$json = array(
				"detalle" => "Credenciales inexistentes",
			);

			echo json_encode($json, true);
		}
	}
	// fin function create

	// show se manda a llamar cuando existen 5 parametros y el ID que se requiere es numero
	public function show($id){
		// al chile validamos que el usuario este validado Auth Basic
		// validamos las variables del cliente por el metodo basico de Auth 
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$user = $_SERVER['PHP_AUTH_USER']; $pass = $_SERVER['PHP_AUTH_PW'];

			// la primera validacion es que el usuario exista en la validacion Auth
			// source de todos los administradores
			$clientes = ModeloClientes::index("administrador");

			foreach ($clientes as $key => $value) {
				if ($user == $value["id_uso"] && $pass == $value["llave_secreta"]) {
					$validado = true; break;
				} else {
					$validado = false;
				}
			}

			// si en el foreach encuentra un valor valido, la validacion es true
			// si las credenciales no son validas, venimos aqui
			if ($validado) {
				// recuperamos los datos solamente del curso requerido por el ID
				$curso = modeloCursos::show("actividad", $id);

				// validamos que el curso exista, que al menos obtengamos un valor
				// tambien valida que no venga vacio -> if(empty($curso))
				if ($curso != []) {
					$json = array(
						"detalle" => $curso
					);
				} else {
					$json = array(
						"detalle" => "No existen datos con el {$id}"
					);
				}

				echo json_encode($json, true);

			} else {
				$json = array(
					"detalle" => "Credenciales incorrectas",
				);

				echo json_encode($json, true);
			}
		// si las credenciales ni siquiera existen, se acaba aqui.
		} else {
			$json = array(
				"detalle" => "Credenciales inexistentes",
			);

			echo json_encode($json, true);
		}
	}
	// fin function show

	// con el put nos aseguramos de subir al servidor
	// tambien requerimos la informacicion para actualizar
	public function update($id, $datos){
		// validamos las variables del cliente por el metodo basico de Auth 
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$user = $_SERVER['PHP_AUTH_USER']; $pass = $_SERVER['PHP_AUTH_PW'];

			// la primera validacion es que el usuario exista en la validacion Auth
			// source de todos los administradores
			$clientes = ModeloClientes::index("administrador");

			foreach ($clientes as $key => $value) {
				if ($user == $value["id_uso"] && $pass == $value["llave_secreta"]) {
					$validado = true; break;
				} else {
					$validado = false;
				}
			}

			// si en el foreach encuentra un valor valido, la validacion es true
			// si las credenciales no son validas, venimos aqui
			if ($validado) {
				// validamos los datos entrantes en el array de datos
				foreach ($datos as $key => $valueDatos) {

					// validamos cada uno de los campos que contenga la informacion
					// y que los string contengan datos validos
					if (isset($valueDatos) && !preg_match('/^[\\-\\:\\0-9a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $valueDatos)) {
						// si los datos evualuad contiene informacion no deseada, saltamos alerta y quebramos el codigo
						$json = array(
							"status"  => 404,
							"detalle" => "Error en el campo del ".$key.", no se permiten caracteres especiales."
						);

						echo json_encode($json, true);
						return;
					}
				}

				// tambien se puede validar que el id del creador sea el unico que pueda actualizarlo
				// pero equis

				// llevemos los datos al modelo
				$datos = array(
					"id"               => $id,
					"id_categoria"     => $datos["id_categoria"],
					"id_facilitador"   => $datos["id_facilitador"],
					"nombre_actividad" => $datos["nombre_actividad"],
					"fecha_inicio"     => date("Y-m-d", strtotime($datos["fecha_inicio"])),
					"fecha_final"      => date("Y-m-d", strtotime($datos["fecha_final"])),
					"dias_semana"      => $datos["dias_semana"],
					"horas_dias"       => $datos["horas_dias"],
					"descripcion"      => $datos["descripcion"]
				);

				// envio de datos y el insert into
				// vamos al modelo para invocar la funcion de insertar
				$update = modeloCursos::update("actividad", $datos);

				// si el return es true, se inserto correctamente
				if ($update) {
					$json = array("detalle" => "Se actualizo la actividad '".$datos["nombre_actividad"]."', en base de datos.");
				} else {
					$json = array("detalle" => $update);
				}
				echo json_encode($json, true);
				return;

			} else {
				$json = array(
					"detalle" => "Credenciales incorrectas",
				);

				echo json_encode($json, true);
			}
		// si las credenciales ni siquiera existen, se acaba aqui.
		} else {
			$json = array(
				"detalle" => "Credenciales inexistentes",
			);

			echo json_encode($json, true);
		}
	}
	// fin function update
	
	// este sera para delete
	public function delete($id){
		// validamos las variables del cliente por el metodo basico de Auth 
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
			$user = $_SERVER['PHP_AUTH_USER']; $pass = $_SERVER['PHP_AUTH_PW'];

			// la primera validacion es que el usuario exista en la validacion Auth
			// source de todos los administradores
			$clientes = ModeloClientes::index("administrador");

			foreach ($clientes as $key => $value) {
				if ($user == $value["id_uso"] && $pass == $value["llave_secreta"]) {
					$validado = true; break;
				} else {
					$validado = false;
				}
			}

			// si en el foreach encuentra un valor valido, la validacion es true
			// si las credenciales no son validas, venimos aqui
			if ($validado) {
				// vamos al modelo para invocar la funcion de insertar
				$delete = modeloCursos::delete("actividad", $id);

				// si el return es true, se inserto correctamente
				if ($delete) {
					$json = array("detalle" => "Se elimino la actividad {$id}");
				} else {
					$json = array("detalle" => $delete);
				}
				echo json_encode($json, true);
				return;

			} else {
				$json = array(
					"detalle" => "Credenciales incorrectas",
				);

				echo json_encode($json, true);
			}
		// si las credenciales ni siquiera existen, se acaba aqui.
		} else {
			$json = array(
				"detalle" => "Credenciales inexistentes",
			);

			echo json_encode($json, true);
		}
	}
}


// // validamos las variables del cliente por el metodo basico de Auth 
// if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
// 	$user = $_SERVER['PHP_AUTH_USER']; $pass = $_SERVER['PHP_AUTH_PW'];

// 	// la primera validacion es que el usuario exista en la validacion Auth
// 	// source de todos los administradores
// 	$clientes = ModeloClientes::index("administrador");

// 	foreach ($clientes as $key => $value) {
// 		if ($user == $value["id_uso"] && $pass == $value["llave_secreta"]) {
// 			$validado = true; break;
// 		} else {
// 			$validado = false;
// 		}
// 	}

// 	// si en el foreach encuentra un valor valido, la validacion es true
// 	// si las credenciales no son validas, venimos aqui
// 	if ($validado) {
// 		$json = array(
// 			"detalle" => "Este es la actividad con el id {$id}"
// 		);

// 		echo json_encode($json, true);

// 	} else {
// 		$json = array(
// 			"detalle" => "Credenciales incorrectas",
// 		);

// 		echo json_encode($json, true);
// 	}
// // si las credenciales ni siquiera existen, se acaba aqui.
// } else {
// 	$json = array(
// 		"detalle" => "Credenciales inexistentes",
// 	);

// 	echo json_encode($json, true);
// }

?>