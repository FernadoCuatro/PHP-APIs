<?php 
require_once 'controladores/rutas.controlador.php';
require_once 'controladores/cursos.controlador.php';
require_once 'controladores/clientes.controlador.php';
require_once 'modelos/cursos.modelo.php';
require_once 'modelos/clientes.modelo.php';

// se crea el objeto
$rutas = new ControladorRutas();
$rutas->inicio();
?>