<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();

$p_usuario = strip_tags(trim($_POST["psi_usuario"]));
$p_clave = strip_tags(trim($_POST["psi_contrasena"]));
$p_remember = strip_tags(trim($_POST["psi_remember"]));

// Obtener fecha y hora
$current_time = time();
$current_date = date("Y-m-d H:i:s", $current_time);

// Agregar la expiracion del Cookie expiration para 1 mes
$cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month


$con_l = fnc_consulta_login($conexion, $p_usuario, hash("sha256", md5($p_clave)));

if ($p_remember == true) {
    setcookie("login_usuario", $p_usuario, $cookie_expiration_time, "/");
    setcookie("usuario_password", $p_clave, $cookie_expiration_time, "/");
}
//echo $_COOKIE["login_usuario"];

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