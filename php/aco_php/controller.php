<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';

session_start();
$psi_usuario = $_SESSION["psi_user"]["id"];
$perfil = $_SESSION["psi_user"]["perfCod"];
$sedeCodigo = $_SESSION["psi_user"]["sedCod"];
define("p_usuario", $psi_usuario);
define("p_perfil", $perfil);
define("p_sede", $sedeCodigo);

if (isset($_POST['opcion'])) {
    $opcion = $_POST['opcion'];
    $opcion();
}

function formulario_registro_nuevo_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "", "1");
    $l_tipo_documentos = fnc_lista_tipo_documentos($conexion, "");
    $str_sede = "";
    if (p_sede === "1") {
        $str_sede = "";
    } else {
        $str_sede = p_sede;
    }
    $l_sedes = fnc_lista_sede($conexion, $str_sede);
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Tipo de Usuario: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbTipoUsuario" class="form-control select2" style="width: 100%;">
                <option value="0">-- Seleccione --</option>
                <?php
                foreach ($l_tipo_usuarios as $listatu) {
                    echo "<option value='" . $listatu["id"] . "'>" . $listatu["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Tipo de Documento: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbTipoDoc" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                foreach ($l_tipo_documentos as $listatd) {
                    echo "<option value='" . $listatd["id"] . "'>" . $listatd["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>N&uacute;mero de Documento: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtNumDoc" class="form-control" maxlength="12" style="width: 100%;"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Apellido Paterno: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtPaterno" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Apellido Materno: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtMaterno" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Nombres: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtNombres" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Correo: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtCorreo" class="form-control" style="width: 100%;text-transform: uppercase;"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Tel&eacute;fono: (Opcional) </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtTelefono" class="form-control" style="width: 100%;" maxlength="9" onkeypress="return solo_numeros(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Sede: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbSede" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                foreach ($l_sedes as $listase) {
                    echo "<option value='" . $listase["id"] . "'>" . $listase["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Sexo: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbSexo" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
            </select>
        </div>
    </div>
    <?php
}

function formulario_editar_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_e_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    $usuario_dta = fnc_lista_usuarios($conexion, $eu_codi[0], "");
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "", "1");
    $l_tipo_documentos = fnc_lista_tipo_documentos($conexion, "");
    $str_sede = "";
    if (p_sede === "1") {
        $str_sede = "";
    } else {
        $str_sede = p_sede;
    }
    $l_sedes = fnc_lista_sede($conexion, $str_sede);
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Tipo de Usuario: </label>
        </div>
        <div class="col-md-6">
            <input type="hidden" id="hdnCodiUsua" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <select id="cbbTipoUsuarioEdi" class="form-control select2" style="width: 100%;">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedtu = "";
                foreach ($l_tipo_usuarios as $listatu) {
                    if ($listatu["id"] == $usuario_dta[0]["perfId"]) {
                        $selectedtu = " selected ";
                    } else {
                        $selectedtu = "";
                    }
                    echo "<option value='" . $listatu["id"] . "' $selectedtu>" . $listatu["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Tipo de Documento: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbTipoDocEdi" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedtd = "";
                foreach ($l_tipo_documentos as $listatd) {
                    if ($listatd["id"] == $usuario_dta[0]["tipoDocId"]) {
                        $selectedtd = " selected ";
                    } else {
                        $selectedtd = "";
                    }
                    echo "<option value='" . $listatd["id"] . "' $selectedtd>" . $listatd["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>N&uacute;mero de Documento: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtNumDocEdi" class="form-control" maxlength="12" style="width: 100%;" value="<?php echo trim($usuario_dta[0]["numDoc"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Apellido Paterno: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtPaternoEdi" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);" value="<?php echo trim($usuario_dta[0]["paterno"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Apellido Materno: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtMaternoEdi" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);" value="<?php echo trim($usuario_dta[0]["materno"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Nombres: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtNombresEdi" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);" value="<?php echo trim($usuario_dta[0]["nombres"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Correo: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtCorreoEdi" class="form-control" style="width: 100%;text-transform: uppercase;" value="<?php echo trim($usuario_dta[0]["correo"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Tel&eacute;fono: (Opcional) </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtTelefonoEdi" class="form-control" style="width: 100%;" maxlength="9" onkeypress="return solo_numeros(event);" value="<?php echo trim($usuario_dta[0]["telefono"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Sede: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbSedeEdi" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedse = "";
                foreach ($l_sedes as $listase) {
                    if ($listase["id"] == $usuario_dta[0]["sedeId"]) {
                        $selectedse = " selected ";
                    } else {
                        $selectedse = "";
                    }
                    echo "<option value='" . $listase["id"] . "' $selectedse>" . $listase["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Sexo: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbSexoEdi" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedsexo = "";
                $array_sexo = array();
                array_push($array_sexo, ["id" => "M", "nombre" => "Masculino"]);
                array_push($array_sexo, ["id" => "F", "nombre" => "Femenino"]);
                foreach ($array_sexo as $listasexo) {
                    if ($listasexo["id"] == $usuario_dta[0]["sexoId"]) {
                        $selectedsexo = " selected ";
                    } else {
                        $selectedsexo = "";
                    }
                    echo "<option value='" . $listasexo["id"] . "' $selectedsexo>" . $listasexo["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Estado: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbEstadoEdi" class="form-control select2" style="width: 100%">
                <option value="-1">-- Seleccione --</option>
                <?php
                $selectedestado = "";
                $array_estado = array();
                array_push($array_estado, ["id" => "1", "nombre" => "Activo"]);
                array_push($array_estado, ["id" => "0", "nombre" => "Inactivo"]);
                foreach ($array_estado as $listestado) {
                    if ($listestado["id"] == $usuario_dta[0]["estado"]) {
                        $selectedestado = " selected ";
                    } else {
                        $selectedestado = "";
                    }
                    echo "<option value='" . $listestado["id"] . "' $selectedestado>" . $listestado["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}

function operacion_editar_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiUsuIdEdi = strip_tags(trim($_POST["u_codiUsuIdEdi"]));
    $u_tipoUsuarioEdi = strip_tags(trim($_POST["u_tipoUsuarioEdi"]));
    $u_tipoDocEdi = strip_tags(trim($_POST["u_tipoDocEdi"]));
    $u_numDocEdi = strip_tags(trim($_POST["u_numDocEdi"]));
    $u_paternoEdi = strip_tags(trim($_POST["u_paternoEdi"]));
    $u_maternoEdi = strip_tags(trim($_POST["u_maternoEdi"]));
    $u_nombresEdi = strip_tags(trim($_POST["u_nombresEdi"]));
    $u_correoEdi = strip_tags(trim($_POST["u_correoEdi"]));
    $u_telefonoEdi = strip_tags(trim($_POST["u_telefonoEdi"]));
    $u_sedeEdi = strip_tags(trim($_POST["u_sedeEdi"]));
    $u_sexoEdi = strip_tags(trim($_POST["u_sexoEdi"]));
    $u_estadoEdi = strip_tags(trim($_POST["u_estadoEdi"]));

    $valicant_ndoced = fnc_lista_tipo_documentos($conexion, $u_tipoDocEdi);
    $usuario_dta = fnc_lista_usuarios($conexion, $u_codiUsuIdEdi, "");
    $boolean = true;
    if (count($valicant_ndoced) > 0) {
        if ($valicant_ndoced[0]["cantidad"] * 1 !== strlen($u_numDocEdi) * 1) {
            echo "***0***La cantidad de dígitos para el tipo de documento " . $valicant_ndoced[0]["nombre"] . " debe ser " . $valicant_ndoced[0]["cantidad"] . ".<br/>";
        } else {
            if ($usuario_dta[0]["numDoc"] !== $u_numDocEdi) {
                $vali_ndoced = fnc_validar_existe_nro_documento($conexion, $u_tipoDocEdi, $u_numDocEdi);
                if (count($vali_ndoced) > 0) {
                    $boolean = false;
                    echo "***0***El número de documento ya esta registrado, favor de ingresar otro.<br/>";
                } else {
                    $boolean = true;
                }
            }
            if ($boolean) {
                if ($usuario_dta[0]["correo"] !== $u_correoEdi) {
                    $vali_correoed = fnc_validar_exite_correo($conexion, $u_correoEdi);
                    if (count($vali_correoed) > 0) {
                        $boolean = false;
                        echo "***0***El correo electrónico ya esta registrado, favor de ingresar otro.<br/>";
                    } else {
                        $boolean = true;
                    }
                }
                if ($boolean) {
                    $str_submenu = "";
                    $str_menu_id = "";
                    $str_menu_nombre = "";
                    $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
                    if (count($submenu) > 0) {
                        $str_submenu = $submenu[0]["ruta"];
                        $str_menu_id = $submenu[0]["id"];
                        $str_menu_nombre = $submenu[0]["nombre"];
                    } else {
                        $str_submenu = "";
                        $str_menu_id = "";
                        $str_menu_nombre = "";
                    }
                    try {
                        fnc_editar_usuario($conexion, $u_codiUsuIdEdi, $u_tipoUsuarioEdi, $u_tipoDocEdi, $u_numDocEdi, strtoupper($u_paternoEdi), strtoupper($u_maternoEdi), strtoupper($u_nombresEdi), strtolower($u_correoEdi), $u_telefonoEdi, $u_sedeEdi, $u_sexoEdi, $u_estadoEdi);
                        $sql_auditoria = fnc_editar_usuario_auditoria($u_codiUsuIdEdi, $u_tipoUsuarioEdi, $u_tipoDocEdi, $u_numDocEdi, strtoupper($u_paternoEdi), strtoupper($u_maternoEdi), strtoupper($u_nombresEdi), strtolower($u_correoEdi), $u_telefonoEdi, $u_sedeEdi, $u_sexoEdi, $u_estadoEdi);
                        $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_editar_usuario" . '", "' . "fnc_editar_usuario" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_usuario" . '","' . p_usuario . '",NOW(),"1"';
                        fnc_registrar_auditoria($conexion, $sql_insert);
                        echo "***1***Usuario editado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
                    } catch (Exception $exc) {
                        echo "***0***Error al editar usuario.***<br/>";
                    }
                }
            }
        }
    } else {
        echo "***0***Error al verificar dígitos del número de documento.***<br/>";
    }
}

function formulario_eliminar_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_el_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiUsuaEli" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar el estado del usuario a inactivo?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiUsuIdEli = strip_tags(trim($_POST["u_codiUsuIdEli"]));
    try {
        fnc_eliminar_usuario($conexion, $u_codiUsuIdEli);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $sql_auditoria = fnc_eliminar_usuario_auditoria($u_codiUsuIdEli);
        $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_usuario" . '", "' . "fnc_eliminar_usuario" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_usuario" . '","' . p_usuario . '",NOW(),"1"';
        fnc_registrar_auditoria($conexion, $sql);
        echo "***1***Usuario eliminado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar usuario.***<br/>";
    }
}

function formulario_cambiar_clave_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_cc_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    $usuario_dta = fnc_lista_usuarios($conexion, $eu_codi[0], "");
    $str_nombre = "";
    if (count($usuario_dta) > 0) {
        $str_nombre = $usuario_dta[0]["nombres"] . " " . $usuario_dta[0]["paterno"] . " " . $usuario_dta[0]["materno"];
    }
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiUsuaCam" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar la contrase&ntilde;a del usuario <?php echo trim($str_nombre); ?>?</label><br/>
            <span><i class="nav-icon fa fa-info-circle" style="color: skyblue"></i> Nota: Al dar clic en Cambiar contrase&ntilde;a, el sistema crear&aacute; una nueva contrase&ntilde;a y se le enviar&aacute; a su correo institucional.</span>
        </div>
    </div>
    <?php
}

function formulario_registro_nuevo_menu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $l_iconos = fnc_lista_iconos($conexion, "");
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtDescripcion" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Imagen: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbImagen" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                foreach ($l_iconos as $iconos) {
                    echo "<option value='" . $iconos["imagen"] . "' data-content=" . '"' . "<i class='" . $iconos["imagen"] . "'>&nbsp;&nbsp;" . $iconos["nombre"] . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Nombre de carpeta: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtCarpeta" class="form-control" style="width: 100%;text-transform: lowercase;" onkeypress="return solo_letras(event);" 
                   placeholder="Ejm: prueba"/>
        </div>
    </div>
    <?php
}

function proceso_registro_nuevo_menu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $m_descripcion = strip_tags(trim($_POST["m_descripcion"]));
    $m_imagen = strip_tags(trim($_POST["m_imagen"]));
    $m_carpeta = strip_tags(trim($_POST["m_carpeta"]));
    $m_codigo = substr($m_descripcion, 0, 5) . "_" . fnc_generate_random_string(5);
    $registrar_menu = fnc_registrar_menu($conexion, $m_codigo, strtoupper($m_descripcion), $m_imagen, "aco_" . $m_carpeta);
    if ($registrar_menu) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        if (count($submenu) > 0) {
            $sql_auditoria = fnc_registrar_menu_auditoria($m_codigo, strtoupper($m_descripcion), $m_imagen, "aco_" . $m_carpeta);
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registro_nuevo_menu" . '", "' . "fnc_registrar_menu" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_menu" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
        }


        echo "***1***Menú registrado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al registrar menú.***<br/>";
    }
}

function formulario_editar_menu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $l_iconos = fnc_lista_iconos($conexion, "");
    $u_em_codigo = strip_tags(trim($_POST["u_em_codigo"]));
    $eu_codmenu = explode("-", $u_em_codigo);
    $menu_codi = explode("/", $eu_codmenu[1]);
    $lista_menu = fnc_lista_menus($conexion, $menu_codi[0], "");
    $carpeta = explode("_", $lista_menu[0]["carpeta"]);
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="hidden" id="hdnCodiMenu" class="form-control" value="<?php echo trim($menu_codi[0]); ?>"/>
            <input type="text" id="txtDescripcionEdi" class="form-control" style="width: 100%;text-transform: uppercase;" 
                   onkeypress="return solo_letras(event);" value="<?php echo trim($lista_menu[0]["nombre"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Imagen: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbImagenEdi" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedme = "";
                foreach ($l_iconos as $lista_iconos) {
                    if ($lista_iconos["imagen"] == $lista_menu[0]["imagen"]) {
                        $selectedme = " selected ";
                    } else {
                        $selectedme = "";
                    }
                    echo "<option value='" . $lista_iconos["imagen"] . "' $selectedme data-content=" . '"' . "<i class='" . $lista_iconos["imagen"] . "'>&nbsp;&nbsp;" . $lista_iconos["nombre"] . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Nombre de carpeta: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtCarpetaEdi" class="form-control" style="width: 100%;text-transform: lowercase;" 
                   onkeypress="return solo_letras(event);" placeholder="Ejm: prueba" value="<?php echo trim($carpeta[1]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Estado: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbEstadoMeEdi" class="form-control select2" style="width: 100%">
                <option value="-1">-- Seleccione --</option>
                <?php
                $selectedestado = "";
                $array_estado = array();
                array_push($array_estado, ["id" => "1", "nombre" => "Activo"]);
                array_push($array_estado, ["id" => "0", "nombre" => "Inactivo"]);
                foreach ($array_estado as $listestado) {
                    if ($listestado["id"] == $lista_menu[0]["estadoId"]) {
                        $selectedestado = " selected ";
                    } else {
                        $selectedestado = "";
                    }
                    echo "<option value='" . $listestado["id"] . "' $selectedestado>" . $listestado["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}

function proceso_editar_menu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $m_codigoEdi = strip_tags(trim($_POST["m_codigoEdi"]));
    $m_descripcion = strip_tags(trim($_POST["m_descripcionEdi"]));
    $m_imagen = strip_tags(trim($_POST["m_imagenEdi"]));
    $m_carpeta = strip_tags(trim($_POST["m_carpetaEdi"]));
    $m_estadoMe = strip_tags(trim($_POST["m_estadoMeEdi"]));
    $nombre_carpeta = "aco_" . $m_carpeta;

    $m_codigo = substr($m_descripcion, 0, 5) . "_" . fnc_generate_random_string(5);
    $menuData = fnc_lista_menus($conexion, $m_codigoEdi, "");
    $strCodigo = "";
    if (count($menuData) > 0) {
        if (trim($menuData[0]["codigo"]) === "") {
            $strCodigo = $m_codigo;
        } else {
            $strCodigo = "";
        }
    } else {
        $strCodigo = "";
    }
    try {
        $editar = fnc_editar_menu($conexion, $m_codigoEdi, $strCodigo, strtoupper($m_descripcion), $m_imagen, $nombre_carpeta, $m_estadoMe);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        if ($editar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_menu_auditoria($m_codigoEdi, $strCodigo, strtoupper($m_descripcion), $m_imagen, $nombre_carpeta, $m_estadoMe);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_menu" . '", "' . "fnc_editar_menu" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Menú editado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al editar menú.***<br/>";
    }
}

function formulario_eliminar_menu() {
    $eu_codigo = strip_tags(trim($_POST["u_elmenu_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiMenuEli" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar el estado del men&uacute; a inactivo?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_menu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiMenuIdEli = strip_tags(trim($_POST["u_codiMenuIdEli"]));
    try {
        $eliminar = fnc_eliminar_menu($conexion, $u_codiMenuIdEli);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($eliminar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_eliminar_menu_auditoria($u_codiMenuIdEli);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_menu" . '", "' . "fnc_eliminar_menu" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Menú eliminado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar menú.***<br/>";
    }
}

function formulario_registro_nuevo_submenu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $lsm_menus = fnc_lista_menus($conexion, "", "1");
    $lsm_iconos = fnc_lista_iconos($conexion, "");
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtDescripcionSub" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Men&uacute;: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbMenus" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                foreach ($lsm_menus as $menus) {
                    echo "<option value='" . $menus["id"] . "'>" . $menus["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Imagen:</label>
        </div>
        <div class="col-md-6">
            <select id="cbbSubImagen" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                foreach ($lsm_iconos as $iconos) {
                    echo "<option value='" . $iconos["imagen"] . "' data-content=" . '"' . "<i class='" . $iconos["imagen"] . "'>&nbsp;&nbsp;" . $iconos["nombre"] . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Link: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtLinkSub" class="form-control" style="width: 100%;text-transform: lowercase;" placeholder="Ejm: lista" onkeypress="return solo_letras_v(event);"/>
        </div>
    </div>
    <?php
}

function proceso_registro_nuevo_submenu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $s_descripcion = strip_tags(trim($_POST["s_descripcion"]));
    $s_menu = strip_tags(trim($_POST["s_menu"]));
    $s_imagen = strip_tags(trim($_POST["s_imagen"]));
    $s_link = strip_tags(trim($_POST["s_link"]));
    $m_codigo = substr($s_descripcion, 0, 5) . "_" . fnc_generate_random_string(5);
    $m_orden = fnc_consulta_ultimo_orden_menu($conexion, $s_menu);
    $lista_menu = fnc_lista_menus($conexion, $s_menu, "");
    $str_orden = "";
    if ($m_orden[0]["cantidad"] == 0) {
        $str_orden = $s_menu . "001";
    } else {
        $cadena = substr(str_repeat(0, 3) . $m_orden[0]["cantidad"], -3);
        $str_orden = ($s_menu . $cadena) * 1 + 1;
    }
    $strUrl = "./php/aco_view/" . $lista_menu[0]["carpeta"] . "/" . substr($lista_menu[0]["carpeta"], 4, 4) . "_" . $s_link . ".php";
    $registrar_menu = fnc_registrar_submenu($conexion, $str_orden, $m_codigo, ucwords(strtolower($s_descripcion)), $s_menu, $s_imagen, $strUrl);
    if ($registrar_menu) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        if (count($submenu) > 0) {
            $sql_auditoria = fnc_registrar_submenu_auditoria($str_orden, $m_codigo, ucwords(strtolower($s_descripcion)), $s_menu, $s_imagen, $strUrl);
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registro_nuevo_submenu" . '", "' . "fnc_registrar_submenu" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_submenu" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
        }
        echo "***1***Submenú registrado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al registrar submenú.***<br/>";
    }
}

function formulario_editar_submenu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $lsm_menus = fnc_lista_menus($conexion, "", "1");
    $lsm_iconos = fnc_lista_iconos($conexion, "");
    $u_em_codigo = strip_tags(trim($_POST["u_esub_codigo"]));
    $eu_codsubmenu = explode("-", $u_em_codigo);
    $submenu_codi = explode("/", $eu_codsubmenu[1]);
    $lista_submenu = fnc_lista_submenus($conexion, $submenu_codi[0], "");
    $enlace = explode("_", $lista_submenu[0]["link"]);
    $enlace_ruta = explode(".php", $enlace[3]);
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="hidden" id="hdnCodiSubMenu" class="form-control" value="<?php echo trim($submenu_codi[0]); ?>"/>
            <input type="text" id="txtDescripcionSubEdi" class="form-control" style="width: 100%;text-transform: uppercase;"
                   onkeypress="return solo_letras(event);" value="<?php echo trim($lista_submenu[0]["nombre"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Men&uacute;: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbMenusEdi" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectemenu = "";
                foreach ($lsm_menus as $menus) {
                    if ($menus["id"] == $lista_submenu[0]["menuId"]) {
                        $selectemenu = " selected ";
                    } else {
                        $selectemenu = "";
                    }
                    echo "<option value='" . $menus["id"] . "' $selectemenu>" . $menus["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Imagen:</label>
        </div>
        <div class="col-md-6">
            <select id="cbbSubImagenEdi" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedimagen = "";
                foreach ($lsm_iconos as $iconos) {
                    if ($iconos["imagen"] == $lista_submenu[0]["imagen"]) {
                        $selectedimagen = " selected ";
                    } else {
                        $selectedimagen = "";
                    }
                    echo "<option value='" . $iconos["imagen"] . "' $selectedimagen data-content=" . '"' . "<i class='" . $iconos["imagen"] . "'>&nbsp;&nbsp;" . $iconos["nombre"] . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Link: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtLinkSubEdi" class="form-control" style="width: 100%;text-transform: lowercase;" placeholder="Ejm: lista" value="<?php echo trim($enlace_ruta[0]); ?>" onkeypress="return solo_letras_v(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Estado: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbEstadosubEdi" class="form-control select2" style="width: 100%">
                <option value="-1">-- Seleccione --</option>
                <?php
                $selectedestado = "";
                $array_estado = array();
                array_push($array_estado, ["id" => "1", "nombre" => "Activo"]);
                array_push($array_estado, ["id" => "0", "nombre" => "Inactivo"]);
                foreach ($array_estado as $listestado) {
                    if ($listestado["id"] == $lista_submenu[0]["estadoId"]) {
                        $selectedestado = " selected ";
                    } else {
                        $selectedestado = "";
                    }
                    echo "<option value='" . $listestado["id"] . "' $selectedestado>" . $listestado["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}

function proceso_editar_submenu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $s_codisubmenu = strip_tags(trim($_POST["sub_codisubmenu"]));
    $s_descripcion = strip_tags(trim($_POST["sub_descripcion"]));
    $s_menu = strip_tags(trim($_POST["sub_menu"]));
    $s_imagen = strip_tags(trim($_POST["sub_imagen"]));
    $s_link = strip_tags(trim($_POST["sub_link"]));
    $s_estado = strip_tags(trim($_POST["sub_estado"]));
    $m_codigo = substr($s_descripcion, 0, 5) . "_" . fnc_generate_random_string(5);
//$m_orden = fnc_consulta_ultimo_orden_menu($conexion, $s_menu);
    $lista_submenu = fnc_lista_submenus($conexion, $s_codisubmenu, "");
    /* $str_orden = "";
      if ($m_orden[0]["cantidad"] == 0) {
      $str_orden = $s_menu . "001";
      } else {
      $cadena = substr(str_repeat(0, 3) . $m_orden[0]["cantidad"], -3);
      $str_orden = ($s_menu . $cadena) * 1 + 1;
      } */
    $rutaArray = explode("_", $lista_submenu[0]["link"]);
    $strUrl = $rutaArray[0] . "_" . $rutaArray[1] . "_" . $rutaArray[2] . "_" . $s_link . ".php";
    $strCodigo = "";
    if (count($lista_submenu) > 0) {
        if (trim($lista_submenu[0]["codigo"]) === "") {
            $strCodigo = $m_codigo;
        } else {
            $strCodigo = "";
        }
    } else {
        $strCodigo = "";
    }
    try {
        $editar = fnc_editar_submenu($conexion, $s_codisubmenu, "", $strCodigo, ucwords(strtolower($s_descripcion)), $s_menu, $s_imagen, $strUrl, $s_estado);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        if ($editar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_submenu_auditoria($s_codisubmenu, "", $strCodigo, ucwords(strtolower($s_descripcion)), $s_menu, $s_imagen, $strUrl, $s_estado);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_submenu" . '", "' . "fnc_editar_submenu" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_submenu" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Submenú editado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $ex) {
        echo "***0***Error al editar submenú.***<br/>";
    }
}

function formulario_eliminar_submenu() {
    $eu_codigo = strip_tags(trim($_POST["u_elsub_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiSubmenuEli" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar el estado del submen&uacute; a inactivo?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_submenu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiSubmenuIdEli = strip_tags(trim($_POST["u_codiSubmenuIdEli"]));
    try {
        $eliminar = fnc_eliminar_submenu($conexion, $u_codiSubmenuIdEli);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        if ($eliminar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_eliminar_submenu_auditoria($u_codiSubmenuIdEli);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_submenu" . '", "' . "fnc_eliminar_submenu" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_submenu" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Submenú eliminado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar submenú.***<br/>";
    }
}

function formulario_registro_nuevo_perfil() {
    $con = new DB(1111);
    $conexion = $con->connect();
    ?>
    <div class="row space-div">
        <div class="col-md-3" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-3">
            <input type="text" id="txtDescripcionPer" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <fieldset class="col-md-12 fieldset" id="listFieldset">
            <legend class="legend">ACCESOS OPCIONES</legend>
            <?php
            $html = "";
            $lista_menu = fnc_lista_menus($conexion, "", "1");
            foreach ($lista_menu as $lista) {
                $menu = $lista["id"];
                $lista_submenu = fnc_submenu_x_menu_perfil($conexion, $menu);
                if (count($lista_submenu) > 0) {
                    $html .= "<fieldset id='' class='col-md-12 fieldset_menu'>"
                            . "<legend class='legend_menu'>" . $lista["nombre"] . "</legend>";
                    foreach ($lista_submenu as $lista_s) {
                        $html .= "<div class='custom-control custom-checkbox'>
                          <input class='custom-control-input' style='z-index: 1000 !important;' type='checkbox' id='customCheckbox" . $lista_s["id"] . "' value='" . $lista_s["id"] . "'>
                          <label for='customCheckbox" . $lista_s["id"] . "' class='custom-control-label'>" . $lista_s["submenu"] . "</label>
                        </div>";
                    }
                    $html .= "</fieldset><br/>";
                }
            }
            echo $html;
            ?>
        </fieldset>
    </div>
    <?php
}

function proceso_nuevo_perfil() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $per_descripcion = strip_tags(trim($_POST["per_descripcion"]));
    $per_lista = strip_tags(trim($_POST["per_lista"]));
    $str_codigo = substr(ucfirst($per_descripcion), 0, 4) . "_" . fnc_generate_random_string(5);
    try {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $lastInsertPerfil = fnc_registrar_perfil($conexion, $str_codigo, ucfirst($per_descripcion));
        if ($lastInsertPerfil !== "0") {
            $str_inserts = "";
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_perfil_auditoria($str_codigo, ucfirst($per_descripcion));
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_nuevo_perfil" . '", "' . "fnc_registrar_perfil" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_perfil" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }

            if ($per_lista !== "") {
                $lista_submenu_perfil = explode("*", $per_lista);
                if (count($lista_submenu_perfil) > 0) {
                    for ($i = 0; $i < count($lista_submenu_perfil); $i++) {
                        $str_inserts .= "('" . $lastInsertPerfil . "','" . $lista_submenu_perfil[$i] . "','1'),";
                    }
                    $str_lista_insert = substr($str_inserts, 0, -1);
                    if ($str_lista_insert !== "") {
                        $registrar_accesos = fnc_registrar_accesos_perfil($conexion, $str_lista_insert);
                        if ($registrar_accesos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_registrar_accesos_perfil_auditoria($str_lista_insert);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_nuevo_perfil" . '", "' . "fnc_registrar_accesos_perfil" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    }
                }
            }
            echo "***1***Perfil registrado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        } else {
            echo "***0***Error al registrar el perfil.***<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al registrar el perfil.***<br/>";
    }
}

function formulario_editar_perfil() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_perf_codigo = strip_tags(trim($_POST["u_eperf_codigo"]));
    $eu_codsubmenu = explode("-", $u_perf_codigo);
    $perfil_codi = explode("/", $eu_codsubmenu[1]);
    $lista_perfil = fnc_lista_tipo_usuarios($conexion, $perfil_codi[0], "");
    $lista_accesos = fnc_lista_menu_asigna($conexion, $perfil_codi[0]);
    ?>
    <div class="row space-div">
        <div class="col-md-3" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiPerfil" class="form-control" value="<?php echo trim($perfil_codi[0]); ?>"/>
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-3">
            <input type="text" id="txtDescripcionPerEdi" class="form-control" style="width: 100%;text-transform: uppercase;"
                   onkeypress="return solo_letras(event);" value="<?php echo trim($lista_perfil[0]["nombre"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-3" style="margin-bottom: 0px;">
            <label>Estado: </label>
        </div>
        <div class="col-md-3">
            <select id="cbbEstadoPerEdi" class="form-control select2" style="width: 100%">
                <option value="-1">-- Seleccione --</option>
                <?php
                $selectedestado = "";
                $array_estado = array();
                array_push($array_estado, ["id" => "1", "nombre" => "Activo"]);
                array_push($array_estado, ["id" => "0", "nombre" => "Inactivo"]);
                foreach ($array_estado as $listestado) {
                    if ($listestado["id"] == $lista_perfil[0]["estadoId"]) {
                        $selectedestado = " selected ";
                    } else {
                        $selectedestado = "";
                    }
                    echo "<option value='" . $listestado["id"] . "' $selectedestado>" . $listestado["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <fieldset class="col-md-12 fieldset" id="listFieldsetEdi">
            <legend class="legend">ACCESOS OPCIONES</legend>
            <?php
            $html = "";
            $lista_menu = fnc_lista_menus($conexion, "", "1");
            $checked = "";
            $idMenuAsig = 0;
            foreach ($lista_menu as $lista) {
                $menu = $lista["id"];
                $lista_submenu = fnc_submenu_x_menu_perfil($conexion, $menu);
                if (count($lista_submenu) > 0) {
                    $html .= "<fieldset id='' class='col-md-12 fieldset_menu'>"
                            . "<legend class='legend_menu'>" . $lista["nombre"] . "</legend>";
                    foreach ($lista_submenu as $lista_s) {
                        $dat = array_search($lista_s["id"], array_column($lista_accesos, 'menId'));
                        if ($dat !== false) {
                            if ($lista_accesos[$dat]["estado"] == "1") {
                                $checked = " checked ";
                            } else {
                                $checked = "";
                            }
                            $idMenuAsig = $lista_accesos[$dat]["id"];
                        } else {
                            $checked = "";
                            $idMenuAsig = 0;
                        }
                        $html .= "<div class='custom-control custom-checkbox'>
                          <input class='custom-control-input' style='z-index: 1000 !important;' type='checkbox' id='customCheckbox" . $lista_s["id"] . "' $checked value='" . $idMenuAsig . "_" . $lista_s["id"] . "'>
                          <label for='customCheckbox" . $lista_s["id"] . "' class='custom-control-label'>" . $lista_s["submenu"] . "</label>
                        </div>";
                    }
                    $html .= "</fieldset><br/>";
                }
            }
            echo $html;
            ?>
        </fieldset>
    </div>
    <?php
}

function proceso_editar_perfil() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $perf_codigo = strip_tags(trim($_POST["perf_codigo"]));
    $perf_descripcion = strip_tags(trim($_POST["perf_descripcion"]));
    $perf_estado = strip_tags(trim($_POST["perf_estado"]));
    $perf_lista = strip_tags(trim($_POST["perf_lista"]));
    try {
        $editar = fnc_editar_perfil($conexion, $perf_codigo, $perf_descripcion, $perf_estado);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $str_inserts = "";
        $str_updates = "";
        if ($editar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_perfil_auditoria($perf_codigo, $perf_descripcion, $perf_estado);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_perfil" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_perfil" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        if ($perf_lista !== "") {
            $lista_submenu_perfil = explode("*", $perf_lista);
            if (count($lista_submenu_perfil) > 0) {
                for ($i = 0; $i < count($lista_submenu_perfil); $i++) {
                    $array_da = explode("_", $lista_submenu_perfil[$i]);
                    if (count($array_da) > 1) {
                        if ($array_da[0] === "0") {//nuevo registro perfil
                            $str_inserts .= "('" . $perf_codigo . "','" . $array_da[1] . "','1'),";
                        } else {//modificar perfil
                            $str_updates .= "" . $array_da[0] . ",";
                        }
                    }
                }
                $str_inserts = substr($str_inserts, 0, -1);
                $str_updates = substr($str_updates, 0, -1);
                if (trim($str_inserts) === "") {
                    if (trim($str_updates) !== "") {
                        $editar_accesos_perfil = fnc_editar_accesos_perfil($conexion, $str_updates, $perf_codigo);
                        if ($editar_accesos_perfil) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_editar_accesos_perfil_auditoria($str_updates, $perf_codigo);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                        $editar_accesos_todos = fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                        if ($editar_accesos_todos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_editar_accesos_perfil_todos_auditoria($str_updates, $perf_codigo);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil_todos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    } else {
                        $editar_accesos_todos = fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                        if ($editar_accesos_todos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_editar_accesos_perfil_todos_auditoria($str_updates, $perf_codigo);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil_todos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    }
                }
                if (trim($str_inserts) !== "") {
                    if (trim($str_updates) !== "") {
                        $editar_accesos_perfil = fnc_editar_accesos_perfil($conexion, $str_updates, $perf_codigo);
                        if ($editar_accesos_perfil) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_editar_accesos_perfil_auditoria($str_updates, $perf_codigo);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                        $editar_accesos_todos = fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                        if ($editar_accesos_todos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_editar_accesos_perfil_todos_auditoria($str_updates, $perf_codigo);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil_todos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                        if ($str_inserts !== "") {
                            $registrar_accesos = fnc_registrar_accesos_perfil($conexion, $str_inserts);
                            if ($registrar_accesos) {
                                if (count($submenu) > 0) {
                                    $sql_auditoria = fnc_registrar_accesos_perfil_auditoria($str_inserts);
                                    $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_registrar_accesos_perfil" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                    fnc_registrar_auditoria($conexion, $sql_insert);
                                }
                            }
                        }
                    } else {
                        $editar_accesos_todos = fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                        if ($editar_accesos_todos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_editar_accesos_perfil_todos_auditoria($str_updates, $perf_codigo);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil_todos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                        $registrar_accesos = fnc_registrar_accesos_perfil($conexion, $str_inserts);
                        if ($registrar_accesos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_registrar_accesos_perfil_auditoria($str_inserts);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_registrar_accesos_perfil" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    }
                }
            }
        } else {
            //Aqui se cambia de estado a todos
            $editar_accesos_todos = fnc_editar_accesos_perfil_todos($conexion, "", $perf_codigo);
            if ($editar_accesos_todos) {
                if (count($submenu) > 0) {
                    $sql_auditoria = fnc_editar_accesos_perfil_todos_auditoria("", $perf_codigo);
                    $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_perfil" . '", "' . "fnc_editar_accesos_perfil_todos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_menu_asigna" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                    fnc_registrar_auditoria($conexion, $sql_insert);
                }
            }
        }
        echo "***1***Perfil editado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $ex) {
        echo "***0***Error al editar perfil.***<br/>";
    }
}

function formulario_eliminar_perfil() {
    $eu_codigo = strip_tags(trim($_POST["u_eliperf_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiPerfilEli" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar el estado del perfil a inactivo?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_perfil() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiPerfilIdEli = strip_tags(trim($_POST["u_codiPerfilIdEli"]));
    try {
        $eliminar = fnc_eliminar_perfil($conexion, $u_codiPerfilIdEli);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($eliminar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_eliminar_perfil_auditoria($u_codiPerfilIdEli);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_perfil" . '", "' . "fnc_eliminar_perfil" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_perfil" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Perfil eliminado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar perfil.***<br/>";
    }
}

function load_modal_carga_alumnos() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = $_SESSION["psi_user"]["id"];
    $lista = func_lista_data_tabla_tmp_carga_alumnos($conexion, $codigo);
    $aux = 1;
    $color = "";
    $salida_alu = "";
    $salida_sede = "";
    $salida_secc = "";
    $str_valida = "";
    $str_validacion = "";
    $count1 = 0;
    $count2 = 0;
    $count_t = 0;
    $html = "<input type='hidden' id='hdnNumeral' value='" . $codigo . "'><div class='col-md-12 table-responsive' id='divPreCargaAlumnos' >"
            . "<table id='tablePreCargaAlumnos' class='table' style='font-size: 13px;width:100% '>"
            . "<thead>"
            . "<th>Nro.</th>"
            . "<th>Cod. Alumno</th>"
            . "<th>Apellidos y nombres</th>"
            . "<th>DNI</th>"
            . "<th>Tipo Matricula</th>"
            . "<th>Grado</th>"
            . "<th>Sección</th>"
            . "<th>Sede</th>"
            . "<th>Estado</th>"
            . "</thead><tbody>";
    foreach ($lista as $value) {
        if ($value["actual_alu_id"] == "0") {
            $color = "";
            $salida_alu = "Alumno no existe*";
            $salida_sede = "";
            $salida_secc = "";
            if (trim($value["grado"]) === "") {
                $salida_secc = "El grado esta vacio*";
            }
            if (trim($value["seccion"]) === "") {
                $salida_secc .= "La seccion esta vacia*";
            }
            $count1++;
        } else {
            if ($value["actual_alu_id"] !== "0") {
                $salida_alu = 'Alumno ya existe*';
            }
            if ($value["registrar_sed_id"] !== 0) {
                $salida_sede = 'Alumno ya registrado en la sede*';
            } else {
                $salida_sede = '';
            }
            if ($value["registrar_sec_id"] !== 0) {
                $salida_secc = 'Alumno ya registrado en la seccion*';
            } else {
                $salida_secc = '';
            }
            $count2++;
        }
        $validacion = $salida_alu . $salida_sede . $salida_secc;
        $array_fila = explode("*", $validacion);
        if (count($array_fila) > 1) {
            if (count($array_fila) === 2) {
                $color = "color:orange";
            } else if (count($array_fila) === 3) {
                $color = "color:brown";
            } else {
                $color = "color:red";
            }
            $count_t++;
            $validacion = substr($validacion, 0, -1);
            $str_validacion = str_replace("*", "<br/>", $validacion);
        } else {
            $array_fila2 = explode("--", $validacion);
            if (count($array_fila2) > 1) {
                $color = "color:red";
                $count_t++;
                $validacion = str_replace("--", "<br/>", $validacion);
                $str_validacion = $validacion;
            }
        }
        $html .= "<tr style='$color'>"
                . "<td>$aux</td>"
                . "<td>" . $value["cod_alumno"] . "</td>"
                . "<td>" . $value["nombres"] . "</td>"
                . "<td>" . $value["dni"] . "</td>"
                . "<td>" . $value["tip_alu"] . "</td>"
                . "<td>" . $value["grado"] . "</td>"
                . "<td>" . $value["seccion"] . "</td>"
                . "<td>" . $value["sede"] . "</td>"
                . "<td>" . $str_validacion . "</td>"
                . "</tr>";
        $aux++;
    }
    $html .= "</tbody></table></div>"
            . "<div class='col-md-12'>"
            . "<span class='text-bold text'>Alumnos nuevos: </span><span class='badge bg-info'>" . $count1 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Alumnos ya registrados: </span><span class='badge bg-danger'>" . $count2 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Total de alumnos: </span><span class='badge bg-success'>" . (count($lista)) . "</span>"
            . "</div>"
            . "<div class='col-md-12'>"
            . "<span><i class='nav-icon fa fa-info-circle' style='color: skyblue'></i> Nota: Solo se cargaran los registros que estan de color negro.</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class='text-bold text'>Registros que se cargarán: </span><span class='badge bg-success'>" . (count($lista) - $count_t) . "</span>"
            . "</div>";
    echo $html;
}

function formulario_confirmacion_carga_alumnos() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = $_SESSION["psi_user"]["id"];
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnNumeral" class="form-control" value="<?php echo trim($codigo); ?>"/>
            <label>&iquest;Esta seguro de realizar la carga de alumnos?</label>
        </div>
    </div>
    <?php
}

function operacion_registrar_carga_alumnos() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codPerson = strip_tags(trim($_POST["u_codPerson"]));
    $cadenaInsertGrupo = "";
    $cadenaInsertAlumno = "";
    $cadenaInsertMatri = "";
    try {
        $lista = fnc_lista_data_validos_tabla_tmp_carga_alumnos($conexion, $u_codPerson);
        if (count($lista) > 0) {
            $str_codigo = "Grupo" . "_" . fnc_generate_random_string(7);
            $cadenaInsertGrupo = "('" . $str_codigo . "','" . $u_codPerson . "',NOW(),'1')";
            $grupo_creado = fnc_registrar_grupo($conexion, $cadenaInsertGrupo);
            if ($grupo_creado) {
                foreach ($lista as $value) {
                    if (trim($value["grado"]) !== "" && trim($value["seccion"] !== "")) {
                        $cadenaInsertAlumno = '("' . $value["dni"] . '","' . $value["nombres"] . '","' . $value["cod_alumno"] . '","' .
                                $value["cor_alu"] . '","1")';
                        if ($value["actual_alu_id"] == 0) {
                            $aluCodigo = fnc_registrar_data_tmp_a_alumno($conexion, $cadenaInsertAlumno);
                            if ($aluCodigo) {
                                $cadenaInsertApoderado = ''
                                        . '("' . $aluCodigo . '","1","' . $value["nom_padre"] . '","' . $value["cor_padre"] . '","' . $value["cel_padre"] . '","' . $value["apo_dir"] . '","' . $value["dis_apo"] . '","1"),'
                                        . '("' . $aluCodigo . '","2","' . $value["nom_madre"] . '","' . $value["cor_madre"] . '","' . $value["cel_madre"] . '","' . $value["apo_dir"] . '","' . $value["dis_apo"] . '","1")';
                                fnc_registrar_data_tmp_a_apoderado($conexion, $cadenaInsertApoderado);
                            }
                            if ($aluCodigo && $value["valida_matricula"] == 0) {
                                $cadenaInsertMatri = "('" . $aluCodigo . "','" . $value["sede"] . "','" . $value["registrar_sec_id"] . "','" .
                                        $value["tip_alu"] . "',NOW(),'" . $grupo_creado . "','1')";
                                fnc_registrar_matricula_alumno($conexion, $cadenaInsertMatri);
                            }
                        } else {
                            if ($value["valida_matricula"] == 0) {
                                $cadenaInsertMatri = "('" . $value["actual_alu_id"] . "','" . $value["sede"] . "','" . $value["registrar_sec_id"] . "','" .
                                        $value["tip_alu"] . "',NOW(),'" . $grupo_creado . "','1')";
                                fnc_registrar_matricula_alumno($conexion, $cadenaInsertMatri);
                            }
                        }
                    }
                }
            }
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
            $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
            if (count($submenu) > 0) {
                $str_submenu = $submenu[0]["ruta"];
                $str_menu_id = $submenu[0]["id"];
                $str_menu_nombre = $submenu[0]["nombre"];
            } else {
                $str_submenu = "";
                $str_menu_id = "";
                $str_menu_nombre = "";
            }
            echo "***1***Alumnos cargados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        } else {
            echo "***0***No se encontraron alumnos a registrar.***<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al cargar alumnos.***<br/>";
    }
}

function formulario_detalle_grupo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_grupo = strip_tags(trim($_POST["u_gru_codigo"]));
    $eu_codgrupo = explode("-", $u_grupo);
    $grupo_codi = explode("/", $eu_codgrupo[1]);
    $lista = func_lista_grupo_detalle($conexion, $grupo_codi[0]);
    $aux = 1;
    $html = "<div class='col-md-12 table-responsive' id='divGrupoDetalle' >"
            . "<table id='tableGrupoDetalle' class='table' style='font-size: 13px;width:100% '>"
            . "<thead>"
            . "<th>Nro.</th>"
            . "<th>Cod. Alumno</th>"
            . "<th>Apellidos y nombres</th>"
            . "<th>DNI</th>"
            . "<th>Tipo Matricula</th>"
            . "<th>Grado</th>"
            . "<th>Sección</th>"
            . "<th>Sede</th>"
            . "<th>Estado</th>"
            . "</thead><tbody>";
    foreach ($lista as $value) {
        if ($value["estado"] == "Inactivo") {
            $color = "color:red";
        } else {
            $color = "";
        }
        $html .= "<tr style='$color'>"
                . "<td>$aux</td>"
                . "<td>" . $value["codigo"] . "</td>"
                . "<td>" . $value["alumno"] . "</td>"
                . "<td>" . $value["dni"] . "</td>"
                . "<td>" . $value["tipo"] . "</td>"
                . "<td>" . $value["grado"] . "</td>"
                . "<td>" . $value["seccion"] . "</td>"
                . "<td>" . $value["sede"] . "</td>"
                . "<td>" . $value["estado"] . "</td>"
                . "</tr>";
        $aux++;
    }
    echo $html;
}

function formulario_eliminar_detalle_grupo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_gru_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    $grupo = fnc_lista_grupos($conexion, $eu_codi[0], "");
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiGrupo" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de eliminar el Grupo "<label style="font-style: italic; "><?php echo $grupo[0]["codigo"] ?>"</label> de Carga de Alumno?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_detalle_grupo() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiGrupoIdEli = strip_tags(trim($_POST["u_codiGrupoIdEli"]));
    try {
        fnc_eliminar_grupo($conexion, $u_codiGrupoIdEli, "0");
        fnc_eliminar_matricula_grupo($conexion, $u_codiGrupoIdEli, "0");
        //fnc_eliminar_alumno_grupo($conexion, $u_codiGrupoIdEli,"0");
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        echo "***1***Grupo y alumnos eliminados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar Grupo y Alumnos.***<br/>";
    }
}

function formulario_activar_detalle_grupo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_gru_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    $grupo = fnc_lista_grupos($conexion, $eu_codi[0], "");
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiGrupoAc" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de activar el Grupo "<label style="font-style: italic; "><?php echo $grupo[0]["codigo"] ?>"</label> de Carga de Alumno?</label>
        </div>
    </div>
    <?php
}

function operacion_activar_detalle_grupo() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiGrupoIdEli = strip_tags(trim($_POST["u_codiGrupoIdEli"]));
    try {
        fnc_eliminar_grupo($conexion, $u_codiGrupoIdEli, "1");
        fnc_eliminar_matricula_grupo($conexion, $u_codiGrupoIdEli, "1");
        //fnc_eliminar_alumno_grupo($conexion, $u_codiGrupoIdEli,"1");
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        echo "***1***Grupo y alumnos activados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al activar Grupo y Alumnos.***<br/>";
    }
}

function load_modal_carga_usuarios() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = $_SESSION["psi_user"]["id"];
    $lista = func_lista_data_tabla_tmp_carga_usuarios($conexion, $codigo);
    $aux = 1;
    $color = "";
    $salida_usu = "";
    $salida_nivel = "";
    $salida_plana = "";
    $str_valida = "";
    $str_validacion = "";
    $count1 = 0;
    $count2 = 0;
    $count_correos = 0;
    $html = "<input type='hidden' id='hdnNumeralUsu' value='" . $codigo . "'><div class='col-md-12 table-responsive' id='divPreCargaAlumnos' >"
            . "<table id='tablePreCargaUsuarios' class='table' style='font-size: 13px;width:100% '>"
            . "<thead>"
            . "<th>Nro.</th>"
            . "<th>Tipo Personal</th>"
            . "<th>Tipo Documento</th>"
            . "<th>Nro. Documento</th>"
            . "<th>Apellido y nombres</th>"
            . "<th>Correo</th>"
            . "<th>Nivel</th>"
            . "<th>Plana</th>"
            . "<th>Secci&oacute;n</th>"
            . "<th>Estado</th>"
            . "</thead><tbody>";
    foreach ($lista as $value) {
        if ($value["actual_usu_id"] == 0) {
            $color = "";
            $salida_usu = "Usuario no existe";
            $salida_nivel = "";
            $salida_plana = "";
            $count1++;
        } else {
            if ($value["actual_usu_id"] !== 0) {
                $salida_usu = 'Usuario ya existe*';
            }
            if ($value["registrar_nivel_id"] !== 0) {
                $salida_nivel = 'Usuario ya registrado en el nivel*';
            } else {
                $salida_nivel = '';
            }
            if ($value["registrar_plana_id"] !== 0) {
                $salida_plana = 'Usuario ya registrado en la plana*';
            } else {
                $salida_plana = '';
            }
            $count2++;
        }
        if (strlen(strstr($value["correo"], '@')) > 0) {
            $count_correos++;
        }
        $validacion = $salida_usu . $salida_nivel . $salida_plana;
        $array_fila = explode("*", $validacion);
        if (count($array_fila) > 1) {
            if (count($array_fila) === 2) {
                $color = "color:orange";
            } else if (count($array_fila) === 3) {
                $color = "color:brown";
            } else {
                $color = "color:red";
            }
            $validacion = substr($validacion, 0, -1);
            $str_validacion = str_replace("*", "<br/>", $validacion);
        } else {
            $str_validacion = $validacion;
        }
        $html .= "<tr style='$color'>"
                . "<td>$aux</td>"
                . "<td>" . $value["perfil"] . "</td>"
                . "<td>" . $value["tipo_documento"] . "</td>"
                . "<td>" . $value["dni"] . "</td>"
                . "<td>" . $value["usuario"] . "</td>"
                . "<td>" . $value["correo"] . "</td>"
                . "<td>" . $value["nivel"] . "</td>"
                . "<td>" . $value["plana"] . "</td>"
                . "<td>" . $value["seccion"] . "</td>"
                . "<td>" . $str_validacion . "</td>"
                . "</tr>";
        $aux++;
    }
    $html .= "</tbody></table></div>"
            . "<div class='col-md-12'>"
            . "   <span class='text-bold text'>Usuarios nuevos:</span> <span class='badge bg-info'>" . $count1 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Usuarios ya registrados:</span><span class='badge bg-danger'>" . $count2 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Total de usuarios: </span><span class='badge bg-success'>" . (count($lista)) . "</span><br/>"
            . "<span><i class='nav-icon fa fa-info-circle' style='color: skyblue'></i> Nota: Solo se cargaran los usuarios que tienen correo electrónico.</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class='text-bold text'>Cantidad de usuarios con correos: </span><span class='badge bg-success'>" . ($count_correos) . "</span>"
            . "</div>";
    echo $html;
}

function formulario_detalle_grupo_usuarios() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_grupo = strip_tags(trim($_POST["u_gru_codigo"]));
    $eu_codgrupo = explode("-", $u_grupo);
    $grupo_codi = explode("/", $eu_codgrupo[1]);
    $lista = func_lista_grupo_detalle_usuarios($conexion, $grupo_codi[0]);
    $aux = 1;
    $html = "<div class='col-md-12 table-responsive' id='divGrupoDetalleUsuarios' >"
            . "<table id='tableGrupoDetalleUsuarios' class='table' style='font-size: 13px;width:100% '>"
            . "<thead>"
            . "<th>Nro.</th>"
            . "<th>Tipo Personal</th>"
            . "<th>Tipo Documento</th>"
            . "<th>Nro. Documento</th>"
            . "<th>Apellidos y nombres</th>"
            . "<th>Correo</th>"
            . "<th>Nivel</th>"
            . "<th>Plana</th>"
            . "<th>Estado</th>"
            . "</thead><tbody>";
    foreach ($lista as $value) {
        if ($value["estado"] == "Inactivo") {
            $color = "color:red";
        } else {
            $color = "";
        }
        $html .= "<tr style='$color'>"
                . "<td>$aux</td>"
                . "<td>" . $value["perfil"] . "</td>"
                . "<td>" . $value["tipoDoc"] . "</td>"
                . "<td>" . $value["numDoc"] . "</td>"
                . "<td>" . $value["usuario"] . "</td>"
                . "<td>" . $value["correo"] . "</td>"
                . "<td>" . $value["nivel"] . "</td>"
                . "<td>" . $value["plana"] . "</td>"
                . "<td>" . $value["estado"] . "</td>"
                . "</tr>";
        $aux++;
    }
    echo $html;
}

function formulario_eliminar_detalle_grupo_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_gru_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    $grupo = fnc_lista_grupos_usuarios($conexion, $eu_codi[0], "");
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiGrupoUsu" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de eliminar el Grupo "<label style="font-style: italic; "><?php echo $grupo[0]["codigo"] ?>"</label> de Carga de Usuarios y docentes?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_detalle_grupo_usuario() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiGrupoIdEli = strip_tags(trim($_POST["u_codiGrupoIdEliUsua"]));
    try {
        fnc_eliminar_grupo_usuario($conexion, $u_codiGrupoIdEli, "0");
        fnc_eliminar_usuario_grupo($conexion, $u_codiGrupoIdEli, "0");
        //fnc_eliminar_alumno_grupo($conexion, $u_codiGrupoIdEli,"0");
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        echo "***1***Grupo y usuarios eliminados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar Grupo y Usuarios.***<br/>";
    }
}

function formulario_activar_detalle_grupo_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["u_gru_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    $grupo = fnc_lista_grupos_usuarios($conexion, $eu_codi[0], "");
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiGrupoUsu" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de activar el Grupo "<label style="font-style: italic; "><?php echo $grupo[0]["codigo"] ?>"</label> de Carga de Usuarios y docentes?</label>
        </div>
    </div>
    <?php
}

function operacion_activar_detalle_grupo_usuario() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiGrupoIdEli = strip_tags(trim($_POST["u_codiGrupoIdEliUsua"]));
    try {
        fnc_eliminar_grupo_usuario($conexion, $u_codiGrupoIdEli, "1");
        fnc_eliminar_usuario_grupo($conexion, $u_codiGrupoIdEli, "1");
        //fnc_eliminar_alumno_grupo($conexion, $u_codiGrupoIdEli,"0");
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        echo "***1***Grupo y usuarios eliminados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar Grupo y Usuarios.***<br/>";
    }
}

function formulario_confirmacion_carga_usuarios() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = $_SESSION["psi_user"]["id"];
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnNumeral" class="form-control" value="<?php echo trim($codigo); ?>"/>
            <label>&iquest;Esta seguro de realizar la carga de usuarios?</label>
        </div>
    </div>
    <?php
}

function formulario_carga_subcategorias() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_cat_cod = strip_tags(trim($_POST["cat_cod"]));
    if ($sm_cat_cod === "") {
        echo "<option value='0'>-- Seleccione --</option>";
    } else {
        $lista_subcategorias = fnc_lista_subcategorias($conexion, $sm_cat_cod, "");
        if (count($lista_subcategorias) > 0) {
            echo "<option value=''>-- Seleccione --</option>";
            foreach ($lista_subcategorias as $lista) {
                echo "<option value='" . $lista["id"] . "'>" . $lista["nombre"] . "</option>";
            }
        } else {
            echo "<option value='0'>-- Seleccione --</option>";
        }
    }
}

function formulario_nueva_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $tipos_entrevistas = fnc_lista_tipo_entrevistas($conexion, "");
    $lista_categorias = fnc_lista_categorias($conexion, "");
    $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Buscar alumno: </label>
            </div>
            <div class="col-md-4">
                <input type="text" id="searchAlumno" class="typeahead form-control" style="size:12px;text-transform: uppercase;" value="" autocomplete="off">
            </div>
            <div class="col-md-4">
                <input type="hidden" id="matric" value=""/>
                <label id="dataAlumno" style="font-size: 16px;"></label>
            </div>
        </div>
        <div class="row space-div">
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Categoria: </label>
        </div>
        <div class="col-md-4">';
    $html .= '<select id="cbbCategoria" class="form-control select2" style="width: 100%" onchange="cargar_subcategorias(this)">
               <option value="">-- Seleccione --</option>';
    if (count($lista_categorias) > 0) {
        foreach ($lista_categorias as $lista) {
            $html .= "<option value='" . $lista["id"] . "' >" . $lista["nombre"] . "</option>";
        }
    }
    $html .= '</select>';
    $html .= '</div>
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Subcategoria: </label>
        </div>
        <div class="col-md-4">';
    $html .= '<select id="cbbSubcategoria" class="form-control select2" style="width: 100%">
                <option value="">-- Seleccione --</option>';
    $html .= '</select>';
    $html .= '</div>
       </div>';
    $html .= '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Tipo de entrevista: </label>
            </div>
            <div class="col-md-3">';
    $html .= '<select id="cbbTipoSolicitud" class="form-control select2" style="width: 100%" onchange="mostrar_tipo_solicitud(this)">
                <option value="">-- Seleccione --</option>';
    if (count($tipos_entrevistas) > 0) {
        foreach ($tipos_entrevistas as $lista) {
            $html .= "<option value='" . $lista["id"] . "'>" . $lista["nombre"] . "</option>";
        }
    }
    $html .= '</select>';
    $html .= '</div>
        </div>';
    $html .= '<div class="card card-primary" id="divEntrevista">';
    $html .= '</div>';
    echo $html;
}

function formulario_detalle_tipo_solicitud() {//Guadalupe
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_tipo_sol = strip_tags(trim($_POST["sol_tipo"]));
    $sm_sol_matricula = strip_tags(trim($_POST["sol_matricula"]));
    $sm_sol_categoria = strip_tags(trim($_POST["sol_categoria"]));
    $sm_sol_subcategoria = strip_tags(trim($_POST["sol_subcategoria"]));
    $usuario_data = fnc_datos_usuario($conexion, p_usuario);
    $arreglo_matricula = explode("*", $sm_sol_matricula); //guadalupe
    $sol_matricula = $arreglo_matricula[0];
    $sol_alumno = $arreglo_matricula[1];
    $matricula = fnc_alumno_matricula_detalle($conexion, $sol_matricula);
    $html = '<input type="hidden" id="txt_sede" value="' . $matricula[0]["sedeId"] . '">';
    $html .= '<input type="hidden" id="txt_perfil" value="' . p_perfil . '">'; //Guadalupe
    if ($sm_tipo_sol === "1") {
        $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad"> RESERVADO:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad" style="transform : scale(1.8);">
                    </div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($matricula[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Sexo: </label>
                    </div>
                    <div class="col-md-4">';
        $html .= '<select id="cbbSexo" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>';
        $lista_sexo = fnc_lista_sexo();
        $selected_sexo = "";
        if (count($lista_sexo) > 0) {
            foreach ($lista_sexo as $lista) {
                if ($lista["codigo"] === $matricula[0]["sexo"]) {
                    $selected_sexo = " selected ";
                } else {
                    $selected_sexo = "";
                }
                $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_sexo . '>' . strtoupper($lista["nombre"]) . '</option>';
            }
        }
        $html .= '</select></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . $usuario_data[0]["usuariodata"] . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["sede"] . '</span></div>
                </div>                
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo" placeholder=""></textarea>
                        ';
        $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span> <i class="fa fa-info-circle celeste"></i> La fecha y hora se crea al guardar la ficha.</span></div>
                </div>
                <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del estudiante: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEstudiante" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del entrevistador(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEntrevistador" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdos" rows="3" placeholder=""></textarea></div>
                </div>
              </div>'
                . '<div class="row space-div">'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad" class="signature-pad" style="margin-left: 20px;">
                    <div class="description">Firma del estudiante</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;" id="canvas1"></canvas>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_1()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . str_replace(" - ", "<br/>", strtoupper($matricula[0]["alumno"])) . '<label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>'; //Guadalupe

        $html .= '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>';
        $imagen_perfil = "";
        $data_firma = fnc_ultima_firma_usuario($conexion, p_usuario);
        $html .= '<input type="hidden" id="txtCantiFirma" value="' . count($data_firma) . '"';
        if (p_perfil === '3') {
            if (count($data_firma) > 0) {
                $imagen_perfil = $data_firma[0]["imagen"];
                $html .= '<div class="signature-pad--body">
                        <img id="canvas2_img" src="./php/' . $imagen_perfil . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152">
                    </div>';
            } else {
                $html .= '<div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_2()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador();">Limpiar firma</button>
                   </div>';
            }
        } else {
            $html .= '<div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_2()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador();">Limpiar firma</button>
                   </div>';
        }
        $html .= '<div style="margin-left: 20px;">
                       <label>' . strtoupper($usuario_data[0]["usuariodata"]) . '<br/>' . $usuario_data[0]["usuarioDni"] . '<label/>
                   </div>'
                . '</div>'
                . '</div>';
    } elseif ($sm_tipo_sol === "2") {
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $matricula[0]["aluId"], "");
        $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A PADRES DE FAMILIA</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad"> RESERVADO:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad" style="transform : scale(1.8);">
                    </div>
                </div>
                <div class="row space-div">
                    <input type="hidden" id="txtAlumCodig" value="' . $matricula[0]["aluId"] . '"/>
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($matricula[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Sexo: </label>
                    </div>
                    <div class="col-md-4">';
        $html .= '<select id="cbbSexo" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>';
        $lista_sexo = fnc_lista_sexo();
        $selected_sexo = "";
        if (count($lista_sexo) > 0) {
            foreach ($lista_sexo as $lista) {
                if ($lista["codigo"] === $matricula[0]["sexo"]) {
                    $selected_sexo = " selected ";
                } else {
                    $selected_sexo = "";
                }
                $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_sexo . '>' . strtoupper($lista["nombre"]) . '</option>';
            }
        }

        $html .= '</select></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del padre/madre/apoderado: </label>
                    </div>
                    <div class="col-md-4">';
        $html .= '<select id="cbbTipoApoderado" class="form-control select2" style="width: 100%" onchange="mostrar_info_apoderado(this)">
                <option value="">-- Seleccione --</option>';
        if (count($lista_apoderados) > 0) {
            foreach ($lista_apoderados as $lista) {
                $html .= '<option value="' . $lista["codigo"] . '">' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
            }
        }
        $html .= ' <option value="-1" >-- Otro --</option></select>';
        $html .= '</div><div class="col-md-3" id="divEditarInfoApoderado"></div></div>
                <div class="row space-div" id="detalleApoderado">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Correo: </label>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <label>Teléfono: </label>
                    </div>
                    <div class="col-md-3"></div>
                </div>';
        $html .= '
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($usuario_data[0]["usuariodata"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["sede"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo" placeholder=""></textarea>';
        $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span> <i class="fa fa-info-circle celeste"></i> La fecha y hora se crea al guardar la ficha.</span></div>
                </div>
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div class="row space-div">
                    <div class="col-md-12"><textarea class="form-control" rows="3" id="txtInforme" placeholder=""></textarea></div>
                </div>

                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del padre, madre o apoderado: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanPadre" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanDocente" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por los padres: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosPadres" rows="3" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por el colegio: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosColegio" rows="3" placeholder=""></textarea></div>
                </div>
              </div>'
                . '<div class="row space-div">'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad" class="signature-pad" style="margin-left: 20px;">
                    <div class="description">Firma del padre, madre o apoderado</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas1"></canvas>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_1()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI">
                       <label><label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>'; //Guadalupe

        $html .= '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>';

        $html .= '<div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_2()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador();">Limpiar firma</button>
                   </div>';
        $html .= '<div style="margin-left: 20px;">
                       <label>' . strtoupper($usuario_data[0]["usuariodata"]) . '<br/>' . $usuario_data[0]["usuarioDni"] . '<label/>
                   </div>'
                . ' </div>'
                . '</div>';
    }
    $html .= '</div>';
    echo $html;
}

function formulario_carga_info_apoderado() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alu_cod = strip_tags(trim($_POST["alu_cod"]));
    $tip_apod = strip_tags(trim($_POST["tip_apod"]));
    $html = "";
    if ($tip_apod == "" || $tip_apod == "-1") {
        $html = ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span></span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span></span></div>';
        $html .= "********";
    } else {
        $apoderado = fnc_lista_apoderados_de_alumno($conexion, $alu_cod, $tip_apod);

        if (count($apoderado) > 0) {
            $html = ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span>' . $apoderado[0]["correo"] . '</span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span>' . $apoderado[0]["telefono"] . '</span></div>';

            $html .= "****";
            $html .= '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-editar-apoderado" data-backdrop="static" '
                    . ' data-alumno="' . $alu_cod . '" data-info-apoderado="' . $apoderado[0]["codigo"] . '">Editar</button>****<label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '</label>';
        } else {
            $html = ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span></span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span></span></div>';
            $html .= "********";
        }
    }
    echo $html;
}

function formulario_editar_apoderado() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $apod = strip_tags(trim($_POST["sm_apoderado"]));
    $alumnoCod = strip_tags(trim($_POST["sm_alumno"]));
    $html = "";
    $apoderado = fnc_apoderado_del_alumno($conexion, $apod);
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    if (count($apoderado) > 0) {
        $html = '<div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tipo</label>
            </div>
            <input id="txtAlumnCodigo" type="hidden" class="form-control" value="' . $alumnoCod . '" />
            <div class="col-md-6">';
        $html .= '<select id="cbbTipoApoderado" class="form-control select2" style="width: 100%" disabled>
                ';
        if (count($tipos_apoderados) > 0) {
            $selected = "";
            foreach ($tipos_apoderados as $lista) {
                if ($lista["id"] == $apoderado[0]["tipo"]) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $html .= '<option value="' . $lista["id"] . '" ' . $selected . ' >' . $lista["nombre"] . '</option>';
            }
        }
        $html .= '</select></div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>DNI</label>
            </div>
            <div class="col-md-6">
                <input id="txtDniApoderado" type="text" class="form-control select2" style="width: 100%;text-transform: uppercase;" value="' . $apoderado[0]["dni"] . '" 
                    maxlength="12" onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Apellidos y nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderado" class="form-control select2" style="width: 100%;text-transform: uppercase;" value="' . $apoderado[0]["nombres"] . '" />
            </div>
        </div>
        <input id="txtApoderadoCod" type="hidden" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["id"] . '" />
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Correo</label>
            </div>
            <div class="col-md-6">
                <input id="txtCorreoApoderado" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["correo"] . '" />
            </div>
        </div>
        
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tel&eacute;fono (Opcional)</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderado" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["telefono"] . '" 
                    maxlength="9" onkeypress="return solo_numeros(event);"/>
            </div>
        </div>';
    }
    echo $html;
}

function formulario_nuevo_apoderado() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumno = strip_tags(trim($_POST["sm_alumno"]));
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    $html = '<div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tipo</label>
            </div>
            <div class="col-md-6">';
    $html .= '<input type="hidden" id="txtAlumnoCodiN" class="form-control" value="' . $alumno . '"/>';
    $html .= '<select id="cbbTipoApoderadoN" class="form-control" style="width: 100%" disabled>
                ';
    if (count($tipos_apoderados) > 0) {
        $selected = "";
        foreach ($tipos_apoderados as $lista) {
            if ($lista["id"] == 3) {
                $selected = " selected ";
            } else {
                $selected = "";
            }
            $html .= '<option value="' . $lista["id"] . '" ' . $selected . ' >' . $lista["nombre"] . '</option>';
        }
    }
    $html .= '</select></div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>DNI</label>
            </div>
            <div class="col-md-6">
                <input id="txtDniN" class="form-control" style="width: 100%" maxlength="12" 
                onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Apellidos y nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderadoN" class="form-control" style="width: 100%;text-transform: uppercase;"
                   onkeypress="return solo_letras(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Correo</label>
            </div>
            <div class="col-md-6">
                <input id="txtCorreoApoderadoN" type="text" class="form-control" style="width: 100%"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tel&eacute;fono (Opcional)</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderadoN" type="text" class="form-control" style="width: 100%;text-transform: uppercase;" maxlength="9" 
                onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Direcci&oacute;n</label>
            </div>
            <div class="col-md-6">
                <input id="txtDireccionN" type="text" class="form-control" style="width: 100%;text-transform: uppercase;"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Distrito</label>
            </div>
            <div class="col-md-6">
                <input id="txtDistritoN" type="text" class="form-control" style="width: 100%;text-transform: uppercase;"
                onkeypress="return solo_letras(event);"/>
            </div>
        </div>';
    echo $html;
}

function operacion_registrar_apoderado() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = strip_tags(trim($_POST["a_txtAlumnoCodiN"]));
    $tipo_apoderado = strip_tags(trim($_POST["a_cbbTipoApoderadoN"]));
    $dni = strip_tags(trim($_POST["a_txtDniN"]));
    $nombres = strip_tags(trim($_POST["a_txtNombresApoderadoN"]));
    $correo = strip_tags(trim($_POST["a_txtCorreoApoderadoN"]));
    $telefono = strip_tags(trim($_POST["a_txtTelfApoderadoN"]));
    $direccion = strip_tags(trim($_POST["a_txtDireccionN"]));
    $distrito = strip_tags(trim($_POST["a_txtDistritoN"]));
    $html = "";
    $valida_dni = fnc_validar_dni_apoderado($conexion, $codigo, "", $dni);
    if (count($valida_dni) > 0) {
        $html .= "0***El número de documento ya esta registrado, favor de ingresar otro.************";
    } else {
        $cadena = "('" . $codigo . "','" . $tipo_apoderado . "','" . strtoupper($nombres) . "','" .
                $dni . "','" . trim($correo) . "','" . $telefono . "','" . strtoupper($direccion) . "','" . strtoupper($distrito) . "','1')";
        $idApoderado = fnc_registrar_apoderado($conexion, $cadena);
        if ($idApoderado) {
            $htmlSelect = "";
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $codigo, "");
            if (count($lista_apoderados) > 0) {
                $selected = "";
                $htmlSelect .= '<option value="">-- Seleccione --</option>';
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $idApoderado) {
                        $selected = " selected ";
                    }
                    $htmlSelect .= '<option value="' . $lista["codigo"] . '" ' . $selected . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . $lista["nombre"] . '</option>';
                }
                $htmlSelect .= '<option value="-1" >-- Otro --</option>';
            } else {
                $htmlSelect = "";
            }
            $html_editarInfoApoderado = '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-editar-apoderado" data-backdrop="static" data-alumno="' . $codigo . '" data-info-apoderado="' . $idApoderado . '">Editar</button>';

            $html_detalle_apo = '<div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span>' . trim($correo) . '</span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span>' . $telefono . '</span>
                  </div>';
            $html_apo_nombreDni = '<label>' . strtoupper($nombres) . '<br>' . $dni . '</label>';
            $html = "1***Datos de Apoderado registrados correctamente***" . $htmlSelect . "***" . $html_editarInfoApoderado . "***" . $html_detalle_apo . "***" . $html_apo_nombreDni;
        }
    }
    echo $html;
}

function operacion_editar_apoderado() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumnoCod = strip_tags(trim($_POST["a_txtAlumnCodigo"]));
    $codigo = strip_tags(trim($_POST["a_txtApoderadoCod"]));
    $nombres = strip_tags(trim($_POST["a_txtNombresApoderado"]));
    $dni = strip_tags(trim($_POST["a_txtDniApoderado"]));
    $correo = strip_tags(trim($_POST["a_txtCorreoApoderado"]));
    $telefono = strip_tags(trim($_POST["a_txtTelfApoderado"]));
    $html = "";
    $valida_dni = fnc_validar_dni_apoderado($conexion, "", $codigo, $dni);
    if (count($valida_dni) > 0) {
        $html .= "0***El número de documento ya esta registrado, favor de ingresar otro.*********";
    } else {
        $htmlSelect = "";
        $apoderado = fnc_apoderado_del_alumno($conexion, $codigo);
        if (trim($apoderado[0]["dni"]) !== trim($dni) || trim($apoderado[0]["correo"]) !== trim($correo) || trim($apoderado[0]["telefono"]) !== trim($telefono)) {
            $str_cadena = "('" . $codigo . "',NOW(),'" . $apoderado[0]["correo"] . "','" . $apoderado[0]["telefono"] . "','1')";
            $resp = fnc_registrar_apoderado_historico($conexion, $str_cadena);
            if ($resp) {
                fnc_modificar_apoderado($conexion, $codigo, $nombres, $dni, $correo, $telefono);
            }
        }
        $html .= "1***Apoderado editado correctamente.***";
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $alumnoCod, "");
        if (count($lista_apoderados) > 0) {
            $selected = "";
            $htmlSelect .= '<option value="">-- Seleccione --</option>';
            foreach ($lista_apoderados as $lista) {
                if ($lista["codigo"] == $codigo) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $htmlSelect .= '<option value="' . $lista["codigo"] . '" ' . $selected . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . $lista["nombre"] . '</option>';
            }
            $htmlSelect .= '<option value="-1" >-- Otro --</option>';
        } else {
            $htmlSelect = "";
        }

        $html .= $htmlSelect . "***";

        $html .= ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span>' . $correo . '</span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span>' . $telefono . '</span></div>***';
        $html .= '<label>' . strtoupper($nombres) . '<br>' . $dni . '</label>';
    }
    echo $html;
}

function formulario_detalle_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $eu_codsolicitud = explode("-", $s_solicitud);
    $solicitud_codigo = explode("/", $eu_codsolicitud[1]);
    $lista_sol = fnc_listar_todas_solicitudes_x_entrevista($conexion, $solicitud_codigo[0], "1", "1", p_perfil, p_sede);
    $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
              <label>Entrevista / Subentrevista</label>
            </div>
            <div class="col-md-10">';
    $html .= '<select id="cbbTipoSolicitudCodis" name="cbbTipoSolicitudCodis" class="form-control select2" style="width: 100%" onchange="cargar_solicitudes_a_detallar(this)">
                <option value="" >-- Seleccione --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value="' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
        </div>
        <div id="divDetalleEntrevista"></div>';
    echo $html;
}

function formulario_eliminar_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $eu_codsolicitud = explode("-", $s_solicitud);
    $solicitud_codigo = explode("/", $eu_codsolicitud[1]);
    $lista_sol = fnc_listar_todas_solicitudes_x_entrevista($conexion, $solicitud_codigo[0], "1", "1", p_perfil, p_sede);
    $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
              <label>Entrevista / Subentrevista</label>
            </div>
            <div class="col-md-10">';
    $html .= '<select id="cbbTipoSolicitudCodisElis" class="form-control select2" style="width: 100%" onchange="cargar_solicitudes_a_eliminar(this)">
                <option value="" >-- Seleccione --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value="' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
        </div>
        <div id="divDetalleEliminarEntrevista"></div>';
    echo $html;
}

function operacion_eliminar_solicitud() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiSoliEliAlu = strip_tags(trim($_POST["u_codiSolicitud"]));
    try {
        $array = explode("-", $u_codiSoliEliAlu);
        if ($array[0] === "ent") {
            fnc_eliminar_solicitud_alumno($conexion, $array[1], "0");
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
            $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
            if (count($submenu) > 0) {
                $str_submenu = $submenu[0]["ruta"];
                $str_menu_id = $submenu[0]["id"];
                $str_menu_nombre = $submenu[0]["nombre"];
            } else {
                $str_submenu = "";
                $str_menu_id = "";
                $str_menu_nombre = "";
            }
            echo "***1***Solicitud eliminada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        } elseif ($array[1] === "sub") {
            fnc_eliminar_sub_solicitud_alumno($conexion, $array[1], "0");
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
            $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
            if (count($submenu) > 0) {
                $str_submenu = $submenu[0]["ruta"];
                $str_menu_id = $submenu[0]["id"];
                $str_menu_nombre = $submenu[0]["nombre"];
            } else {
                $str_submenu = "";
                $str_menu_id = "";
                $str_menu_nombre = "";
            }
            echo "***1***Solicitud eliminada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        }

        if ($array[0] !== "ent" || $array[0] !== "sub") {
            echo "***0***Error al eliminar solicitud.***<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al eliminar solicitud.***<br/>";
    }
}

function operacion_buscar_semaforo_docentes() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_semaforo = strip_tags(trim($_POST["s_semaforo"]));
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $s_docente = strip_tags(trim($_POST["s_docente"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;
    $sedeCodi = "";
    $usuarioCodi = "0";
    $privacidad = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "0";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
            $s_docente = $usuarioCodi;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        }
    }
    $lista = fnc_buscar_semaforo_docentes($conexion, $s_sede, $s_semaforo, $s_bimestre, $s_nivel, $s_grado, $s_seccion, $s_docente);
    $html = "";
    $aux = 1;
    $color = "";
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            if ($value["color"] == "Rojo") {
                $color = "color:red";
            } else if ($value["color"] == "Ambar") {
                $color = "color:#ff7e00 ";
            } else if ($value["color"] == "Verde") {
                $color = "color:green";
            }
            $html .= "<tr >"
                    . "<td>$aux</td>"
                    . "<td>" . $value["sede"] . "</td>"
                    . "<td>" . $value["perfil"] . "</td>"
                    . "<td >" . $value["docente"] . "</td>"
                    //. "<td>" . $value["grado"] . "</td>"
                    . "<td style='text-align:center'>" . $value["cantidad"] . "</td>"
                    . "<td style='text-align:center'>" . $value["cantidad_faltantes"] . "</td>"
                    . "<td style='text-align:center'>" . $value["cantidad_realizados"] . "</td>"
                    . "<td style='text-align:center'>" . $value["porcentaje"] . "</td>"
                    . "<td style='text-align:center;$color'>" . $value["color"] . "<i class='fas fa-circle nav-icon' style='font-size:23px;'></i></td>"
                    . "</tr>";
            $aux++;
        }
    } else {
        $html = '';
    }
    echo $html;
}

function formulario_nueva_subsolicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $entrevista = strip_tags(trim($_POST["s_entrevista"]));
    $tipos_entrevistas = fnc_lista_tipo_entrevistas($conexion, "");
    $lista_categorias = fnc_lista_categorias($conexion, "");
    $eu_codentrevista = explode("-", $entrevista);
    $entrevista_codigo = explode("/", $eu_codentrevista[1]);
    $entre = fnc_obtener_codigo_entrevista($conexion, $entrevista_codigo[0]);
    $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>C&oacute;digo de Entrevista: </label>
            </div>
            <div class="col-md-4">
                <label>' . $entre[0]["codigo_ent"] . '</label>
                <input type="hidden" id="codi_entre_sub" value="' . $entrevista_codigo[0] . '"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Buscar alumno: </label>
            </div>
            <div class="col-md-4">
                <input type="text" id="searchAlumno_sub" class="typeahead form-control" style="size:12px;text-transform: uppercase;" value="" autocomplete="off">
            </div>
            <div class="col-md-4">
                <input type="hidden" id="matric_sub" value=""/>
                <label id="dataAlumno_sub" style="font-size: 16px;"></label>
            </div>
        </div>
        <div class="row space-div">
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Categoria: </label>
        </div>
        <div class="col-md-4">';
    $html .= '<select id="cbbCategoria_sub" class="form-control select2" style="width: 100%" onchange="cargar_subcategorias_sub(this)">
               <option value="">-- Seleccione --</option>';
    if (count($lista_categorias) > 0) {
        foreach ($lista_categorias as $lista) {
            $html .= "<option value='" . $lista["id"] . "' >" . $lista["nombre"] . "</option>";
        }
    }
    $html .= '</select>';
    $html .= '</div>
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Subcategoria: </label>
        </div>
        <div class="col-md-4">';
    $html .= '<select id="cbbSubcategoria_sub" class="form-control select2" style="width: 100%">
                <option value="">-- Seleccione --</option>';
    $html .= '</select>';
    $html .= '</div>
       </div>';
    $html .= '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Tipo de entrevista: </label>
            </div>
            <div class="col-md-3">';
    $html .= '<select id="cbbTipoSolicitud_sub" class="form-control select2" style="width: 100%" onchange="mostrar_tipo_solicitud_sub(this)">
                <option value="">-- Seleccione --</option>';
    if (count($tipos_entrevistas) > 0) {
        foreach ($tipos_entrevistas as $lista) {
            $html .= "<option value='" . $lista["id"] . "'>" . $lista["nombre"] . "</option>";
        }
    }
    $html .= '</select>';
    $html .= '</div>
        </div>';
    $html .= '<div class="card card-success" id="divSubEntrevista">';
    $html .= '</div>';
    echo $html;
}

function formulario_detalle_tipo_solicitud_sub() {//Guadalupe
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_tipo_sol = strip_tags(trim($_POST["sol_tipo"]));
    $sm_s_matricula = strip_tags(trim($_POST["sol_matricula"]));
    $sm_sol_categoria = strip_tags(trim($_POST["sol_categoria"]));
    $sm_sol_subcategoria = strip_tags(trim($_POST["sol_subcategoria"]));
    $usuario_data = fnc_datos_usuario($conexion, p_usuario);
    $arreglo_alu = explode("*", $sm_s_matricula);
    $sm_sol_matricula = $arreglo_alu[0];
    $sm_sol_alumno = $arreglo_alu[1];
    $matricula = fnc_alumno_matricula_detalle($conexion, $sm_sol_matricula);
    $html = '<input type="hidden" id="txt_sede_sub" value="' . $matricula[0]["sedeId"] . '">';
    $html .= '<input type="hidden" id="txt_perfil_sub" value="' . p_perfil . '">'; //Guadalupe
    if ($sm_tipo_sol === "1") {
        $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad_sub"> RESERVADO:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad_sub" style="transform : scale(1.8);">
                    </div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($matricula[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Sexo: </label>
                    </div>
                    <div class="col-md-4">';
        $html .= '<select id="cbbSexo_sub" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>';
        $lista_sexo = fnc_lista_sexo();
        $selected_sexo = "";
        if (count($lista_sexo) > 0) {
            foreach ($lista_sexo as $lista) {
                if ($lista["codigo"] === $matricula[0]["sexo"]) {
                    $selected_sexo = " selected ";
                } else {
                    $selected_sexo = "";
                }
                $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_sexo . '>' . strtoupper($lista["nombre"]) . '</option>';
            }
        }
        $html .= '</select></div></div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . $usuario_data[0]["usuariodata"] . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["sede"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_sub" placeholder=""></textarea>
                        ';
        $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span> <i class="fa fa-info-circle celeste"></i> La fecha y hora se crea al guardar la ficha.</span></div>
                </div>
                <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del estudiante: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEstudiante_sub" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del entrevistador(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEntrevistador_sub" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdos_sub" rows="3" placeholder=""></textarea></div>
                </div>
              </div>'
                . '<div class="row space-div">'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-sub" class="signature-pad" style="margin-left: 20px;">
                    <div class="description">Firma del estudiante</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas1_sub"></canvas>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_sub_1()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_sub();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . str_replace(" - ", "<br/>", strtoupper($matricula[0]["alumno"])) . '<label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>';
        $html .= '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador-sub" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>';
        $imagen_perfil = "";
        if (p_perfil === '3') {
            $data_firma = fnc_ultima_firma_usuario($conexion, p_usuario);
            if (count($data_firma) > 0) {
                $imagen_perfil = $data_firma[0]["imagen"];
                $html .= '<div class="signature-pad--body">
                        <img id="canvas2_img_edi" src="./php/' . $imagen_perfil . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152">
                    </div>';
            }
        } else {
            $html .= '<div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2_sub"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_sub_2()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador_sub();">Limpiar firma</button>
                   </div>';
        }
        $html .= '<div style="margin-left: 20px;">
                       <label>' . strtoupper($usuario_data[0]["usuariodata"]) . '<br/>' . $usuario_data[0]["usuarioDni"] . '<label/>
                   </div>'
                . ' </div>'
                . '</div>'; //Guadalupe
    } elseif ($sm_tipo_sol === "2") {
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $matricula[0]["aluId"], "");
        $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A PADRES DE FAMILIA</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad_sub"> RESERVADO:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad_sub" style="transform : scale(1.8);">
                    </div>
                </div>
                <div class="row space-div">
                    <input type="hidden" id="txtAlumCodig_sub" value="' . $matricula[0]["aluId"] . '"/>
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($matricula[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Sexo: </label>
                    </div>
                    <div class="col-md-4">';
        $html .= '<select id="cbbSexo_sub" class="form-control select2" style="width: 100%">
                <option value="0">-- Seleccione --</option>';
        $lista_sexo = fnc_lista_sexo();
        $selected_sexo = "";
        if (count($lista_sexo) > 0) {
            foreach ($lista_sexo as $lista) {
                if ($lista["codigo"] === $matricula[0]["sexo"]) {
                    $selected_sexo = " selected ";
                } else {
                    $selected_sexo = "";
                }
                $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_sexo . '>' . strtoupper($lista["nombre"]) . '</option>';
            }
        }

        $html .= '</select></div></div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del padre/madre/apoderado: </label>
                    </div>
                    <div class="col-md-4">';
        $html .= '<select id="cbbTipoApoderado_sub" class="form-control select2" style="width: 100%" onchange="mostrar_info_apoderado_sub(this)">
                <option value="">-- Seleccione --</option>';
        if (count($lista_apoderados) > 0) {
            foreach ($lista_apoderados as $lista) {
                $html .= '<option value="' . $lista["codigo"] . '">' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
            }
        }
        $html .= ' <option value="-1" >-- Otro --</option></select>';
        $html .= '</div><div class="col-md-3" id="divEditarInfoApoderado_sub"></div></div>
                <div class="row space-div" id="detalleApoderado_sub">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Correo: </label>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <label>Teléfono: </label>
                    </div>
                    <div class="col-md-3"></div>
                </div>';
        $html .= '
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($usuario_data[0]["usuariodata"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span>' . $matricula[0]["sede"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_sub" placeholder=""></textarea>';
        $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span> <i class="fa fa-info-circle celeste"></i> La fecha y hora se crea al guardar la ficha.</span></div>
                </div>
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div class="row space-div">
                    <div class="col-md-12"><textarea class="form-control" rows="3" id="txtInforme_sub" placeholder=""></textarea></div>
                </div>

                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del padre, madre o apoderado: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanPadre_sub" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanDocente_sub" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por los padres: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosPadres_sub" rows="3" placeholder=""></textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por el colegio: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosColegio_sub" rows="3" placeholder=""></textarea></div>
                </div>
              </div>'
                . '<div class="row space-div">'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-sub" class="signature-pad" style="margin-left: 20px;">
                    <div class="description">Firma del padre, madre o apoderado</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas1_sub"></canvas>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_sub_1()">Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_sub();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI_sub">
                       <label><label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>';
        $html .= '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador-sub" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>';
        $html .= '<div class="signature-pad--body">
                    <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2_sub"></canvas>
                  </div>
                  <div>
                    <button type="button" class="btn btn-default" onclick="iniciar_firma_sub_2()">Iniciar</button>&nbsp;&nbsp;
                    <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador_sub();">Limpiar firma</button>
                  </div>';
        $html .= '<div style="margin-left: 20px;">
                       <label>' . strtoupper($usuario_data[0]["usuariodata"]) . '<br/>' . $usuario_data[0]["usuarioDni"] . '<label/>
                   </div>'
                . ' </div>'
                . '</div>';
    }
    $html .= '</div>';
    echo $html;
}

function formulario_carga_info_apoderado_sub() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alu_cod = strip_tags(trim($_POST["alu_cod"]));
    $tip_apod = strip_tags(trim($_POST["tip_apod"]));
    $html = "";
    if ($tip_apod == "" || $tip_apod == "-1") {
        $html = ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span></span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span></span></div>';
        $html .= "********";
    } else {
        $apoderado = fnc_lista_apoderados_de_alumno($conexion, $alu_cod, $tip_apod);

        if (count($apoderado) > 0) {
            $html = ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span>' . $apoderado[0]["correo"] . '</span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span>' . $apoderado[0]["telefono"] . '</span></div>';

            $html .= "****";
            $html .= '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-editar-apoderado-sub" data-backdrop="static" '
                    . ' data-alumno="' . $alu_cod . '" data-info-apoderado="' . $apoderado[0]["codigo"] . '">Editar</button>****<label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '</label>';
        } else {
            $html = ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span></span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span></span></div>';
            $html .= "********";
        }
    }
    echo $html;
}

function formulario_editar_apoderado_sub() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $apod = strip_tags(trim($_POST["sm_apoderado"]));
    $alumnoCod = strip_tags(trim($_POST["sm_alumno"]));
    $html = "";
    $apoderado = fnc_apoderado_del_alumno($conexion, $apod);
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    if (count($apoderado) > 0) {
        $html = '<div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tipo</label>
            </div>
            <input id="txtAlumnCodigo_sub" type="hidden" class="form-control" value="' . $alumnoCod . '" />
            <div class="col-md-6">';
        $html .= '<select id="cbbTipoApoderado_sub" class="form-control select2" style="width: 100%" disabled>
                ';
        if (count($tipos_apoderados) > 0) {
            $selected = "";
            foreach ($tipos_apoderados as $lista) {
                if ($lista["id"] == $apoderado[0]["tipo"]) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $html .= '<option value="' . $lista["id"] . '" ' . $selected . ' >' . $lista["nombre"] . '</option>';
            }
        }
        $html .= '</select></div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>DNI</label>
            </div>
            <div class="col-md-6">
                <input id="txtDniApoderado_sub" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["dni"] . '" 
                    maxlength="12" onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
         <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Apellidos y nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderado_sub" class="form-control select2" style="width: 100%;text-transform: uppercase;" value="' . $apoderado[0]["nombres"] . '" />
            </div>
        </div>
        <input id="txtApoderadoCod_sub" type="hidden" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["id"] . '" />
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Correo</label>
            </div>
            <div class="col-md-6">
                <input id="txtCorreoApoderado_sub" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["correo"] . '" />
            </div>
        </div>
        
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tel&eacute;fono (Opcional)</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderado_sub" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["telefono"] . '" 
                    maxlength="9" onkeypress="return solo_numeros(event);"/>
            </div>
        </div>';
    }
    echo $html;
}

function operacion_editar_apoderado_sub() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumnoCod = strip_tags(trim($_POST["a_txtAlumnCodigo_sub"]));
    $codigo = strip_tags(trim($_POST["a_txtApoderadoCod_sub"]));
    $nombres = strip_tags(trim($_POST["a_txtNombresApoderado_sub"]));
    $dni = strip_tags(trim($_POST["a_txtDniApoderado_sub"]));
    $correo = strip_tags(trim($_POST["a_txtCorreoApoderado_sub"]));
    $telefono = strip_tags(trim($_POST["a_txtTelfApoderado_sub"]));
    $html = "";
    $valida_dni = fnc_validar_dni_apoderado($conexion, "", $codigo, $dni);
    if (count($valida_dni) > 0) {
        $html .= "0***El número de documento ya esta registrado, favor de ingresar otro.*********";
    } else {
        $htmlSelect = "";
        $apoderado = fnc_apoderado_del_alumno($conexion, $codigo);
        if (trim($apoderado[0]["dni"]) !== trim($dni) || trim($apoderado[0]["correo"]) !== trim($correo) || trim($apoderado[0]["telefono"]) !== trim($telefono)) {
            $str_cadena = "('" . $codigo . "',NOW(),'" . $apoderado[0]["correo"] . "','" . $apoderado[0]["telefono"] . "','1')";
            $resp = fnc_registrar_apoderado_historico($conexion, $str_cadena);
            if ($resp) {
                fnc_modificar_apoderado($conexion, $codigo, $nombres, $dni, $correo, $telefono);
            }
        }
        $html .= "1***Apoderado editado correctamente.***";
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $alumnoCod, "");
        if (count($lista_apoderados) > 0) {
            $selected = "";
            $htmlSelect .= '<option value="">-- Seleccione --</option>';
            foreach ($lista_apoderados as $lista) {
                if ($lista["codigo"] == $codigo) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $htmlSelect .= '<option value="' . $lista["codigo"] . '" ' . $selected . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . $lista["nombre"] . '</option>';
            }
            $htmlSelect .= '<option value="-1" >-- Otro --</option>';
        } else {
            $htmlSelect = "";
        }

        $html .= $htmlSelect . "***";

        $html .= ' <div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span>' . $correo . '</span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span>' . $telefono . '</span></div>***';
        $html .= '<label>' . strtoupper($nombres) . '<br>' . $dni . '</label>';
    }
    echo $html;
}

function formulario_nuevo_apoderado_sub() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumno = strip_tags(trim($_POST["sm_alumno"]));
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    $html = '<div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tipo</label>
            </div>
            <div class="col-md-6">';
    $html .= '<input type="hidden" id="txtAlumnoCodiN_sub" class="form-control" value="' . $alumno . '"/>';
    $html .= '<select id="cbbTipoApoderadoN_sub" class="form-control" style="width: 100%" disabled>
                ';
    if (count($tipos_apoderados) > 0) {
        $selected = "";
        foreach ($tipos_apoderados as $lista) {
            if ($lista["id"] == 3) {
                $selected = " selected ";
            } else {
                $selected = "";
            }
            $html .= '<option value="' . $lista["id"] . '" ' . $selected . ' >' . $lista["nombre"] . '</option>';
        }
    }
    $html .= '</select></div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>DNI</label>
            </div>
            <div class="col-md-6">
                <input id="txtDniN_sub" class="form-control" style="width: 100%" maxlength="12" 
                onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Apellidos y nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderadoN_sub" class="form-control" style="width: 100%;text-transform: uppercase;"
                   onkeypress="return solo_letras(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Correo</label>
            </div>
            <div class="col-md-6">
                <input id="txtCorreoApoderadoN_sub" type="text" class="form-control" style="width: 100%"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tel&eacute;fono (Opcional)</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderadoN_sub" type="text" class="form-control" style="width: 100%" maxlength="9" 
                onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Direcci&oacute;n</label>
            </div>
            <div class="col-md-6">
                <input id="txtDireccionN_sub" type="text" class="form-control" style="width: 100%;text-transform: uppercase;"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Distrito</label>
            </div>
            <div class="col-md-6">
                <input id="txtDistritoN_sub" type="text" class="form-control" style="width: 100%;text-transform: uppercase;"
                onkeypress="return solo_letras(event);"/>
            </div>
        </div>';
    echo $html;
}

function operacion_registrar_apoderado_sub() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = strip_tags(trim($_POST["a_txtAlumnoCodiN_sub"]));
    $tipo_apoderado = strip_tags(trim($_POST["a_cbbTipoApoderadoN_sub"]));
    $dni = strip_tags(trim($_POST["a_txtDniN_sub"]));
    $nombres = strip_tags(trim($_POST["a_txtNombresApoderadoN_sub"]));
    $correo = strip_tags(trim($_POST["a_txtCorreoApoderadoN_sub"]));
    $telefono = strip_tags(trim($_POST["a_txtTelfApoderadoN_sub"]));
    $direccion = strip_tags(trim($_POST["a_txtDireccionN_sub"]));
    $distrito = strip_tags(trim($_POST["a_txtDistritoN_sub"]));
    $html = "";
    $valida_dni = fnc_validar_dni_apoderado($conexion, $codigo, "", $dni);
    if (count($valida_dni) > 0) {
        $html .= "0***El número de documento ya esta registrado, favor de ingresar otro.************";
    } else {
        $cadena = "('" . $codigo . "','" . $tipo_apoderado . "','" . strtoupper($nombres) . "','" .
                $dni . "','" . trim($correo) . "','" . $telefono . "','" . strtoupper($direccion) . "','" . strtoupper($distrito) . "','1')";
        $idApoderado = fnc_registrar_apoderado($conexion, $cadena);
        if ($idApoderado) {
            $htmlSelect = "";
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $codigo, "");
            if (count($lista_apoderados) > 0) {
                $selected = "";
                $htmlSelect .= '<option value="">-- Seleccione --</option>';
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $idApoderado) {
                        $selected = " selected ";
                    }
                    $htmlSelect .= '<option value="' . $lista["codigo"] . '" ' . $selected . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . $lista["nombre"] . '</option>';
                }
                $htmlSelect .= '<option value="-1" >-- Otro --</option>';
            } else {
                $htmlSelect = "";
            }
            $html_editarInfoApoderado = '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-editar-apoderado-sub" data-backdrop="static" data-alumno="' . $codigo . '" data-info-apoderado="' . $idApoderado . '">Editar</button>';

            $html_detalle_apo = '<div class="col-md-3" style="margin-bottom: 0px;">
                    <label>Correo: </label>
                  </div>
                  <div class="col-md-4"><span>' . trim($correo) . '</span></div>
                  <div class="col-md-2">
                     <label>Teléfono: </label>
                  </div>
                  <div class="col-md-3"><span>' . $telefono . '</span>
                  </div>';
            $html_apo_nombreDni = '<label>' . strtoupper($nombres) . '<br>' . $dni . '</label>';
            $html = "1***Datos de Apoderado registrados correctamente***" . $htmlSelect . "***" . $html_editarInfoApoderado . "***" . $html_detalle_apo . "***" . $html_apo_nombreDni;
        }
    }
    echo $html;
}

function formulario_editar_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $eu_codsolicitud = explode("-", $s_solicitud);
    $solicitud_codigo = explode("/", $eu_codsolicitud[1]);
    $lista_sol = fnc_listar_todas_solicitudes_x_entrevista($conexion, $solicitud_codigo[0], "1", "1", p_perfil, p_sede);
    $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
              <label>Entrevista / Subentrevista</label>
            </div>
            <div class="col-md-10">';
    $html .= '<select id="cbbTipoSolicitudCodisEdi" class="form-control select2" style="width: 100%" onchange="cargar_solicitudes_a_editar(this)">
                <option value="" >-- Seleccione --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value="' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
        </div>
        <div id="divDetalleEditar"></div>';
    echo $html;
}

function formulario_carga_solicitudes() {//Guadalupe
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["sol_cod"]));
    $array = explode("-", $s_solicitud);
    $entre_sub = "";
    $html = "";
    $userData = fnc_datos_usuario($conexion, p_usuario);
    $perfil = $userData[0]["perfil"];
    $campos_disabled = "";
    if ($perfil === "2") {
        $campos_disabled = " disabled ";
    } else {
        $campos_disabled = "";
    }
    if ($array[0] === "ent") {
        $entre_sub = "Entrevista";
    } else {
        $entre_sub = "Subentrevista";
    }
    $lista_solicitud = fnc_obtener_solicitud_x_codigo($conexion, $array[0], $array[1]);
    if (count($lista_solicitud) > 0) {
        $tipos_entrevistas = fnc_lista_tipo_entrevistas($conexion, "");
        $lista_categorias = fnc_lista_categorias($conexion, "");
        $lista_subcategorias = fnc_lista_subcategorias($conexion, $lista_solicitud[0]["categoria"], "");
        $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>C&oacute;digo de ' . $entre_sub . ': </label>
            </div>
            <div class="col-md-10">
                <label>' . $lista_solicitud[0]["codigo"] . '</label>
                <input type="hidden" id="cod_solicitud_edi" value="' . $s_solicitud . '"/>
                <input type="hidden" id="codi_solicitud_edi" value="' . $array[1] . '"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Buscar alumno: </label>
            </div>
            <div class="col-md-4">
                <input type="text" id="searchAlumno_edi" class="typeahead form-control" ' . $campos_disabled . ' style="size:12px;text-transform: uppercase;" value="" autocomplete="off">
            </div>
            <div class="col-md-4">
                <input type="hidden" id="matric_edi" value="' . $lista_solicitud[0]["matricula"] . '*' . $lista_solicitud[0]["aluId"] . '"/>
                <label id="dataAlumno_edi" style="font-size: 16px;">' . $lista_solicitud[0]["alumno_busq"] . '</label>
            </div>
        </div>
        <div class="row space-div">
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Categoria: </label>
        </div>
        <div class="col-md-4">';
        $html .= '<select id="cbbCategoria_edi" class="form-control select2" ' . $campos_disabled . ' style="width: 100%" onchange="cargar_subcategorias_edi(this)">
               <option value="">-- Seleccione --</option>';
        if (count($lista_categorias) > 0) {
            $selected_cate = "";
            foreach ($lista_categorias as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["categoria"]) {
                    $selected_cate = " selected ";
                } else {
                    $selected_cate = "";
                }
                $html .= "<option value='" . $lista["id"] . "' $selected_cate>" . $lista["nombre"] . "</option>";
            }
        }
        $html .= '</select>';
        $html .= '</div>
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Subcategoria: </label>
        </div>
        <div class="col-md-4">';
        $html .= '<select id="cbbSubcategoria_edi" class="form-control select2" ' . $campos_disabled . ' style="width: 100%">
                <option value="">-- Seleccione --</option>';
        if (count($lista_subcategorias) > 0) {
            $selected_subcate = "";
            foreach ($lista_subcategorias as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["subcategorgia"]) {
                    $selected_subcate = " selected ";
                } else {
                    $selected_subcate = "";
                }
                $html .= "<option value='" . $lista["id"] . "' $selected_subcate>" . $lista["nombre"] . "</option>";
            }
        }
        $html .= '</select>';
        $html .= '</div>
       </div>';
        $html .= '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Tipo de entrevista: </label>
            </div>
            <div class="col-md-3">';
        $html .= '<select id="cbbTipoSolicitud_edi" class="form-control select2" style="width: 100%" disabled>
                <option value="">-- Seleccione --</option>';
        if (count($tipos_entrevistas) > 0) {
            $selected_tips = "";
            foreach ($tipos_entrevistas as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["ent_id"]) {
                    $selected_tips = " selected ";
                } else {
                    $selected_tips = "";
                }
                $html .= "<option value='" . $lista["id"] . "' $selected_tips>" . $lista["nombre"] . "</option>";
            }
        }
        $html .= '</select>';
        $html .= '</div><input type="hidden" id="txt_sede_edi" value="' . $lista_solicitud[0]["sedeId"] . '">
        </div>';
        $html .= '<div class="card card-warning" id="divSubEntrevista_edi">';
        $html .= '<input type="hidden" id="txt_perfil" value="' . p_perfil . '">';
        if ($lista_solicitud[0]["ent_id"] === "1") {
            $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad_edi"> RESERVADO:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad_edi" ' . $campos_disabled . ' style="transform : scale(1.8);" ' . ($lista_solicitud[0]['privacidad'] == '1' ? 'checked' : '') . '>
                    </div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span id="nombre_estu_edi">' . strtoupper($lista_solicitud[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span id="grado_estu_id">' . $lista_solicitud[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Sexo: </label>
                    </div>
                    <div class="col-md-4">';
            $html .= '<select id="cbbSexo_edi" class="form-control select2" ' . $campos_disabled . ' style="width: 100%">
                <option value="0">-- Seleccione --</option>';
            $lista_sexo = fnc_lista_sexo();
            $selected_sexo = "";
            if (count($lista_sexo) > 0) {
                foreach ($lista_sexo as $lista) {
                    if ($lista["codigo"] === $lista_solicitud[0]["sexo"]) {
                        $selected_sexo = " selected ";
                    } else {
                        $selected_sexo = "";
                    }
                    $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_sexo . '>' . strtoupper($lista["nombre"]) . '</option>';
                }
            }

            $html .= '</select></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . $lista_solicitud[0]["usuario"] . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span id="sede_estu_id">' . $lista_solicitud[0]["sede"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_edi" ' . $campos_disabled . ' placeholder="">' . $lista_solicitud[0]["motivo"] . '</textarea>
                        ';
            $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
                </div>
                <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del estudiante: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEstudiante_edi" ' . $campos_disabled . ' placeholder="">' . $lista_solicitud[0]["plan_estudiante"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del entrevistador(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEntrevistador_edi" placeholder="">' . $lista_solicitud[0]["plan_entrevistador"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdos_edi" rows="3" ' . $campos_disabled . ' placeholder="">' . $lista_solicitud[0]["acuerdos"] . '</textarea></div>
                </div>
              </div>'
                    . '<div class="row space-div">'
                    . '<div class="col-md-5" style="margin-bottom: 0px;">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">';
            $imagen_codi = "";
            $imagen1 = "";
            if (count($imagen_soli) > 0) {
                if ($imagen_soli[0]["id"] !== "") {
                    $imagen_codi = $imagen_soli[0]["id"];
                    $imagen1 = "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]);
                } else {
                    $imagen_codi = "";
                    $imagen1 = "";
                }
            } else {
                $imagen_codi = "";
                $imagen1 = "";
            }
            $html .= '<input type="hidden" id="firma1" value="' . $imagen_codi . '"/>
                    <div class="description">Firma del estudiante</div>
                    <div class="signature-pad--body" id="divFirma1">';
            if (trim($imagen1) !== "") {
                $html .= '<img id="ruta_img1" src="' . $imagen1 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>';
            } else {
                $html .= '<div style="height:152px;width: 80%;cursor:pointer;border: 1px black solid;"></div>';
            }
            $html .= '<canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas1_edi" height="152" width="531"></canvas>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="iniciar_firma_edi()" ' . $campos_disabled . '>Iniciar</button>&nbsp;&nbsp;
                        <button type="button" class="btn btn-default" id="btnLimpiarFirma1" onclick="limpiar_firma_edi();" disabled>Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label id="divApoderadoNombreDNI_edi">' . str_replace(" - ", "<br/>", strtoupper($lista_solicitud[0]["alumno"])) . '<label/>
                   </div>'
                    . '</div>'
                    . '<div class="col-md-2" style="margin-bottom: 0px;">'
                    . '</div>'
                    . '<div class="col-md-5" style="margin-bottom: 0px;">';
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >';
            $imagen_codi2 = "";
            $imagen2 = "";
            if (count($imagen_soli2) > 0) {
                if ($imagen_soli2[0]["id"] !== "") {
                    $imagen_codi2 = $imagen_soli2[0]["id"];
                    $imagen2 = "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]);
                } else {
                    $imagen_codi2 = "";
                    $imagen2 = "";
                }
            } else {
                $imagen_codi2 = "";
                $imagen2 = "";
            }
            $html .= '<input type="hidden" id="firma2" value="' . $imagen_codi2 . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body" id="divFirma2">';
            if (trim($imagen2) !== "") {
                $html .= '<img id="ruta_img2" src="' . $imagen2 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>';
            } else {
                $html .= '<div style="height:152px;"></div>';
            }
            $html .= '<canvas style = "width: 80%;cursor:pointer;border: 1px black solid;display:none" id = "canvas2_edi" height = "152" width = "531"></canvas>
            <br/>
            </div>'; //Guadalupe
            if ($lista_solicitud[0]["usuCodi"] === p_usuario && $lista_solicitud[0]["perfil"] === p_perfil && p_perfil === "3") {
                $html .= '';
            } else {
                $html .= '<div>
            <button type="button" class="btn btn-default" onclick="iniciar_firma_edi2()" ' . $campos_disabled . '>Iniciar</button>&nbsp;&nbsp;
            <button type = "button" class = "btn btn-default" id="btnLimpiarFirma2" onclick="limpiar_firma_entrevistador_edi();" disabled>Limpiar firma</button>
            </div>';
            }
            $html .= '<div style = "margin-left: 20px;">
            <label>' . strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"] . '<label/>
            </div>'
                    . ' </div>'
                    . '</div>'; //Guadalupe
        } elseif ($lista_solicitud[0]["ent_id"] === "2") {
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], "");
            $apoderado = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], $lista_solicitud[0]["apoderado"]);
            $html .= '<div class = "card-header">
            <h3 class = "card-title">FICHA DE ENTREVISTA A PADRES DE FAMILIA</h3>
            </div>
            <div class = "card-body">
            <h5>I. DATOS INFORMATIVOS:</h5>
            <div class = "row space-div">
            <div class = "col-md-12 icheck-success d-inline">
            <label for = "checkPrivacidad_edi"> RESERVADO:
            </label>&nbsp;
            &nbsp;
            &nbsp;
            <input type = "checkbox" id = "checkPrivacidad_edi" ' . $campos_disabled . ' style="transform:scale(1.8);" ' . ($lista_solicitud[0]['privacidad'] == '1' ? 'checked' : '') . '>
            </div>
            </div>
            <div class = "row space-div">
            <input type = "hidden" id = "txtAlumCodig_edi" value = "' . $lista_solicitud[0]["aluId"] . '"/>
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Nombre del estudiante: </label>
            </div>
            <div class = "col-md-4"><span id = "nombre_estu_edi">' . strtoupper($lista_solicitud[0]["alumno"]) . '</span></div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Grado, sección y nivel: </label>
            </div>
            <div class = "col-md-3"><span id = "grado_estu_id">' . $lista_solicitud[0]["grado"] . '</span></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Sexo: </label>
            </div>
            <div class = "col-md-4">';
            $html .= '<select id="cbbSexo_edi" class="form-control select2" ' . $campos_disabled . ' style="width: 100%">
            <option value = "0"> --Seleccione --</option>';
            $lista_sexo = fnc_lista_sexo();
            $selected_sexo = "";
            if (count($lista_sexo) > 0) {
                foreach ($lista_sexo as $lista) {
                    if ($lista["codigo"] === $lista_solicitud[0]["sexo"]) {
                        $selected_sexo = " selected ";
                    } else {
                        $selected_sexo = "";
                    }
                    $html .= '<option value = "' . $lista["codigo"] . '" ' . $selected_sexo . '>' . strtoupper($lista["nombre"]) . '</option>';
                }
            }

            $html .= '</select></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Nombre del padre/madre/apoderado: </label>
            </div>
            <div class = "col-md-4">';
            $html .= '<select id = "cbbTipoApoderado_edi" ' . $campos_disabled . ' class="form-control select2" style = "width: 100%" onchange = "mostrar_info_apoderado_edi(this)">
            <option value = ""> --Seleccione --</option>';
            if (count($lista_apoderados) > 0) {
                $selected_apoderado = "";
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $lista_solicitud[0]["apoderado"]) {
                        $selected_apoderado = " selected ";
                    } else {
                        $selected_apoderado = "";
                    }
                    $html .= '<option value = "' . $lista["codigo"] . '" ' . $selected_apoderado . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
                }
            }
            $html .= ' <option value = "-1" > --Otro --</option></select>';
            $html .= '</div><div class = "col-md-3" id = "divEditarInfoApoderado_edi">
            <button type = "button" class = "btn btn-warning" ' . $campos_disabled . ' data-toggle="modal" data-target = "#modal-editar-apoderado-edi" data-backdrop = "static" data-alumno = "' . $lista_solicitud[0]["aluId"] . '" data-info-apoderado = "' . $lista_solicitud[0]["apoderado"] . '">Editar</button>
            </div></div>
            <div class = "row space-div" id = "detalleApoderado_edi">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4">' . $apoderado[0]["correo"] . '</div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3">' . $apoderado[0]["telefono"] . '</div>
            </div>';
            $html .= '
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Entrevistador: </label>
            </div>
            <div class = "col-md-4"><span>' . strtoupper($lista_solicitud[0]["usuario"]) . '</span></div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Sede: </label>
            </div>
            <div class = "col-md-3"><span id = "sede_estu_id">' . $lista_solicitud[0]["sede"] . '</span></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Motivo de la entrevista: </label>
            </div>
            <div class = "col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_edi" ' . $campos_disabled . ' placeholder="">' . $lista_solicitud[0]["motivo"] . '</textarea>';
            $html .= ' </div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Fecha y hora: </label>
            </div>
            <div class = "col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
            </div>
            <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
            <div class = "row space-div">
            <div class = "col-md-12"><textarea class = "form-control" rows="3" id="txtInforme_edi" ' . $campos_disabled . ' placeholder = "">' . $lista_solicitud[0]["informe"] . '</textarea></div>
            </div>

            <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Planteamiento del padre, madre o apoderado: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" rows = "3" id="txtPlanPadre_edi" ' . $campos_disabled . ' placeholder="">' . $lista_solicitud[0]["plan_padre"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" rows = "3" id = "txtPlanDocente_edi" ' . $campos_disabled . ' placeholder = "">' . $lista_solicitud[0]["plan_docente"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Acuerdos - Acciones a realizar por los padres: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" id = "txtAcuerdosPadres_edi" rows="3" ' . $campos_disabled . ' placeholder = "">' . $lista_solicitud[0]["acuerdos1"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Acuerdos - Acciones a realizar por el colegio: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" id = "txtAcuerdosColegio_edi" rows = "3" ' . $campos_disabled . ' placeholder = "">' . $lista_solicitud[0]["acuerdos2"] . '</textarea></div>
            </div>
            </div>'
                    . '<div class = "row space-div">'
                    . '<div class = "col-md-5" style = "margin-bottom: 0px;">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_codi = "";
            $imagen1 = "";
            if (count($imagen_soli) > 0) {
                if ($imagen_soli[0]["id"] !== "") {
                    $imagen_codi = $imagen_soli[0]["id"];
                    $imagen1 = "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]);
                } else {
                    $imagen_codi = "";
                    $imagen1 = "";
                }
            } else {
                $imagen_codi = "";
                $imagen1 = "";
            }

            $html .= '<div id = "signature-pad-edi" class = "signature-pad" style = "margin-left: 20px;">
            <input type = "hidden" id = "firma1" value = "' . $imagen_codi . '"/>
            <div class = "description">Firma del padre, madre o apoderado</div>
            <div class = "signature-pad--body" id="divFirma1">';
            if (trim($imagen1) !== "") {
                $html .= '<img id="ruta_img1" src="' . $imagen1 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>';
            } else {
                $html .= '<div style="height:152px;width: 80%;cursor:pointer;border: 1px black solid;"></div>';
            }
            $html .= '<canvas style = "width: 80%;cursor:pointer;border: 1px black solid;display:none" id = "canvas1_edi" height = "152" width = "531"></canvas>
            <br/>
            </div>
            </div>
            <div style = "margin-left: 20px;">
                <button type="button" class="btn btn-default" onclick="iniciar_firma_edi()" ' . $campos_disabled . '>Iniciar</button>&nbsp;&nbsp;
                <button type = "button" class = "btn btn-default" id="btnLimpiarFirma1" onclick = "limpiar_firma_edi();" disabled>Limpiar firma</button>
            </div>
            <div style = "margin-left: 20px;" id = "divApoderadoNombreDNI_edi">
            <label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '<label/>
            </div>'
                    . '</div>'
                    . '<div class = "col-md-2" style = "margin-bottom: 0px;">'
                    . '</div>'
                    . '<div class = "col-md-5" style = "margin-bottom: 0px;">';
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $imagen_codi2 = "";
            $imagen2 = "";
            if (count($imagen_soli2) > 0) {
                if ($imagen_soli2[0]["id"] !== "") {
                    $imagen_codi2 = $imagen_soli2[0]["id"];
                    $imagen2 = "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]);
                } else {
                    $imagen_codi2 = "";
                    $imagen2 = "";
                }
            } else {
                $imagen_codi2 = "";
                $imagen2 = "";
            }
            $html .= '<div id = "signature-pad-entrevistador-edi" class = "signature-pad" >
            <input type = "hidden" id = "firma2" value = "' . $imagen_codi2 . '"/>
            <div class = "description">Firma del entrevistador</div>
            <div class = "signature-pad--body" id="divFirma2">';
            if (trim($imagen2) !== "") {
                $html .= '<img id = "ruta_img2" src = "' . $imagen2 . '" style = "width: 80%;cursor:pointer;border: 1px black solid;" height = "152"/>';
            } else {
                $html .= '<div style="height:152px;"></div>';
            }
            $html .= '<canvas style = "width: 80%;cursor:pointer;border: 1px black solid;display:none" id = "canvas2_edi" height = "152" width = "531"></canvas>
            <br/>
            </div>'; //Guadalupe
            $html .= '<div>
            <button type = "button" class = "btn btn-default" onclick = "iniciar_firma_edi2()" ' . $campos_disabled . '>Iniciar</button>&nbsp;
            &nbsp;
            <button type = "button" class = "btn btn-default" id = "btnLimpiarFirma2" onclick = "limpiar_firma_entrevistador_edi();" disabled>Limpiar firma</button>
            </div>';

            $html .= '<div style = "margin-left: 20px;">
            <label>' . strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"] . '<label/>
            </div>'
                    . ' </div>'
                    . '</div>';
        }
        $html .= '</div>';
    }
    echo $html;
}

function formulario_detalle_tipo_solicitud_edi() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_tipo_sol = strip_tags(trim($_POST["sol_tipo"]));
    $sm_sol_matricula = strip_tags(trim($_POST["sol_matricula"]));
    $sm_sol_categoria = strip_tags(trim($_POST["sol_categoria"]));
    $sm_sol_subcategoria = strip_tags(trim($_POST["sol_subcategoria"]));
    $usuario_data = fnc_datos_usuario($conexion, p_usuario);
    $matricula = fnc_alumno_matricula_detalle($conexion, $sm_sol_matricula);
    $html = strtoupper($matricula[0]["alumno"]) . '***' . $matricula[0]["grado"] . "***";
    if ($sm_tipo_sol === "1") {
        $html .= '***';
    } elseif ($sm_tipo_sol === "2") {
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $matricula[0]["aluId"], "");
        $html .= '<option value = ""> --Seleccione --</option>';
        if (count($lista_apoderados) > 0) {
            $selected_apoderado = "";
            foreach ($lista_apoderados as $lista) {
                $html .= '<option value = "' . $lista["codigo"] . '" >' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
            }
        }
        $html .= ' <option value = "-1" > --Otro --</option>***';
    }
    $html .= $matricula[0]["sede"] . "***" . str_replace(" - ", "<br/>", strtoupper($matricula[0]["alumno"])) . "***";
    $html .= '<div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4"></div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3"></div>';
    echo $html;
}

function formulario_carga_info_apoderado_edi() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alu_cod = strip_tags(trim($_POST["alu_cod"]));
    $tip_apod = strip_tags(trim($_POST["tip_apod"]));
    $html = "";
    if ($tip_apod == "" || $tip_apod == "-1") {
        $html = ' <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4"><span></span></div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3"><span></span></div>';
        $html .= "********";
    } else {
        $apoderado = fnc_lista_apoderados_de_alumno($conexion, $alu_cod, $tip_apod);

        if (count($apoderado) > 0) {
            $html = ' <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4"><span>' . $apoderado[0]["correo"] . '</span></div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3"><span>' . $apoderado[0]["telefono"] . '</span></div>';

            $html .= "****";
            $html .= '<button type = "button" class = "btn btn-warning" data-toggle = "modal" data-target = "#modal-editar-apoderado-edi" data-backdrop = "static" '
                    . ' data-alumno = "' . $alu_cod . '" data-info-apoderado = "' . $apoderado[0]["codigo"] . '">Editar</button>****<label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '</label>';
        } else {
            $html = ' <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4"><span></span></div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3"><span></span></div>';
            $html .= "********";
        }
    }
    echo $html;
}

function formulario_nuevo_apoderado_edi() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumno = strip_tags(trim($_POST["sm_alumno"]));
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    $html = '<div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Tipo</label>
            </div>
            <div class = "col-md-6">';
    $html .= '<input type = "hidden" id = "txtAlumnoCodiN_edi" class = "form-control" value = "' . $alumno . '"/>';
    $html .= '<select id = "cbbTipoApoderadoN_edi" class = "form-control" style = "width: 100%" disabled>
            ';
    if (count($tipos_apoderados) > 0) {
        $selected = "";
        foreach ($tipos_apoderados as $lista) {
            if ($lista["id"] == 3) {
                $selected = " selected ";
            } else {
                $selected = "";
            }
            $html .= '<option value = "' . $lista["id"] . '" ' . $selected . ' >' . $lista["nombre"] . '</option>';
        }
    }
    $html .= '</select></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>DNI</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtDniN_edi" class = "form-control" style = "width: 100%" maxlength = "12"
            onkeypress = "return solo_numeros(event);"/>
            </div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Apellidos y nombres</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtNombresApoderadoN_edi" class = "form-control" style = "width: 100%;text-transform: uppercase;"
            onkeypress = "return solo_letras(event);"/>
            </div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtCorreoApoderadoN_edi" type = "text" class = "form-control" style = "width: 100%"/>
            </div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Telefono</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtTelfApoderadoN_edi" type = "text" class = "form-control" style = "width: 100%" maxlength = "9"
            onkeypress = "return solo_numeros(event);"/>
            </div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label > Direcci & oacute;
            n</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtDireccionN_edi" type = "text" class = "form-control" style = "width: 100%;text-transform: uppercase;"/>
            </div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Distrito</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtDistritoN_edi" type = "text" class = "form-control" style = "width: 100%;text-transform: uppercase;"
            onkeypress = "return solo_letras(event);"/>
            </div>
            </div>';
    echo $html;
}

function formulario_editar_apoderado_edi() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $apod = strip_tags(trim($_POST["sm_apoderado"]));
    $alumnoCod = strip_tags(trim($_POST["sm_alumno"]));
    $html = "";
    $apoderado = fnc_apoderado_del_alumno($conexion, $apod);
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    if (count($apoderado) > 0) {
        $html = '<div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Tipo</label>
            </div>
            <input id = "txtAlumnCodigo_edi" type = "hidden" class = "form-control" value = "' . $alumnoCod . '" />
            <div class = "col-md-6">';
        $html .= '<select id = "cbbTipoApoderado_edi" class = "form-control select2" style = "width: 100%" disabled>
            ';
        if (count($tipos_apoderados) > 0) {
            $selected = "";
            foreach ($tipos_apoderados as $lista) {
                if ($lista["id"] == $apoderado[0]["tipo"]) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $html .= '<option value = "' . $lista["id"] . '" ' . $selected . ' >' . $lista["nombre"] . '</option>';
            }
        }
        $html .= '</select></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Nombres</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtNombresApoderado_edi" class = "form-control select2" style = "width: 100%" disabled value = "' . $apoderado[0]["nombres"] . '" />
            </div>
            </div>

            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>DNI</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtDniApoderado_edi" type = "text" class = "form-control select2" style = "width: 100%" value = "' . $apoderado[0]["dni"] . '"
            maxlength = "12" onkeypress = "return solo_numeros(event);"/>
            </div>
            </div>
            <input id = "txtApoderadoCod_edi" type = "hidden" class = "form-control select2" style = "width: 100%" value = "' . $apoderado[0]["id"] . '" />
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtCorreoApoderado_edi" type = "text" class = "form-control select2" style = "width: 100%" value = "' . $apoderado[0]["correo"] . '" />
            </div>
            </div>

            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Telefono</label>
            </div>
            <div class = "col-md-6">
            <input id = "txtTelfApoderado_edi" type = "text" class = "form-control select2" style = "width: 100%" value = "' . $apoderado[0]["telefono"] . '"
            maxlength = "9" onkeypress = "return solo_numeros(event);"/>
            </div>
            </div>';
    }
    echo $html;
}

function operacion_editar_apoderado_edi() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumnoCod = strip_tags(trim($_POST["a_txtAlumnCodigo_edi"]));
    $codigo = strip_tags(trim($_POST["a_txtApoderadoCod_edi"]));
    $dni = strip_tags(trim($_POST["a_txtDniApoderado_edi"]));
    $correo = strip_tags(trim($_POST["a_txtCorreoApoderado_edi"]));
    $telefono = strip_tags(trim($_POST["a_txtTelfApoderado_edi"]));
    $html = "";
    $valida_dni = fnc_validar_dni_apoderado($conexion, "", $codigo, $dni);
    if (count($valida_dni) > 0) {
        $html .= "0***El número de documento ya esta registrado, favor de ingresar otro.*********";
    } else {
        $htmlSelect = "";
        $apoderado = fnc_apoderado_del_alumno($conexion, $codigo);
        if (trim($apoderado[0]["dni"]) !== trim($dni) || trim($apoderado[0]["correo"]) !== trim($correo) || trim($apoderado[0]["telefono"]) !== trim($telefono)) {
            $str_cadena = "('" . $codigo . "',NOW(),'" . $apoderado[0]["correo"] . "','" . $apoderado[0]["telefono"] . "','1')";
            $resp = fnc_registrar_apoderado_historico($conexion, $str_cadena);
            if ($resp) {
                fnc_modificar_apoderado($conexion, $codigo, $dni, $correo, $telefono);
            }
        }
        $html .= "1***Apoderado editado correctamente.***";
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $alumnoCod, "");
        if (count($lista_apoderados) > 0) {
            $selected = "";
            $htmlSelect .= '<option value = ""> --Seleccione --</option>';
            foreach ($lista_apoderados as $lista) {
                if ($lista["codigo"] == $codigo) {
                    $selected = " selected ";
                } else {
                    $selected = "";
                }
                $htmlSelect .= '<option value = "' . $lista["codigo"] . '" ' . $selected . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . $lista["nombre"] . '</option>';
            }
            $htmlSelect .= '<option value = "-1" > --Otro --</option>';
        } else {
            $htmlSelect = "";
        }

        $html .= $htmlSelect . "***";

        $html .= ' <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4"><span>' . $correo . '</span></div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3"><span>' . $telefono . '</span></div>***';
        $html .= '<label>' . strtoupper($apoderado[0]["nombres"]) . '<br>' . $dni . '</label>';
    }
    echo $html;
}

function operacion_registrar_apoderado_edi() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $codigo = strip_tags(trim($_POST["a_txtAlumnoCodiN_edi"]));
    $tipo_apoderado = strip_tags(trim($_POST["a_cbbTipoApoderadoN_edi"]));
    $dni = strip_tags(trim($_POST["a_txtDniN_edi"]));
    $nombres = strip_tags(trim($_POST["a_txtNombresApoderadoN_edi"]));
    $correo = strip_tags(trim($_POST["a_txtCorreoApoderadoN_edi"]));
    $telefono = strip_tags(trim($_POST["a_txtTelfApoderadoN_edi"]));
    $direccion = strip_tags(trim($_POST["a_txtDireccionN_edi"]));
    $distrito = strip_tags(trim($_POST["a_txtDistritoN_edi"]));
    $html = "";
    $valida_dni = fnc_validar_dni_apoderado($conexion, $codigo, "", $dni);
    if (count($valida_dni) > 0) {
        $html .= "0***El número de documento ya esta registrado, favor de ingresar otro.************";
    } else {
        $cadena = "('" . $codigo . "','" . $tipo_apoderado . "','" . strtoupper($nombres) . "','" .
                $dni . "','" . trim($correo) . "','" . $telefono . "','" . strtoupper($direccion) . "','" . strtoupper($distrito) . "','1')";
        $idApoderado = fnc_registrar_apoderado($conexion, $cadena);
        if ($idApoderado) {
            $htmlSelect = "";
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $codigo, "");
            if (count($lista_apoderados) > 0) {
                $selected = "";
                $htmlSelect .= '<option value = ""> --Seleccione --</option>';
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $idApoderado) {
                        $selected = " selected ";
                    }
                    $htmlSelect .= '<option value = "' . $lista["codigo"] . '" ' . $selected . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . $lista["nombre"] . '</option>';
                }
                $htmlSelect .= '<option value = "-1" > --Otro --</option>';
            } else {
                $htmlSelect = "";
            }
            $html_editarInfoApoderado = '<button type = "button" class = "btn btn-warning" data-toggle = "modal" data-target = "#modal-editar-apoderado-sub" data-backdrop = "static" data-alumno = "' . $codigo . '" data-info-apoderado = "' . $idApoderado . '">Editar</button>';

            $html_detalle_apo = '<div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4"><span>' . trim($correo) . '</span></div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3"><span>' . $telefono . '</span>
            </div>';
            $html_apo_nombreDni = '<label>' . strtoupper($nombres) . '<br>' . $dni . '</label>';
            $html = "1***Datos de Apoderado registrados correctamente***" . $htmlSelect . "***" . $html_editarInfoApoderado . "***" . $html_detalle_apo . "***" . $html_apo_nombreDni;
        }
    }
    echo $html;
}

