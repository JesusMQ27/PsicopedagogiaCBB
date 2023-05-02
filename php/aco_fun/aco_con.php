<?php

function con_consulta_login($p_usua, $p_clave) {
    $sql = "SELECT usu_id as id,usu_num_doc as num_doc,usu_nombres, CONCAT(usu_paterno,' ',usu_materno) as apellidos "
            . " FROM tb_usuario WHERE usu_num_doc='$p_usua' AND usu_clave='$p_clave';";
    return $sql;
}

function con_datos_usuario($p_usuaId) {
    $sql = "SELECT a.perf_id as perfil,perf_nombre as perfil_nombre,a.sed_id as sedeId,sed_nombre as sede,
        usu_id as usuCodi,usu_num_doc as usuarioDni,concat(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuariodata       
        FROM tb_usuario a
        INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
        LEFT JOIN tb_sede c ON a.sed_id=c.sed_id
        WHERE usu_id='$p_usuaId';";
    return $sql;
}

function con_menu_x_perfil($id, $usua) {
    $sql = "SELECT e.men_id as id,e.men_nombre as menu,men_icono as icono
FROM tb_usuario a
INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
INNER JOIN tb_menu_asigna c ON b.perf_id=c.perf_id
INNER JOIN tb_submenu d ON c.smen_id=d.smen_id
INNER JOIN tb_menu e ON d.men_id=e.men_id
WHERE b.perf_id=$id AND perf_estado=1 AND d.smen_estado=1 AND e.men_estado=1 and usu_estado=1 AND a.usu_id=$usua 
GROUP BY e.men_id
ORDER BY e.men_id;";
    return $sql;
}

function con_submenu_x_menu($id, $perfil, $usuario) {
    $sql = "SELECT d.smen_id as id,d.smen_orden as orden,smen_nombre as submenu,smen_icono as icono,smen_ruta as ruta
FROM tb_usuario a
INNER JOIN tb_perfil b ON a.perf_id=b.perf_id
INNER JOIN tb_menu_asigna c ON b.perf_id=c.perf_id
INNER JOIN tb_submenu d ON c.smen_id=d.smen_id
INNER JOIN tb_menu e ON d.men_id=e.men_id
WHERE e.men_id=$id AND perf_estado=1 AND d.smen_estado=1 AND e.men_estado=1 AND a.perf_id=$perfil AND a.usu_id=$usuario AND men_asi_estado=1 
ORDER BY e.men_id,smen_orden;";
    return $sql;
}

function con_submenu_x_menu_perfil($id) {
    $sql = "SELECT d.smen_id as id,d.smen_orden as orden,smen_nombre as submenu,smen_icono as icono,smen_ruta as ruta
FROM tb_submenu d 
INNER JOIN tb_menu e ON d.men_id=e.men_id
WHERE e.men_id=$id AND d.smen_estado=1 AND e.men_estado=1
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

function con_lista_tipo_usuarios($id, $estado) {
    $sql = "SELECT perf_id as id,perf_nombre as nombre,perf_codigo as codigo,"
            . "CASE perf_estado WHEN 1 THEN 'Activo' WHEN 0 THEN 'Inactivo' ELSE '' END as estado, "
            . " perf_estado as estadoId FROM tb_perfil WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND perf_id='$id' ";
    } else {
        $sql .= "";
    }
    if ($estado !== "") {
        $slq .= " AND perf_estado='$estado' ";
    } else {
        $sql .= "";
    }
    $sql .= " ;";
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
CASE men_estado WHEN 1 THEN 'Activo' WHEN 0 THEN 'Inactivo' ELSE '' END AS estado, men_codigo as codigo,men_carpeta as carpeta,men_estado as estadoId 
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

function con_registrar_menu($codigo, $descripcion, $imagen, $carpeta) {
    $sql = "INSERT INTO tb_menu(men_codigo,men_nombre,men_icono,men_carpeta,men_estado)
               VALUES ('" . $codigo . "','" . $descripcion . "','" . $imagen . "','" . $carpeta . "','1')";
    return $sql;
}

function con_editar_menu($id, $codigo, $nombre, $icono, $carpeta, $estado) {
    $sdata = "";
    if ($codigo !== "") {
        $sdata = " men_codigo='$codigo',";
    } else {
        $sdata = "";
    }
    $sql = "UPDATE tb_menu SET $sdata men_nombre='$nombre',men_icono='$icono',men_carpeta='$carpeta',men_estado='$estado' "
            . " WHERE men_id='$id';";
    return $sql;
}

function con_eliminar_menu($id) {
    $sql = "UPDATE tb_menu SET men_estado='0' "
            . " WHERE men_id='$id';";
    return $sql;
}

function con_lista_submenus($id, $estado) {
    $cadena = "";
    $sql = "SELECT smen_id as id,smen_orden as orden,smen_codigo as codigo, smen_nombre as nombre, smen_icono as imagen,
CASE smen_estado WHEN 1 THEN 'Activo' WHEN 0 THEN 'Inactivo' ELSE '' END AS estado, smen_estado as estadoId, men_nombre as menu,a.men_id as menuId,
smen_ruta as link
FROM tb_submenu a
INNER JOIN tb_menu b on a.men_id=b.men_id
WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND smen_id='$id' ";
    }
    if ($estado !== "") {
        $cadena .= " AND smen_estado=1;";
    } else {
        $cadena .= "  ";
    }
    $sql .= $cadena;
    return $sql;
}

function con_consulta_ultimo_orden_menu($menu) {
    $sql = "SELECT COUNT(*) AS cantidad FROM tb_submenu WHERE men_id='$menu';";
    return $sql;
}

function con_registrar_submenu($orden, $codigo, $descripcion, $menu, $imagen, $link) {
    $sql = "INSERT INTO tb_submenu(smen_orden,smen_codigo,smen_nombre,men_id,smen_icono,smen_ruta,smen_estado)
               VALUES ('" . $orden . "','" . $codigo . "','" . $descripcion . "','" . $menu . "','" . $imagen . "','" . $link . "','1')";
    return $sql;
}

function con_editar_submenu($id, $orden, $codigo, $nombre, $menu, $icono, $link, $estado) {
    $sdata = "";
    if ($codigo !== "") {
        $sdata = " smen_codigo='$codigo',";
    } else {
        $sdata = "";
    }
    $sql = "UPDATE tb_submenu SET $sdata smen_nombre='$nombre',men_id='$menu',smen_icono='$icono',smen_ruta='$link',smen_estado='$estado' "
            . " WHERE smen_id='$id';";
    return $sql;
}

function con_eliminar_submenu($id) {
    $sql = "UPDATE tb_submenu SET smen_estado='0' "
            . " WHERE smen_id='$id';";
    return $sql;
}

function con_registrar_perfil($codigo, $descripcion) {
    $sql = "INSERT INTO tb_perfil(perf_codigo,perf_nombre,perf_estado)
               VALUES ('" . $codigo . "','" . $descripcion . "','1')";
    return $sql;
}

function con_registrar_accesos_perfil($lista) {
    $sql = "INSERT INTO tb_menu_asigna(perf_id,smen_id,men_asi_estado)
               VALUES $lista";
    return $sql;
}

function con_lista_menu_asigna($codigo) {
    $sql = "SELECT men_asi_id as id,smen_id as menId,men_asi_estado as estado "
            . "FROM tb_menu_asigna WHERE perf_id='$codigo' AND men_asi_estado IN (0,1);";
    return $sql;
}

function con_editar_perfil($id, $nombre, $estado) {
    $sdata = "";
    $sql = "UPDATE tb_perfil SET $sdata perf_nombre='$nombre',perf_estado='$estado' "
            . " WHERE perf_id='$id';";
    return $sql;
}

function con_editar_accesos_perfil($lista, $codigo) {
    $sql = "UPDATE tb_menu_asigna SET men_asi_estado='1'
               WHERE men_asi_id IN ($lista) AND perf_id='$codigo';";
    return $sql;
}

function con_editar_accesos_perfil_todos($lista, $codigo) {
    $str_list = "";
    $sql = "UPDATE tb_menu_asigna SET men_asi_estado='0'";
    if ($lista !== "") {
        $str_list = " AND men_asi_id NOT IN ($lista) ";
    } else {
        $str_list = "";
    }
    $sql .= " WHERE 1=1 " . $str_list . " AND perf_id='$codigo';";
    return $sql;
}

function con_eliminar_perfil($id) {
    $sql = "UPDATE tb_perfil SET perf_estado='0' "
            . " WHERE perf_id='$id';";
    return $sql;
}

function con_drop_tabla_tmp_carga_alumnos($codigo) {
    $sql = "DROP TABLE IF EXISTS tmp_cbb_carga_alumnos_" . $codigo . ";";
    return $sql;
}

function con_crear_tabla_tmp_carga_alumnos($codigo) {
    $sql = "CREATE TABLE `tmp_cbb_carga_alumnos_" . $codigo . "`  (
        `id` int(11) primary key not null AUTO_INCREMENT,
        `car_num` varchar(6) NULL DEFAULT NULL COMMENT 'Numeracion',
        `car_tip_alu` char(3) COMMENT 'Tipo de Alumno(N:Nuevos; R:Ratifican matricula; NR: Reincorporacion)',
        `car_car_gene` double(10,2) NULL DEFAULT NULL COMMENT 'Cargos generados',
        `car_mon_pag` double(10,2) NULL DEFAULT NULL COMMENT 'Monto pagado',
        `car_mon_pen` double(10,2) NULL DEFAULT NULL COMMENT 'Monto pendiente',
        `car_online` varchar(8) NULL DEFAULT NULL COMMENT 'Número del comprobante',
        `car_cod_alumno` varchar(10) NULL DEFAULT NULL COMMENT 'Codigo del estudiante',
        `car_nombres` varchar(60) NULL DEFAULT NULL COMMENT 'Nombre del estudiante',
        `car_grado` char(5) NULL DEFAULT NULL COMMENT 'Grado',
        `car_seccion` char(5) NULL DEFAULT NULL COMMENT 'Seccion',
        `car_sede` varchar(60) NULL DEFAULT NULL COMMENT 'Sede',
        `car_dni` varchar(15) NULL DEFAULT NULL COMMENT 'DNI estudiante',
        `car_nom_padre` varchar(60) NULL DEFAULT NULL COMMENT 'Nombre padre o primer tutor',
        `car_cor_padre` varchar(40) NULL DEFAULT NULL COMMENT 'Correo padre o primer tutor',
        `car_nom_madre` varchar(60) NULL DEFAULT NULL COMMENT 'Nombre madre o primer tutor',
        `car_cor_madre` varchar(40) NULL DEFAULT NULL COMMENT 'Correo madre o primer tutor',
        `car_cel_padre` varchar(9) NULL DEFAULT NULL COMMENT 'Celular padre',
        `car_cel_madre` varchar(9) NULL DEFAULT NULL COMMENT 'Celular madre',
        `car_apo_dir` longtext NULL DEFAULT NULL COMMENT 'Direccion del apoderado',
        `car_dis_apo` varchar(9) NULL DEFAULT NULL COMMENT 'Distrito del apoderado',
        `car_orden` varchar(6) NULL DEFAULT NULL COMMENT 'Orden',
        `car_cor_alu` varchar(60) NULL DEFAULT NULL COMMENT 'Correo estudiante',
        `car_estado` char(1) NULL DEFAULT NULL COMMENT 'Estado',
        INDEX(car_cod_alumno),
        INDEX(car_grado),
        INDEX(car_seccion),        
        INDEX(car_sede),
        INDEX(car_dni)
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_spanish_ci ROW_FORMAT = Dynamic;
        SET FOREIGN_KEY_CHECKS = 1;";
    return $sql;
}

function con_inserta_data_tabla_tmp_carga_alumnos($tabla, $data) {
    $sql = "INSERT INTO $tabla(
        car_num,
        car_tip_alu,
        car_car_gene,
        car_mon_pag,
        car_mon_pen,
        car_online,
        car_cod_alumno,
        car_nombres,
        car_grado,
        car_seccion,
        car_sede,
        car_dni,
        car_nom_padre,
        car_cor_padre,
        car_nom_madre,
        car_cor_madre,
        car_cel_padre,
        car_cel_madre,
        car_apo_dir,
        car_dis_apo,
        car_cor_alu,
        car_estado)
     VALUES $data";
    return $sql;
}

