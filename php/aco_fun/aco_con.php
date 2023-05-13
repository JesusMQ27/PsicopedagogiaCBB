<?php

function con_consulta_login($p_usua, $p_clave) {
    $sql = "SELECT usu_id as id,usu_num_doc as num_doc,usu_nombres, CONCAT(usu_paterno,' ',usu_materno) as apellidos,perf_id as perfCod,sed_id as sedCod  "
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

function con_lista_usuarios($id, $sede) {
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
    if ($id !== "") {
        $sql .= " AND usu_id=$id ";
    }
    if ($sede !== "") {
        $sql .= " AND a.sed_id=$sede ";
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
    $sql = "SELECT * FROM tb_uscuario WHERE usu_id='$id' AND usu_token_clave='$token';";
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
        $sql .= " AND perf_estado='$estado' ";
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

function con_lista_sedes($id, $estado) {
    $cadena = "";
    $sql = "SELECT sed_id as id,sed_codigo as codigo, sed_nombre as nombre, sed_descripcion as descripcion,
sed_color as color,CASE sed_estado WHEN 1 THEN 'Activo' WHEN 0 THEN 'Inactivo' ELSE '' END AS estado,sed_estado as estadoId 
FROM tb_sede WHERE 1=1 ";
    if ($id !== "") {
        $sql .= " AND sed_id in ('$id') ";
    }
    if ($estado !== "") {
        $cadena .= " AND sed_estado=1;";
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

function con_lista_solicitudes($sede, $fechaInicio, $fechaFin, $codigoUsuario, $privacidad) {
    $sql = "SELECT sol_id as id,
sed_nombre as sede,
DATE(sol_fecha) as fecha,
CONCAT(gra_nombre, ' - ',REPLACE(sec_nombre,'Seccion ','')) as grado, 
alu_dni as nroDocumento,
UPPER(alu_nombres) as alumno,
ent_nombre as entrevista,
cat_nombre as categoria,
subca_nombre as subcategoria,
sol_motivo as motivo,
sol_plan_estu as planteamiento_estu,
sol_plan_entre as planteamiento_entre,
sol_acuerdos as acuerdos,
sol_informe as informe,
sol_plan_padre as plan_padre,
sol_plan_docen as plan_docen,
sol_acuerdos_1 as acuerdos_1,
sol_acuerdos_2 as acuerdos_2,
CASE sol_privacidad WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END as privacidad,
sol_duracion as duracion,
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
    if ($privacidad !== "0") {
        $sql .= " AND sol_privacidad in ($privacidad)";
    } else {
        $sql .= " AND sol_privacidad in ($privacidad)";
    }
    $sql .= " ORDER BY sol_fecha DESC;";
    return $sql;
}

function con_lista_solicitudes_alertas($sede, $codigoUsuario, $fechaInicio, $fechaFin, $privacidad) {
    $sql = "SELECT a.sol_id
FROM tb_solicitudes a 
WHERE 1=1 ";
    if ($sede !== "0") {
        $sql .= " AND a.sed_id IN ($sede) ";
    }
    if ($codigoUsuario !== "") {
        $sql .= " AND a.usu_id IN ($codigoUsuario) ";
    }
    if ($privacidad !== "0") {
        $sql .= " AND sol_privacidad in ($privacidad)";
    } else {
        $sql .= " AND sol_privacidad in ($privacidad)";
    }
    if ($fechaInicio != "") {
        $sql .= " AND sol_fecha>='$fechaInicio 00:00:00' ";
    }
    if ($fechaFin != "") {
        $sql .= " AND sol_fecha<='$fechaFin 23:59:59' ";
    }
    $sql .= " AND sol_estado=1;";
    return $sql;
}

function con_lista_solicitudes_grafico_linear($sede, $privacidad) {
    $sql = "SELECT p1.mes,nombre as nombreMes,IF(cantidad is null,0,cantidad) as cantidad,
if(cantidad_estudiantes is null,0,cantidad_estudiantes) as cantidad_estudiantes,
if(cantidad_padres is null,0,cantidad_padres) as cantidad_padres FROM (
SELECT 1 as mes ,'Enero' as nombre
UNION
SELECT 2 as mes ,'Febrero' as nombre
UNION
SELECT 3 as mes ,'Marzo' as nombre
UNION
SELECT 4 as mes ,'Abril' as nombre
UNION
SELECT 5 as mes ,'Mayo' as nombre
UNION
SELECT 6 as mes ,'Junio' as nombre
UNION
SELECT 7 as mes ,'Julio' as nombre
UNION
SELECT 8 as mes ,'Agosto' as nombre
UNION
SELECT 9 as mes ,'Setiembre' as nombre
UNION
SELECT 10 as mes ,'Octubre' as nombre
UNION
SELECT 11 as mes ,'Noviembre' as nombre
UNION
SELECT 12 as mes ,'Diciembre' as nombre) as p1
LEFT JOIN (
	SELECT MONTH(sol_fecha) as mes,COUNT(*) as cantidad, SUM(IF(a.ent_id=1,1,0)) as cantidad_estudiantes,	SUM(IF(a.ent_id=2,1,0)) as cantidad_padres
FROM tb_solicitudes a 
INNER JOIN tb_entrevista b ON a.ent_id=b.ent_id
WHERE 1=1 AND YEAR(sol_fecha)=YEAR(NOW()) ";
    if ($sede !== "0") {
        $sql .= " AND a.sed_id IN ($sede) ";
    }
    if ($privacidad !== "0") {
        $sql .= " AND sol_privacidad in ($privacidad)";
    } else {
        $sql .= " AND sol_privacidad in ($privacidad)";
    }
    $sql .= " GROUP BY MONTH(sol_fecha)) as p2 ON p1.mes=p2.mes "
            . " ORDER BY p1.mes";
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
        sol_privacidad,
        sol_duracion,
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
        firm_tipo,
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

function con_eliminar_sub_solicitud_alumno($id, $estado) {
    $sql = "UPDATE tb_sub_solicitudes SET ssol_estado='$estado' "
            . " WHERE ssol_id='$id';";
    return $sql;
}

function con_buscar_semaforo_docentes($sede, $fecha_ini, $fecha_fin, $semaforo) {
    $sql = "SELECT * FROM ( SELECT sede,docente,grado,cantidad,cantidad_faltantes,cantidad_realizados,CONCAT((cantidad_realizados/cantidad)*100,' %') as porcentaje,
		CASE WHEN cantidad_realizados/cantidad<0.4 THEN '3' 
		WHEN cantidad_realizados/cantidad>=0.4 AND cantidad_realizados/cantidad<0.8 THEN '2'
		WHEN cantidad_realizados/cantidad>=0.8 AND cantidad_realizados/cantidad<1.0 THEN '1'
		END AS valor,
		CASE WHEN cantidad_realizados/cantidad<0.4 THEN 'Rojo' 
		WHEN cantidad_realizados/cantidad>=0.4 AND cantidad_realizados/cantidad<0.8 THEN 'Ambar'
		WHEN cantidad_realizados/cantidad>=0.8 AND cantidad_realizados/cantidad<1.0 THEN 'Verde'
		END AS color
	FROM (
		SELECT sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as docente,
		CONCAT(gra_nombre,' - ',sec_nombre) as grado,mat_fech_regi as fecha ,COUNT(c.mat_id) as cantidad,
                COUNT(g.sol_id) as cantidad_realizados,COUNT(c.mat_id) -COUNT(g.sol_id) as cantidad_faltantes
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
    $sql .= " GROUP BY a.usu_id,a.sed_id,a.sec_id) as p1 ) as p2 WHERE 1=1  ";
    if ($semaforo !== "0") {
        $sql .= " and p2.valor=$semaforo ";
    }
    $sql .= ";";
    return $sql;
}

function con_buscar_semaforo_docentes_alerta($sede, $fecha_ini, $fecha_fin, $semaforo) {
    $sql = "SELECT * FROM ( SELECT *
	FROM (
		SELECT sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as docente,
		CONCAT(gra_nombre,' - ',sec_nombre) as grado,mat_fech_regi as fecha ,COUNT(c.mat_id) as cantidad,
                COUNT(g.sol_id) as cantidad_realizados,COUNT(c.mat_id) -COUNT(g.sol_id) as cantidad_faltantes,
                CONCAT(((COUNT(g.sol_id)/COUNT(c.mat_id))*100),' %') as porcentaje
		FROM tb_usuario_dictado a
		INNER JOIN tb_usuario b ON a.usu_id=b.usu_id
		INNER JOIN tb_matricula c ON a.sec_id=c.sec_id AND a.sed_id=c.sed_id
		INNER JOIN tb_seccion d ON c.sec_id=d.sec_id
		INNER JOIN tb_grado e ON d.gra_id=e.gra_id
		INNER JOIN tb_sede f ON c.sed_id=f.sed_id
		LEFT JOIN tb_solicitudes g ON c.mat_id=g.mat_id AND sol_estado=1
		WHERE dic_estado=1 and mat_estado=1  ";
    if ($fecha_ini !== "") {
        $sql .= " AND mat_fech_regi>='$fecha_ini 00:00:00' ";
    }
    if ($fecha_fin !== "") {
        $sql .= " AND mat_fech_regi<='$fecha_fin 23:59:59' ";
    }
    if ($sede !== "0") {
        $sql .= " and c.sed_id=$sede ";
    }
    $sql .= " ) as p1 ) as p2 WHERE 1=1  ";
    if ($semaforo !== "0") {
        $sql .= " and p2.valor=$semaforo ";
    }
    $sql .= ";";
    return $sql;
}

function con_buscar_semaforo_docentes_grafico_barras($sede, $fecha_ini, $fecha_fin) {
    $sql = "SELECT sed_nombre as sede,
		CONCAT(gra_nombre) as grado,COUNT(c.mat_id) as cantidad,
		COUNT(g.sol_id) as cantidad_realizados,COUNT(c.mat_id) -COUNT(g.sol_id) as cantidad_faltantes
		FROM tb_usuario_dictado a
		INNER JOIN tb_usuario b ON a.usu_id=b.usu_id
		INNER JOIN tb_matricula c ON a.sec_id=c.sec_id AND a.sed_id=c.sed_id
		INNER JOIN tb_seccion d ON c.sec_id=d.sec_id
		INNER JOIN tb_grado e ON d.gra_id=e.gra_id
		INNER JOIN tb_sede f ON c.sed_id=f.sed_id
		LEFT JOIN tb_solicitudes g ON c.mat_id=g.mat_id AND sol_estado=1
		WHERE dic_estado=1 and mat_estado=1 AND YEAR(mat_fech_regi)=YEAR(NOW())
		";
    if ($fecha_ini !== "") {
        $sql .= " AND mat_fech_regi>='$fecha_ini 00:00:00' ";
    }
    if ($fecha_fin !== "") {
        $sql .= " AND mat_fech_regi<='$fecha_fin 23:59:59' ";
    }
    if ($sede !== "0") {
        $sql .= " AND a.sed_id in ($sede) ";
    }
    $sql .= "GROUP BY d.gra_id ORDER BY d.gra_id ;";
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
        ssol_privacidad,
        ssol_duracion,
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
        sfirm_tipo,
        sfirm_estado)
        VALUES $cadena";
    return $sql;
}

function con_listar_todas_solicitudes_x_entrevista($codigo, $entre, $sub, $privacidad) {
    $sql = "SELECT id,CONCAT(nomb,' - ',codigo ,' - ',ent_nombre,' - ',alu_dni,' - ', alu_nombres) as detalle FROM ( ";
    if ($entre === "") {
        $sql .= "";
    } else {
        $sql .= " SELECT CONCAT('ent-',sol_id) as id,'Entrevista' as nomb,sol_codigo as codigo,ent_nombre,d.alu_dni, d.alu_nombres,'1' as orden
            FROM tb_solicitudes a 
            INNER JOIN tb_entrevista b ON a.ent_id=b.ent_id
            INNER JOIN tb_matricula c ON a.mat_id=c.mat_id
            INNER JOIN tb_alumno d ON c.alu_id=d.alu_id
            WHERE sol_id=$codigo ";
        $sql .= " AND sol_privacidad in ($privacidad) ";
        $sql .= " UNION ";
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
        $sql .= " AND ssol_privacidad in ($privacidad)";
    }
    $sql .= " ) as p1 ORDER BY orden;";
    return $sql;
}

function con_obtener_solicitud_x_codigo($tipo, $codi) {
    $sql = "";
    if ($tipo === "ent") {
        $sql = "SELECT sol_codigo as codigo,a.mat_id as matricula,a0.alu_id as aluId,CONCAT(alu_dni,' - ',alu_nombres) as alumno_busq,CONCAT(alu_nombres,' - ',alu_dni) as alumno,f.cat_id as categoria,a.subca_id as subcategorgia,a.ent_id,CONCAT(gra_nombre,' - ',sec_nombre) as grado,
a.sed_id as sedeId,sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuario,usu_num_doc as dni,sol_motivo as motivo, DATE_FORMAT(sol_fecha, '%d/%m/%Y %H:%i:%s') as fecha,sol_plan_estu as plan_estudiante,sol_plan_entre as plan_entrevistador,sol_acuerdos as acuerdos,sol_informe as informe,sol_plan_padre as plan_padre,
sol_plan_docen as plan_docente,sol_acuerdos_1 as acuerdos1, sol_acuerdos_2 as acuerdos2,apo_id as apoderado,sol_estado as estadoId,sol_privacidad as privacidad,
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
ssol_plan_docen as plan_docente,ssol_acuerdos_1 as acuerdos1, ssol_acuerdos_2 as acuerdos2,apo_id as apoderado,ssol_estado as estadoId,ssol_privacidad as privacidad,
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

function con_obtener_firma_entrevista($codigo, $tipo) {
    $sql = "SELECT firm_id as id,firm_fecha as fecha,firm_imagen as imagen,firm_tipo as tipo 
FROM tb_solicitudes_firmas WHERE sol_id=$codigo AND firm_tipo=$tipo AND firm_estado='1';";
    return $sql;
}

function con_obtener_firma_subentrevista($codigo, $tipo) {
    $sql = "SELECT sfirm_id as id,sfirm_fecha as fecha,sfirm_imagen as imagen,sfirm_tipo as tipo 
FROM tb_sub_solicitudes_firmas WHERE ssol_id=$codigo AND sfirm_tipo=$tipo AND sfirm_estado='1';";
    return $sql;
}

function con_modificar_solicitud_entrevista($codigo, $matricual, $usuario, $solicitud_tipo, $s_subcategoria, $s_motivo, $fecha, $sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $_privacidad, $estado) {
    $sql = "UPDATE tb_solicitudes SET 
            mat_id='$matricual',
            usu_id='$usuario',
            ent_id='$solicitud_tipo',
            subca_id='$s_subcategoria',
            sol_motivo='$s_motivo',
            sol_fecha=$fecha,
            sed_id='$sede',
            sol_plan_estu='$s_planEstudiante',
            sol_plan_entre='$s_planEntrevistador',
            sol_acuerdos='$s_acuerdos',
            sol_informe='$s_informe',
            sol_plan_padre='$s_planPadre',
            sol_plan_docen='$s_planDocente',
            sol_acuerdos_1='$s_acuerdosPadres',
            sol_acuerdos_2='$s_acuerdosColegio',
            apo_id='$s_apoderado',
            sol_privacidad='$_privacidad',
            sol_estado='$estado' "
            . " WHERE sol_id='$codigo';";
    return $sql;
}

function con_modificar_solicitud_entrevista_firmas($codigo, $tipo, $matricual, $usuario, $s_apoderado, $imagen, $fecha, $estado) {
    $sql = "UPDATE tb_solicitudes_firmas SET 
        mat_id='$matricual',
        usu_id='$usuario',
        apo_id='$s_apoderado',
        firm_imagen='$imagen',
        firm_fecha=$fecha,
        firm_estado='$estado'
         WHERE sol_id='$codigo' and firm_tipo='$tipo';";
    return $sql;
}

function con_modificar_solicitud_sub_entrevista($codigo, $matricual, $usuario, $solicitud_tipo, $s_subcategoria, $s_motivo, $fecha, $sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $_privacidad, $estado) {
    $sql = "UPDATE tb_sub_solicitudes SET 
            mat_id='$matricual',
            usu_id='$usuario',
            ent_id='$solicitud_tipo',
            subca_id='$s_subcategoria',
            ssol_motivo='$s_motivo',
            ssol_fecha=$fecha,
            sed_id='$sede',
            ssol_plan_estu='$s_planEstudiante',
            ssol_plan_entre='$s_planEntrevistador',
            ssol_acuerdos='$s_acuerdos',
            ssol_informe='$s_informe',
            ssol_plan_padre='$s_planPadre',
            ssol_plan_docen='$s_planDocente',
            ssol_acuerdos_1='$s_acuerdosPadres',
            ssol_acuerdos_2='$s_acuerdosColegio',
            apo_id='$s_apoderado',
            ssol_privacidad='$_privacidad',
            ssol_estado='$estado' "
            . " WHERE ssol_id='$codigo';";
    return $sql;
}

function con_modificar_solicitud_sub_entrevista_firmas($codigo, $tipo, $matricual, $usuario, $s_apoderado, $imagen, $fecha, $estado) {
    $sql = "UPDATE tb_sub_solicitudes_firmas SET 
        mat_id='$matricual',
        usu_id='$usuario',
        apo_id='$s_apoderado',
        sfirm_imagen='$imagen',
        sfirm_fecha=$fecha,
        sfirm_estado='$estado'
         WHERE ssol_id='$codigo' and sfirm_tipo='$tipo';";
    return $sql;
}

function con_registrar_sede($cadena) {
    $sql = "INSERT INTO tb_sede(
        sed_codigo,
        sed_nombre,
        sed_descripcion,
        sed_color,
        sed_estado)
        VALUES $cadena";
    return $sql;
}

function con_editar_sede($id, $nombre, $descripcion, $icono, $estado) {
    $sql = "UPDATE tb_sede SET sed_nombre='$nombre',sed_descripcion='$descripcion',sed_color='$icono',sed_estado='$estado' "
            . " WHERE sed_id='$id';";
    return $sql;
}

function con_eliminar_sede($id) {
    $sql = "UPDATE tb_sede SET sed_estado='0' "
            . " WHERE sed_id='$id';";
    return $sql;
}

function con_eliminar_matriculas_sede($id, $anio) {
    $sql = "UPDATE tb_matricula SET mat_estado='0' "
            . " WHERE sed_id='$id' AND YEAR(mat_fech_regi)=$anio;";
    return $sql;
}

function con_lista_anios() {
    $sql = "SELECT YEAR(NOW()) as fecha
        UNION
        SELECT YEAR(DATE_SUB(NOW(), INTERVAL 1 YEAR)) as fecha
        UNION
        SELECT YEAR(DATE_SUB(NOW(), INTERVAL 2 YEAR)) as fecha";
    return $sql;
}

function con_fecha_actual() {
    $sql = "SELECT YEAR(NOW()) as anio,NOW() as hoy";
    return $sql;
}

function con_modificar_matriculas_sedes($id) {
    $cadena = "";
    $sql = "UPDATE tb_matriculas SET mat_estado='0' "
            . " WHERE 1=1 ";
    if ($id === "1") {
        $cadena = "";
    } else {
        $cadena = " AND sed_id='$id' ";
    }
    $sql .= $cadena . " AND YEAR(mat_fech_regi)=YEAR(DATE_SUB(NOW(), INTERVAL 1 YEAR)); ";
    return $sql;
}

function con_lista_correos_estudiantes_y_apoderados_entrevistas($codigo) {
    $sql = "SELECT * FROM (
SELECT DISTINCT CONCAT('alu-',b.alu_id) as codigo,CONCAT('ESTUDIANTE - ',UPPER(alu_nombres),' - ',alu_correo) as dato, 
trim(alu_correo) as correo,UPPER(alu_nombres) as persona
FROM tb_solicitudes a
INNER JOIN tb_matricula b ON a.mat_id=b.mat_id
INNER JOIN tb_alumno c ON b.alu_id=c.alu_id
WHERE sol_id=$codigo 
UNION ALL
SELECT DISTINCT CONCAT('apo-',b.apo_id) as codigo,CONCAT(tip_nombre,' - ',UPPER(apo_nombres),' - ',apo_correo) as dato,
trim(apo_correo) as correo,UPPER(apo_nombres) as persona
FROM tb_solicitudes a
INNER JOIN tb_alumno_apoderado b ON a.apo_id=b.apo_id
INNER JOIN tb_tipo_apoderado c ON b.tip_id=c.tip_id
WHERE sol_id=$codigo
) AS p1 WHERE p1.correo!='';";
    return $sql;
}

function con_lista_correos_estudiantes_y_apoderados_sub_entrevistas($codigo) {
    $sql = "
SELECT * FROM (
SELECT DISTINCT CONCAT('alu-',b.alu_id) as codigo,CONCAT('ESTUDIANTE - ',UPPER(alu_nombres),' - ',alu_correo) as dato, 
trim(alu_correo) as correo,UPPER(alu_nombres) as persona
FROM tb_sub_solicitudes a
INNER JOIN tb_matricula b ON a.mat_id=b.mat_id
INNER JOIN tb_alumno c ON b.alu_id=c.alu_id
WHERE ssol_id=$codigo
UNION ALL
SELECT DISTINCT CONCAT('apo-',b.apo_id) as codigo,CONCAT(tip_nombre,' - ',UPPER(apo_nombres),' - ',apo_correo) as dato,
trim(apo_correo) as correo,UPPER(apo_nombres) as persona
FROM tb_sub_solicitudes a
INNER JOIN tb_alumno_apoderado b ON a.apo_id=b.apo_id
INNER JOIN tb_tipo_apoderado c ON b.tip_id=c.tip_id
WHERE ssol_id=$codigo
) AS p1 WHERE p1.correo!='';";
    return $sql;
}

function con_busca_intento($usuario) {
    $sql = "select * from tb_solicitudes_intentos where usu_id='$usuario';";
    return $sql;
}

function con_insertar_intento($usuario, $int_hini, $int_hfin) {
    $sql = "insert into tb_solicitudes_intentos(usu_id,int_hini,int_hfin,int_hreg,int_esta)"
            . "  values('" . $usuario . "','" . $int_hini . "','" . $int_hfin . "',NOW(),1);";
    return $sql;
}

function con_buscar_alumnos_no_entrevistados($sede, $usuario, $fecha_ini, $fecha_fin) {
    $sql = "SELECT * FROM (
		SELECT sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as docente,
		UPPER(CONCAT(gra_nombre,' - ',sec_nombre)) as grado,alu_dni as dni,UPPER(alu_nombres) as alumno,
		IF(h.sol_id IS NULL,'No entrevistado','Entrevistado') as tipo,
		IF(sol_fecha IS NULL,'No entrevistado',DATE_FORMAT(DATE(sol_fecha) , '%d/%m/%Y')) as fecha,b.sed_id
FROM tb_usuario a
		INNER JOIN tb_usuario_dictado b ON a.usu_id=b.usu_id
		INNER JOIN tb_matricula c ON b.sec_id=c.sec_id AND b.sed_id=c.sed_id  
		INNER JOIN tb_seccion d ON b.sec_id=d.sec_id AND c.sec_id=d.sec_id 
		INNER JOIN tb_grado e ON d.gra_id=e.gra_id
		INNER JOIN tb_alumno f ON c.alu_id=f.alu_id
		INNER JOIN tb_sede g ON b.sed_id=g.sed_id AND c.sed_id=g.sed_id
		LEFT JOIN tb_solicitudes h ON c.mat_id=h.mat_id AND c.sed_id=h.sed_id
		WHERE 1=1 AND dic_estado=1 AND c.mat_estado=1 AND f.alu_estado=1 ";

    if ($usuario !== "") {
        $sql .= " AND b.usu_id=$usuario ";
    }
    $sql .= " AND sol_fecha is NULL OR sol_fecha BETWEEN '$fecha_ini 00:00:00' AND '$fecha_fin 23:59:59') AS p1
        WHERE 1=1 ";
    if ($sede !== "0") {
        $sql .= " AND p1.sed_id=$sede ";
    }
    $sql .= " ORDER BY p1.fecha DESC, p1.sede, p1.tipo, p1.docente, p1.grado, p1.alumno";
    return $sql;
}

function con_buscar_alumnos_no_entrevistados_alerta($sede, $usuario) {
    $sql = "SELECT * FROM (
		SELECT sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as docente,
		UPPER(CONCAT(gra_nombre,' - ',sec_nombre)) as grado,alu_dni as dni,UPPER(alu_nombres) as alumno,
		IF(h.sol_id IS NULL,'No entrevistado','Entrevistado') as tipo,
		IF(sol_fecha IS NULL,'No entrevistado',DATE_FORMAT(DATE(sol_fecha) , '%d/%m/%Y')) as fecha,b.sed_id
FROM tb_usuario a
		INNER JOIN tb_usuario_dictado b ON a.usu_id=b.usu_id
		INNER JOIN tb_matricula c ON b.sec_id=c.sec_id AND b.sed_id=c.sed_id  
		INNER JOIN tb_seccion d ON b.sec_id=d.sec_id AND c.sec_id=d.sec_id 
		INNER JOIN tb_grado e ON d.gra_id=e.gra_id
		INNER JOIN tb_alumno f ON c.alu_id=f.alu_id
		INNER JOIN tb_sede g ON b.sed_id=g.sed_id AND c.sed_id=g.sed_id
		LEFT JOIN tb_solicitudes h ON c.mat_id=h.mat_id AND c.sed_id=h.sed_id
		WHERE 1=1 AND dic_estado=1 AND c.mat_estado=1 AND f.alu_estado=1 AND sol_fecha is NULL ";

    if ($usuario !== "") {
        $sql .= " AND b.usu_id=$usuario ";
    }
    $sql .= ") AS p1
        WHERE 1=1 ";
    if ($sede !== "0") {
        $sql .= " AND p1.sed_id=$sede ";
    }
    $sql .= " AND p1.tipo='No entrevistado'; ";
    return $sql;
}

function con_buscar_alumnos_no_entrevistados_graficos_barras($sede, $usuario) {
    $sql = "SELECT sede,grado,COUNT(*) as cantidad,SUM(if(tipo = 'No entrevistado', 1, 0)) AS no_entre,
SUM(if(tipo = 'Entrevistado', 1, 0)) AS si_entre
FROM (
		SELECT sed_nombre as sede,CONCAT(usu_paterno,' ',usu_materno,' ',usu_nombres) as docente,
		CONCAT(gra_nombre) as grado,alu_dni as dni,UPPER(alu_nombres) as alumno,
		IF(h.sol_id IS NULL,'No entrevistado','Entrevistado') as tipo,b.sed_id,d.gra_id
FROM tb_usuario a
		INNER JOIN tb_usuario_dictado b ON a.usu_id=b.usu_id
		INNER JOIN tb_matricula c ON b.sec_id=c.sec_id AND b.sed_id=c.sed_id  
		INNER JOIN tb_seccion d ON b.sec_id=d.sec_id AND c.sec_id=d.sec_id 
		INNER JOIN tb_grado e ON d.gra_id=e.gra_id
		INNER JOIN tb_alumno f ON c.alu_id=f.alu_id
		INNER JOIN tb_sede g ON b.sed_id=g.sed_id AND c.sed_id=g.sed_id
		LEFT JOIN tb_solicitudes h ON c.mat_id=h.mat_id AND c.sed_id=h.sed_id
		WHERE 1=1 AND dic_estado=1 AND c.mat_estado=1 AND f.alu_estado=1 ";

    if ($usuario !== "") {
        $sql .= " AND b.usu_id=$usuario ";
    }
    if ($sede !== "0") {
        $sql .= " AND b.sed_id=$sede ";
    }
    $sql .= " ) as p1 GROUP BY grado ORDER BY gra_id ";
    return $sql;
}

function con_lista_solicitudes_y_subsolicitudes($sede, $codigoUsuario, $fechaInicio, $fechaFin, $privacidad) {
    $sql = "SELECT * FROM 
        (SELECT sol_id as id,
'Entrevista' as tipo,
sed_nombre as sede,
DATE(sol_fecha) as fecha,
CONCAT(gra_nombre, ' - ',REPLACE(sec_nombre,'Seccion ','')) as grado, 
alu_dni as nroDocumento,
UPPER(alu_nombres) as alumno,
ent_nombre as entrevista,
cat_nombre as categoria,
subca_nombre as subcategoria,
sol_motivo as motivo,
sol_plan_estu as planteamiento_estu,
sol_plan_entre as planteamiento_entre,
sol_acuerdos as acuerdos,
sol_informe as informe,
sol_plan_padre as plan_padre,
sol_plan_docen as plan_docen,
sol_acuerdos_1 as acuerdos_1,
sol_acuerdos_2 as acuerdos_2,
CASE sol_privacidad WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END as privacidad,
sol_duracion as duracion,
concat(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuario,
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
INNER JOIN tb_usuario k ON a.usu_id=k.usu_id
WHERE sol_fecha BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59' ";
    if ($sede !== "0") {
        $sql .= " AND b.sed_id IN ($sede) ";
    }
    if ($codigoUsuario !== "") {
        $sql .= " AND a.usu_id IN ($codigoUsuario) ";
    }
    if ($privacidad !== "0") {
        $sql .= " AND sol_privacidad in ($privacidad)";
    } else {
        $sql .= " AND sol_privacidad in ($privacidad)";
    }
    $sql .= " ORDER BY sol_fecha DESC) as p1 "
            . " UNION ";
    $sql .= " SELECT * FROM 
        (SELECT ssol_id as id,
'Sub Entrevista' as tipo,
sed_nombre as sede,
DATE(ssol_fecha) as fecha,
CONCAT(gra_nombre, ' - ',REPLACE(sec_nombre,'Seccion ','')) as grado, 
alu_dni as nroDocumento,
UPPER(alu_nombres) as alumno,
ent_nombre as entrevista,
cat_nombre as categoria,
subca_nombre as subcategoria,
ssol_motivo as motivo,
ssol_plan_estu as planteamiento_estu,
ssol_plan_entre as planteamiento_entre,
ssol_acuerdos as acuerdos,
ssol_informe as informe,
ssol_plan_padre as plan_padre,
ssol_plan_docen as plan_docen,
ssol_acuerdos_1 as acuerdos_1,
ssol_acuerdos_2 as acuerdos_2,
CASE ssol_privacidad WHEN 0 THEN 'NO' WHEN 1 THEN 'SI' END as privacidad,
ssol_duracion as duracion,
concat(usu_paterno,' ',usu_materno,' ',usu_nombres) as usuario,
CASE ssol_estado WHEN 0 THEN 'Inactivo' WHEN 1 THEN 'Activo' END as estado
FROM tb_sub_solicitudes a
INNER JOIN tb_matricula b ON a.mat_id=b.mat_id
INNER JOIN tb_alumno c ON b.alu_id=c.alu_id
INNER JOIN tb_seccion d ON b.sec_id=d.sec_id
INNER JOIN tb_grado e ON d.gra_id=e.gra_id
INNER JOIN tb_entrevista f ON a.ent_id=f.ent_id
INNER JOIN tb_subcategoria g ON a.subca_id=g.subca_id
INNER JOIN tb_categoria h ON g.cat_id=h.cat_id
INNER JOIN tb_sede j ON a.sed_id=j.sed_id
INNER JOIN tb_usuario k ON a.usu_id=k.usu_id
WHERE ssol_fecha BETWEEN '$fechaInicio 00:00:00' AND '$fechaFin 23:59:59' ";
    if ($sede !== "0") {
        $sql .= " AND b.sed_id IN ($sede) ";
    }
    if ($codigoUsuario !== "") {
        $sql .= " AND a.usu_id IN ($codigoUsuario) ";
    }
    if ($privacidad !== "0") {
        $sql .= " AND ssol_privacidad in ($privacidad)";
    } else {
        $sql .= " AND ssol_privacidad in ($privacidad)";
    }
    $sql .= " ORDER BY ssol_fecha DESC ) as p2 ORDER BY sede,fecha DESC,grado,alumno;";
    return $sql;
}
