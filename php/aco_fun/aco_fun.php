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

function fnc_contrasena_php_mailer() {
    $contrasena = "rotyziaemysifotl";
    return $contrasena;
}

function fnc_menu_x_perfil($conexion, $id, $usuario) {
    $arreglo = array();
    $sql = con_menu_x_perfil($id, $usuario);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_submenu_x_menu($conexion, $id, $perfil, $usuario) {
    $arreglo = array();
    $sql = con_submenu_x_menu($id, $perfil, $usuario);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_submenu_x_menu_perfil($conexion, $id) {
    $arreglo = array();
    $sql = con_submenu_x_menu_perfil($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_consultar_submenu($conexion, $id) {
    $arreglo = array();
    $sql = con_consultar_submenu($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_usuarios($conexion, $id, $sede) {
    $arreglo = array();
    $sql = con_lista_usuarios($id, $sede);
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
        $randomString .= $characters[rand(0, $charactersLength - 1)];
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

function fnc_cambiar_pass($conexion, $id, $token, $password) {
    $sql = con_cambiar_pass($id, $token, $password);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_cambiar_contrasena_usuario($conexion, $id, $token, $password) {
    $sql = con_cambiar_contrasena_usuario($id, $token, $password);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_tipo_usuarios($conexion, $id, $estado) {
    $arreglo = array();
    $sql = con_lista_tipo_usuarios($id, $estado);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_tipo_documentos($conexion, $id) {
    $arreglo = array();
    $sql = con_lista_tipo_documentos($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_sede($conexion, $id) {
    $arreglo = array();
    $sql = con_lista_sede($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_validar_cantidad_digitos($conexion, $tipoDoc) {
    $arreglo = array();
    $sql = con_validar_cantidad_digitos($tipoDoc);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_validar_existe_nro_documento($conexion, $tipoDoc, $numDoc) {
    $arreglo = array();
    $sql = con_validar_existe_nro_documento($tipoDoc, $numDoc);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_validar_exite_correo($conexion, $correo) {
    $arreglo = array();
    $sql = con_validar_exite_correo($correo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_nuevo_usuario($conexion, $tipo_usuario, $tipo_doc, $num_doc, $paterno, $materno, $nombres, $correo, $clave, $telefono, $sede, $sexo, $token) {
    $sql = con_registrar_nuevo_usuario($tipo_usuario, $tipo_doc, $num_doc, $paterno, $materno, $nombres, $correo, $clave, $telefono, $sede, $sexo, $token);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_editar_usuario($conexion, $id, $tipo_usuario, $tipo_doc, $num_doc, $paterno, $materno, $nombres, $correo, $telefono, $sede, $sexo, $estado) {
    $sql = con_editar_usuario($id, $tipo_usuario, $tipo_doc, $num_doc, $paterno, $materno, $nombres, $correo, $telefono, $sede, $sexo, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_usuario($conexion, $id) {
    $sql = con_eliminar_usuario($id);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_menus($conexion, $id, $estado) {
    $arreglo = array();
    $sql = con_lista_menus($id, $estado);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_sedes($conexion, $id, $estado) {
    $arreglo = array();
    $sql = con_lista_sedes($id, $estado);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_iconos($conexion, $id) {
    $arreglo = array();
    $sql = con_lista_iconos($id);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_menu($conexion, $codigo, $descripcion, $imagen, $carpeta) {
    $sql = con_registrar_menu($codigo, $descripcion, $imagen, $carpeta);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_editar_menu($conexion, $id, $codigo, $nombre, $icono, $carpeta, $estado) {
    $sql = con_editar_menu($id, $codigo, $nombre, $icono, $carpeta, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_menu($conexion, $id) {
    $sql = con_eliminar_menu($id);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_submenus($conexion, $id, $estado) {
    $arreglo = array();
    $sql = con_lista_submenus($id, $estado);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_consulta_ultimo_orden_menu($conexion, $menu) {
    $arreglo = array();
    $sql = con_consulta_ultimo_orden_menu($menu);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_submenu($conexion, $orden, $codigo, $descripcion, $menu, $imagen, $link) {
    $sql = con_registrar_submenu($orden, $codigo, $descripcion, $menu, $imagen, $link);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_editar_submenu($conexion, $id, $orden, $codigo, $nombre, $menu, $icono, $link, $estado) {
    $sql = con_editar_submenu($id, $orden, $codigo, $nombre, $menu, $icono, $link, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_submenu($conexion, $id) {
    $sql = con_eliminar_submenu($id);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_registrar_perfil($conexion, $codigo, $descripcion) {
    $sql = con_registrar_perfil($codigo, $descripcion);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_accesos_perfil($conexion, $lista) {
    $sql = con_registrar_accesos_perfil($lista);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_menu_asigna($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_menu_asigna($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_editar_perfil($conexion, $id, $nombre, $estado) {
    $sql = con_editar_perfil($id, $nombre, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_editar_accesos_perfil($conexion, $lista, $codigo) {
    $sql = con_editar_accesos_perfil($lista, $codigo);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_editar_accesos_perfil_todos($conexion, $lista, $codigo) {
    $sql = con_editar_accesos_perfil_todos($lista, $codigo);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_perfil($conexion, $id) {
    $sql = con_eliminar_perfil($id);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_drop_tabla_tmp_carga_alumnos($conexion, $codigo) {
    $sql = con_drop_tabla_tmp_carga_alumnos($codigo);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fun_crear_tabla_tmp_carga_alumnos($conexion, $codigo) {
    $sql = con_crear_tabla_tmp_carga_alumnos($codigo);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function func_inserta_data_tabla_tmp_carga_alumnos($conexion, $tabla, $data) {
    $sql = con_inserta_data_tabla_tmp_carga_alumnos($tabla, $data);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function func_lista_data_tabla_tmp_carga_alumnos($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_data_tabla_tmp_carga_alumnos($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_data_validos_tabla_tmp_carga_alumnos($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_data_validos_tabla_tmp_carga_alumnos($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_grupo($conexion, $cadena) {
    $sql = con_registrar_grupo($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_data_tmp_a_alumno($conexion, $cadena) {
    $sql = con_registrar_data_tmp_a_alumno($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_data_tmp_a_apoderado($conexion, $cadena) {
    $sql = con_registrar_data_tmp_a_apoderado($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_matricula_alumno($conexion, $cadena) {
    $sql = con_registrar_matricula_alumno($cadena);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_grupos($conexion, $id, $estado) {
    $arreglo = array();
    $sql = con_lista_grupos($id, $estado);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function func_lista_grupo_detalle($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_grupo_detalle($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_eliminar_grupo($conexion, $id, $estado) {
    $sql = con_eliminar_grupo($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_alumno_grupo($conexion, $id, $estado) {
    $sql = con_eliminar_alumno_grupo($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_matricula_grupo($conexion, $id, $estado) {
    $sql = con_eliminar_matricula_grupo($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_grupos_usuarios($conexion, $id, $estado) {
    $arreglo = array();
    $sql = con_lista_grupos_usuarios($id, $estado);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_fecha_a_YY_MM_DD($fecha) {
    $nueva_fecha = "";
    $array = explode("/", $fecha);
    if (count($array) > 1) {
        $nueva_fecha = $array[2] . "-" . $array[1] . "-" . $array[0];
    }
    return $nueva_fecha;
}

function fnc_drop_tabla_tmp_carga_usuarios($conexion, $codigo) {
    $sql = con_drop_tabla_tmp_carga_usuarios($codigo);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fun_crear_tabla_tmp_carga_usuarios($conexion, $codigo) {
    $sql = con_crear_tabla_tmp_carga_usuarios($codigo);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function func_inserta_data_tabla_tmp_carga_usuarios($conexion, $tabla, $data) {
    $sql = con_inserta_data_tabla_tmp_carga_usuarios($tabla, $data);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function func_lista_data_tabla_tmp_carga_usuarios($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_data_tabla_tmp_carga_usuarios($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_grupo_usuario($conexion, $cadena) {
    $sql = con_registrar_grupo_usuario($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_data_tmp_a_usuario($conexion, $cadena) {
    $sql = con_registrar_data_tmp_a_usuario($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_usuario_dictado($conexion, $cadena) {
    $sql = con_registrar_usuario_dictado($cadena);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function func_lista_grupo_detalle_usuarios($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_grupo_detalle_usuarios($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_eliminar_grupo_usuario($conexion, $id, $estado) {
    $sql = con_eliminar_grupo_usuario($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_usuario_grupo($conexion, $id, $estado) {
    $sql = con_eliminar_usuario_grupo($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function func_lista_data_tabla_tmp_carga_usuarios_correos($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_data_tabla_tmp_carga_usuarios_correos($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_sedes_x_perfil($conexion, $p_usuaId) {
    $arreglo = array();
    $sql = con_sedes_x_perfil($p_usuaId);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_fechas_rango($conexion) {
    $arreglo = array();
    $sql = con_fechas_rango();
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_solicitudes($conexion, $sede, $fechaInicio, $fechaFin, $codigoUsuario, $privacidad) {
    $arreglo = array();
    $sql = con_lista_solicitudes($sede, $fechaInicio, $fechaFin, $codigoUsuario, $privacidad);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_tipo_entrevistas($conexion, $codi) {
    $arreglo = array();
    $sql = con_lista_tipo_entrevistas($codi);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_categorias($conexion, $codi) {
    $arreglo = array();
    $sql = con_lista_categorias($codi);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_subcategorias($conexion, $categoria, $codi) {
    $arreglo = array();
    $sql = con_lista_subcategorias($categoria, $codi);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_motivos($conexion, $motivo) {
    $arreglo = array();
    $sql = con_lista_motivos($motivo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_secciones_por_usuario($conexion, $p_usuaId) {
    $arreglo = array();
    $sql = con_secciones_por_usuario($p_usuaId);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_buscar_alumno($conexion, $filtro, $sede, $seccion) {
    $reporte = array();
    $sql = con_buscar_alumno($filtro, $sede, $seccion);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        $reporte_row["value"] = $data["value"];
        $reporte_row["label"] = $data["text"];
        $reporte_row["dni"] = $data["dni"];
        $reporte_row["nombres"] = $data["nombres"];
        array_push($reporte, $reporte_row);
    }
    return $reporte;
}

function fnc_alumno_matricula_detalle($conexion, $codigo) {
    $arreglo = array();
    $sql = con_alumno_matricula_detalle($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_apoderados_de_alumno($conexion, $alumno, $codigo) {
    $arreglo = array();
    $sql = con_lista_apoderados_de_alumno($alumno, $codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_tipos_apoderados($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_tipos_apoderados($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_validar_dni_apoderado($conexion, $codigoAlumno, $codigoApoderado, $numero) {
    $arreglo = array();
    $sql = con_validar_dni_apoderado($codigoAlumno, $codigoApoderado, $numero);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_apoderado_del_alumno($conexion, $codigo) {
    $arreglo = array();
    $sql = con_apoderado_del_alumno($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_apoderado($conexion, $cadena) {
    $sql = con_registrar_apoderado($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_apoderado_historico($conexion, $cadena) {
    $sql = con_registrar_apoderado_historico($cadena);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_modificar_apoderado($conexion, $codigo, $dni, $correo, $telefono) {
    $sql = con_modificar_apoderado($codigo, $dni, $correo, $telefono);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_registrar_solicitud_estudiante($conexion, $cadena) {
    $sql = con_registrar_solicitud_estudiante($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_solicitud_firmas($conexion, $cadena) {
    $sql = con_registrar_solicitud_firmas($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_solicitud_alumno($conexion, $codigo) {
    $arreglo = array();
    $sql = con_solicitud_alumno($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_eliminar_solicitud_alumno($conexion, $id, $estado) {
    $sql = con_eliminar_solicitud_alumno($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_sub_solicitud_alumno($conexion, $id, $estado) {
    $sql = con_eliminar_sub_solicitud_alumno($id, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function convertirFecha($fecha) {
    $date = explode("/", $fecha);
    $rfecha = $date[2] . "-" . $date[1] . "-" . $date[0];
    return $rfecha;
}

function fnc_buscar_semaforo_docentes($conexion, $sede, $fecha_ini, $fecha_fin, $semaforo) {
    $arreglo = array();
    $sql = con_buscar_semaforo_docentes($sede, $fecha_ini, $fecha_fin, $semaforo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_obtener_codigo_entrevista($conexion, $codi) {
    $arreglo = array();
    $sql = con_obtener_codigo_entrevista($codi);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_registrar_sub_solicitud_estudiante($conexion, $cadena) {
    $sql = con_registrar_sub_solicitud_estudiante($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_registrar_sub_solicitud_firmas($conexion, $cadena) {
    $sql = con_registrar_sub_solicitud_firmas($cadena);
    $stmt = $conexion->exec($sql);
    return $conexion->lastInsertId();
}

function fnc_listar_todas_solicitudes_x_entrevista($conexion, $codi, $entre, $sub, $perfil, $sede) {
    $arreglo = array();
    $privacidad = "";
    if ($sede == "1" && ($perfil == "1" || $perfil == "5")) {
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
    }
    $sql = con_listar_todas_solicitudes_x_entrevista($codi, $entre, $sub, $privacidad);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_obtener_solicitud_x_codigo($conexion, $tipo, $codi) {
    $arreglo = array();
    $sql = con_obtener_solicitud_x_codigo($tipo, $codi);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_obtener_firma_entrevista($conexion, $codigo, $tipo) {
    $arreglo = array();
    $sql = con_obtener_firma_entrevista($codigo, $tipo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_obtener_firma_subentrevista($conexion, $codigo, $tipo) {
    $arreglo = array();
    $sql = con_obtener_firma_subentrevista($codigo, $tipo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_modificar_solicitud_entrevista($conexion, $codigo, $matricual, $usuario, $solicitud_tipo, $s_subcategoria, $s_motivo, $fecha, $sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $_privacidad, $estado) {
    $sql = con_modificar_solicitud_entrevista($codigo, $matricual, $usuario, $solicitud_tipo, $s_subcategoria, $s_motivo, $fecha, $sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $_privacidad, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_modificar_solicitud_entrevista_firmas($conexion, $codigo, $tipo, $matricual, $usuario, $s_apoderado, $imagen, $fecha, $estado) {
    $sql = con_modificar_solicitud_entrevista_firmas($codigo, $tipo, $matricual, $usuario, $s_apoderado, $imagen, $fecha, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_modificar_solicitud_sub_entrevista($conexion, $codigo, $matricual, $usuario, $solicitud_tipo, $s_subcategoria, $s_motivo, $fecha, $sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $_privacidad, $estado) {
    $sql = con_modificar_solicitud_sub_entrevista($codigo, $matricual, $usuario, $solicitud_tipo, $s_subcategoria, $s_motivo, $fecha, $sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $_privacidad, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_modificar_solicitud_sub_entrevista_firmas($conexion, $codigo, $tipo, $matricual, $usuario, $s_apoderado, $imagen, $fecha, $estado) {
    $sql = con_modificar_solicitud_sub_entrevista_firmas($codigo, $tipo, $matricual, $usuario, $s_apoderado, $imagen, $fecha, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_colores() {
    $array = array('#4f5962', '#fd7e14', '#28a745', '#007bff', '#6f42c1', '#001f3f', '#6610f2', '#3c8dbc', '#3d9970');
    return $array;
}

function fnc_registrar_sede($conexion, $cadena) {
    $sql = con_registrar_sede($cadena);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_editar_sede($conexion, $id, $nombre, $descripcion, $icono, $estado) {
    $sql = con_editar_sede($id, $nombre, $descripcion, $icono, $estado);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_sede($conexion, $id, $anio) {
    $sql = con_eliminar_sede($id, $anio);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_eliminar_matriculas_sede($conexion, $id, $anio) {
    $sql = con_eliminar_matriculas_sede($id, $anio);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_modificar_matriculas_sedes($conexion, $id, $anio) {
    $sql = con_modificar_matriculas_sedes($id, $anio);
    $stmt = $conexion->exec($sql);
    return $stmt;
}

function fnc_lista_anios($conexion) {
    $arreglo = array();
    $sql = con_lista_anios();
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_fecha_actual($conexion) {
    $arreglo = array();
    $sql = con_fecha_actual();
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_correos_estudiantes_y_apoderados_entrevistas($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_correos_estudiantes_y_apoderados_entrevistas($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

function fnc_lista_correos_estudiantes_y_apoderados_sub_entrevistas($conexion, $codigo) {
    $arreglo = array();
    $sql = con_lista_correos_estudiantes_y_apoderados_sub_entrevistas($codigo);
    $stmt = $conexion->query($sql);
    foreach ($stmt as $data) {
        array_push($arreglo, $data);
    }
    return $arreglo;
}

?>