function con_lista_data_tabla_tmp_carga_alumnos($codigo) {
    $sql = "SELECT car_num as num,
        CASE car_tip_alu WHEN 'N' THEN 'Nueva matricula' 
        WHEN 'R' THEN 'Ratifican matricula' WHEN 'NR' THEN 'Reincorporacion' END as tip_alu,
        car_car_gene as car_gene,
        car_mon_pag as mon_pag,
        car_mon_pen as mon_pen,
        car_online as online,
        car_cod_alumno as cod_alumno,
        car_nombres as nombres,
        car_grado as grado,
        car_seccion as seccion,
        REPLACE(car_sede,'Bertolt Brecht','') as sede,
        car_dni as dni,
        car_nom_padre as nom_padre,
        car_cor_padre as cor_padre,
        car_nom_madre as nom_madre,
        car_cor_madre as cor_madre,
        car_cel_padre as cel_padre,
        car_cel_madre as cel_madre,
        car_apo_dir as apo_dir,
        car_dis_apo as dis_apo,
        car_cor_alu as cor_alu,
        car_estado as estado ,
        obtenerSedeId(car_sede) as registrar_sed_id,
	obtenerSeccionId(car_seccion) as registrar_sec_id,
	obtenerAlumnoId(car_dni) as actual_alu_id,
	validarMatricula(car_dni,car_seccion,car_sede) as valida_matricula
        FROM tmp_cbb_carga_alumnos_$codigo ORDER BY id;";
    return $sql;
}