function formulario_carga_solicitudes_detalla() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["sol_cod"]));
    $array = explode("-", $s_solicitud);
    $entre_sub = "";
    $html = "";
    if ($array[0] === "ent") {
        $entre_sub = "Entrevista";
    } else {
        $entre_sub = "Subentrevista";
    }
    $lista_solicitud = fnc_obtener_solicitud_x_codigo($conexion, $array[0], $array[1]);
    if (count($lista_solicitud) > 0) {
        $tipos_entrevistas = fnc_lista_tipo_entrevistas($conexion, "");
        $lista_categorias = fnc_lista_categorias($conexion, "");
        $lista_subcategorias = fnc_lista_subcategorias($conexion, $lista_solicitud[0]["categoria"], "");
        $html = '<div class = "row space-div">
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label > C&oacute;digo de ' . $entre_sub . ': </label>
            </div>
            <div class = "col-md-10">
            <label>' . $lista_solicitud[0]["codigo"] . '</label>
            </div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Categoria: </label>
            </div>
            <div class = "col-md-4">';
        $html .= '<select id = "cbbCategoria_edi" class = "form-control select2" style = "width: 100%" onchange = "cargar_subcategorias_edi(this)" disabled>
            <option value = ""> --Seleccione --</option>';
        if (count($lista_categorias) > 0) {
            $selected_cate = "";
            foreach ($lista_categorias as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["categoria"]) {
                    $selected_cate = " selected ";
                } else {
                    $selected_cate = "";
                }
                $html .= "<option value='" . $lista["id"] . "' $selected_cate>" . $lista["nombre"] . "</option>";
            }
        }
        $html .= '</select>';
        $html .= '</div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Subcategoria: </label>
            </div>
            <div class = "col-md-4">';
        $html .= '<select id = "cbbSubcategoria_edi" class = "form-control select2" style = "width: 100%" disabled>
            <option value = ""> --Seleccione --</option>';
        if (count($lista_subcategorias) > 0) {
            $selected_subcate = "";
            foreach ($lista_subcategorias as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["subcategorgia"]) {
                    $selected_subcate = " selected ";
                } else {
                    $selected_subcate = "";
                }
                $html .= "<option value='" . $lista["id"] . "' $selected_subcate>" . $lista["nombre"] . "</option>";
            }
        }
        $html .= '</select>';
        $html .= '</div>
            </div>';
        $html .= '<div class = "row space-div">
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Tipo de entrevista: </label>
            </div>
            <div class = "col-md-3">';
        $html .= '<select id = "cbbTipoSolicitud_edi" class = "form-control select2" style = "width: 100%" disabled>
            <option value = ""> --Seleccione --</option>';
        if (count($tipos_entrevistas) > 0) {
            $selected_tips = "";
            foreach ($tipos_entrevistas as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["ent_id"]) {
                    $selected_tips = " selected ";
                } else {
                    $selected_tips = "";
                }
                $html .= "<option value='" . $lista["id"] . "' $selected_tips>" . $lista["nombre"] . "</option>";
            }
        }
        $html .= '</select>';
        $html .= '</div><input type = "hidden" id = "txt_sede_edi" value = "' . $lista_solicitud[0]["sedeId"] . '">
            </div>';
        $html .= '<div class = "card card-warning" id = "divSubEntrevista_edi">';
        if ($lista_solicitud[0]["ent_id"] === "1") {
            $html .= '<div class = "card-header">
            <h3 class = "card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
            </div>
            <div class = "card-body">
            <h5>I. DATOS INFORMATIVOS:</h5>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Nombre del estudiante: </label>
            </div>
            <div class = "col-md-4"><span id = "nombre_estu_edi">' . strtoupper($lista_solicitud[0]["alumno"]) . '</span></div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Grado, sección y nivel: </label>
            </div>
            <div class = "col-md-3"><span id = "grado_estu_id">' . $lista_solicitud[0]["grado"] . '</span></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Entrevistador: </label>
            </div>
            <div class = "col-md-4"><span>' . $lista_solicitud[0]["usuario"] . '</span></div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Sede: </label>
            </div>
            <div class = "col-md-3"><span id = "sede_estu_id">' . $lista_solicitud[0]["sede"] . '</span></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Motivo de la entrevista: </label>
            </div>
            <div class = "col-md-4"><textarea class = "form-control" rows = "3" id = "txtMotivo_edi" placeholder = "" disabled>' . $lista_solicitud[0]["motivo"] . '</textarea>
            ';
            $html .= ' </div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Fecha y hora: </label>
            </div>
            <div class = "col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
            </div>
            <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Planteamiento del estudiante: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" rows = "3" id = "txtPlanEstudiante_edi" placeholder = "" disabled>' . $lista_solicitud[0]["plan_estudiante"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Planteamiento del entrevistador(a): </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" rows = "3" id = "txtPlanEntrevistador_edi" placeholder = "" disabled>' . $lista_solicitud[0]["plan_entrevistador"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Acuerdos: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" id = "txtAcuerdos_edi" rows = "3" placeholder = "" disabled>' . $lista_solicitud[0]["acuerdos"] . '</textarea></div>
            </div>
            </div>'
                    . '<div class = "row space-div">'
                    . '<div class = "col-md-5" style = "margin-bottom: 0px;">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_codi = "";
            $imagen1 = "";
            if (count($imagen_soli) > 0) {
                if ($imagen_soli[0]["id"] !== "") {
                    $imagen_codi = $imagen_soli[0]["id"];
                    $imagen1 = "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]);
                } else {
                    $imagen_codi = "";
                    $imagen1 = "";
                }
            } else {
                $imagen_codi = "";
                $imagen1 = "";
            }
            $html .= '<div class = "signature-pad" style = "margin-left: 20px;">
            <input type = "hidden" id = "firma1" value = "' . $imagen_codi . '"/>
            <div class = "description">Firma del estudiante</div>
            <div class = "signature-pad--body">';
            if (trim($imagen1) !== "") {
                $html .= '<img id = "ruta_imgdet1" src = "' . $imagen1 . '" style = "width: 80%;cursor:pointer;border: 1px black solid;" height = "152"/>';
            } else {
                $html .= '<div style = "height:152px"></div>';
            }
            $html .= '<canvas style = "width: 80%;cursor:pointer;border: 1px black solid;display:none" id = "canvas1_edi" height = "152" width = "531"></canvas>
            </div>
            </div>
            <div style = "margin-left: 20px;">
            <label id = "divApoderadoNombreDNI_edi">' . str_replace(" - ", "<br/>", strtoupper($lista_solicitud[0]["alumno"])) . '<label/>
            </div>'
                    . '</div>'
                    . '<div class = "col-md-2" style = "margin-bottom: 0px;">'
                    . '</div>'
                    . '<div class = "col-md-5" style = "margin-bottom: 0px;">';
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $imagen_codi2 = "";
            $imagen2 = "";
            if (count($imagen_soli2) > 0) {
                if ($imagen_soli2[0]["id"] !== "") {
                    $imagen_codi2 = $imagen_soli2[0]["id"];
                    $imagen2 = "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]);
                } else {
                    $imagen_codi2 = "";
                    $imagen2 = "";
                }
            } else {
                $imagen_codi2 = "";
                $imagen2 = "";
            }
            $html .= '<div class = "signature-pad" >
            <input type = "hidden" id = "firma2" value = "' . $imagen_codi2 . '"/>
            <div class = "description">Firma del entrevistador</div>
            <div class = "signature-pad--body">';
            if ($imagen2 != "") {
                $html .= '<img id = "ruta_imgdet2" src = "' . $imagen2 . '" style = "width: 80%;cursor:pointer;border: 1px black solid;" height = "152"/>';
            }
            $html .= '<canvas style = "width: 80%;cursor:pointer;border: 1px black solid;display:none" id = "canvas2_edi" height = "152" width = "531"></canvas>
            <br/>
            </div>
            <div style = "margin-left: 20px;">
            <label>' . strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"] . '<label/>
            </div>'
                    . ' </div>'
                    . '</div>';
        } elseif ($lista_solicitud[0]["ent_id"] === "2") {
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], "");
            $apoderado = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], $lista_solicitud[0]["apoderado"]);
            $html .= '<div class = "card-header">
            <h3 class = "card-title">FICHA DE ENTREVISTA A PADRES DE FAMILIA</h3>
            </div>
            <div class = "card-body">
            <h5>I. DATOS INFORMATIVOS:</h5>
            <div class = "row space-div">
            <input type = "hidden" id = "txtAlumCodig_edi" value = "' . $lista_solicitud[0]["aluId"] . '"/>
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Nombre del estudiante: </label>
            </div>
            <div class = "col-md-4"><span id = "nombre_estu_edi">' . strtoupper($lista_solicitud[0]["alumno"]) . '</span></div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Grado, sección y nivel: </label>
            </div>
            <div class = "col-md-3"><span id = "grado_estu_id">' . $lista_solicitud[0]["grado"] . '</span></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Nombre del padre/madre/apoderado: </label>
            </div>
            <div class = "col-md-4">';
            $html .= '<select id = "cbbTipoApoderado_edi" class = "form-control select2" style = "width: 100%" disabled>
            <option value = ""> --Seleccione --</option>';
            if (count($lista_apoderados) > 0) {
                $selected_apoderado = "";
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $lista_solicitud[0]["apoderado"]) {
                        $selected_apoderado = " selected ";
                    } else {
                        $selected_apoderado = "";
                    }
                    $html .= '<option value = "' . $lista["codigo"] . '" ' . $selected_apoderado . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
                }
            }
            $html .= ' <option value = "-1" > --Otro --</option></select>';
            $html .= '</div><div class = "col-md-3" id = "divEditarInfoApoderado_edi">
            </div></div>
            <div class = "row space-div" id = "detalleApoderado_edi">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Correo: </label>
            </div>
            <div class = "col-md-4">' . $apoderado[0]["correo"] . '</div>
            <div class = "col-md-2">
            <label>Teléfono: </label>
            </div>
            <div class = "col-md-3">' . $apoderado[0]["telefono"] . '</div>
            </div>';
            $html .= '
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Entrevistador: </label>
            </div>
            <div class = "col-md-4"><span>' . strtoupper($lista_solicitud[0]["usuario"]) . '</span></div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Sede: </label>
            </div>
            <div class = "col-md-3"><span id = "sede_estu_id">' . $lista_solicitud[0]["sede"] . '</span></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Motivo de la entrevista: </label>
            </div>
            <div class = "col-md-4"><textarea class = "form-control" rows = "3" id = "txtMotivo_edi" placeholder = "" disabled>' . $lista_solicitud[0]["motivo"] . '</textarea>';
            $html .= ' </div>
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Fecha y hora: </label>
            </div>
            <div class = "col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
            </div>
            <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
            <div class = "row space-div">
            <div class = "col-md-12"><textarea class = "form-control" rows = "3" id = "txtInforme_edi" placeholder = "" disabled>' . $lista_solicitud[0]["informe"] . '</textarea></div>
            </div>

            <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Planteamiento del padre, madre o apoderado: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" rows = "3" id = "txtPlanPadre_edi" placeholder = "" disabled>' . $lista_solicitud[0]["plan_padre"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" rows = "3" id = "txtPlanDocente_edi" placeholder = "" disabled>' . $lista_solicitud[0]["plan_docente"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Acuerdos - Acciones a realizar por los padres: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" id = "txtAcuerdosPadres_edi" rows = "3" placeholder = "" disabled>' . $lista_solicitud[0]["acuerdos1"] . '</textarea></div>
            </div>
            <div class = "row space-div">
            <div class = "col-md-3" style = "margin-bottom: 0px;">
            <label>Acuerdos - Acciones a realizar por el colegio: </label>
            </div>
            <div class = "col-md-9"><textarea class = "form-control" id = "txtAcuerdosColegio_edi" rows = "3" placeholder = "" disabled>' . $lista_solicitud[0]["acuerdos2"] . '</textarea></div>
            </div>
            </div>'
                    . '<div class = "row space-div">'
                    . '<div class = "col-md-5" style = "margin-bottom: 0px;">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_codi = "";
            $imagen1 = "";
            if (count($imagen_soli) > 0) {
                if ($imagen_soli[0]["id"] !== "") {
                    $imagen_codi = $imagen_soli[0]["id"];
                    $imagen1 = "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]);
                } else {
                    $imagen_codi = "";
                    $imagen1 = "";
                }
            } else {
                $imagen_codi = "";
                $imagen1 = "";
            }

            $html .= '<div class = "signature-pad" style = "margin-left: 20px;">
            <input type = "hidden" id = "firma1" value = "' . $imagen_codi . '"/>
            <div class = "description">Firma del padre, madre o apoderado</div>
            <div class = "signature-pad--body">
            <img id = "ruta_imgdet1" src = "' . $imagen1 . '" style = "width: 80%;cursor:pointer;border: 1px black solid;" height = "152"/>
            <br/>
            </div>
            </div>
            <div style = "margin-left: 20px;">
            </div>
            <div style = "margin-left: 20px;" id = "divApoderadoNombreDNI_edi">
            <label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '<label/>
            </div>'
                    . '</div>'
                    . '<div class = "col-md-2" style = "margin-bottom: 0px;">'
                    . '</div>'
                    . '<div class = "col-md-5" style = "margin-bottom: 0px;">';
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $imagen_codi2 = "";
            $imagen2 = "";
            if (count($imagen_soli2) > 0) {
                if ($imagen_soli2[0]["id"] !== "") {
                    $imagen_codi2 = $imagen_soli2[0]["id"];
                    $imagen2 = "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]);
                } else {
                    $imagen_codi2 = "";
                    $imagen2 = "";
                }
            } else {
                $imagen_codi2 = "";
                $imagen2 = "";
            }
            $html .= '<div class = "signature-pad" >
            <input type = "hidden" id = "firma2" value = "' . $imagen_codi2 . '"/>
            <div class = "description">Firma del entrevistador</div>
            <div class = "signature-pad--body">
            <img id = "ruta_imgdet2" src = "' . $imagen2 . '" style = "width: 80%;cursor:pointer;border: 1px black solid;" height = "152"/>
            <br/>
            </div>
            <div>
            </div>
            <div style = "margin-left: 20px;">
            <label>' . strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"] . '<label/>
            </div>'
                    . ' </div>'
                    . '</div>';
        }
        $html .= '</div>';
    }
    echo $html;
}

