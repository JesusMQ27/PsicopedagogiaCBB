<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();
$useId = $_SESSION["psi_user"]["id"];
$userData = fnc_datos_usuario($conexion, $useId);
$perfil = $userData[0]["perfil"];
$sede = $userData[0]["sedeId"];
$s_sede = $_POST['s_sede'];
$s_seccion = $_POST['s_seccion'];
$filtro = $_POST['query'];

echo json_encode(fnc_buscar_docente($conexion, $filtro, $s_sede, $s_seccion));
