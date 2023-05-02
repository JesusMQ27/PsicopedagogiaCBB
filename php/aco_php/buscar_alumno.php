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

$filtro = $_POST['query'];
$seccion_codi = '';
$lista_seccion = fnc_secciones_por_usuario($conexion, $useId);
if (count($lista_seccion) > 0) {
    $seccion_codi = trim($lista_seccion[0]["seccion"]) . '';
} else {
    $seccion_codi = '';
}
echo json_encode(fnc_buscar_alumno($conexion, $filtro, $sede, $seccion_codi));