function con_lista_data_validos_tabla_tmp_carga_alumnos($codigo) {
    $sql = "SELECT * FROM (
	SELECT id,
	car_num as num,
        car_tip_alu as tip_alu,
        car_car_gene as car_gene,
        car_mon_pag as mon_pag,
        car_mon_pen as mon_pen,
        car_online as online,
        car_cod_alumno as cod_alumno,
        car_nombres as nombres,
        car_grado as grado,
        car_seccion as seccion,
        car_sede as sede,
        car_dni as dni,
        car_nom_padre as nom_padre,
        car_cor_padre as cor_padre,
        car_nom_madre as nom_madre,
        car_cor_madre as cor_madre,
        car_cel_padre as cel_padre,
        car_cel_madre as cel_madre,
        car_apo_dir as apo_dir,
        car_dis_apo as dis_apo,
        car_cor_alu as cor_alu,
	obtenerSedeId(car_sede) as registrar_sed_id,
	obtenerSeccionId(car_seccion) as registrar_sec_id,
	obtenerAlumnoId(car_dni) as actual_alu_id,
	validarMatricula(car_dni,car_seccion,car_sede) as valida_matricula,
        car_estado as estado FROM tmp_cbb_carga_alumnos_$codigo ORDER BY id ) AS p1 WHERE p1.valida_matricula=0;";
    return $sql;
}

function con_registrar_grupo($cadena) {
    $sql = "INSERT INTO tb_grupo_matricula(
        grup_codigo,
        usu_id,
        grup_fecha_registro,
        grup_estado)
     VALUES $cadena";
    return $sql;
}

function con_registrar_data_tmp_a_alumno($cadena) {
    $sql = "INSERT INTO tb_alumno(
        alu_dni,
        alu_nombres,
        alu_codigo,
        alu_correo,
        alu_estado)
     VALUES $cadena";
    return $sql;
}

function con_registrar_data_tmp_a_apoderado($cadena) {
    $sql = "INSERT INTO tb_alumno_apoderado(
        alu_id,
        tip_id,
        apo_nombres,
        apo_correo,
        apo_telefono,
        apo_direccion,
        apo_distrito,
        apo_estado)
     VALUES $cadena";
    return $sql;
}

function con_registrar_matricula_alumno($cadena) {
    $sql = "INSERT INTO tb_matricula(
        alu_id,
        sed_id,
        sec_id,
        mat_tipo,
        mat_fech_regi,
        grup_id,
        mat_estado)
     VALUES $cadena";
    return $sql;
}

function con_lista_grupos($id, $estado) {
    $sql = "SELECT grup_id as id,grup_codigo as codigo,CONCAT(usu_nombres,' ',usu_paterno,' ',usu_materno) AS usuario,
	grup_fecha_registro as fechaRegistro,usu_correo as correo,CASE grup_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
	FROM tb_grupo_matricula a
	INNER JOIN tb_usuario b ON a.usu_id=b.usu_id
	WHERE 1=1 ";
    if ($id != "") {
        $sql .= " AND grup_id=$id ";
    }
    if ($estado != "") {
        $sql .= " AND grup_estado='$estado' ";
    }
    $sql .= " ORDER BY 1;";
    return $sql;
}

function con_lista_grupo_detalle($codigo) {
    $sql = "SELECT c.alu_codigo as codigo,alu_nombres as alumno,alu_dni as dni,
	CASE mat_tipo WHEN 'N' THEN 'Nueva matricula' WHEN 'R' THEN 'Ratifican matricula' 
	WHEN 'NR' THEN 'Reincorporacion' END as tipo,gra_codigo as grado,sec_codigo as seccion,
	REPLACE(sed_descripcion,'Bertolt Brecht ','') as sede, 
	CASE mat_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END estado 
	FROM tb_grupo_matricula a
        INNER JOIN tb_matricula b ON a.grup_id=b.grup_id	
	INNER JOIN tb_alumno c ON b.alu_id=c.alu_id 
	INNER JOIN tb_sede d ON b.sed_id=d.sed_id
	INNER JOIN tb_seccion e ON b.sec_id=e.sec_id
	INNER JOIN tb_grado f ON e.gra_id=f.gra_id
	WHERE a.grup_id='$codigo';";
    return $sql;
}