function formulario_carga_solicitudes_eliminar() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["sol_cod"]));
    $array = explode("-", $s_solicitud);
    $entre_sub = "";
    $html = "";
    if ($array[0] === "ent") {
        $entre_sub = "Entrevista";
    } else {
        $entre_sub = "Subentrevista";
    }
    $lista_solicitud = fnc_obtener_solicitud_x_codigo($conexion, $array[0], $array[1]);
    if (count($lista_solicitud) > 0) {
        $html .= '<div class = "row space-div">
            <div class = "col-md-12" style = "margin-bottom: 0px;">
            <input type = "hidden" id = "hdnCodiSolicitud" class = "form-control" value = "' . $s_solicitud . '"/>
            <label>&iquest;
            Esta seguro de eliminar la ' . $entre_sub . ' con c & oacute;
            digo ' . $lista_solicitud[0]["codigo"] . ' del alumno ' . $lista_solicitud[0]["alumno"] . '?</label>
            </div>
            </div>';
    }
    echo $html;
}

function formulario_registro_nueva_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Nombre: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtNombreSed" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtDescripcionSed" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Color: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbColorSed" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $l_iconos = fnc_lista_colores();
                foreach ($l_iconos as $iconos) {
                    echo "<option value='" . $iconos . "' data-content=" . '"' . "<i class = 'fas fa-circle nav-icon' style = 'font-size:18px;color:" . $iconos . "'>&nbsp;
            &nbsp;
            " . $iconos . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}

function proceso_registro_nueva_sede() {//jesucito
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $m_nombre = strip_tags(trim($_POST["m_nombre"]));
    $m_descripcion = strip_tags(trim($_POST["m_descripcion"]));
    $m_imagen = strip_tags(trim($_POST["m_imagen"]));
    $m_codigo = strtoupper(substr($m_descripcion, 0, 4)) . "_" . fnc_generate_random_string(5);
    $cadena = "('" . $m_codigo . "','" . strtoupper($m_nombre) . "','" . $m_descripcion . "','" . $m_imagen . "','1')";
    $registrar_menu = fnc_registrar_sede($conexion, $cadena);
    if ($registrar_menu) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if (count($submenu) > 0) {
            $sql_auditoria = fnc_registrar_sede_auditoria($cadena);
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registro_nueva_sede" . '", "' . "fnc_registrar_sede" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_sede" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
        }

        echo "***1***Sede registrado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al registrar la sede.***<br/>";
    }
}

