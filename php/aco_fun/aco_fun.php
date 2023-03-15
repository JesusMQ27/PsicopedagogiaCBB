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

function fnc_existe_correo($conexion, $correo) {
    $arreglo = array();
    $sql = con_existe_correo($correo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_generate_token() {
    $gen = md5(uniqid(mt_rand(), false));
    return $gen;
}

function fnc_ultima_session($conexion, $id) {
    $sql = con_ultima_session($id);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_generate_token_contrasena($conexion, $id) {
    $token = fnc_generate_token();
    $sql = con_generate_token_contrasena($id, $token);
    $conexion->exec($sql);
    return $token;
}

function fnc_generate_random_string($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function fnc_verificar_token_pass($conexion, $id, $token) {
    $arreglo = array();
    $sql = con_verificar_token_pass($id, $token);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_cambiar_pass($conexion, $id, $token, $password){
    $sql = con_cambiar_pass($id, $token, $password);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

?>