function con_eliminar_grupo($id, $estado) {
    $sql = "UPDATE tb_grupo_matricula SET grup_estado='$estado' "
            . " WHERE grup_id='$id';";
    return $sql;
}

function con_eliminar_alumno_grupo($id, $estado) {
    $sql = "UPDATE tb_grupo_matricula AS a "
            . "INNER JOIN tb_matricula b ON a.grup_id=b.grup_id "
            . "INNER JOIN tb_alumno c ON b.alu_id=c.alu_id "
            . " SET alu_estado='$estado' "
            . " WHERE a.grup_id='$id';";
    return $sql;
}

function con_eliminar_matricula_grupo($id, $estado) {
    $sql = "UPDATE tb_grupo_matricula AS a "
            . "INNER JOIN tb_matricula b ON a.grup_id=b.grup_id "
            . " SET mat_estado='$estado' "
            . " WHERE a.grup_id='$id';";
    return $sql;
}

function con_lista_grupos_usuarios($id, $estado) {
    $sql = "SELECT grup_id as id,grup_codigo as codigo,CONCAT(usu_nombres,' ',usu_paterno,' ',usu_materno) AS usuario,
	grup_fecha_registro as fechaRegistro,usu_correo as correo,CASE grup_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
	FROM tb_grupo_usuarios a
	INNER JOIN tb_usuario b ON a.usu_id=b.usu_id
	WHERE 1=1 ";
    if ($id != "") {
        $sql .= " AND grup_id=$id ";
    }
    if ($estado != "") {
        $sql .= " AND grup_estado='$estado' ";
    }
    $sql .= " ORDER BY 1;";
    return $sql;
}

function con_drop_tabla_tmp_carga_usuarios($codigo) {
    $sql = "DROP TABLE IF EXISTS tmp_cbb_carga_usuarios_" . $codigo . ";";
    return $sql;
}

function con_crear_tabla_tmp_carga_usuarios($codigo) {
    $sql = "CREATE TABLE `tmp_cbb_carga_usuarios_" . $codigo . "`  (
        `id` int(11) primary key not null AUTO_INCREMENT,
        `usu_num` varchar(6) NULL DEFAULT NULL COMMENT 'Numeracion',
        `usu_tip_usu` varchar(30) NULL DEFAULT NULL COMMENT 'Perfil del Usuario',
        `usu_tip_doc` varchar(30) NULL DEFAULT NULL COMMENT 'Tipo de documento',
        `usu_num_doc` varchar(12) NULL DEFAULT NULL COMMENT 'Nro de documento',
        `usu_ape_pat` varchar(30) NULL DEFAULT NULL COMMENT 'Apellido paterno',
        `usu_ape_mat` varchar(30) NULL DEFAULT NULL COMMENT 'Apellido materno',
        `usu_nom` varchar(30) NULL DEFAULT NULL COMMENT 'Nombres',
        `usu_cor` varchar(60) NULL DEFAULT NULL COMMENT 'Correo',
        `usu_niv` varchar(12) NULL DEFAULT NULL COMMENT 'Nivel',
        `usu_pla` varchar(25) NULL DEFAULT NULL COMMENT 'Plana',
        `usu_fec_ing` DATE NULL DEFAULT NULL COMMENT 'Fecha Ingreso',
        `usu_estado` char(1) NULL DEFAULT NULL COMMENT 'Estado',
        INDEX(usu_tip_usu),
        INDEX(usu_tip_doc),
        INDEX(usu_num_doc),        
        INDEX(usu_niv),
        INDEX(usu_pla)
        ) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_spanish_ci ROW_FORMAT = Dynamic;
        SET FOREIGN_KEY_CHECKS = 1;";
    return $sql;
}

function con_inserta_data_tabla_tmp_carga_usuarios($tabla, $data) {
    $sql = "INSERT INTO $tabla(
        usu_num,
        usu_tip_usu,
        usu_tip_doc,
        usu_num_doc,
        usu_ape_pat,
        usu_ape_mat,
        usu_nom,
        usu_cor,
        usu_niv,
        usu_pla,
        usu_fec_ing,
        usu_estado)
     VALUES $data";
    return $sql;
}

function con_lista_data_tabla_tmp_carga_usuarios($codigo) {
    $sql = "SELECT usu_num AS num,
usu_tip_usu AS perfil,
usu_tip_doc AS tipo_documento,
usu_num_doc AS dni,
usu_ape_pat AS paterno,
usu_ape_mat AS materno,
usu_nom AS nombres,
CONCAT(usu_ape_pat,' ',usu_ape_mat,' ',usu_nom) as usuario,
usu_cor AS correo,
usu_niv AS nivel,
usu_pla AS plana,
usu_fec_ing AS fecha_ingreso,
usu_estado as estado,
obtenerPerfilId(usu_tip_usu) AS perfId,
obtenerTipoDocumentoId(usu_tip_doc) AS tipDocId,
obtenerNivelId(usu_niv) AS registrar_nivel_id,
obtenerPlanaId(usu_pla) AS registrar_plana_id,
obtenerUsuarioId(usu_num_doc) as actual_usu_id,
validarDictado(usu_num_doc,usu_niv,usu_pla) AS validar_dictado
FROM tmp_cbb_carga_usuarios_$codigo
ORDER BY id";
    return $sql;
}

function con_registrar_grupo_usuario($cadena) {
    $sql = "INSERT INTO tb_grupo_usuarios(
        grup_codigo,
        usu_id,
        grup_fecha_registro,
        grup_estado)
     VALUES $cadena";
    return $sql;
}

function con_registrar_data_tmp_a_usuario($cadena) {
    $sql = "INSERT INTO tb_usuario(
        tipo_doc_id,
        usu_num_doc,
        usu_paterno,
        usu_materno,
        usu_nombres,
        usu_correo,
        usu_clave,
        perf_id,
        usu_creado,
        usu_token,
        usu_estado)
     VALUES $cadena";
    return $sql;
}

