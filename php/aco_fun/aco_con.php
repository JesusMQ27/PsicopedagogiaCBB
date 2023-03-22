<?php

function con_consulta_login($p_usua, $p_clave) {
    $sql = "SELECT usu_id as id,usu_num_doc as num_doc,usu_nombres, CONCAT(usu_paterno,' ',usu_materno) as apellidos "
            . " FROM tb_usuario WHERE usu_num_doc='$p_usua' AND usu_clave='$p_clave';";
    return $sql;
}

function con_datos_usuario($p_usuaId) {
    $sql = "SELECT a.perf_id as perfil,perf_nombre as perfil_nombre,a.sed_id as sedeId,sed_nombre as sede FROM tb_usuario a
        INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
        INNER JOIN tb_sede c ON a.sed_id=c.sed_id
        WHERE usu_id='$p_usuaId';";
    return $sql;
}

function con_menu_x_perfil($id) {
    $sql = "SELECT e.men_id as id,e.men_nombre as menu,men_icono as icono
FROM tb_usuario a
INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
INNER JOIN tb_menu_asigna c ON b.perf_id=c.perf_id
INNER JOIN tb_submenu d ON c.smen_id=d.smen_id
INNER JOIN tb_menu e ON d.men_id=e.men_id
WHERE b.perf_id=$id AND perf_estado=1 AND d.smen_estado=1 AND e.men_estado=1 and usu_estado=1 AND a.usu_id=1
GROUP BY e.men_id
ORDER BY e.men_id;";
    return $sql;
}

function con_submenu_x_menu($id) {
    $sql = "SELECT d.smen_id as id,d.smen_orden as orden,smen_nombre as submenu,smen_icono as icono,smen_ruta as ruta
FROM tb_usuario a
INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
INNER JOIN tb_menu_asigna c ON b.perf_id=c.perf_id
INNER JOIN tb_submenu d ON c.smen_id=d.smen_id
INNER JOIN tb_menu e ON d.men_id=e.men_id
WHERE e.men_id=$id AND perf_estado=1 AND d.smen_estado=1 AND e.men_estado=1 and usu_estado=1 AND a.usu_id=1 
ORDER BY e.men_id,smen_orden;";
    return $sql;
}

function con_consultar_submenu($id) {
    $sql = "SELECT smen_id as id,smen_orden as orden,smen_nombre as nombre,smen_ruta as ruta,smen_icono as icono FROM tb_submenu WHERE smen_id='$id';";
    return $sql;
}

function con_lista_usuarios($id) {
    $sql = "SELECT usu_id as id,a.perf_id as perfId,perf_nombre as perfilNombre,a.tipo_doc_id as tipoDocId,tipo_doc_nombre as tipoDoc,
usu_num_doc as numDoc,CONCAT(usu_paterno,' ',usu_materno,' ', usu_nombres) as fullnombre,
usu_paterno as paterno,usu_materno as materno, usu_nombres as nombres,
CONCAT(usu_nombres,' ',usu_paterno,' ', usu_materno) as nombrecompleto,
usu_sexo as sexoId,CASE usu_sexo WHEN 'M' THEN 'Masculino' WHEN 'F' THEN 'Femenino' END as sexo,
usu_correo as correo,usu_telefono as telefono,a.sed_id as sedeId, sed_nombre as sede,usu_estado as  estado,
usu_estado as estado, CASE usu_estado WHEN '1' THEN 'Activo' WHEN '0' THEN 'Inactivo' END as estado_nombre,usu_clave as clave,usu_token as token
FROM tb_usuario a
INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
INNER JOIN tb_documento_tipo c ON a.tipo_doc_id=c.tipo_doc_id
INNER JOIN tb_sede d ON a.sed_id=d.sed_id
WHERE 1=1 ";
    if ($id != "") {
        $sql .= " AND usu_id=$id";
    }
    $sql .= " ORDER BY 1;";
    return $sql;
}

function con_existe_correo($correo) {
    $sql = "SELECT usu_id,usu_num_doc,usu_correo,usu_paterno as paterno,usu_materno as materno,usu_nombres as nombres,"
            . " CONCAT(usu_nombres,' ',usu_paterno,' ', usu_materno) as nombrecompleto,"
            . " CONCAT(usu_paterno,' ',usu_materno,' ', usu_nombres) as fullnombre"
            . " FROM tb_usuario WHERE usu_correo='$correo' AND usu_estado=1;";
    return $sql;
}

function con_ultima_session($id) {
    $sql = "UPDATE tb_usuario SET usu_ultima_session=NOW(),usu_token_clave='',usu_solicito_clave='1' WHERE usu_id='$id';";
    return $sql;
}

function con_generate_token_contrasena($id, $token) {
    $sql = "UPDATE tb_usuario SET usu_token_clave='$token',usu_solicito_clave='1' WHERE usu_id='$id';";
    return $sql;
}

function con_verificar_token_pass($id, $token) {
    $sql = "SELECT * FROM tb_usuario WHERE usu_id='$id' AND usu_token_clave='$token';";
    return $sql;
}

function con_cambiar_pass($id, $token, $password) {
    $sql = "UPDATE tb_usuario SET usu_clave='$password',usu_token_clave='',usu_solicito_clave='' WHERE usu_id='$id' AND usu_token_clave='$token';";
    return $sql;
}