function formulario_editar_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_em_codigo = strip_tags(trim($_POST["u_sede_codigo"]));
    $eu_codsede = explode("-", $u_em_codigo);
    $sede_codi = explode("/", $eu_codsede[1]);
    $lista_sede = fnc_lista_sedes($conexion, $sede_codi[0], "");
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Nombre: </label>
        </div>
        <div class="col-md-6">
            <input type="hidden" id="hdnCodiSede" class="form-control" value="<?php echo trim($sede_codi[0]); ?>"/>
            <input type="text" id="txtNombreSedEdi" class="form-control" style="width: 100%;text-transform: uppercase;" 
                   onkeypress="return solo_letras(event);" value="<?php echo trim($lista_sede[0]["nombre"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtDescripcionSedEdi" class="form-control" style="width: 100%;text-transform: uppercase;" 
                   onkeypress="return solo_letras(event);" value="<?php echo trim($lista_sede[0]["descripcion"]); ?>"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Imagen: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbImagenSedeEdi" data-show-content="true" class="form-control" style="width: 100%">
                <option value="0">-- Seleccione --</option>
                <?php
                $selectedsede = "";
                $l_iconos = fnc_lista_colores();
                foreach ($l_iconos as $icono) {
                    if ($icono == $lista_sede[0]["color"]) {
                        $selectedsede = " selected ";
                    } else {
                        $selectedsede = "";
                    }
                    echo "<option value='" . $icono . "' $selectedsede data-content=" . '"' . "<i class = 'fas fa-circle nav-icon' style = 'font-size:18px;color:" . $icono . "' >&nbsp;
            &nbsp;
            " . $icono . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Estado: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbEstadoSedeEdi" class="form-control select2" style="width: 100%">
                <option value="-1">-- Seleccione --</option>
                <?php
                $selectedestado = "";
                $array_estado = array();
                array_push($array_estado, ["id" => "1", "nombre" => "Activo"]);
                array_push($array_estado, ["id" => "0", "nombre" => "Inactivo"]);
                foreach ($array_estado as $listestado) {
                    if ($listestado["id"] == $lista_sede[0]["estadoId"]) {
                        $selectedestado = " selected ";
                    } else {
                        $selectedestado = "";
                    }
                    echo "<option value='" . $listestado["id"] . "' $selectedestado>" . $listestado["nombre"] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}

function proceso_editar_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $m_codigoEdi = strip_tags(trim($_POST["m_codigoEdi"]));
    $m_nombreEdi = strip_tags(trim($_POST["m_nombreEdi"]));
    $m_descripcion = strip_tags(trim($_POST["m_descripcionEdi"]));
    $m_imagen = strip_tags(trim($_POST["m_imagenEdi"]));
    $m_estado = strip_tags(trim($_POST["m_estadoMeEdi"]));
    try {
        $editar = fnc_editar_sede($conexion, $m_codigoEdi, strtoupper($m_nombreEdi), $m_descripcion, $m_imagen, $m_estado);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($editar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_sede_auditoria($m_codigoEdi, strtoupper($m_nombreEdi), $m_descripcion, $m_imagen, $m_estado);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_sede" . '", "' . "fnc_editar_sede" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sede" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Sede editada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al editar sede.***<br/>";
    }
}

function formulario_eliminar_sede() {
    $eu_codigo = strip_tags(trim($_POST["u_elsede_codigo"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiSedeEli" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar el estado de la sede a inactivo?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiSedeIdEli = strip_tags(trim($_POST["u_codiSedeIdEli"]));
    try {
        $eliminar = fnc_eliminar_sede($conexion, $u_codiSedeIdEli);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($eliminar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_sede_auditoria($u_codiSedeIdEli);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_sede" . '", "' . "fnc_eliminar_sede" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sede" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Sede eliminada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar la sede.***<br/>";
    }
}

function formulario_modificar_matriculas() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $eu_codigo = strip_tags(trim($_POST["s_sede"]));
    $sede_data = fnc_lista_sedes($conexion, $eu_codigo, "");
    $str_cadena = "";
    if ($sede_data[0]["id"] == "1") {
        $str_cadena = ' de "TODAS" las sedes ';
    } else {
        $str_cadena = ' de la sede "' . $sede_data[0]["nombre"] . '" ';
    }
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiSedeMatri" class="form-control" value="<?php echo trim($eu_codigo); ?>"/>
            <label>&iquest;Esta seguro de cambiar los estados de las matriculas <?php echo trim($str_cadena); ?> a inactivas?</label>
        </div>
    </div>
    <?php
}

function operacion_modificar_matriculas_por_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiSedeIdEli = strip_tags(trim($_POST["u_codiSedeId"]));
    $u_anio = strip_tags(trim($_POST["sm_anio"]));
    try {
        $anio = fnc_fecha_actual($conexion);
        if ($u_anio == $anio[0]["anio"]) {
            echo "***0***No puede modificar las matrículas a inactivas de un año actual - " . $anio[0]["anio"] . ".***<br/>";
        } else {
            $eliminar = fnc_eliminar_matriculas_sede($conexion, $u_codiSedeIdEli, $u_anio);
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
            $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
            if (count($submenu) > 0) {
                $str_submenu = $submenu[0]["ruta"];
                $str_menu_id = $submenu[0]["id"];
                $str_menu_nombre = $submenu[0]["nombre"];
            } else {
                $str_submenu = "";
                $str_menu_id = "";
                $str_menu_nombre = "";
            }
            if ($eliminar) {
                if (count($submenu) > 0) {
                    $sql_auditoria = fnc_eliminar_matriculas_sede_auditoria($u_codiSedeIdEli, $u_anio);
                    $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_modificar_matriculas_por_sede" . '", "' . "fnc_eliminar_matriculas_sede" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_matricula" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                    fnc_registrar_auditoria($conexion, $sql_insert);
                }
            }
            echo "***1***Matrículas del " . $u_anio . " modificadas correctamente a estados inactivos." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        }
    } catch (Exception $exc) {
        echo "***0***Error al modificar estados de matrículas a inactivas.***<br/>";
    }
}

function formulario_descargar_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $eu_codsolicitud = explode("-", $s_solicitud);
    $solicitud_codigo = explode("/", $eu_codsolicitud[1]);
    $lista_sol = fnc_listar_todas_solicitudes_x_entrevista($conexion, $solicitud_codigo[0], "1", "1", p_perfil, p_sede);
    $html = '<div class = "row space-div">
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Entrevista / Subentrevista</label>
            </div>
            <div class = "col-md-10">';
    $html .= '<select id = "cbbTipoSolicitudCodisDes" class = "form-control select2" style = "width: 100%" onchange = "cargar_solicitudes_a_descargar(this)">
            <option value = "" > --Seleccione una opción para descargar --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value = "' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
            </div>
            <div id = "divDetalleDescargarEntrevista"></div>';
    echo $html;
}

function formulario_enviar_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $eu_codsolicitud = explode("-", $s_solicitud);
    $solicitud_codigo = explode("/", $eu_codsolicitud[1]);
    $lista_sol = fnc_listar_todas_solicitudes_x_entrevista($conexion, $solicitud_codigo[0], "1", "1", p_perfil, p_sede);
    $html = '<div class = "row space-div">
            <div class = "col-md-2" style = "margin-bottom: 0px;">
            <label>Entrevista / Subentrevista</label>
            </div>
            <div class = "col-md-10">';
    $html .= '<select id = "cbbTipoSolicitudCodisEnv" class = "form-control select2" style = "width: 100%" onchange = "cargar_solicitudes_a_enviar(this)">
            <option value = "" > --Seleccione --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value = "' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
            </div>
            <div id = "divDetalleEnviarEntrevista"></div>';
    echo $html;
}

function formulario_carga_solicitudes_enviar() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["sol_cod"]));
    $lista_correos = array();
    $array = explode("-", $s_solicitud);
    $entre_sub = "";
    $html = "";
    if ($array[0] === "ent") {
        $entre_sub = "Entrevista";
        $lista_correos = fnc_lista_correos_estudiantes_y_apoderados_entrevistas($conexion, $array[1]);
    } else {
        $entre_sub = "Subentrevista";
        $lista_correos = fnc_lista_correos_estudiantes_y_apoderados_sub_entrevistas($conexion, $array[1]);
    }
    $lista_solicitud = fnc_obtener_solicitud_x_codigo($conexion, $array[0], $array[1]);
    if (count($lista_correos) > 0) {
        $html = '1***<input type = "hidden" id = "hdnCodiSolicitudEnv" class = "form-control" value = "' . $s_solicitud . '"/>'
                . '<div class = "row space-div"><div id = "checkboxesEnv">';
        foreach ($lista_correos as $lista) {
            $html .= '<div class = "checkbox">
            <label style = "color:#007bff">
            <input type = "checkbox" value = "' . $lista["codigo"] . '**' . $lista["correo"] . '**' . $lista["persona"] . '">
            ' . $lista["dato"] . '
            </label>
            </div>';
        }
        $html .= '</div></div>';
    } else {
        $html = '0***<div class = "row space-div">
            <span><i class = "nav-icon fa fa-info-circle" style = "color: red"></i> Nota: La ' . $entre_sub . ' con c & oacute;
            digo ' . $lista_solicitud[0]["codigo"] . ' no tiene correo registrados del estudiante y/o apoderado(s).</span>';
        $html .= '</div>';
    }
    echo $html;
}