function con_registrar_usuario_dictado($cadena) {
    $sql = "INSERT INTO tb_usuario_dictado(
        usu_id,
        dic_nivel,
        dic_plana,
        dic_fecha_ingreso,
        grup_id,
        dic_estado)
     VALUES $cadena";
    return $sql;
}

function con_lista_grupo_detalle_usuarios($codigo) {
    $sql = "SELECT perf_descripcion as perfil,tipo_doc_nombre as tipoDoc,usu_num_doc as numDoc,
        CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuario,usu_correo as correo,
        niv_nombre as nivel,pla_nombre as plana,
        CASE usu_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END estado 
        FROM tb_grupo_usuarios a
        INNER JOIN tb_usuario_dictado b ON a.grup_id=b.grup_id
        INNER JOIN tb_usuario c ON b.usu_id=c.usu_id
        INNER JOIN tb_documento_tipo c1 ON c.tipo_doc_id=c1.tipo_doc_id
        LEFT JOIN tb_nivel d ON b.dic_nivel=d.niv_id
        LEFT JOIN tb_plana e ON b.dic_plana=e.pla_id
        LEFT JOIN tb_perfil f ON c.perf_id=f.perf_id
        WHERE a.grup_id='$codigo';";
    return $sql;
}

function con_eliminar_grupo_usuario($id, $estado) {
    $sql = "UPDATE tb_grupo_usuarios SET grup_estado='$estado' "
            . " WHERE grup_id='$id';";
    return $sql;
}

function con_eliminar_usuario_grupo($id, $estado) {
    $sql = "UPDATE tb_grupo_usuarios AS a "
            . "INNER JOIN tb_usuario_dictado b ON a.grup_id=b.grup_id "
            . " SET dic_estado='$estado' "
            . " WHERE a.grup_id='$id';";
    return $sql;
}

function con_lista_data_tabla_tmp_carga_usuarios_correos($codigo) {
    $sql = "SELECT usu_num AS num,
usu_tip_usu AS perfil,
usu_tip_doc AS tipo_documento,
usu_num_doc AS dni,
usu_ape_pat AS paterno,
usu_ape_mat AS materno,
usu_nom AS nombres,
CONCAT(usu_ape_pat,' ',usu_ape_mat,' ',usu_nom) as usuario,
usu_cor AS correo,
usu_niv AS nivel,
usu_pla AS plana,
usu_fec_ing AS fecha_ingreso,
usu_estado as estado,
obtenerPerfilId(usu_tip_usu) AS perfId,
obtenerTipoDocumentoId(usu_tip_doc) AS tipDocId,
obtenerNivelId(usu_niv) AS registrar_nivel_id,
obtenerPlanaId(usu_pla) AS registrar_plana_id,
obtenerUsuarioId(usu_num_doc) as actual_usu_id,
validarDictado(usu_num_doc,usu_niv,usu_pla) AS validar_dictado
FROM tmp_cbb_carga_usuarios_$codigo WHERE usu_cor LIKE '%@%'
ORDER BY id";
    return $sql;
}

function con_sedes_x_perfil($sede) {
    $cadena = "";
    if ($sede === "1") {
        $cadena = "NOT IN ($sede)";
    } else {
        $cadena = "IN ($sede)";
    }
    $sql = "SELECT sed_id as id,sed_nombre as nombre,sed_descripcion as descripcion,
        sed_estado as estado FROM tb_sede WHERE sed_id $cadena;";
    return $sql;
}

function con_fechas_rango() {
    $sql = "SELECT DATE_FORMAT(NOW(),'%d/%m/%Y') as hoy,DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 3 MONTH),'%d/%m/%Y') as ayer,"
            . "DATE(NOW()) as date_hoy,DATE(DATE_SUB(NOW(), INTERVAL 3 MONTH)) AS date_ayer;";
    return $sql;
}

function con_lista_solicitudes($sede, $fechaInicio, $fechaFin, $codigoUsuario) {
    $sql = "SELECT sol_id as id,
DATE(sol_fecha) as fecha,
CONCAT(gra_nombre, ' - ',REPLACE(sec_nombre,'Seccion ','')) as grado, 
alu_dni as nroDocumento,
alu_nombres as alumno,
ent_nombre as entrevista,
cat_nombre as categoria,
subca_nombre as subcategoria,
sol_motivo as motivo,
sed_nombre as sede,
sol_plan_estu as planteamiento_estu,
sol_plan_entre as planteamiento_entre,
sol_acuerdos as acuerdos,
sol_informe as informe,
sol_plan_padre as plan_padre,
sol_plan_docen as plan_docen,
sol_acuerdos_1 as acuerdos_1,
sol_acuerdos_2 as acuerdos_2,
CASE sol_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado
FROM tb_solicitudes a
INNER JOIN tb_matricula b ON a.mat_id=b.mat_id
INNER JOIN tb_alumno c ON b.alu_id=c.alu_id
INNER JOIN tb_seccion d ON b.sec_id=d.sec_id
INNER JOIN tb_grado e ON d.gra_id=e.gra_id
INNER JOIN tb_entrevista f ON a.ent_id=f.ent_id
INNER JOIN tb_subcategoria g ON a.subca_id=g.subca_id
INNER JOIN tb_categoria h ON g.cat_id=h.cat_id
INNER JOIN tb_sede j ON a.sed_id=j.sed_id
WHERE sol_fecha BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59' ";
    if ($sede !== "0") {
        $sql .= " AND b.sed_id IN ($sede) ";
    }
    if ($codigoUsuario !== "") {
        $sql .= " AND a.usu_id IN ($codigoUsuario) ";
    }
    $sql .= ";";
    return $sql;
}

function con_lista_tipo_entrevistas($codi) {
    $cadena = "";
    if ($codi !== "") {
        $cadena = " AND ent_id='$codi' ";
    } else {
        $cadena = "";
    }
    $sql = "SELECT ent_id as id,ent_nombre as nombre,
CASE ent_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
FROM tb_entrevista WHERE 1=1 $cadena";
    $sql .= " AND ent_estado=1";
    return $sql;
}