function con_cambiar_contrasena_usuario($id, $token, $password) {
    $sql = "UPDATE tb_usuario SET usu_clave='$password',usu_token_clave='',usu_solicito_clave='' WHERE usu_id='$id' AND usu_token='$token';";
    return $sql;
}

function con_lista_tipo_usuarios($id) {
    $sql = "SELECT perf_id as id,perf_nombre as nombre,perf_codigo as codigo,"
            . "CASE perf_estado WHEN 1 THEN 'Activo' WHEN 2 THEN 'Inactivo' ELSE '' END as estado FROM tb_perfil WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND perf_id='$id' ";
    }
    $sql .= " AND perf_estado=1;";
    return $sql;
}

function con_lista_tipo_documentos($id) {
    $sql = "SELECT tipo_doc_id as id,tipo_doc_nombre as nombre,tipo_cantidad as cantidad FROM tb_documento_tipo WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND tipo_doc_id='$id' ";
    }
    $sql .= " AND tipo_doc_estado=1;";
    return $sql;
}

function con_lista_sede($id) {
    $sql = "SELECT sed_id as id,sed_nombre as nombre FROM tb_sede WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND sed_id='$id' ";
    }
    $sql .= " AND sed_estado=1;";
    return $sql;
}

function con_validar_existe_nro_documento($tipoDoc, $numDoc) {
    $sql = "SELECT usu_id as id,usu_paterno as pate,usu_materno as mate,usu_nombres as nombres 
FROM tb_usuario WHERE tipo_doc_id=$tipoDoc AND usu_num_doc='$numDoc' AND usu_estado=1";
    return $sql;
}

function con_validar_exite_correo($correo) {
    $sql = "SELECT usu_id as id,usu_paterno as pate,usu_materno as mate,usu_nombres as nombres 
FROM tb_usuario WHERE usu_correo='$correo' AND usu_estado=1";
    return $sql;
}

function con_registrar_nuevo_usuario($tipo_usuario, $tipo_doc, $num_doc, $paterno, $materno, $nombres, $correo, $clave, $telefono, $sede, $sexo, $token) {
    $sql = "INSERT INTO tb_usuario(tipo_doc_id,usu_num_doc,usu_paterno,usu_materno,usu_nombres,usu_sexo,usu_correo,usu_clave,usu_telefono,perf_id,sed_id,usu_creado,usu_token,usu_estado)
               VALUES ('" . $tipo_doc . "','" . $num_doc . "','" . $paterno . "','" . $materno . "','" . $nombres . "','" .
            $sexo . "','" . $correo . "','" . $clave . "','" . $telefono . "','" . $tipo_usuario . "','" . $sede . "',NOW(),'" . $token . "','1')";
    return $sql;
}

function con_editar_usuario($id, $tipo_usuario, $tipo_doc, $num_doc, $paterno, $materno, $nombres, $correo, $telefono, $sede, $sexo, $estado) {
    $sql = "UPDATE tb_usuario SET perf_id='$tipo_usuario',tipo_doc_id='$tipo_doc',usu_num_doc='$num_doc',usu_paterno='$paterno',usu_materno='$materno',"
            . " usu_nombres='$nombres',usu_correo='$correo',usu_telefono='$telefono',sed_id='$sede',usu_sexo='$sexo',usu_estado='$estado' "
            . " WHERE usu_id='$id';";
    return $sql;
}

function con_eliminar_usuario($id) {
    $sql = "UPDATE tb_usuario SET usu_estado='0' "
            . " WHERE usu_id='$id';";
    return $sql;
}

function con_lista_menus($id, $estado) {
    $cadena = "";
    $sql = "SELECT men_id as id,men_codigo as codigo, men_nombre as nombre, men_icono as imagen,
CASE men_estado WHEN 1 THEN 'Activo' WHEN 0 THEN 'Inactivo' ELSE '' END AS estado, men_codigo as codigo, men_estado as estadoId 
FROM tb_menu WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND men_id='$id' ";
    }
    if ($estado !== "") {
        $cadena .= " AND men_estado=1;";
    } else {
        $cadena .= "  ";
    }
    $sql .= $cadena;
    return $sql;
}

function con_lista_iconos($id) {
    $sql = "SELECT icon_id as id,icon_nombre as nombre,icon_imagen as imagen,"
            . "CASE icon_estado WHEN 1 THEN 'Activo' WHEN 0 THEN 'Inactivo' END estado "
            . " FROM tb_iconos WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND icon_estado='1' ";
    } else {
        $sql .= "";
    }
    return $sql;
}

function con_registrar_menu($codigo, $descripcion, $imagen) {
    $sql = "INSERT INTO tb_menu(men_codigo,men_nombre,men_icono,men_estado)
               VALUES ('" . $codigo . "','" . $descripcion . "','" . $imagen . "','1')";
    return $sql;
}

function con_editar_menu($id, $codigo, $nombre, $icono, $estado) {
    $sdata = "";
    if ($codigo !== "") {
        $sdata = " men_codigo='$codigo',";
    } else {
        $sdata = "";
    }
    $sql = "UPDATE tb_menu SET $sdata men_nombre='$nombre',men_icono='$icono',men_estado='$estado' "
            . " WHERE men_id='$id';";
    return $sql;
}

function con_eliminar_menu($id) {
    $sql = "UPDATE tb_menu SET men_estado='0' "
            . " WHERE men_id='$id';";
    return $sql;
}