function mostrar_busqueda_entrevistas() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_fecha1 = strip_tags(trim($_POST["s_fecha1"]));
    $s_fecha2 = strip_tags(trim($_POST["s_fecha2"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;
    $sedeCodi = "";
    $usuarioCodi = "";
    $privacidad = "";
    $grados = "";
    if ($sedeCodigo == "1" && ($perfil == "1" || $perfil == "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "";
        $privacidad = "0,1";
        $grados = "";
    } else {
        $privacidad = "0";
        if ($perfil === "1" || $perfil === "5" || $perfil === "3") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
            $grados = "";
            $privacidad = "0,1";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
            $lista_grados = fnc_secciones_por_usuario($conexion, p_usuario);
            if (count($lista_grados) > 0) {
                $grados = $lista_grados[0]["seccion"];
            } else {
                $grados = "";
            }
        } elseif ($perfil === "9") {//Legal
            $sedeCodi = "0";
            $usuarioCodi = "";
            $grados = "";
            $privacidad = "1";
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
            $grados = "";
        }
    }
    $lista_solicitudes = fnc_lista_solicitudes($conexion, $sedeCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2), $usuarioCodi, $privacidad, $grados);
    $html = "";
    $num = 1;
    if (count($lista_solicitudes) > 0) {
        foreach ($lista_solicitudes as $lista) {
            $solicitudCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
            $str_motivo = "";
            if (strlen($lista["motivo"]) > 30) {
                $str_motivo = substr($lista["motivo"], 0, 30) . "...";
            } else {
                $str_motivo = $lista["motivo"];
            }
            $html .= "<tr>
                    <td>" . $num . "</td>
                    <td>" . $lista["sede"] . "</td>
                    <td>" . $lista["fecha"] . "</td>
                    <td>" . $lista["grado"] . "</td>
                    <td>" . $lista["nroDocumento"] . "</td>
                    <td style='width:250px'>" . $lista["alumno"] . "</td>
                    <td>" . $lista["entrevista"] . "</td>";
            if ($perfil == "1" || $perfil == "5" || $perfil == "9") {
                $html .= "<td>" . $lista["privacidad"] . "</td>";
            }
            $html .= "
                    <td>" . $str_motivo . "</td>
                    <td>" . $lista["estado"] . "</td>
                    <td align='center' style='width:100px'>";
            if ($perfil !== "9") {
                $html .= "<i class='nav-icon fas fa-plus green' title='Nueva Subentrevista' data-toggle='modal' data-target='#modal-subentrevista' data-backdrop='static' data-entrevista='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
            }
            $html .= "<i class='nav-icon fas fa-file-pdf rojo' title='Descargar' data-toggle='modal' data-target='#modal-descargar' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                    . "<i class='nav-icon fas fa-info-circle celeste' title='Detalle' data-toggle='modal' data-target='#modal-detalle-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "' data-grupo_nombre='" . $lista["id"] . "'></i>&nbsp;&nbsp;&nbsp;";
            if ($perfil !== "9") {
                $html .= "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
            }
            if ($perfil === "1" || $perfil === "5") {
                $html .= "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
            }
            if ($perfil !== "9") {
                $html .= "<i class='nav-icon fas fa-paper-plane azul' title='Enviar al correo' data-toggle='modal' data-target='#modal-enviar-solicitud' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
            }
            $html .= "<i class='nav-icon fas fa-upload negro' title='Cargar archivo(s)' data-toggle='modal' data-target='#modal-cargar-archivos' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
            $html .= "</td>"
                    . "</tr>";
            $num++;
        }
    } else {
        $html = "";
    }
    echo $html;
}

function operacion_buscar_no_entrevistados() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_fecha1 = strip_tags(trim($_POST["s_fecha_inicio"]));
    $s_fecha2 = strip_tags(trim($_POST["s_fecha_fin"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $s_docente = strip_tags(trim($_POST["s_docente"]));
    $sedeCodi = "";
    $usuarioCodi = "0";
    $privacidad = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "0";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
            $s_docente = $usuarioCodi;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        }
    }
    $lista_no_entrevistados = fnc_buscar_alumnos_no_entrevistados($conexion, $sedeCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2), $s_bimestre, $s_nivel, $s_grado, $s_seccion, $s_docente);
    $html = "";
    $num = 1;
    $aux = 1;
    if (count($lista_no_entrevistados) > 0) {
        foreach ($lista_no_entrevistados as $value) {
            $html .= "<tr >"
                    . "<td>$aux</td>"
                    . "<td>" . $value["tipo"] . "</td>"
                    . "<td >" . $value["fecha"] . "</td>"
                    . "<td>" . $value["sede"] . "</td>"
                    . "<td>" . $value["docente"] . "</td>"
                    . "<td>" . $value["grado"] . "</td>"
                    . "<td>" . $value["dni"] . "</td>"
                    . "<td>" . $value["alumno"] . "</td>"
                    . "</tr>";
            $aux++;
        }
    } else {
        $html = "";
    }
    echo $html;
}

