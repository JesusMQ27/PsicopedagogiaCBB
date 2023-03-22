<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';

$con = new DB(1111);
$conexion = $con->connect();

$p_id = strip_tags(trim($_POST["psi_id"]));
$p_token = strip_tags(trim($_POST["psi_token"]));
$p_nueva_contra = strip_tags(trim($_POST["psi_nueva_contra"]));
$p_confirmar_contra = strip_tags(trim($_POST["psi_confirmar_contra"]));

$nueva_contraseña = hash("sha256", md5($p_nueva_contra));
$cambiar_contra = fnc_cambiar_pass($conexion, $p_id, $p_token, $nueva_contraseña);
if($cambiar_contra){
    echo "1**Contraseña modificada.";
} else {
    echo "0**Erorr al modificar contraseña";
}