function con_lista_categorias($codi) {
    $cadena = "";
    if ($codi !== "") {
        $cadena = " AND cat_id='$codi' ";
    } else {
        $cadena = "";
    }
    $sql = "SELECT cat_id as id,cat_nombre as nombre,
CASE cat_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
FROM tb_categoria WHERE 1=1  $cadena ";
    $sql .= " AND cat_estado=1 ";
    return $sql;
}

function con_lista_subcategorias($categoria, $codi) {
    $cadenaCa = "";
    $cadena = "";
    if ($categoria !== "") {
        $cadenaCa = " AND cat_id='$categoria' ";
    } else {
        $cadenaCa = "";
    }
    if ($codi !== "") {
        $cadena = " AND subca_id='$codi' ";
    } else {
        $cadena = "";
    }
    $sql = "SELECT subca_id as id,subca_nombre as nombre,
CASE subca_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
FROM tb_subcategoria WHERE 1=1 $cadenaCa $cadena";
    $sql .= " AND subca_estado=1;";
    return $sql;
}

function con_lista_motivos($motivo) {
    $cadena = "";
    if ($motivo !== "") {
        $cadena = " AND mot_id='$motivo' ";
    } else {
        $cadena = "";
    }
    $sql = "SELECT mot_id as id,mot_nombre as nombre,
CASE mot_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
FROM tb_motivo WHERE 1=1  $cadena ";
    $sql .= " AND mot_estado=1 ";
    return $sql;
}

function con_secciones_por_usuario($usuario) {
    $sql = "SELECT if(seccion=0,'',seccion) as seccion FROM (
        SELECT GROUP_CONCAT(DISTINCT sec_id) as seccion FROM tb_usuario a
        INNER JOIN tb_usuario_dictado b ON a.usu_id=b.usu_id
        WHERE dic_estado=1 AND a.usu_id=$usuario ) as p1;";
    return $sql;
}

function con_buscar_alumno($nombres, $sede, $secciones) {
    $cadena_sede = "";
    $cadena_seccion = "";
    $sql = "SELECT b.mat_id as value,REPLACE(UPPER(CONCAT(alu_dni, ' - ',alu_nombres)),'.','') as text,
alu_dni as dni,alu_nombres as nombres 
FROM tb_alumno a
INNER JOIN tb_matricula b ON a.alu_id=b.alu_id
WHERE mat_estado=1 AND alu_estado=1 ";
    if ($secciones !== 0 && $secciones !== '' && $secciones !== '0') {
        $cadena_seccion = " AND sec_id IN ($secciones) ";
    } else {
        $cadena_seccion = "";
    }
    if ($sede !== 1 && $sede !== '1') {
        $cadena_sede = " AND sed_id=$sede ";
    } else {
        $cadena_sede = "";
    }
    $sql .= $cadena_seccion . $cadena_sede . " AND alu_nombres like '$nombres%' ORDER BY alu_nombres;";
    return $sql;
}

function con_alumno_matricula_detalle($codigo) {
    $sql = "SELECT a.alu_id AS aluId,CONCAT(alu_nombres,' - ',alu_dni) as alumno,CONCAT(gra_nombre,' - ',sec_nombre) as grado,
a.sed_id as sedeId,sed_nombre as sede
FROM tb_matricula a
INNER JOIN tb_alumno b ON a.alu_id=b.alu_id
INNER JOIN tb_sede c ON a.sed_id=c.sed_id
INNER JOIN tb_seccion d ON a.sec_id=d.sec_id
INNER JOIN tb_grado e ON d.gra_id=e.gra_id
WHERE mat_id=$codigo;";
    return $sql;
}

function con_lista_apoderados_de_alumno($alumno, $codigo) {
    $sql = "SELECT apo_id as codigo,a.tip_id as tipo,apo_dni as dni,tip_nombre as tipo,apo_nombres as nombre,apo_correo as correo,apo_telefono as telefono 
	FROM tb_alumno_apoderado a
	INNER JOIN tb_tipo_apoderado b on a.tip_id=b.tip_id
        WHERE alu_id=$alumno and apo_estado=1 AND 1=1 ";
    if ($codigo !== "") {
        $sql .= " AND apo_id='$codigo' ";
    }
    $sql .= ";";
    return $sql;
}

function con_lista_tipos_apoderados($codigo) {
    $sql = "SELECT tip_id as id,tip_nombre as nombre, 
	CASE tip_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
	FROM tb_tipo_apoderado WHERE tip_estado=1 ";
    if ($codigo !== "") {
        $sql .= " AND tip_id='$codigo';";
    }
    return $sql;
}

function con_validar_dni_apoderado($codigoAlumno, $codigoApoderado, $numero) {
    $str_consulta = "";
    if ($codigoAlumno !== "") {
        $str_consulta = " alu_id!='$codigoAlumno' ";
    }
    if ($codigoApoderado !== "") {
        $str_consulta = " apo_id!='$codigoApoderado' ";
    }
    $sql = "SELECT *  FROM tb_alumno_apoderado WHERE $str_consulta AND apo_dni='$numero'";
    return $sql;
}

function con_apoderado_del_alumno($codigo) {
    $sql = "SELECT apo_id as id,tip_id as tipo,apo_nombres as nombres,apo_dni as dni,
	apo_correo as correo,apo_telefono as telefono
	FROM tb_alumno_apoderado 
	WHERE apo_id=$codigo and apo_estado=1;";
    return $sql;
}

function con_registrar_apoderado($cadena) {
    $sql = "INSERT INTO tb_alumno_apoderado(
        alu_id,
        tip_id,
        apo_nombres,
        apo_dni,
        apo_correo,
        apo_telefono,
        apo_direccion,
        apo_distrito,
        apo_estado)
        VALUES $cadena";
    return $sql;
}

function con_registrar_apoderado_historico($cadena) {
    $sql = "INSERT INTO tb_alumno_apoderado_historico(
        apo_id,
        hist_fecha,
        hist_correo,
        hist_telefono,
        hist_estado)
        VALUES $cadena";
    return $sql;
}

function con_modificar_apoderado($codigo, $dni, $correo, $telefono) {
    $sql = "UPDATE tb_alumno_apoderado SET apo_dni='$dni',apo_correo='$correo',apo_telefono='$telefono' "
            . " WHERE apo_id='$codigo';";
    return $sql;
}