function operacion_entrevistas_alumnos() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_fecha1 = strip_tags(trim($_POST["s_fecha_inicio"]));
    $s_fecha2 = strip_tags(trim($_POST["s_fecha_fin"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;

    $sedeCodi = "";
    $usuarioCodi = "";
    $privacidad = "";
    $grados = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "";
        $privacidad = "0,1";
        $grados = "";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
            $grados = "";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
            $lista_grados = fnc_secciones_por_usuario($conexion, $usuarioCodi);
            if (count($lista_grados) > 0) {
                $grados = $lista_grados[0]["seccion"];
            } else {
                $grados = "";
            }
        } elseif ($perfil === "9") {//Legal
            $sedeCodi = "0";
            $usuarioCodi = "";
            $grados = "";
            $privacidad = "1";
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
            $grados = "";
        }
    }

    $lista_entrevistas = fnc_lista_solicitudes_y_subsolicitudes($conexion, $sedeCodi, $usuarioCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2), $privacidad, $grados);
    $html = "";
    $num = 1;
    $aux = 1;
    if (count($lista_entrevistas) > 0) {
        foreach ($lista_entrevistas as $lista) {
            $solicitudCod = fnc_generate_random_string(6) . "*" . $lista["tipoId"] . "*" . fnc_generate_random_string(6);
            $html .= "<tr>
                                        <td>" . $num . "</td>
                                        <td>" . $lista["tipo"] . "</td>
                                        <td>" . $lista["sede"] . "</td>
                                        <td width='57px'>" . $lista["fecha"] . "</td>
                                        <td>" . $lista["grado"] . "</td>
                                        <td>" . $lista["nroDocumento"] . "</td>
                                        <td width='200px'>" . $lista["alumno"] . "</td>
                                        <td>" . $lista["entrevista"] . "</td>";
            if ($perfil == "1" || $perfil == "5" || $perfil == "9") {
                $html .= "<td>" . $lista["privacidad"] . "</td>";
            }
            $html .= "<td width='200px'>" . $lista["usuario"] . "</td>" .
                    "<td>" . $lista["categoria"] . "</td>" .
                    "<td>" . $lista["subcategoria"] . "</td>" .
                    "<td>" . $lista["duracion"] . "</td>" .
                    "<td>" . $lista["estado"] . "</td>" .
                    "<td><i class='nav-icon fas fa-info-circle celeste' title='Detalle' data-toggle='modal' data-target='#modal-detalle-solicitudes-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "' ></i></td>" .
                    "</tr>";
            $num++;
        }
    } else {
        $html = "";
    }
    echo $html;
}

