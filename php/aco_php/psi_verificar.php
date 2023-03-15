<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();

$p_usuario = strip_tags(trim($_POST["psi_usuario"]));
$p_clave = strip_tags(trim($_POST["psi_contrasena"]));
$p_remember = strip_tags(trim($_POST["psi_remember"]));


$con_l = fnc_consulta_login($conexion, $p_usuario, hash("sha256", md5($p_clave)));

if (!isset($_POST["psi_remember"])) {
    $_SESSION["usuario"] = $_POST["psi_usuario"];
    $_SESSION["contrasena"] = $_POST["psi_contrasena"];
} else {
    $_SESSION["usuario"] = "";
    $_SESSION["contrasena"] = "";
}
if (count($con_l) > 0) {
    $user_data = array();
    fnc_ultima_session($conexion, $con_l[0]["id"]);
    $_SESSION["psi_user"] = $con_l[0];
    echo "1";
} else {
    echo 'Usuario y/o contraseña incorrectos';
}
?>