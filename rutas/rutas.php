<?php
	/* con el .htaccess no quebramos la URL aunque se escriba cosas */

	/* capturamos la URL del navegador */
	/* separamos la url por medio del slach segun indices */
	$arrayRutas = explode("/", $_SERVER['REQUEST_URI']);
	// echo $_SERVER['REQUEST_URI'];
	// echo var_dump($arrayRutas);

	// se evalua cuando se pasa un indice mas
	// si la ruta es completa por 4 elementos, es decir: /Web/aContinuar/api_rest/actividad
	if (count(array_filter($arrayRutas)) == 4) {
		// validamos despues de los tres indices que es lo que pide
		// se valida en el indice cuatro
		if (array_filter($arrayRutas)[4] == "actividad") {
			// envio de datos por post
			// validamos que exista el metodo requerido y que sea POST
			if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
				// recuperamos los datos por medio qu se envian
				$datos = array(
					"id_categoria"     => $_POST['id_categoria'],
					"id_facilitador"   => $_POST['id_facilitador'],
					"nombre_actividad" => $_POST['nombre_actividad'],
					"fecha_inicio"     => $_POST['fecha_inicio'],
					"fecha_final"      => $_POST['fecha_final'],
					"dias_semana"      => $_POST['dias_semana'],
					"horas_dias"       => $_POST['horas_dias'],
					"descripcion"      => $_POST['descripcion']
				);
				// echo var_dump($datos)
				
				// se crea el objeto
				$cursos = new ControladorCursos();
				$cursos->create($datos); // enviamos los datos en el array recuperado con la informacion

			// ahora se valida con el metodo GET
			} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
				// se crea el objeto
				$cursos = new ControladorCursos();
				$cursos->index();
			}





		} elseif (array_filter($arrayRutas)[4] == "bitacora") {
			$json = array(
				"detalle" => "Estas en la vista bitacora"
			);





		} elseif (array_filter($arrayRutas)[4] == "registro") {
			// envio de datos por post
			// validamos que exista el metodo requerido y que sea GET
			if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
				// se crea el objeto
				$cursos = new ControladorClientes();
				$cursos->index();

			// para enviar los datos por metodo POST
			} else if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "POST") {
				// capturamos la informacion por POST
				$datos = array(
					"nombre"   => $_POST['nombre'],
					"apellido" => $_POST['apellido'],
					"email"    => $_POST['email']
				);

				// se crea el objeto
				$cursos = new ControladorClientes();
				$cursos->create($datos);
			}



		} else {
			$json = array(
				"detalle" => "no encontrado"
			);

			echo json_encode($json, true);
		}

	// si la ruta se completa con 5 elementos, es decir: /Web/aContinuar/api_rest/actividad/1
	} elseif (count(array_filter($arrayRutas)) == 5) {
		// el parametro 5 lo vamos a pedir por numeric, es decir el id de la actividad
		if (array_filter($arrayRutas)[4] == "actividad" && is_numeric(array_filter($arrayRutas)[5])) {
			// por medio de la peticion GET
			if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "GET") {
				// se crea el objeto
				$cursos = new ControladorCursos();
				$cursos->show($arrayRutas[5]);

			// cuando traremos la informacion, la mostramos para editar
			// lo mismo pero con la peticion de tipo PUT
			// es decir, se va a subir informacion a la base de datos
			} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "PUT") {
				// capturamos los datos para editar
				$datos = array();
				// para capturar los datos del formulario por medio del metodo PUT se utiliza
				parse_str(file_get_contents('php://input'), $datos);
				// echo var_dump($datos);

				// se crea el objeto
				$cursos = new ControladorCursos();
				$cursos->update($arrayRutas[5], $datos);

			// ahora con DELETE, ya te la sabes.
			} elseif (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == "DELETE") {
				// se crea el objeto para eliminar el dato
				$cursos = new ControladorCursos();
				$cursos->delete($arrayRutas[5]);
			}

		} else {
			$json = array(
				"detalle" => "Los datos ingresados en el id no son validos"
			);
			echo json_encode($json, true);
		}

	// si es mas de cuatro y 5 o menos de estos, cae aqui abajo
	// segun esta configuracion, no se hace ninguna peticion a la API
	} else {
		$json = array(
			"detalle" => "no encontrado"
		);


		echo json_encode($json, true);
	}
	

	// la forma original del curso
	// si la ruta va por defecto, es decir: Web/aContinuar/api_rest/
	// es decir que tenga tres indice y este por defecto
	// array_filter lo que hace es remover los indices vacios
	// if (count(array_filter($arrayRutas)) == 3) {
	// 	$json = array(
	// 		"detalle" => "no encontrado"
	// 	);

	// // se evalua cuando se pasa un indice mas
	// } elseif (count(array_filter($arrayRutas)) == 4) {
	// 	// validamos despues de los tres indices que es lo que pide
	// 	// se valida en el indice cuatro
	// 	if (array_filter($arrayRutas)[4] == "actividad") {
	// 		$json = array(
	// 			"detalle" => "Estas en la vista actividad"
	// 		);
	// 	} elseif (array_filter($arrayRutas)[4] == "bitacora") {
	// 		$json = array(
	// 			"detalle" => "Estas en la vista bitacora"
	// 		);
	// 	} else {

	// 	}
	// }


	
?>