function formulario_grafico_solicitudes_registradas() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_privacidad = strip_tags(trim($_POST["s_privacidad"]));
    $data = array();
    $lista = fnc_lista_solicitudes_grafico_linear($conexion, $s_sede, $s_privacidad, "0", "0", "0");
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["mes"] . "-" . $value["nombreMes"],
                'a' => $value["cantidad"],
                'b' => $value["cantidad_estudiantes"],
                'c' => $value["cantidad_padres"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_grafico_solicitudes_registradas_niveles() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_privacidad = strip_tags(trim($_POST["s_privacidad"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $data = array();
    $lista = fnc_lista_solicitudes_grafico_linear($conexion, $s_sede, $s_privacidad, $s_nivel, "0", "0");
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["mes"] . "-" . $value["nombreMes"],
                'a' => $value["cantidad"],
                'b' => $value["cantidad_estudiantes"],
                'c' => $value["cantidad_padres"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_grado_nivel() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $html = "";
    $lista_grados = fnc_lista_grados_x_nivel($conexion, $s_nivel);
    if (count($lista_grados) > 0) {
        $html .= '<div class="row ">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Grado: </label>
                        </div>
                        <select id="cbbGrados" data-show-content="true" class="form-control" style="width: 100%" onchange="entrevistas_registradas_grafico_lineal_grados(this)">';
        if (count($lista_grados) > 0) {
            $html .= '<option value="0">-- Seleccione --</option>';
            foreach ($lista_grados as $grado) {
                $html .= "<option value='" . $grado["codigo"] . "' >" . $grado["nombre"] . "</option>";
            }
        }
        $html .= '</select>
                    </div>
                </div><br>
                <div class="col-md-12">
                    <div id="line-chart3"></div>
                </div>';
    }
    echo $html;
}

function formulario_grafico_solicitudes_registradas_grados() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_privacidad = strip_tags(trim($_POST["s_privacidad"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $data = array();
    $lista = fnc_lista_solicitudes_grafico_linear($conexion, $s_sede, $s_privacidad, $s_nivel, $s_grado, "0");
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["mes"] . "-" . $value["nombreMes"],
                'a' => $value["cantidad"],
                'b' => $value["cantidad_estudiantes"],
                'c' => $value["cantidad_padres"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_seccion_grado() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $html = "";
    $lista_secciones = fnc_lista_secciones_grados($conexion, $s_nivel, $s_grado);
    if (count($lista_secciones) > 0) {
        $html .= '<div class="row ">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Secci&oacute;n: </label>
                        </div>
                        <select id="cbbSecciones" data-show-content="true" class="form-control" style="width: 100%" onchange="entrevistas_registradas_grafico_lineal_secciones(this)">';
        if (count($lista_secciones) > 0) {
            $html .= '<option value="0">-- Seleccione --</option>';
            foreach ($lista_secciones as $seccion) {
                $html .= "<option value='" . $seccion["codigo"] . "' >" . $seccion["nombre"] . "</option>";
            }
        }
        $html .= '</select>
                    </div>
                </div><br>
                <div class="col-md-12">
                    <div id="line-chart4"></div>
                </div>';
    }
    echo $html;
}

function formulario_grafico_solicitudes_registradas_secciones() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_privacidad = strip_tags(trim($_POST["s_privacidad"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $data = array();
    $lista = fnc_lista_solicitudes_grafico_linear($conexion, $s_sede, $s_privacidad, $s_nivel, $s_grado, $s_seccion);
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["mes"] . "-" . $value["nombreMes"],
                'a' => $value["cantidad"],
                'b' => $value["cantidad_estudiantes"],
                'c' => $value["cantidad_padres"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_docentes_grafico_barras_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $data = array();
    $lista = fnc_buscar_semaforo_docentes_grafico_barras($conexion, $s_sede);
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["nombre"],
                'a' => $value["cantidad"],
                'b' => $value["cantidad_faltantes"],
                'c' => $value["cantidad_realizados"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_no_entrevistados_grafico_barras_sede() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $data = array();
    $lista = fnc_buscar_alumnos_no_entrevistados_graficos_barras($conexion, $s_sede, "", "", "", "");
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["nombre"],
                'a' => $value["cantidad"],
                'b' => $value["no_entre"],
                'c' => $value["si_entre"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_historial_alumno() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_matricu = strip_tags(trim($_POST["sol_matricula"]));
    $arreglo = explode("*", $s_matricu);
    $s_matricula = $arreglo[0];
    $s_alumno = $arreglo[1];
    $html = "";
    $lista = fnc_obtener_codigo_alumno($conexion, $s_matricula);
    if (count($lista) > 0) {
        $alumno = $lista[0]["alu"];
        $historial = fnc_historial_todas_solicitudes_alumno($conexion, $alumno);
        $html .= '<div class="row">'
                . '<div class="col-4">'
                . '<div class="card-body table-responsive p-0" style="height: 400px;font-size:13px">
                <table class="table table-hover text-nowrap" id="tablaHistorial">
                  <thead>
                    <tr>
                      <th><input type="checkbox" name="checkAll" class="checkAll"/></th>
                      <th>A&ntilde;o</th>
                      <th>Grado y Sección</th>
                      <th>Detalle</th>
                    </tr>
                  </thead>
                  <tbody>';
        foreach ($historial as $value) {
            $html .= '<tr class="tr-cursor">
                      <td >
                      <div class="checkbox">
                        <label style="color:#007bff">
                          <input type="checkbox" class="checkboxes" value="' . $value["id"] . '">
                        </label>
                      </div></td>
                      <td onclick="mostrar_entrevis_subentrevis(' . "'" . $value["id"] . "'" . ')">' . $value["anio"] . '</td>
                          <td onclick="mostrar_entrevis_subentrevis(' . "'" . $value["id"] . "'" . ')">' . $value["grado"] . '</td>
                      <td onclick="mostrar_entrevis_subentrevis(' . "'" . $value["id"] . "'" . ')">' . $value["tipo"] . ' - ' . $value["nombre"] . '</td>
                    </tr>';
        }

        $html .= '</tbody>'
                . '</table>'
                . '</div><br/>'
                . '<div>'
                . '<fieldset class="col-md-12" id="listFieldsetCampos" style="background-color: #f6f8fb;border-radius: 6px;">
                    <legend class="legend">Seleccione los Campos para el reporte &nbsp;&nbsp;<input type="checkbox" name="checkAllCampos" class="checkAllCampos"/></legend>'
                . ' <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_1" value="3" />C&oacute;digo de Entrevista &nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_2" value="4" />Categoria&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_3" value="5" />Subcategoria&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_4" value="6" />Tipo de entrevista&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_5" value="7" />Nombre del estudiante&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_6" value="8" />Grado, secci&oacute;n y nivel&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_7" value="9" />Sexo&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_8" value="10" />Nombre del padre/madre/apoderado&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_9" value="11" />Correo del apoderado&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_10" value="12" />Teléfono del apoderado&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_11" value="13" />Entrevistador&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_12" value="14" />Sede&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_13" value="15" />Motivo de la entrevista&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_14" value="16" />Fecha y hora&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_15" value="17" />Informe al apoderado&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_16" value="18" />Planteamiento del apoderado&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_17" value="19" />Planteamiento del estudiante&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_18" value="20" />Planteamiento del entrevistador&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_19" value="21" />Acuerdos&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_20" value="22" />Acuerdos por el colegio&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_21" value="23" />Privacidad&nbsp;&nbsp;&nbsp;</label>
                    <label style="color:#007bff"><input type="checkbox" class="checkboxesCampos" id="check_22" value="25" />Duraci&oacute;n&nbsp;&nbsp;&nbsp;</label>
                    </fieldset>'
                . '</div><br/>'
                . '<div>'
                . '<fieldset class="col-md-12" id="listReportes" style="background-color: #f6f8fb;border-radius: 4px;">
                        <legend class="legend">Tipos de Reportes&nbsp;&nbsp;</legend>
                        <div class="card-body row"> 
                            <div class="col-md-6"> 
                            <button type="button" class="btn btn-danger btn-block btn-sm" onclick="descargar_historial_pdf()"><i class="fa fa-pdf"></i> Descargar PDF</button>
                            </div>
                            <div class="col-md-6">
                            <button type="button" class="btn btn-success btn-block btn-sm" onclick="descargar_historial_excel()"><i class="fa fa-excel"></i> Descargar Excel</button>
                            </div>
                        </div>
                   </fieldset>'
                . '</div>'
                . '</div>'
                . ''
                . '<div class="col-8" id="divHistorialDetalle" style="font-size:13px">'
                . '</div>'
                . '</div>';
    }
    echo $html;
}

function formulario_historial_detalle() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["sol_cod"]));
    $array = explode("-", $s_solicitud);
    $entre_sub = "";
    $html = "";
    if ($array[0] === "ent") {
        $entre_sub = "Entrevista";
    } else {
        $entre_sub = "Subentrevista";
    }
    $lista_solicitud = fnc_obtener_solicitud_x_codigo($conexion, $array[0], $array[1]);
    if (count($lista_solicitud) > 0) {
        $tipos_entrevistas = fnc_lista_tipo_entrevistas($conexion, "");
        $lista_categorias = fnc_lista_categorias($conexion, $lista_solicitud[0]["categoria"]);
        $lista_subcategorias = fnc_lista_subcategorias($conexion, $lista_solicitud[0]["categoria"], "");
        $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>C&oacute;digo de ' . $entre_sub . ': </label>
            </div>
            <div class="col-md-10">
                <label>' . $lista_solicitud[0]["codigo"] . '</label>
            </div>
        </div>
        <div class="row space-div">
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Categoria: </label>
        </div>
        <div class="col-md-4">';
        $html .= '<span>' . $lista_categorias[0]["nombre"] . '</span>';
        $html .= '</div>
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Subcategoria: </label>
        </div>
        <div class="col-md-4">';
        $html .= '';
        if (count($lista_subcategorias) > 0) {
            $selected_subcate = "";
            foreach ($lista_subcategorias as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["subcategorgia"]) {
                    $selected_subcate = $lista["nombre"];
                }
            }
            $html .= "<span>" . $selected_subcate . "</span>";
        }
        $html .= '</div>
       </div>';
        $html .= '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
                <label>Tipo de entrevista: </label>
            </div>
            <div class="col-md-3">';
        $html .= '';
        if (count($tipos_entrevistas) > 0) {
            $selected_tips = "";
            foreach ($tipos_entrevistas as $lista) {
                if ($lista["id"] === $lista_solicitud[0]["ent_id"]) {
                    $selected_tips = $lista["nombre"];
                }
            }
            $html .= "<span>" . $selected_tips . "</span>";
        }
        $html .= '</div><input type="hidden" id="txt_sede_edi" value="' . $lista_solicitud[0]["sedeId"] . '">
        </div>';
        $html .= '<div class="card card-warning" id="divSubEntrevista_edi">';
        if ($lista_solicitud[0]["ent_id"] === "1") {
            $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span id="nombre_estu_edi">' . strtoupper($lista_solicitud[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span id="grado_estu_id">' . $lista_solicitud[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . $lista_solicitud[0]["usuario"] . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span id="sede_estu_id">' . $lista_solicitud[0]["sede"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4">' . $lista_solicitud[0]["motivo"] . '
                        ';
            $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
                </div>
                <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del estudiante: </label>
                    </div>
                    <div class="col-md-9">' . $lista_solicitud[0]["plan_estudiante"] . '</div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del entrevistador(a): </label>
                    </div>
                    <div class="col-md-9">' . $lista_solicitud[0]["plan_entrevistador"] . '</div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos: </label>
                    </div>
                    <div class="col-md-9">' . $lista_solicitud[0]["acuerdos"] . '</div>
                </div>
              </div>'
                    . '<div class="row space-div">'
                    . '<div class="col-md-6" style="margin-bottom: 0px;">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_codi = "";
            $imagen1 = "";
            if (count($imagen_soli) > 0) {
                if ($imagen_soli[0]["id"] !== "") {
                    $imagen_codi = $imagen_soli[0]["id"];
                    $imagen1 = "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]);
                } else {
                    $imagen_codi = "";
                    $imagen1 = "";
                }
            } else {
                $imagen_codi = "";
                $imagen1 = "";
            }
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">
                        <input type="hidden" id="firma1" value="' . $imagen_codi . '"/>
                    <div class="description">Firma del estudiante</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img1" src="' . $imagen1 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas1_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                       <label id="divApoderadoNombreDNI_edi">' . str_replace(" - ", "<br/>", strtoupper($lista_solicitud[0]["alumno"])) . '<label/>
                   </div>'
                    . '</div>'
                    . '<div class="col-md-6" style="margin-bottom: 0px;">';
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $imagen_codi2 = "";
            $imagen2 = "";
            if (count($imagen_soli2) > 0) {
                if ($imagen_soli2[0]["id"] !== "") {
                    $imagen_codi2 = $imagen_soli2[0]["id"];
                    $imagen2 = "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]);
                } else {
                    $imagen_codi2 = "";
                    $imagen2 = "";
                }
            } else {
                $imagen_codi2 = "";
                $imagen2 = "";
            }
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >
                        <input type="hidden" id="firma2" value="' . $imagen_codi2 . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img2" src="' . $imagen2 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas2_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
                   <div style="margin-left: 20px;">
                       <label>' . strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"] . '<label/>
                   </div>'
                    . ' </div>'
                    . '</div>';
        } elseif ($lista_solicitud[0]["ent_id"] === "2") {
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], "");
            $apoderado = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], $lista_solicitud[0]["apoderado"]);
            $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A PADRES DE FAMILIA</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div">
                    <input type="hidden" id="txtAlumCodig_edi" value="' . $lista_solicitud[0]["aluId"] . '"/>
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del estudiante: </label>
                    </div>
                    <div class="col-md-4"><span id="nombre_estu_edi">' . strtoupper($lista_solicitud[0]["alumno"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Grado, sección y nivel: </label>
                    </div>
                    <div class="col-md-3"><span id="grado_estu_id">' . $lista_solicitud[0]["grado"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Nombre del padre/madre/apoderado: </label>
                    </div>
                    <div class="col-md-4">';
            $html .= '';
            if (count($lista_apoderados) > 0) {
                $selected_apoderado = "";
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $lista_solicitud[0]["apoderado"]) {
                        $selected_apoderado = $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]);
                    }
                }
            }
            $html .= ' <span>' . $selected_apoderado . '</span>';
            $html .= '</div><div class="col-md-3" id="divEditarInfoApoderado_edi">
                </div></div>
                <div class="row space-div" id="detalleApoderado_edi">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Correo: </label>
                    </div>
                    <div class="col-md-4">' . $apoderado[0]["correo"] . '</div>
                    <div class="col-md-2">
                        <label>Teléfono: </label>
                    </div>
                    <div class="col-md-3">' . $apoderado[0]["telefono"] . '</div>
                </div>';
            $html .= '
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Entrevistador: </label>
                    </div>
                    <div class="col-md-4"><span>' . strtoupper($lista_solicitud[0]["usuario"]) . '</span></div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label>
                    </div>
                    <div class="col-md-3"><span id="sede_estu_id">' . $lista_solicitud[0]["sede"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Motivo de la entrevista: </label>
                    </div>
                    <div class="col-md-4"><span>' . $lista_solicitud[0]["motivo"] . '</span>';
            $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
                </div>
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div class="row space-div">
                    <div class="col-md-12"><span>' . $lista_solicitud[0]["informe"] . '</span></div>
                </div>

                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del padre, madre o apoderado: </label>
                    </div>
                    <div class="col-md-9"><span>' . $lista_solicitud[0]["plan_padre"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
                    </div>
                    <div class="col-md-9"><span>' . $lista_solicitud[0]["plan_docente"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por los padres: </label>
                    </div>
                    <div class="col-md-9"><span>' . $lista_solicitud[0]["acuerdos1"] . '</span></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por el colegio: </label>
                    </div>
                    <div class="col-md-9"><span>' . $lista_solicitud[0]["acuerdos2"] . '</span></div>
                </div>
              </div>'
                    . '<div class="row space-div">'
                    . '<div class="col-md-6" style="margin-bottom: 0px;">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_codi = "";
            $imagen1 = "";
            if (count($imagen_soli) > 0) {
                if ($imagen_soli[0]["id"] !== "") {
                    $imagen_codi = $imagen_soli[0]["id"];
                    $imagen1 = "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]);
                } else {
                    $imagen_codi = "";
                    $imagen1 = "";
                }
            } else {
                $imagen_codi = "";
                $imagen1 = "";
            }
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">
                        <input type="hidden" id="firma1" value="' . $imagen_codi . '"/>
                    <div class="description">Firma del padre, madre o apoderado</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img1" src="' . $imagen1 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <br/>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI_edi">
                       <label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '<label/>
                   </div>'
                    . '</div>'
                    . '<div class="col-md-6" style="margin-bottom: 0px;">';
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $imagen_codi2 = "";
            $imagen2 = "";
            if (count($imagen_soli2) > 0) {
                if ($imagen_soli2[0]["id"] !== "") {
                    $imagen_codi2 = $imagen_soli2[0]["id"];
                    $imagen2 = "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]);
                } else {
                    $imagen_codi2 = "";
                    $imagen2 = "";
                }
            } else {
                $imagen_codi2 = "";
                $imagen2 = "";
            }
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >
                        <input type="hidden" id="firma2" value="' . $imagen_codi2 . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img2" src="' . $imagen2 . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <br/>
                    </div>
                    <div>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"] . '<label/>
                   </div>'
                    . ' </div>'
                    . '</div>';
        }
        $html .= '</div>';
    }
    echo $html;
}

function formulario_registro_nuevos_bimestres() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $lista_anios = fnc_lista_anios_bimestres($conexion);
    ?>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <label>A&ntilde;o: </label>
        </div>
        <div class="col-md-6">
            <select id="cbbAnios" data-show-content="true" class="form-control" style="width: 100%" onchange="cambiar_anio_bimestre(this)">
                <?php
                if (count($lista_anios) > 0) {
                    foreach ($lista_anios as $anio) {
                        echo "<option value='" . $anio["fecha"] . "' >" . $anio["fecha"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Bimestres: </label>
            </div>
            <label>PRIMER BIMESTRE</label>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Inicio: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha11" readonly/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Fin: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha12" readonly/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> </label>
            </div>
            <label>SEGUNDO BIMESTRE</label>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Inicio: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha21" readonly/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Fin: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha22" readonly/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label>  </label>
            </div>
            <label>TERCER BIMESTRE</label>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Inicio: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha31" readonly/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Fin: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha32" readonly/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label></label>
            </div>
            <label>CUARTO BIMESTRE</label>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Inicio: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha41" readonly/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Fecha Fin: </label>
            </div>
            <input type="text" class="form-control pull-right" id="fecha42" readonly/>
        </div>
    </div>
    <?php
}

function proceso_registrar_bimestres() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
    $s_anio = strip_tags(trim($_POST["s_anio"]));
    $s_fecha11 = strip_tags(trim($_POST["s_fecha11"]));
    $s_fecha12 = strip_tags(trim($_POST["s_fecha12"]));
    $s_fecha21 = strip_tags(trim($_POST["s_fecha21"]));
    $s_fecha22 = strip_tags(trim($_POST["s_fecha22"]));
    $s_fecha31 = strip_tags(trim($_POST["s_fecha31"]));
    $s_fecha32 = strip_tags(trim($_POST["s_fecha32"]));
    $s_fecha41 = strip_tags(trim($_POST["s_fecha41"]));
    $s_fecha42 = strip_tags(trim($_POST["s_fecha42"]));
    $m_codigo = substr($s_anio, 0, 4) . "_" . fnc_generate_random_string(6);
    $anio_bimestre_id = fnc_registrar_anio_bimestres($conexion, $m_codigo, $s_anio, "1");
    if ($anio_bimestre_id) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigo);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        if (count($submenu) > 0) {
            $sql_auditoria = fnc_registrar_anio_bimestres_auditoria($m_codigo, $s_anio, "1");
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_bimestres" . '", "' . "fnc_registrar_anio_bimestres" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_anio_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
        }
        $registrar_bimestre_1 = fnc_registrar_bimestre($conexion, $anio_bimestre_id, "PrimerBi" . "_" . fnc_generate_random_string(6), "PRIMER BIMESTRE", convertirFecha($s_fecha11), convertirFecha($s_fecha12), "1", "1");
        if ($registrar_bimestre_1) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_bimestre_auditoria($anio_bimestre_id, "PrimerBi" . "_" . fnc_generate_random_string(6), "PRIMER BIMESTRE", convertirFecha($s_fecha11), convertirFecha($s_fecha12), "1", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_bimestres" . '", "' . "fnc_registrar_bimestre" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $registrar_bimestre_2 = fnc_registrar_bimestre($conexion, $anio_bimestre_id, "SegundoBi" . "_" . fnc_generate_random_string(6), "SEGUNDO BIMESTRE", convertirFecha($s_fecha21), convertirFecha($s_fecha22), "2", "1");
        if ($registrar_bimestre_2) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_bimestre_auditoria($anio_bimestre_id, "SegundoBi" . "_" . fnc_generate_random_string(6), "SEGUNDO BIMESTRE", convertirFecha($s_fecha21), convertirFecha($s_fecha22), "2", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_bimestres" . '", "' . "fnc_registrar_bimestre" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $registrar_bimestre_3 = fnc_registrar_bimestre($conexion, $anio_bimestre_id, "TercerBi" . "_" . fnc_generate_random_string(6), "TERCER BIMESTRE", convertirFecha($s_fecha31), convertirFecha($s_fecha32), "3", "1");
        if ($registrar_bimestre_3) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_bimestre_auditoria($anio_bimestre_id, "TercerBi" . "_" . fnc_generate_random_string(6), "TERCER BIMESTRE", convertirFecha($s_fecha31), convertirFecha($s_fecha32), "3", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_bimestres" . '", "' . "fnc_registrar_bimestre" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $registrar_bimestre_4 = fnc_registrar_bimestre($conexion, $anio_bimestre_id, "CuartoBi" . "_" . fnc_generate_random_string(6), "CUARTO BIMESTRE", convertirFecha($s_fecha41), convertirFecha($s_fecha42), "4", "1");
        if ($registrar_bimestre_4) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_bimestre_auditoria($anio_bimestre_id, "CuartoBi" . "_" . fnc_generate_random_string(6), "CUARTO BIMESTRE", convertirFecha($s_fecha41), convertirFecha($s_fecha42), "4", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_bimestres" . '", "' . "fnc_registrar_bimestre" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Bimestres del " . $s_anio . " registrados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al registrar los Bimestres del " . $s_anio . ".***<br/>";
    }
}

function formulario_editar_bimestres() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_anio_codigo = strip_tags(trim($_POST["u_anio_codigo"]));
    $eu_codanio = explode("-", $u_anio_codigo);
    $anio_codi = explode("/", $eu_codanio[1]);
    $anios = fnc_lista_anios_bimestres_edi($conexion);
    $lista_anios = fnc_lista_anio_bimestres_x_anio($conexion, $anio_codi[0], "1");
    if (count($lista_anios) > 0) {
        ?>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <label>A&ntilde;o: </label>
            </div>
            <div class="col-md-6">
                <input type="hidden" id="codi_edi" value="<?php echo $anio_codi[0]; ?>"/>
                <select id="cbbAnios_edi" data-show-content="true" class="form-control" style="width: 100%" disabled="">
                    <?php
                    if (count($anios) > 0) {
                        $selected = "";
                        foreach ($anios as $anio) {
                            if ($anio["fecha"] == $lista_anios[0]["nombre"]) {
                                $selected = " selected ";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $anio["fecha"] . "' $selected>" . $anio["fecha"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Bimestres: </label>
                </div>
                <label>PRIMER BIMESTRE</label>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Inicio: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha11_edi" 
                       value="<?php echo $lista_anios[0]["fecha_ini"] ?>" readonly/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Fin: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha12_edi"
                       value="<?php echo $lista_anios[0]["fecha_fin"] ?>" readonly/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> </label>
                </div>
                <label>SEGUNDO BIMESTRE</label>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Inicio: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha21_edi"
                       value="<?php echo $lista_anios[1]["fecha_ini"] ?>" readonly/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Fin: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha22_edi" 
                       value="<?php echo $lista_anios[1]["fecha_fin"] ?>" readonly/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label>  </label>
                </div>
                <label>TERCER BIMESTRE</label>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Inicio: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha31_edi" 
                       value="<?php echo $lista_anios[2]["fecha_ini"] ?>" readonly/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Fin: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha32_edi" 
                       value="<?php echo $lista_anios[2]["fecha_fin"] ?>" readonly/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label></label>
                </div>
                <label>CUARTO BIMESTRE</label>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Inicio: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha41_edi" 
                       value="<?php echo $lista_anios[3]["fecha_ini"] ?>" readonly/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Fecha Fin: </label>
                </div>
                <input type="text" class="form-control pull-right" id="fecha42_edi"
                       value="<?php echo $lista_anios[3]["fecha_fin"] ?>" readonly/>
            </div>
        </div>
        <?php
    }
}

function proceso_editar_bimestres() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
    $s_codi_edi = strip_tags(trim($_POST["s_codi_edi"]));
    $s_anio = strip_tags(trim($_POST["s_anio"]));
    $s_fecha11 = strip_tags(trim($_POST["s_fecha11"]));
    $s_fecha12 = strip_tags(trim($_POST["s_fecha12"]));
    $s_fecha21 = strip_tags(trim($_POST["s_fecha21"]));
    $s_fecha22 = strip_tags(trim($_POST["s_fecha22"]));
    $s_fecha31 = strip_tags(trim($_POST["s_fecha31"]));
    $s_fecha32 = strip_tags(trim($_POST["s_fecha32"]));
    $s_fecha41 = strip_tags(trim($_POST["s_fecha41"]));
    $s_fecha42 = strip_tags(trim($_POST["s_fecha42"]));
    $m_codigo = substr($s_anio, 0, 4) . "_" . fnc_generate_random_string(6);
    if ($s_codi_edi) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigo);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $editar_anios = fnc_editar_anio_bimestres($conexion, $s_codi_edi, $m_codigo, $s_anio, "1");
        if ($editar_anios) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_anio_bimestres_auditoria($s_codi_edi, $m_codigo, $s_anio, "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_bimestres" . '", "' . "fnc_editar_anio_bimestres" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_anio_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $editar_bimestre_1 = fnc_editar_bimestres_x_anio($conexion, $s_codi_edi, "1", convertirFecha($s_fecha11), convertirFecha($s_fecha12), "1");
        if ($editar_bimestre_1) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_bimestres_x_anio_auditoria($s_codi_edi, "1", convertirFecha($s_fecha11), convertirFecha($s_fecha12), "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_bimestres" . '", "' . "fnc_editar_bimestres_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $editar_bimestre_2 = fnc_editar_bimestres_x_anio($conexion, $s_codi_edi, "2", convertirFecha($s_fecha21), convertirFecha($s_fecha22), "1");
        if ($editar_bimestre_2) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_bimestres_x_anio_auditoria($s_codi_edi, "2", convertirFecha($s_fecha21), convertirFecha($s_fecha22), "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_bimestres" . '", "' . "fnc_editar_bimestres_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $editar_bimestre_3 = fnc_editar_bimestres_x_anio($conexion, $s_codi_edi, "3", convertirFecha($s_fecha31), convertirFecha($s_fecha32), "1");
        if ($editar_bimestre_3) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_bimestres_x_anio_auditoria($s_codi_edi, "3", convertirFecha($s_fecha31), convertirFecha($s_fecha32), "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_bimestres" . '", "' . "fnc_editar_bimestres_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $editar_bimestre_4 = fnc_editar_bimestres_x_anio($conexion, $s_codi_edi, "4", convertirFecha($s_fecha41), convertirFecha($s_fecha42), "1");
        if ($editar_bimestre_4) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_bimestres_x_anio_auditoria($s_codi_edi, "4", convertirFecha($s_fecha41), convertirFecha($s_fecha42), "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_bimestres" . '", "' . "fnc_editar_bimestres_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_bimestre" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }

        echo "***1***Bimestres del " . $s_anio . " editados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al editar los Bimestres del " . $s_anio . ".***<br/>";
    }
}

function formulario_registro_nuevo_semaforo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    //$lista_anios = fnc_lista_anios_semaforos($conexion);//
    $lista_bimestres = fnc_lista_semaforo_bimestre($conexion, "", "");
    ?>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <label>Bimestre: </label>
        </div>
        <div class="col-md-8">
            <select id="cbbBimestres" data-show-content="true" class="form-control" style="width: 100%" onchange="cambiar_anio_bimestre(this)">
                <?php
                if (count($lista_bimestres) > 0) {
                    foreach ($lista_bimestres as $aniobimestre) {
                        echo "<option value='" . $aniobimestre["id"] . "' >" . $aniobimestre["anio"] . " - " . $aniobimestre["nombre"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Semaforo: </label>
            </div>
            <label>ROJO</label>
            <i class="fas fa-circle nav-icon" style="font-size:23px;color: red"></i>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> De: (%) </label>
            </div>
            <input type="text" class="form-control pull-right" id="valor11" readonly value="0.00"/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Hasta: (%) </label>
            </div>
            <input type="text" class="form-control pull-right" id="valor12" maxlength="5" min="0.01" step="0.01" onkeyup="this.value = minmax(this.value, 0, 100)"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> </label>
            </div>
            <label>AMBAR</label>
            <i class="fas fa-circle nav-icon" style="font-size:23px;color:#ff7e00"></i>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> De: (%)</label>
            </div>
            <input type="text" class="form-control pull-right" id="valor21" readonly/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Hasta: (%)</label>
            </div>
            <input type="text" class="form-control pull-right" id="valor22" maxlength="5" min="0.01" step="0.01" onkeyup="this.value = minmax(this.value, 0, 100)"/>
        </div>
    </div>
    <div class="row space-div">
        <div class="col-md-4" style="margin-bottom: 0px;">
            <div class="form-group" style="margin-bottom: 0px;">
                <label>  </label>
            </div>
            <label>VERDE</label>
            <i class="fas fa-circle nav-icon" style="font-size:23px;color:green;"></i>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> De: (%)</label>
            </div>
            <input type="text" class="form-control pull-right" id="valor31" readonly/>
        </div>
        <div class="col-md-4">
            <div class="form-group" style="margin-bottom: 0px;">
                <label> Hasta: (%)</label>
            </div>
            <input type="text" class="form-control pull-right" id="valor32" readonly value="100.00"/>
        </div>
    </div>
    <?php
}

function proceso_registrar_semaforo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $s_valor11 = strip_tags(trim($_POST["s_valor11"]));
    $s_valor12 = strip_tags(trim($_POST["s_valor12"]));
    $s_valor21 = strip_tags(trim($_POST["s_valor21"]));
    $s_valor22 = strip_tags(trim($_POST["s_valor22"]));
    $s_valor31 = strip_tags(trim($_POST["s_valor31"]));
    $s_valor32 = strip_tags(trim($_POST["s_valor32"]));
    $m_codigo = $s_bimestre . "_" . fnc_generate_random_string(8);
    $data_bimestre = fnc_lista_bimestre($conexion, $s_bimestre, "");
    if (count($data_bimestre) > 0) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigo);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $registro_semaforo1 = fnc_registrar_semaforo($conexion, $s_bimestre, $m_codigo, $data_bimestre[0]["anio"], $s_valor11, $s_valor12, "Rojo", "1", "1");
        if ($registro_semaforo1) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_semaforo_auditoria($s_bimestre, $m_codigo, $data_bimestre[0]["anio"], $s_valor11, $s_valor12, "Rojo", "1", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_semaforo" . '", "' . "fnc_registrar_semaforo" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_semaforo" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $registro_semaforo2 = fnc_registrar_semaforo($conexion, $s_bimestre, $m_codigo, $data_bimestre[0]["anio"], $s_valor21, $s_valor22, "Ambar", "2", "1");
        if ($registro_semaforo2) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_semaforo_auditoria($s_bimestre, $m_codigo, $data_bimestre[0]["anio"], $s_valor21, $s_valor22, "Ambar", "2", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_semaforo" . '", "' . "fnc_registrar_semaforo" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_semaforo" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $registro_semaforo3 = fnc_registrar_semaforo($conexion, $s_bimestre, $m_codigo, $data_bimestre[0]["anio"], $s_valor31, $s_valor32, "Verde", "3", "1");
        if ($registro_semaforo3) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_semaforo_auditoria($s_bimestre, $m_codigo, $data_bimestre[0]["anio"], $s_valor31, $s_valor32, "Verde", "3", "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_registrar_semaforo" . '", "' . "fnc_registrar_semaforo" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_semaforo" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Semaforo del " . $data_bimestre[0]["nombre"] . " registrado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al registrar el Semaforo del " . $data_bimestre[0]["nombre"] . ".***<br/>";
    }
}

function formulario_editar_semaforo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_anio_codigo = strip_tags(trim($_POST["u_anio_codigo"]));
    $eu_codanio = explode("-", $u_anio_codigo);
    $bimestre_codi = explode("/", $eu_codanio[1]);
    $lista_anios = fnc_lista_anio_semaforo_x_anio($conexion, $bimestre_codi[0], "1");
    $lista_bimestres = fnc_lista_semaforo_bimestre2($conexion, $bimestre_codi[0], "");
    if (count($lista_anios) > 0) {
        ?>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <label>Bimestre: </label>
            </div>
            <div class="col-md-8">
                <input type="hidden" id="codi_edi" value="<?php echo $bimestre_codi[0]; ?>"/>
                <select id="cbbBimestre_edi" data-show-content="true" class="form-control" style="width: 100%" disabled="">
                    <?php
                    if (count($lista_bimestres) > 0) {
                        $selected = "";

                        foreach ($lista_bimestres as $aniobimestre) {
                            if ($bimestre_codi[0] == $aniobimestre["id"]) {
                                $selected = " selected ";
                            } else {
                                $selected = "";
                            }
                            echo "<option value='" . $aniobimestre["id"] . "' >" . $aniobimestre["anio"] . " - " . $aniobimestre["nombre"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Semaforo: </label>
                </div>
                <label>ROJO</label>
                <i class="fas fa-circle nav-icon" style="font-size:23px;color: red"></i>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> De: (%) </label>
                </div>
                <input type="text" class="form-control pull-right" id="valor11_edi" readonly 
                       value="<?php echo $lista_anios[0]["valor_ini"] ?>"/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Hasta: (%) </label>
                </div>
                <input type="text" class="form-control pull-right" id="valor12_edi" maxlength="5" min="0.01" step="0.01" onkeyup="this.value = minmax(this.value, 0, 100)"
                       value="<?php echo $lista_anios[0]["valor_fin"] ?>"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> </label>
                </div>
                <label>AMBAR</label>
                <i class="fas fa-circle nav-icon" style="font-size:23px;color:#ff7e00"></i>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> De: (%)</label>
                </div>
                <input type="text" class="form-control pull-right" id="valor21_edi" readonly
                       value="<?php echo $lista_anios[1]["valor_ini"] ?>"/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Hasta: (%)</label>
                </div>
                <input type="text" class="form-control pull-right" id="valor22_edi" maxlength="5" min="0.01" step="0.01" onkeyup="this.value = minmax(this.value, 0, 100)"
                       value="<?php echo $lista_anios[1]["valor_fin"] ?>"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label>  </label>
                </div>
                <label>VERDE</label>
                <i class="fas fa-circle nav-icon" style="font-size:23px;color:green;"></i>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> De: (%)</label>
                </div>
                <input type="text" class="form-control pull-right" id="valor31_edi" readonly
                       value="<?php echo $lista_anios[2]["valor_ini"] ?>"/>
            </div>
            <div class="col-md-4">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Hasta: (%)</label>
                </div>
                <input type="text" class="form-control pull-right" id="valor32_edi" readonly value="100.00"
                       value="<?php echo $lista_anios[2]["valor_fin"] ?>"/>
            </div>
        </div>
        <?php
    }
}

function proceso_editar_semaforo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
    $s_codi_edi = strip_tags(trim($_POST["s_codi_edi"]));
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $s_valor11 = strip_tags(trim($_POST["s_valor11"]));
    $s_valor12 = strip_tags(trim($_POST["s_valor12"]));
    $s_valor21 = strip_tags(trim($_POST["s_valor21"]));
    $s_valor22 = strip_tags(trim($_POST["s_valor22"]));
    $s_valor31 = strip_tags(trim($_POST["s_valor31"]));
    $s_valor32 = strip_tags(trim($_POST["s_valor32"]));
    $m_codigo = $s_bimestre . "_" . fnc_generate_random_string(6);
    $data_bimestre = fnc_lista_bimestre($conexion, $s_bimestre, "");
    if ($s_codi_edi) {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigo);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $editar_semaforo_1 = fnc_editar_semaforo_x_anio($conexion, $s_codi_edi, "1", $s_valor11, $s_valor12, "1");
        if ($editar_semaforo_1) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_semaforo_x_anio_auditoria($s_codi_edi, "1", $s_valor11, $s_valor12, "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_semaforo" . '", "' . "fnc_editar_semaforo_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_semaforo" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $editar_semaforo_2 = fnc_editar_semaforo_x_anio($conexion, $s_codi_edi, "2", $s_valor21, $s_valor22, "1");
        if ($editar_semaforo_2) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_semaforo_x_anio_auditoria($s_codi_edi, "2", $s_valor21, $s_valor22, "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_semaforo" . '", "' . "fnc_editar_semaforo_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_semaforo" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        $editar_semaforo_3 = fnc_editar_semaforo_x_anio($conexion, $s_codi_edi, "3", $s_valor31, $s_valor32, "1");
        if ($editar_semaforo_3) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_semaforo_x_anio_auditoria($s_codi_edi, "3", $s_valor31, $s_valor32, "1");
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_semaforo" . '", "' . "fnc_editar_semaforo_x_anio" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_semaforo" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Semaforo del " . $data_bimestre[0]["nombre"] . " editados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al editar el Semaforo del " . $data_bimestre[0]["nombre"] . ".***<br/>";
    }
}

function fnc_lista_campos_para_reporte() {
    $lista = ['Código de Entrevista', 'Categoria', 'Subcategoria', 'Tipo de entrevista', 'Nombre del estudiante', 'Grado, sección y nivel', 'Sexo', 'Nombre del padre/madre/apoderado', 'Correo del apoderado', 'Teléfono del apoderado', 'Entrevistador', 'Sede', 'Motivo de la entrevista', 'Fecha y hora',
        'Informe al apoderado', 'Planteamiento del apoderado', 'Planteamiento del estudiante', 'Planteamiento del entrevistador', 'Acuerdos', 'Acuerdos por el colegio'];
    return $lista;
}

function formulario_selector_grado() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $html = "";
    $lista_grados = fnc_lista_grados_x_nivel($conexion, $s_nivel);
    if (count($lista_grados) > 0) {
        $html .= '<option value="0">-- Todos --</option>';
        foreach ($lista_grados as $grado) {
            $html .= "<option value='" . $grado["codigo"] . "' >" . $grado["nombre"] . "</option>";
        }
    }
    echo $html;
}

function formulario_selector_seccion() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $html = "";
    $lista_secciones = fnc_lista_secciones_grados($conexion, $s_nivel, $s_grado);
    if (count($lista_secciones) > 0) {
        $html .= '<option value="0">-- Todos --</option>';
        foreach ($lista_secciones as $seccion) {
            $html .= "<option value='" . $seccion["codigo"] . "' >" . $seccion["nombre"] . "</option>";
        }
    }
    echo $html;
}

function formulario_alumnos_no_entrevistas_grafico_barras_niveles() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $data = array();
    $lista = fnc_buscar_alumnos_no_entrevistados_graficos_barras($conexion, $s_sede, "", $s_nivel, "", "");
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["nombre"],
                'a' => $value["cantidad"],
                'b' => $value["no_entre"],
                'c' => $value["si_entre"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_nivel_grado_no_entrevistados() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $html = "";
    $lista_grados = fnc_lista_grados_x_nivel($conexion, $s_nivel);
    if (count($lista_grados) > 0) {
        $html .= '<div class="row ">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Grado: </label>
                        </div>
                        <select id="cbbGrado" data-show-content="true" class="form-control" style="width: 100%" onchange="alumnos_no_entrevistados_grafico_barras_grados(this)">';
        if (count($lista_grados) > 0) {
            $html .= '<option value="">-- Seleccione --</option>';
            foreach ($lista_grados as $grado) {
                $html .= "<option value='" . $grado["codigo"] . "' >" . $grado["nombre"] . "</option>";
            }
        }
        $html .= '</select>
                    </div>
                </div><br>
                <div class="col-md-12">
                    <div id="bar-chart3"></div>
                </div>';
    }
    echo $html;
}

function formulario_alumnos_no_entrevistas_grafico_barras_grados() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $data = array();
    $lista = fnc_buscar_alumnos_no_entrevistados_graficos_barras($conexion, $s_sede, "", $s_nivel, $s_grado, "");
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["nombre"],
                'a' => $value["cantidad"],
                'b' => $value["no_entre"],
                'c' => $value["si_entre"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function formulario_grado_seccion_no_entrevistados() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $html = "";
    $lista_secciones = fnc_lista_secciones_grados($conexion, $s_nivel, $s_grado);
    if (count($lista_secciones) > 0) {
        $html .= '<div class="row ">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Secci&oacute;n: </label>
                        </div>
                        <select id="cbbSeccion" data-show-content="true" class="form-control" style="width: 100%" onchange="alumnos_no_entrevistados_grafico_barras_secciones(this)">';
        if (count($lista_secciones) > 0) {
            $html .= '<option value="">-- Seleccione --</option>';
            foreach ($lista_secciones as $seccion) {
                $html .= "<option value='" . $seccion["codigo"] . "' >" . $seccion["nombre"] . "</option>";
            }
        }
        $html .= '</select>
                    </div>
                </div><br>
                <div class="col-md-12">
                    <div id="bar-chart4"></div>
                </div>';
    }
    echo $html;
}

function formulario_alumnos_no_entrevistas_grafico_barras_secciones() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $data = array();
    $lista = fnc_buscar_alumnos_no_entrevistados_graficos_barras($conexion, $s_sede, "", $s_nivel, $s_grado, $s_seccion);
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $data[] = array
                (
                'y' => $value["nombre"],
                'a' => $value["cantidad"],
                'b' => $value["no_entre"],
                'c' => $value["si_entre"]
            );
        }
    } else {
        $data = array();
    }

    //returns data as JSON format
    echo json_encode($data);
}

function operacion_historial_auditoria() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_fecha1 = strip_tags(trim($_POST["s_fecha_inicio"]));
    $s_fecha2 = strip_tags(trim($_POST["s_fecha_fin"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;

    $sedeCodi = "";
    $usuarioCodi = "";
    $privacidad = "";
    $grados = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "0";
        $privacidad = "0,1";
        $grados = "";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
            $grados = "";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
            $lista_grados = fnc_secciones_por_usuario($conexion, $usuarioCodi);
            if (count($lista_grados) > 0) {
                $grados = $lista_grados[0]["seccion"];
            } else {
                $grados = "";
            }
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
            $grados = "";
        }
    }

    $lista_auditoria = fnc_lista_auditorias($conexion, $sedeCodi, $usuarioCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2));
    $html = "";
    $num = 1;
    $aux = 1;
    if (count($lista_auditoria) > 0) {
        foreach ($lista_auditoria as $lista) {
            $html .= "<tr>
                    <td>" . $num . "</td>
                    <td>" . $lista["fecha"] . "</td>
                    <td>" . $lista["sede"] . "</td>
                    <td >" . $lista["perfil"] . "</td>
                    <td>" . $lista["usuario"] . "</td>
                    <td>" . $lista["menu"] . "</td>
                    <td>" . $lista["funcion"] . "</td>
                    <td>" . $lista["consulta"] . "</td>";
            $html .= "<td>" . $lista["accion"] . "</td>" .
                    "</tr>";
            $num++;
        }
    } else {
        $html = "";
    }
    echo $html;
}

function mostrar_tabla_mis_aulas() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_usuario = strip_tags(trim($_POST["codigo"]));
    $lista = fnc_mis_aulas_asignadas($conexion, $s_usuario);
    $aux = 1;
    $html = "<div class='card card-primary'>
            <div class='card-header'>
                <h3 class='card-title'>Mis aulas asignadas</h3>
            </div>
            <div class='card-body'>
              <div class='row'>
                <div class='col-md-12 table-responsive' id='divTablaMisAulas'>"
            . "<table id='tableMisAulas' class='table table-bordered' style='font-size: 13px;width:100% '>"
            . "<thead>"
            . "<th>Nro.</th>"
            . "<th>Sede</th>"
            . "<th>Nivel</th>"
            . "<th>Grado</th>"
            . "<th>Secci&oacute;n</th>"
            . "</thead><tbody>";
    if (count($lista) > 0) {
        foreach ($lista as $value) {
            $html .= "<tr>"
                    . "<td>$aux</td>"
                    . "<td>" . $value["sede"] . "</td>"
                    . "<td>" . $value["nivel"] . "</td>"
                    . "<td>" . $value["grado"] . "</td>"
                    . "<td>" . $value["seccion"] . "</td>"
                    . "</tr>";
            $aux++;
        }
    } else {
        $html .= "<tr><td colspan='5'>No hay datos disponibles en la tabla</td></tr>";
    }
    $html .= "</div>
            </div>
        </div>";
    echo $html;
}

//marita
function operacion_cantidad_entrevistas_subentrevitas() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;

    $sedeCodi = "";
    $usuarioCodi = "";
    $privacidad = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "0";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        }
    }

    $lista_cantidad_entrevistas = fnc_lista_cantidad_entrevistas($conexion, $sedeCodi, $s_bimestre, $s_nivel, $s_grado, $s_seccion);
    $html = "";
    $num = 1;
    $aux = 1;
    if (count($lista_cantidad_entrevistas) > 0) {
        foreach ($lista_cantidad_entrevistas as $lista) {
            $html .= "<tr>
                <td>" . $num . "</td>
                <td>" . $lista["sede"] . "</td>
                <td>" . $lista["categoria"] . "</td>
                <td >" . $lista["subcategoria"] . "</td>
                <td style='text-align:center'>" . $lista["cantidad_entrevista"] . "</td>
                <td style='text-align:center'>" . $lista["cantidad_subentrevista"] . "</td>
                <td style='text-align:center'>" . $lista["total"] . "</td>
                <td style='text-align:center'>" . $lista["porcentaje"] . "</td>"
                    . "</tr>";
            $num++;
        }
    } else {
        $html = "";
    }
    echo $html;
}

function formulario_rango_fechas() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $lista = fnc_lista_rango_fechas_bimestre($conexion, $s_bimestre);
    $f_ini = "";
    $f_fin = "";
    if (count($lista) > 0) {
        $f_ini = $lista[0]["inicio"];
        $f_fin = $lista[0]["fin"];
    } else {
        $f_ini = "";
        $f_fin = "";
    }
    echo $f_ini . "*" . $f_fin;
}

function operacion_reporte_semanal() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $s_fecha_ini = strip_tags(trim($_POST["s_fecha_ini"]));
    $s_fecha_fin = strip_tags(trim($_POST["s_fecha_fin"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $perfil = p_perfil;
    $sedeCodigo = $s_sede;

    $sedeCodi = "";
    $usuarioCodi = "";
    $privacidad = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "0";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "0";
        }
    }
    $fecha_ini = fnc_fecha_a_YY_MM_DD($s_fecha_ini);
    $fecha_fin = fnc_fecha_a_YY_MM_DD($s_fecha_fin);
    $lista_reporte_semanal = fnc_lista_reporte_semanal($conexion, $sedeCodi, $s_bimestre, $fecha_ini, $fecha_fin, $s_nivel, $s_grado, $s_seccion);
    $html = "";
    $num = 1;
    if (count($lista_reporte_semanal) > 0) {
        foreach ($lista_reporte_semanal as $lista) {
            $html .= "<tr>
                <td>" . $num . "</td>
                <td>" . $lista["sede"] . "</td>
                <td>" . $lista["nivel"] . "</td>
                <td >" . $lista["grado"] . "</td>
                <td >" . $lista["seccion"] . "</td>
                <td style='text-align:center'>" . $lista["cantidad_entrevistas"] . "</td>
                <td style='text-align:center'>" . $lista["cantidad_subentrevistas"] . "</td>
                <td style='text-align:center'>" . $lista["total"] . "</td>"
                    . "</tr>";
            $num++;
        }
    } else {
        $html = "";
    }
    echo $html;
}

function formulario_cargar_archivos() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $perfil = p_perfil;
    $html = '<div class="row">
        <div class="col-lg-5">
            <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label> Subir archivo: </label>
                </div>
                <input type="hidden" id="hdmSolicitud" value="' . $s_solicitud . '" />
                <div class="col-md-12 col-sm-12 col-12">
                    <form method="post" id="upload_form" enctype="multipart/form-data" >
                        <input type="file" name="select_file" id="select_file" style="" />
                        <input type="submit" name="upload" id="upload" style="border-color: #1cc88a!important;color: white;" class="btn bg-gradient-success" value="Cargar Archivo"/>
                    </form>
                </div>
                <div class="col-sm-6 col-12">
                    <div class="alert" id="messageFile" style="display: none;"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                <table id="tableListArchivos" class="table table-bordered table-hover" style="font-size: 14px;width: 100%">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>';
    $eu_solicitud = explode("-", $s_solicitud);
    $soli_codi = explode("/", $eu_solicitud[1]);
    if ($perfil === "1" || $perfil === "5") {
        $lista_archivos = fnc_lista_solicitudes_archivos($conexion, $soli_codi[0], '0,1');
    } else {
        $lista_archivos = fnc_lista_solicitudes_archivos($conexion, $soli_codi[0], '1');
    }
    if (count($lista_archivos) > 0) {
        $num = 1;
        foreach ($lista_archivos as $lista) {
            $html .= '<tr>
                    <td style="text-align:center">' . $num . '</td>
                    <td>' . $lista["nombre"] . '</td>
                    <td style="text-align:center">' . $lista["tipo"] . '</td>
                    <td style="text-align:center">' . $lista["estado"] . '</td>
                    <td style="text-align:center">
                        <a href="php/aco_archivos/' . $lista["nombre"] . '" download><i class="nav-icon fas fa-download azul" title="Descargar"></i></a>&nbsp;&nbsp;&nbsp;';
            if ($perfil === "1" || $perfil === "5") {
                if ($lista["estado"] == "Activo") {
                    $html .= '<i class="nav-icon fas fa-trash rojo" title="Eliminar" data-toggle="modal" data-target="#modal-eliminar-archivo" data-backdrop="static" data-solicitud="' . $s_solicitud . '" data-archivo="' . $lista["codigo"] . '"></i>';
                } else {
                    $html .= '<i class="nav-icon fas fa-undo green" title="Activar" data-toggle="modal" data-target="#modal-activar-archivo" data-backdrop="static" data-solicitud="' . $s_solicitud . '" data-archivo="' . $lista["codigo"] . '"></i>';
                }
            }
            $html .= '</td>
                </tr>';
            $num++;
        }
    } else {
        $html .= '<tr><td colspan="5">*No se encontraron alumnos a registrar</td></tr>';
    }
    $html .= '</tbody>
                </table>
            </div></div>';
    echo $html;
}

