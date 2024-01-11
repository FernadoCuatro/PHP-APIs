<?php 
/**
  * 
  */
class ControladorClientes {
	public function index(){
			$json = array(
				"detalle" => "Estas en la vista registro desde GET"
			);

			echo json_encode($json, true);
	}

	public function create($datos){
			// validamos la informacion recibida en el array
			// preg_match filtra el patron que los datos tienen que seguir
			// en este caso solo permite letras
			if (isset($datos["nombre"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["nombre"])) {
				$json = array(
					"status"  => 404,
					"detalle" => "Error en el campo del nombre, solo se permiten letras."
				);

				echo json_encode($json, true);
				return;

			// la validacion del apellido es igual que la anterior 
			} elseif (isset($datos["apellido"]) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $datos["apellido"])) {
				$json = array(
					"status"  => 404,
					"detalle" => "Error en el campo del apellido, solo se permiten letras."
				);

				echo json_encode($json, true);
				return;

			// El patron para el correo es un tanto diferente
			} elseif (isset($datos["email"]) && !preg_match('/^(([a-zA-Z0-9\._-]+)@([a-zA-Z0-9-]+)\.([a-zA-Z]{2,4}))$/', $datos["email"])) {
				$json = array(
					"status"  => 404,
					"detalle" => "Error en el campo del email, debe de ser un email valido."
				);

				echo json_encode($json, true);
				return;
			}

			// validamos que el email no es repetido
			$clientes = ModeloClientes::index("administrador");

			foreach ($clientes as $key => $value) {
				if ($value["email"] == $datos["email"]) {
					$json = array(
						"status"  => 404,
						"detalle" => "Error el email ya existe en base de datos."
					);

					echo json_encode($json, true);
					return;
				}
			}

			// Generamos las credenciales del cliente
			$id_cliente    = str_replace("$", "c", crypt($datos["nombre"].$datos["apellido"].$datos["email"], '$2a$07$afartwetsdADS2356FEDGsfhsd$'));
			$llave_secreta = str_replace("$", "c", crypt($datos["email"].$datos["apellido"].$datos["nombre"], '$2a$07$afartwetsdADS2356FEDGsfhsd$'));

			// guardamos los datos en otro array junto con los ultimos datos evaluados
			$datos = array(
				"nombre"        => $datos["nombre"],
				"apellido"      => $datos["apellido"],
				"email"         => $datos["email"],
				"id_cliente"    => $id_cliente,
				"llave_secreta" => $llave_secreta,
				"create_at"     => date("Y-m-d h:i:s"),
				"update_at"     => date("Y-m-d h:i:s")
			);

			// funcion create con el envio de los datos 
			$create = ModeloClientes::create("administrador", $datos);

			if ($create) {
					$json = array(
						"detalle"       => "Se inserto en base de datos. Guarde sus credenciales para obtener la información.",
						"credenciales"  => $id_cliente,
						"llave_secreta" => $llave_secreta
					);

					echo json_encode($json, true);
					return;
			} else {
					$json = array(
						"detalle" => $create
					);

					echo json_encode($json, true);
					return;
			}


			// echo json_encode($json, true);

	}
}
?>