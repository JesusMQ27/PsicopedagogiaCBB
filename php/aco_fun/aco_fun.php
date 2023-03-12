<?php

require_once('aco_con.php');

function fnc_consulta_login($conexion, $p_usua, $p_clave) {
    $arreglo = array();
    $sql = con_consulta_login($p_usua, $p_clave);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_datos_usuario($conexion, $p_usuaId) {
    $arreglo = array();
    $sql = con_datos_usuario($p_usuaId);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_menu_x_perfil($conexion, $id) {
    $arreglo = array();
    $sql = con_menu_x_perfil($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_submenu_x_menu($conexion, $id) {
    $arreglo = array();
    $sql = con_submenu_x_menu($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_usuarios($conexion, $id) {
    $arreglo = array();
    $sql = con_lista_usuarios($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}


?>