function operacion_cargar_tabla_archivos() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $eu_solicitud = explode("-", $s_solicitud);
    $soli_codi = explode("/", $eu_solicitud[1]);
    $perfil = p_perfil;
    if ($perfil === "1" || $perfil === "5") {
        $lista_archivos = fnc_lista_solicitudes_archivos($conexion, $soli_codi[0], '0,1');
    } else {
        $lista_archivos = fnc_lista_solicitudes_archivos($conexion, $soli_codi[0], '1');
    }
    $html = '';
    if (count($lista_archivos) > 0) {
        $num = 1;
        foreach ($lista_archivos as $lista) {
            $html .= '<tr>
                    <td style="text-align:center">' . $num . '</td>
                    <td>' . $lista["nombre"] . '</td>
                    <td style="text-align:center">' . $lista["tipo"] . '</td>
                    <td style="text-align:center">' . $lista["estado"] . '</td>
                    <td style="text-align:center">
                        <a href="php/aco_archivos/' . $lista["nombre"] . '" download><i class="nav-icon fas fa-download azul" title="Descargar"></i></a>&nbsp;&nbsp;&nbsp;';
            if ($lista["estado"] == "Activo") {
                $html .= '<i class="nav-icon fas fa-trash rojo" title="Eliminar" data-toggle="modal" data-target="#modal-eliminar-archivo" data-backdrop="static" data-solicitud="' . $s_solicitud . '" data-archivo="' . $lista["codigo"] . '"></i>';
            } else {
                $html .= '<i class="nav-icon fas fa-undo green" title="Activar" data-toggle="modal" data-target="#modal-activar-archivo" data-backdrop="static" data-solicitud="' . $s_solicitud . '" data-archivo="' . $lista["codigo"] . '"></i>';
            }
            $html .= '</td>
                </tr>';
            $num++;
        }
    } else {
        $html .= '<tr><td colspan="5">*No se encontraron alumnos a registrar</td></tr>';
    }
    echo $html;
}

function formulario_eliminar_archivo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $archivo = strip_tags(trim($_POST["s_archivo"]));
    $dato_archivo = fnc_obtener_solicitud_archivo($conexion, $archivo);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiSolicitud" class="form-control" value="<?php echo trim($solicitud); ?>"/>
            <input type="hidden" id="hdnCodiArchivo" class="form-control" value="<?php echo trim($dato_archivo[0]["codigo"]); ?>"/>
            <label>&iquest;Esta seguro de eliminar el archivo "<label style="font-style: italic; "><?php echo $dato_archivo[0]["nombre"] ?>"</label> ?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_archivo() {//lupis
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiArchivo = strip_tags(trim($_POST["u_codiArchivo"]));
    try {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $resp = fnc_eliminar_archivo($conexion, $u_codiArchivo, "0");
        if ($resp) {
            $sql_auditoria = fnc_eliminar_archivo_auditoria($u_codiArchivo, "0");
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_archivo" . '", "' . "fnc_eliminar_archivo" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes_archivos" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
            echo "***1***Archivo eliminado correctamente.<br/>";
        } else {
            echo "***0***Error al eliminar el archivo.<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al eliminar el archivo.<br/>";
    }
}

function formulario_activar_archivo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $solicitud = strip_tags(trim($_POST["s_solicitud"]));
    $archivo = strip_tags(trim($_POST["s_archivo"]));
    $dato_archivo = fnc_obtener_solicitud_archivo($conexion, $archivo);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiSolicitud" class="form-control" value="<?php echo trim($solicitud); ?>"/>
            <input type="hidden" id="hdnCodiArchivo" class="form-control" value="<?php echo trim($dato_archivo[0]["codigo"]); ?>"/>
            <label>&iquest;Esta seguro de activar el archivo "<label style="font-style: italic; "><?php echo $dato_archivo[0]["nombre"] ?>"</label> ?</label>
        </div>
    </div>
    <?php
}

function operacion_activar_archivo() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_codiArchivo = strip_tags(trim($_POST["u_codiArchivo"]));
    try {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $resp = fnc_eliminar_archivo($conexion, $u_codiArchivo, "1");
        if ($resp) {
            $sql_auditoria = fnc_eliminar_archivo_auditoria($u_codiArchivo, "1");
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_activar_archivo" . '", "' . "fnc_eliminar_archivo" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes_archivos" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
            echo "***1***Archivo activado correctamente.<br/>";
        } else {
            echo "***0***Error al activar el archivo.<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al activar el archivo.<br/>";
    }
}

function formulario_secciones_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_usua = strip_tags(trim($_POST["sol_usuario"]));
    $html = "";
    if ($s_usua) {
        $historial = fnc_historial_todas_secciones_docente($conexion, $s_usua);
        $count = 1;
        $html .= '<div class="row" >'
                . '<div class="col-2">'
                . '<button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nueva-seccion-docente" data-backdrop="static" data-docente="' . $s_usua . '">Nueva sección</button>'
                . '</div>'
                . '</div><br/>'
                . '<div class="row">'
                . '<div class="col-12">'
                . '<div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="tablaHistorialDocente">
                  <thead>
                    <tr>
                      <th>Nro.</th>
                      <th>Nivel</th>
                      <th>Grado</th>
                      <th>Secci&oacute;n</th>
                      <th style="text-align:center">Estado</th>
                      <th style="text-align:center">Opci&oacute;n</th>
                    </tr>
                  </thead>
                  <tbody>';
        foreach ($historial as $value) {
            $html .= '<tr class="tr-cursor">
                      <td>' . $count . '</td>
                      <td>' . $value["nivel"] . '</td>
                      <td>' . $value["grado"] . '</td>
                      <td>' . $value["seccion"] . '</td>
                      <td style="text-align:center">' . $value["estado"] . '</td>
                      <td style="text-align:center">';
            if ($value["estadoId"] === "1") {
                $html .= '<i class = "nav-icon fas fa-trash rojo" title = "Eliminar" data-toggle = "modal" data-target = "#modal-eliminar-seccion-docente" data-backdrop = "static" data-docente = "' . $s_usua . '" data-dictado = "' . $value["dicId"] . '"></i>';
            } else {
                $html .= '<i class = "nav-icon fas fa-undo green" title = "Activar" data-toggle = "modal" data-target = "#modal-activar-seccion-docente" data-backdrop = "static" data-docente = "' . $s_usua . '" data-dictado = "' . $value["dicId"] . '"></i>';
            }
            $html .= '</td>
                    </tr>';
            $count++;
        }

        $html .= '</tbody>'
                . '</table>'
                . '</div><br/>'
                . '<div>'
                . '</div><br/>'
                . '<div>';
    }
    echo $html;
}

function formulario_registro_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_docente = strip_tags(trim($_POST["s_docente"]));
    $data_usuario = fnc_datos_usuario($conexion, $s_docente);
    if (count($data_usuario) > 0) {
        $lista_niveles = fnc_lista_niveles($conexion, '', '1');
        ?>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <label>Nombre: </label>
            </div>
            <div class="col-md-8">
                <input type="hidden" id="hdnDocente" class="form-control" style="width: 100%;text-transform: uppercase" value="<?php echo $s_docente; ?>"/>
                <input type="hidden" id="txtSede" class="form-control" style="width: 100%;text-transform: uppercase" value="<?php echo $data_usuario[0]["sedeId"]; ?>"/>
                <input type="text" id="txtNombreSed" class="form-control" style="width: 100%;text-transform: uppercase;" disabled="" value="<?php echo $data_usuario[0]["usuariodata"]; ?>"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <label> Nivel: </label>
            </div>
            <div class="col-md-8">
                <select id="cbbNivel" data-show-content="true" class="form-control" style="width: 100%" onchange="cargar_selector_grado_docente(this)">
                    <option value="0">-- Seleccione --</option>
                    <?php
                    foreach ($lista_niveles as $nivel) {
                        echo "<option value='" . $nivel["codigo"] . "' >" . $nivel["nombre"] . "</option>";
                    }
                    ?>

                </select>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <label> Grado: </label>
            </div>
            <div class="col-md-8">
                <select id="cbbGrado" data-show-content="true" class="form-control" style="width: 100%" onchange="cargar_selector_seccion_docente(this)">
                    <option value="0">-- Seleccione --</option>
                </select>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-4" style="margin-bottom: 0px;">
                <label> Secci&oacute;n: </label>
            </div>
            <div class="col-md-8">
                <select id="cbbSeccion" data-show-content="true" class="form-control" style="width: 100%">
                    <option value="0">-- Seleccione --</option>
                </select>
            </div>
        </div>
        <?php
    }
}

function operacion_registro_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $s_docente = strip_tags(trim($_POST["s_docente"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $s_seccion = strip_tags(trim($_POST["s_seccion"]));
    $s_sede = strip_tags(trim($_POST["s_sede"]));
    try {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }

        $busqueda = fnc_buscar_seccion_docente($conexion, $s_docente, $s_sede, $s_seccion);
        if (count($busqueda) === 0) {
            $resp = fnc_registrar_seccion_docente($conexion, $s_docente, $s_sede, $s_seccion);
            if ($resp) {
                $sql_auditoria = fnc_registrar_seccion_docente_auditoria($s_docente, $s_sede, $s_seccion);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_registro_seccion_docente" . '", "' . "fnc_registrar_seccion_docente" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_usuario_dictado" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
                echo "***1***Sección registrada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
            } else {
                echo "***0***Error al registrar la sección al docente.<br/>";
            }
        } else {
            echo "***0***El grado y sección " . $busqueda[0]["seccion"] . " ya fue registrada.<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al registrar la sección al docente.<br/>";
    }
}

function formulario_eliminar_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_docente = strip_tags(trim($_POST["u_docente"]));
    $u_dictado = strip_tags(trim($_POST["u_dictado"]));
    $data_dictado = fnc_buscar_gradoseccion_docente($conexion, $u_dictado);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiDictado" class="form-control" value="<?php echo trim($u_dictado); ?>"/>
            <input type="hidden" id="hdnCodiDocente" class="form-control" value="<?php echo trim($u_docente); ?>"/>
            <label>&iquest;Esta seguro de eliminar el grado y secci&oacute;n "<label style="font-style: italic; "><?php echo $data_dictado[0]["seccion"] ?>"</label> al docente?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
    $u_hdnCodiDictado = strip_tags(trim($_POST["u_hdnCodiDictado"]));
    try {
        $eliminar = fnc_eliminar_seccion_docente($conexion, $u_hdnCodiDictado, '0');
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigo);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($eliminar) {
            $sql_auditoria = fnc_eliminar_seccion_docente_auditoria($u_hdnCodiDictado, '0');
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_seccion_docente" . '", "' . "fnc_eliminar_seccion_docente" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_usuario_dictado" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
        }
        echo "***1***Sección del docente eliminada correctamente." . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar el grado y sección al docente.<br/>";
    }
}

function formulario_activar_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_docente = strip_tags(trim($_POST["u_docente"]));
    $u_dictado = strip_tags(trim($_POST["u_dictado"]));
    $data_dictado = fnc_buscar_gradoseccion_docente($conexion, $u_dictado);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiDictado" class="form-control" value="<?php echo trim($u_dictado); ?>"/>
            <input type="hidden" id="hdnCodiDocente" class="form-control" value="<?php echo trim($u_docente); ?>"/>
            <label>&iquest;Esta seguro de activar el grado y secci&oacute;n "<label style="font-style: italic; "><?php echo $data_dictado[0]["seccion"] ?>"</label> al docente?</label>
        </div>
    </div>
    <?php
}

function operacion_activar_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
    $u_hdnCodiDictado = strip_tags(trim($_POST["u_hdnCodiDictado"]));
    try {
        $eliminar = fnc_eliminar_seccion_docente($conexion, $u_hdnCodiDictado, '1');
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigo);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($eliminar) {
            $sql_auditoria = fnc_eliminar_seccion_docente_auditoria($u_hdnCodiDictado, '1');
            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_seccion_docente" . '", "' . "fnc_eliminar_seccion_docente" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_usuario_dictado" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
            fnc_registrar_auditoria($conexion, $sql_insert);
        }
        echo "***1***Sección del docente activada correctamente." . "";
    } catch (Exception $exc) {
        echo "***0***Error al activar el grado y sección al docente.<br/>";
    }
}

function formulario_registro_nuevo_gradoseccion() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $lista_niveles = fnc_lista_niveles($conexion, '', '1');
    ?>
    <div class="row">
        <div class="col-lg-6" style="margin-bottom: 0px;">
            <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                <div class="row space-div">
                    <div class="col-md-4" style="margin-bottom: 0px;">
                        <label>C&oacute;digo: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control pull-right" id="txtCodigo" style="width: 100%;text-transform: uppercase;" maxlength="8" placeholder="Ejm: EP-3,PRI-1,SEC-2,..." />
                    </div>
                </div>
                <div class="row space-div">
                    <div class="col-md-4" style="margin-bottom: 0px;">
                        <label>Nombre: </label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control pull-right" id="txtNombre" />
                    </div>
                </div>
                <div class="row space-div">
                    <div class="col-md-4" style="margin-bottom: 0px;">
                        <label>Nivel: </label>
                    </div>
                    <div class="col-md-8">
                        <select id="cbbNivel" data-show-content="true" class="form-control" style="width: 100%" >
                            <option value="0">-- Seleccione --</option>
                            <?php
                            foreach ($lista_niveles as $nivel) {
                                echo "<option value='" . $nivel["codigo"] . "' >" . $nivel["nombre"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6" style="margin-bottom: 0px;">
            <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                <fieldset class="col-md-12 fieldset2" id="listSecciones">
                    <legend class="legend">SECCIONES</legend>
                    <div class="wrapper">
                        <div class="buttons">  
                            <input type="button" class="btn btn-success" onclick="crear_elemento();" value="Agregar"/>
                        </div><br>
                        <div id="contenedor">
                            <li><label>Secci&oacute;n A</label> <a onclick="eliminar_elemento(this);">&times;</a></li>
                        </div>
                        <br>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <?php
}

function proceso_nuevo_gradoseccion() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $s_codigo = strip_tags(trim($_POST["s_codigo"]));
    $s_nombre = strip_tags(trim($_POST["s_nombre"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $sec_lista = strip_tags(trim($_POST["sec_lista"]));
    try {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $lastInsertGrado = fnc_registrar_grado($conexion, $s_codigo, $s_nombre, $s_nivel);
        if ($lastInsertGrado !== "0") {
            $str_inserts = "";
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_registrar_grado_auditoria($s_codigo, $s_nombre, $s_nivel);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_nuevo_gradoseccion" . '", "' . "fnc_registrar_grado" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_grado" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }

            if ($sec_lista !== "") {
                $sec_lista = substr($sec_lista, 0, -1);
                $lista_secciones = explode("*", $sec_lista);
                if (count($lista_secciones) > 0) {
                    $valor = fnc_obtener_codigo_valor($conexion, $s_nivel);
                    $num_nivel = str_pad($s_nivel - 1, 2, "0", STR_PAD_LEFT);
                    for ($i = 0; $i < count($lista_secciones); $i++) {
                        $arreglo = explode("Sección ", $lista_secciones[$i]);
                        $codigo_seccion = $num_nivel . $valor[0]["valor"] . $arreglo[1];
                        $str_inserts .= "('" . $codigo_seccion . "','" . $lista_secciones[$i] . "','" . $lastInsertGrado . "','1'),";
                    }
                    $str_lista_insert = substr($str_inserts, 0, -1);
                    if ($str_lista_insert !== "") {
                        $registrar_accesos = fnc_registrar_secciones($conexion, $str_lista_insert);
                        if ($registrar_accesos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_registrar_secciones_auditoria($str_lista_insert);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_nuevo_gradoseccion" . '", "' . "fnc_registrar_secciones" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_seccion" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    }
                }
            }
            echo "***1***Grado y seccion registrados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        } else {
            echo "***0***Error al registrar grado y sección.***<br/>";
        }
    } catch (Exception $exc) {
        echo "***0***Error al registrar el grado y sección.***<br/>";
    }
}

function formulario_editar_gradoseccion() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $u_gradoseccion = strip_tags(trim($_POST["u_gradoseccion"]));
    $eu_codgradoseccion = explode("-", $u_gradoseccion);
    $gradoseccion_codigo = explode("/", $eu_codgradoseccion[1]);
    $lista_niveles = fnc_lista_niveles($conexion, '', '1');
    $obtener_datos = fnc_obtener_gradoseccion($conexion, $gradoseccion_codigo[0]);
    if (count($obtener_datos) > 0) {
        $disabled = "";
        if ($obtener_datos[0]["inicio"] === "1") {
            $disabled = " disabled ";
        } else {
            $disabled = "";
        }
        ?>
        <div class="row">
            <div class="col-lg-6" style="margin-bottom: 0px;">
                <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                    <input type="hidden" id="txtCod" value="<?php echo $obtener_datos[0]["id"]; ?>"/>
                    <input type="hidden" id="txtDisable" value="<?php echo trim($disabled); ?>"/>
                    <div class="row space-div">
                        <div class="col-md-4" style="margin-bottom: 0px;">
                            <label>C&oacute;digo: </label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control pull-right" id="txtCodigoEdi" style="width: 100%;text-transform: uppercase;" 
                                   maxlength="8" placeholder="Ejm: EP-3,PRI-1,SEC-2,..." value="<?php echo $obtener_datos[0]["codigo"]; ?>" <?php echo $disabled; ?>/>
                        </div>
                    </div>
                    <div class="row space-div">
                        <div class="col-md-4" style="margin-bottom: 0px;">
                            <label>Nombre: </label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control pull-right" id="txtNombreEdi" value="<?php echo $obtener_datos[0]["nombre"]; ?>" <?php echo $disabled; ?>/>
                        </div>
                    </div>
                    <div class="row space-div">
                        <div class="col-md-4" style="margin-bottom: 0px;">
                            <label>Nivel: </label>
                        </div>
                        <div class="col-md-8">
                            <select id="cbbNivelEdi" data-show-content="true" class="form-control" style="width: 100%" <?php echo $disabled; ?>>
                                <option value="0">-- Seleccione --</option>
                                <?php
                                $selected = "";
                                foreach ($lista_niveles as $nivel) {
                                    if ($obtener_datos[0]["nivId"] === $nivel["codigo"]) {
                                        $selected = " selected ";
                                    } else {
                                        $selected = "";
                                    }
                                    echo "<option value='" . $nivel["codigo"] . "' $selected>" . $nivel["nombre"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row space-div">
                        <div class="col-md-4" style="margin-bottom: 0px;">
                            <label>Estado: </label>
                        </div>
                        <div class="col-md-8">
                            <select id="cbbEstadoGraSec" class="form-control select2" style="width: 100%">
                                <option value="-1">-- Seleccione --</option>
                                <?php
                                $selectedestado = "";
                                $array_estado = array();
                                array_push($array_estado, ["id" => "1", "nombre" => "Activo"]);
                                array_push($array_estado, ["id" => "0", "nombre" => "Inactivo"]);
                                foreach ($array_estado as $listestado) {
                                    if ($listestado["id"] == $obtener_datos[0]["estadoId"]) {
                                        $selectedestado = " selected ";
                                    } else {
                                        $selectedestado = "";
                                    }
                                    echo "<option value='" . $listestado["id"] . "' $selectedestado>" . $listestado["nombre"] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="margin-bottom: 0px;">
                <div class="col-lg-12 col-md-4 col-sm-6 col-12">
                    <fieldset class="col-md-12 fieldset2" id="listSecciones">
                        <legend class="legend">SECCIONES</legend>
                        <div class="wrapper">
                            <div class="buttons">  
                                <input type="button" class="btn btn-success" onclick="crear_elemento_edi();" value="Agregar"/>
                            </div><br>
                            <div id="contenedor0">
                                <?php
                                $secciones = fnc_lista_secciones($conexion, $obtener_datos[0]["id"]);
                                $checked = "";
                                $ultimo_seccion = "";
                                foreach ($secciones as $seccion) {
                                    if ($seccion["estado"] === "1") {
                                        $checked = " checked ";
                                    } else {
                                        $checked = "";
                                    }
                                    $ultimo_seccion = $seccion["letra"];
                                    echo '<li><input type="checkbox" class="checkboxesSecciones" name="check_seccion[]" value="' . $seccion["codigo"] . '" ' . $checked . ' />&nbsp;&nbsp;&nbsp; <label>' . $seccion['nombre'] . '</label></li>';
                                }
                                ?>
                            </div>
                            <div id="contenedor">
                                <?php
                                $lista_nombres = fnc_lista_nombre_secciones();
                                $index = array_search($ultimo_seccion, $lista_nombres, true) + 1;
                                ?>
                                <!--<li><label>Secci&oacute;n <?php //echo $lista_nombres[$index];                                    ?></label> <a onclick="eliminar_elemento(this);">&times;</a></li>-->
                            </div>
                            <br>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
    }
}

function proceso_editar_gradoseccion() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $s_cod = strip_tags(trim($_POST["s_cod"]));
    $s_disable = strip_tags(trim($_POST["s_disable"]));
    $s_codigo = strip_tags(trim($_POST["s_codigo"]));
    $s_nombre = strip_tags(trim($_POST["s_nombre"]));
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_estado = strip_tags(trim($_POST["s_estado"]));
    $sec_lista_edi = strip_tags(trim($_POST["sec_lista_edi"]));
    $sec_lista_nue = strip_tags(trim($_POST["sec_lista_nue"]));
    if (trim($sec_lista_nue) !== "") {
        $sec_lista_nue = substr($sec_lista_nue, 0, -1);
    } else {
        $sec_lista_nue = "";
    }
    $sec_lista_edi = substr($sec_lista_edi, 0, -1);
    try {
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoMenu);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        $lastEditarGrado = fnc_editar_grado($conexion, $s_cod, $s_disable, $s_codigo, $s_nombre, $s_nivel, $s_estado);
        if ($lastEditarGrado) {
            $str_inserts = "";
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_editar_grado_auditoria($s_cod, $s_disable, $s_codigo, $s_nombre, $s_nivel, $s_estado);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_gradoseccion" . '", "' . "fnc_editar_grado" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_grado" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
            if (trim($sec_lista_edi) !== "") {
                $lista_secciones_edi = explode("*", $sec_lista_edi);
                if (count($lista_secciones_edi) > 0) {
                    for ($i = 0; $i < count($lista_secciones_edi); $i++) {
                        $arreglito = explode("-", $lista_secciones_edi[$i]);
                        $codigo_id = $arreglito[0];
                        $estado = $arreglito[1];
                        $edi = fnc_editar_seccion($conexion, $codigo_id, $estado);
                        if ($edi) {
                            $sql_auditoria = fnc_editar_seccion_auditoria($codigo_id, $estado);
                            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_gradoseccion" . '", "' . "fnc_editar_seccion" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_seccion" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql_insert);
                        }
                    }
                }
            }
            if (trim($sec_lista_nue) !== "") {
                $lista_secciones_nuevo = explode("*", $sec_lista_nue);
                if (count($lista_secciones_nuevo) > 0) {
                    $valor = fnc_obtener_codigo_valor_edi($conexion, $s_cod);
                    $num_nivel = str_pad($s_nivel - 1, 2, "0", STR_PAD_LEFT);
                    for ($i = 0; $i < count($lista_secciones_nuevo); $i++) {
                        $arreglo = explode("Sección ", $lista_secciones_nuevo[$i]);
                        $codigo_seccion = $num_nivel . $valor[0]["valor"] . $arreglo[1];
                        $str_inserts .= "('" . $codigo_seccion . "','" . $lista_secciones_nuevo[$i] . "','" . $s_cod . "','1'),";
                    }
                    $str_lista_insert = substr($str_inserts, 0, -1);
                    if ($str_lista_insert !== "") {
                        $registrar_accesos = fnc_registrar_secciones($conexion, $str_lista_insert);
                        if ($registrar_accesos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_registrar_secciones_auditoria($str_lista_insert);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_gradoseccion" . '", "' . "fnc_registrar_secciones" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_seccion" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    }
                }
            }
            echo "***1***Grado y seccion editados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        } else {
            if (trim($sec_lista_edi) !== "") {
                $lista_secciones_edi = explode("*", $sec_lista_edi);
                if (count($lista_secciones_edi) > 0) {
                    for ($i = 0; $i < count($lista_secciones_edi); $i++) {
                        $arreglito = explode("-", $lista_secciones_edi[$i]);
                        $codigo_id = $arreglito[0];
                        $estado = $arreglito[1];
                        $edi = fnc_editar_seccion($conexion, $codigo_id, $estado);
                        if ($edi) {
                            $sql_auditoria = fnc_editar_seccion_auditoria($codigo_id, $estado);
                            $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_gradoseccion" . '", "' . "fnc_editar_seccion" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_seccion" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql_insert);
                        }
                    }
                }
            }
            if (trim($sec_lista_nue) !== "") {
                $lista_secciones_nuevo = explode("*", $sec_lista_nue);
                if (count($lista_secciones_nuevo) > 0) {
                    $valor = fnc_obtener_codigo_valor_edi($conexion, $s_cod);
                    $num_nivel = str_pad($s_nivel - 1, 2, "0", STR_PAD_LEFT);
                    for ($i = 0; $i < count($lista_secciones_nuevo); $i++) {
                        $arreglo = explode("Sección ", $lista_secciones_nuevo[$i]);
                        $codigo_seccion = $num_nivel . $valor[0]["valor"] . $arreglo[1];
                        $str_inserts .= "('" . $codigo_seccion . "','" . $lista_secciones_nuevo[$i] . "','" . $s_cod . "','1'),";
                    }
                    $str_lista_insert = substr($str_inserts, 0, -1);
                    if ($str_lista_insert !== "") {
                        $registrar_accesos = fnc_registrar_secciones($conexion, $str_lista_insert);
                        if ($registrar_accesos) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_registrar_secciones_auditoria($str_lista_insert);
                                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "proceso_editar_gradoseccion" . '", "' . "fnc_registrar_secciones" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_seccion" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql_insert);
                            }
                        }
                    }
                }
            }
            echo "***1***Grado y seccion editados correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        }
    } catch (Exception $exc) {
        echo "***0***Error al editar el grado y sección.***<br/>";
    }
}

function formulario_eliminar_gradoseccion() {
    $eu_codigo = strip_tags(trim($_POST["u_gradoseccion"]));
    $eu_cod1 = explode("-", $eu_codigo);
    $eu_codi = explode("/", $eu_cod1[1]);
    ?>
    <div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiGradoEli" class="form-control" value="<?php echo trim($eu_codi[0]); ?>"/>
            <label>&iquest;Esta seguro de cambiar el estado del grado a inactivo?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_gradoseccion() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
    $u_u_codiGrado = strip_tags(trim($_POST["u_codiGrado"]));
    try {
        $eliminar = fnc_eliminar_grado($conexion, $u_u_codiGrado);
        $str_submenu = "";
        $str_menu_id = "";
        $str_menu_nombre = "";
        $submenu = fnc_consultar_submenu($conexion, $sm_codigoEdi);
        if (count($submenu) > 0) {
            $str_submenu = $submenu[0]["ruta"];
            $str_menu_id = $submenu[0]["id"];
            $str_menu_nombre = $submenu[0]["nombre"];
        } else {
            $str_submenu = "";
            $str_menu_id = "";
            $str_menu_nombre = "";
        }
        if ($eliminar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_eliminar_grado_auditoria($u_u_codiGrado);
                $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "operacion_eliminar_gradoseccion" . '", "' . "fnc_eliminar_grado" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_grado" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql_insert);
            }
        }
        echo "***1***Grado eliminado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } catch (Exception $exc) {
        echo "***0***Error al eliminar el grado.***<br/>";
    }
}

function formulario_selector_grado_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $html = "";
    $lista_grados = fnc_lista_grados_x_nivel($conexion, $s_nivel);
    if (count($lista_grados) > 0) {
        $html .= '<option value="0">-- Seleccione --</option>';
        foreach ($lista_grados as $grado) {
            $html .= "<option value='" . $grado["codigo"] . "' >" . $grado["nombre"] . "</option>";
        }
    }
    echo $html;
}

function formulario_selector_seccion_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_nivel = strip_tags(trim($_POST["s_nivel"]));
    $s_grado = strip_tags(trim($_POST["s_grado"]));
    $html = "";
    $lista_secciones = fnc_lista_secciones_grados($conexion, $s_nivel, $s_grado);
    if (count($lista_secciones) > 0) {
        $html .= '<option value="0">-- Seleccione --</option>';
        foreach ($lista_secciones as $seccion) {
            $html .= "<option value='" . $seccion["codigo"] . "' >" . $seccion["nombre"] . "</option>";
        }
    }
    echo $html;
}

function formulario_cargar_semaforo_bimestre(){
    $con = new DB(1111);
    $conexion = $con->connect();
    $s_bimestre = strip_tags(trim($_POST["s_bimestre"]));
    $html = "";
    $lista_semaforo = fnc_lista_semaforo($conexion, $s_bimestre, '1');
    if (count($lista_semaforo) > 0) {
        $html .= '<option value="0">-- Todos --</option>';
        foreach ($lista_semaforo as $semaforo) {
            $html .= "<option value='" . $semaforo["id"] . "' >" . $semaforo["nombre"] . "</option>";
        }
    } else {
        $html .= '<option value="0">-- Todos --</option>';
    }
    echo $html;
}
?>