function con_registrar_solicitud_estudiante($cadena) {
    $sql = "INSERT INTO tb_solicitudes(
        sol_codigo,
        mat_id,
        usu_id,
        ent_id,
        subca_id,
        sol_motivo,
        sol_fecha,
        sed_id,
        sol_plan_estu,
        sol_plan_entre,
        sol_acuerdos,
        sol_informe,
        sol_plan_padre,
        sol_plan_docen,
        sol_acuerdos_1,
        sol_acuerdos_2,
        apo_id,
        sol_estado)
        VALUES $cadena";
    return $sql;
}

function con_registrar_solicitud_firmas($cadena) {
    $sql = "INSERT INTO tb_solicitudes_firmas(
        sol_id,
        mat_id,
        usu_id,
        apo_id,
        firm_imagen,
        firm_fecha,
        firm_estado)
        VALUES $cadena";
    return $sql;
}

function con_solicitud_alumno($codigo) {
    $sql = "SELECT CONCAT(alu_nombres,' - ',alu_dni) as alumno,CONCAT(gra_nombre,' - ',sec_nombre) as grado,
    sed_nombre as sede,alu_nom_padre AS padre,alu_nom_madre as madre,
    IF(CONCAT(alu_cel_padre,' - ',alu_cor_padre)=' - ','',CONCAT(alu_cel_padre,' - ',alu_cor_padre)) as data_padre,
    IF(CONCAT(alu_cel_madre,' - ',alu_cor_madre)=' - ','',CONCAT(alu_cel_madre,' - ',alu_cor_madre)) as data_madre,
    a.ent_id as entreId,ent_nombre as entrevista,cat_nombre as categoria,subca_nombre as subcategoria,
    sol_motivo as motivo,CONCAT(usu_paterno,' ',usu_materno,' ', usu_nombres) as usuario,
    sol_plan_estu as plan_estu,sol_plan_entre as plan_entre,sol_acuerdos as acuerdos,
    sol_informe as informe,sol_plan_padre as plan_padre,sol_plan_docen as plan_docen,
    sol_acuerdos_1 as acuerdos1,sol_acuerdos_2 as acuerdos2,
    DATE_FORMAT((sol_fecha),'%d/%m/%Y %H:%i:%s') as fecha,
    CASE sol_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
    FROM (
    SELECT * FROM tb_solicitudes WHERE sol_id='$codigo') as a
    INNER JOIN tb_matricula b ON a.mat_id=b.mat_id
    INNER JOIN tb_alumno c ON b.alu_id=c.alu_id
    INNER JOIN tb_sede d ON b.sed_id=d.sed_id
    INNER JOIN tb_seccion e ON b.sec_id=e.sec_id
    INNER JOIN tb_grado f ON e.gra_id=f.gra_id
    INNER JOIN tb_entrevista g ON a.ent_id=g.ent_id
    INNER JOIN tb_subcategoria h ON a.subca_id=h.subca_id
    INNER JOIN tb_categoria i ON h.cat_id=i.cat_id
    INNER JOIN tb_usuario k ON a.usu_id=k.usu_id";
    return $sql;
}

function con_eliminar_solicitud_alumno($id, $estado) {
    $sql = "UPDATE tb_solicitudes SET sol_estado='$estado' "
            . " WHERE sol_id='$id';";
    return $sql;
}

function con_buscar_semaforo_docentes($sede, $fecha_ini, $fecha_fin, $semaforo) {
    $sql = "SELECT * FROM ( SELECT sede,docente,grado,cantidad,cantidad_faltantes,
		CASE WHEN cantidad_faltantes/cantidad<0.4 THEN '3' 
		WHEN cantidad_faltantes/cantidad>=0.4 AND cantidad_faltantes/cantidad<0.8 THEN '2'
		WHEN cantidad_faltantes/cantidad>=0.8 AND cantidad_faltantes/cantidad<1.0 THEN '1'
		END AS valor,
		CASE WHEN cantidad_faltantes/cantidad<0.4 THEN 'Rojo' 
		WHEN cantidad_faltantes/cantidad>=0.4 AND cantidad_faltantes/cantidad<0.8 THEN 'Ambar'
		WHEN cantidad_faltantes/cantidad>=0.8 AND cantidad_faltantes/cantidad<1.0 THEN 'Verde'
		END AS color
	FROM (
		SELECT sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as docente,
		CONCAT(gra_nombre,' - ',sec_nombre) as grado,mat_fech_regi as fecha ,COUNT(c.mat_id) as cantidad,COUNT(g.sol_id) as cantidad_faltantes
		FROM tb_usuario_dictado a
		INNER JOIN tb_usuario b ON a.usu_id=b.usu_id
		INNER JOIN tb_matricula c ON a.sec_id=c.sec_id AND a.sed_id=c.sed_id
		INNER JOIN tb_seccion d ON c.sec_id=d.sec_id
		INNER JOIN tb_grado e ON d.gra_id=e.gra_id
		INNER JOIN tb_sede f ON c.sed_id=f.sed_id
		LEFT JOIN tb_solicitudes g ON c.mat_id=g.mat_id AND sol_estado=1
		WHERE dic_estado=1 and mat_estado=1 AND mat_fech_regi>='$fecha_ini 00:00:00' AND mat_fech_regi<='$fecha_fin 23:59:59' ";
    if ($sede !== "0") {
        $sql .= " and c.sed_id=$sede ";
    }
    $sql .= "GROUP BY a.usu_id,a.sed_id,a.sec_id) as p1 ) as p2 WHERE 1=1  ";
    if ($semaforo !== "0") {
        $sql .= " and p2.valor=$semaforo ";
    }
    $sql .= ";";
    return $sql;
}

function con_obtener_codigo_entrevista($codigo) {
    $sql = "SELECT sol_codigo AS codigo_ent FROM tb_solicitudes WHERE sol_id=$codigo;";
    return $sql;
}

function con_registrar_sub_solicitud_estudiante($cadena) {
    $sql = "INSERT INTO tb_sub_solicitudes(
        sol_id,
        ssol_codigo,
        mat_id,
        usu_id,
        ent_id,
        subca_id,
        ssol_motivo,
        ssol_fecha,
        sed_id,
        ssol_plan_estu,
        ssol_plan_entre,
        ssol_acuerdos,
        ssol_informe,
        ssol_plan_padre,
        ssol_plan_docen,
        ssol_acuerdos_1,
        ssol_acuerdos_2,
        apo_id,
        ssol_estado)
        VALUES $cadena";
    return $sql;
}

