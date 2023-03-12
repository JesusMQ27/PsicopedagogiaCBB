<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();

$p_usuario = strip_tags(trim($_POST["psi_usuario"]));
$p_clave = strip_tags(trim($_POST["psi_contrasena"]));

$con_l = fnc_consulta_login($conexion, $p_usuario, $p_clave);
if (count($con_l) > 0) {
    $user_data = array();
    $_SESSION["psi_user"] = $con_l[0];
    echo "1";
} else {
    echo 'Usuario y/o contraseña incorrectos';
}
?>