function con_registrar_sub_solicitud_firmas($cadena) {
    $sql = "INSERT INTO tb_sub_solicitudes_firmas(
        ssol_id,
        mat_id,
        usu_id,
        apo_id,
        sfirm_imagen,
        sfirm_fecha,
        sfirm_estado)
        VALUES $cadena";
    return $sql;
}

function con_listar_todas_solicitudes_x_entrevista($codigo, $entre, $sub) {
    $sql = "SELECT id,CONCAT(nomb,' - ',codigo ,' - ',ent_nombre,' - ',alu_dni,' - ', alu_nombres) as detalle FROM ( ";
    if ($entre === "") {
        $sql .= "";
    } else {
        $sql .= " SELECT CONCAT('ent-',sol_id) as id,'Entrevista' as nomb,sol_codigo as codigo,ent_nombre,d.alu_dni, d.alu_nombres,'1' as orden
            FROM tb_solicitudes a 
            INNER JOIN tb_entrevista b ON a.ent_id=b.ent_id
            INNER JOIN tb_matricula c ON a.mat_id=c.mat_id
            INNER JOIN tb_alumno d ON c.alu_id=d.alu_id
            WHERE sol_id=$codigo
            UNION ";
    }

    if ($sub === "") {
        $sql .= "";
    } else {
        $sql .= " SELECT CONCAT('sub-',ssol_id) as id,'Subentrevista' as nomb,ssol_codigo as codigo,ent_nombre,d.alu_dni, d.alu_nombres,'2' as orden
            FROM tb_sub_solicitudes a 
            INNER JOIN tb_entrevista b ON a.ent_id=b.ent_id
            INNER JOIN tb_matricula c ON a.mat_id=c.mat_id
            INNER JOIN tb_alumno d ON c.alu_id=d.alu_id
            WHERE sol_id=$codigo ";
    }
    $sql .= " ) as p1 ORDER BY orden;";
    return $sql;
}

function con_obtener_solicitud_x_codigo($tipo, $codi) {
    $sql = "";
    if ($tipo === "ent") {
        $sql = "SELECT sol_codigo as codigo,a.mat_id as matricula,a0.alu_id as aluId,CONCAT(alu_dni,' - ',alu_nombres) as alumno_busq,CONCAT(alu_nombres,' - ',alu_dni) as alumno,f.cat_id as categoria,a.subca_id as subcategorgia,a.ent_id,CONCAT(gra_nombre,' - ',sec_nombre) as grado,
a.sed_id as sedeId,sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuario,usu_num_doc as dni,sol_motivo as motivo, DATE_FORMAT(sol_fecha, '%d/%m/%Y %H:%i:%s') as fecha,sol_plan_estu as plan_estudiante,sol_plan_entre as plan_entrevistador,sol_acuerdos as acuerdos,sol_informe as informe,sol_plan_padre as plan_padre,
sol_plan_docen as plan_docente,sol_acuerdos_1 as acuerdos1, sol_acuerdos_2 as acuerdos2,apo_id as apoderado,sol_estado as estadoId,
CASE sol_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
FROM tb_solicitudes a
INNER JOIN tb_matricula a0 ON a.mat_id=a0.mat_id
INNER JOIN tb_alumno b ON a0.alu_id=b.alu_id
INNER JOIN tb_sede c ON a0.sed_id=c.sed_id
INNER JOIN tb_seccion d ON a0.sec_id=d.sec_id
INNER JOIN tb_grado e ON d.gra_id=e.gra_id
INNER JOIN tb_subcategoria f ON a.subca_id=f.subca_id
INNER JOIN tb_categoria g ON f.cat_id=g.cat_id
INNER JOIN tb_usuario h ON a.usu_id=h.usu_id
WHERE sol_id=$codi;";
    } else {
        $sql = "
SELECT ssol_codigo as codigo,a.mat_id as matricula,a0.alu_id as aluId,CONCAT(alu_dni,' - ',alu_nombres) as alumno_busq,CONCAT(alu_nombres,' - ',alu_dni) as alumno,f.cat_id as categoria,a.subca_id as subcategorgia,a.ent_id,CONCAT(gra_nombre,' - ',sec_nombre) as grado,
a.sed_id as sedeId,sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuario,usu_num_doc as dni,ssol_motivo as motivo, DATE_FORMAT(ssol_fecha, '%d/%m/%Y %H:%i:%s') as fecha,ssol_plan_estu as plan_estudiante,ssol_plan_entre as plan_entrevistador,ssol_acuerdos as acuerdos,ssol_informe as informe,ssol_plan_padre as plan_padre,
ssol_plan_docen as plan_docente,ssol_acuerdos_1 as acuerdos1, ssol_acuerdos_2 as acuerdos2,apo_id as apoderado,ssol_estado as estadoId,
CASE ssol_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado 
FROM tb_sub_solicitudes a
INNER JOIN tb_matricula a0 ON a.mat_id=a0.mat_id
INNER JOIN tb_alumno b ON a0.alu_id=b.alu_id
INNER JOIN tb_sede c ON a0.sed_id=c.sed_id
INNER JOIN tb_seccion d ON a0.sec_id=d.sec_id
INNER JOIN tb_grado e ON d.gra_id=e.gra_id
INNER JOIN tb_subcategoria f ON a.subca_id=f.subca_id
INNER JOIN tb_categoria g ON f.cat_id=g.cat_id
INNER JOIN tb_usuario h ON a.usu_id=h.usu_id
WHERE ssol_id=$codi;";
    }
    return $sql;
}
