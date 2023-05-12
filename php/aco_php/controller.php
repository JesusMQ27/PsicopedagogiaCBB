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
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "", "");
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
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "", "");
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
        fnc_editar_menu($conexion, $m_codigoEdi, $strCodigo, strtoupper($m_descripcion), $m_imagen, $nombre_carpeta, $m_estadoMe);
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
        fnc_eliminar_menu($conexion, $u_codiMenuIdEli);
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
            <input type="text" id="txtLinkSub" class="form-control" style="width: 100%;text-transform: lowercase;" placeholder="Ejm: lista" onkeypress="return solo_letras(event);"/>
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
            <input type="text" id="txtLinkSubEdi" class="form-control" style="width: 100%;text-transform: lowercase;" placeholder="Ejm: lista" value="<?php echo trim($enlace_ruta[0]); ?>" onkeypress="return solo_letras(event);"/>
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
        fnc_editar_submenu($conexion, $s_codisubmenu, "", $strCodigo, ucwords(strtolower($s_descripcion)), $s_menu, $s_imagen, $strUrl, $s_estado);
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
        fnc_eliminar_submenu($conexion, $u_codiSubmenuIdEli);
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
                          <input class='custom-control-input' type='checkbox' id='customCheckbox" . $lista_s["id"] . "' value='" . $lista_s["id"] . "'>
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
            if ($per_lista !== "") {
                $lista_submenu_perfil = explode("*", $per_lista);
                if (count($lista_submenu_perfil) > 0) {
                    for ($i = 0; $i < count($lista_submenu_perfil); $i++) {
                        $str_inserts .= "('" . $lastInsertPerfil . "','" . $lista_submenu_perfil[$i] . "','1'),";
                    }
                    $str_lista_insert = substr($str_inserts, 0, -1);
                    if ($str_lista_insert !== "") {
                        fnc_registrar_accesos_perfil($conexion, $str_lista_insert);
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
                          <input class='custom-control-input' type='checkbox' id='customCheckbox" . $lista_s["id"] . "' $checked value='" . $idMenuAsig . "_" . $lista_s["id"] . "'>
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
        fnc_editar_perfil($conexion, $perf_codigo, $perf_descripcion, $perf_estado);
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
                        fnc_editar_accesos_perfil($conexion, $str_updates, $perf_codigo);
                        fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                    } else {
                        fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                    }
                }
                if (trim($str_inserts) !== "") {
                    fnc_registrar_accesos_perfil($conexion, $str_inserts);
                }
            }
        } else {
            //Aqui se cambia de estado a todos
            fnc_editar_accesos_perfil_todos($conexion, "", $perf_codigo);
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
        fnc_eliminar_perfil($conexion, $u_codiPerfilIdEli);
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
        if ($value["actual_alu_id"] == 0) {
            $color = "";
            $salida_alu = "Alumno no existe";
            $salida_sede = "";
            $salida_secc = "";
            if (trim($value["grado"]) === "") {
                $salida_secc = "--El grado esta vacio--";
            }
            if (trim($value["seccion"]) === "") {
                $salida_secc .= "La seccion esta vacia";
            }
            $count1++;
        } else {
            if ($value["actual_alu_id"] !== 0) {
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

function operacion_registrar_carga_alumnos() {
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
                                $cadenaInsertMatri = "('" . $aluCodigo . "','" . $value["registrar_sed_id"] . "','" . $value["registrar_sec_id"] . "','" .
                                        $value["tip_alu"] . "',NOW(),'" . $grupo_creado . "','1')";
                                fnc_registrar_matricula_alumno($conexion, $cadenaInsertMatri);
                            }
                        } else {
                            if ($value["valida_matricula"] == 0) {
                                $cadenaInsertMatri = "('" . $value["actual_alu_id"] . "','" . $value["registrar_sed_id"] . "','" . $value["registrar_sec_id"] . "','" .
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

function operacion_eliminar_detalle_grupo() {
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

function operacion_activar_detalle_grupo() {
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
            <label>&iquest;Esta seguro de eliminar el Grupo "<label style="font-style: italic; "><?php echo $grupo[0]["codigo"] ?>"</label> de Carga de Alumno?</label>
        </div>
    </div>
    <?php
}

function operacion_eliminar_detalle_grupo_usuario() {
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
            <label>&iquest;Esta seguro de activar el Grupo "<label style="font-style: italic; "><?php echo $grupo[0]["codigo"] ?>"</label> de Carga de Alumno?</label>
        </div>
    </div>
    <?php
}

function operacion_activar_detalle_grupo_usuario() {
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

function formulario_detalle_tipo_solicitud() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_tipo_sol = strip_tags(trim($_POST["sol_tipo"]));
    $sm_sol_matricula = strip_tags(trim($_POST["sol_matricula"]));
    $sm_sol_categoria = strip_tags(trim($_POST["sol_categoria"]));
    $sm_sol_subcategoria = strip_tags(trim($_POST["sol_subcategoria"]));
    $usuario_data = fnc_datos_usuario($conexion, p_usuario);
    $matricula = fnc_alumno_matricula_detalle($conexion, $sm_sol_matricula);
    $html = '<input type="hidden" id="txt_sede" value="' . $matricula[0]["sedeId"] . '">';
    if ($sm_tipo_sol === "1") {
        $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad"> PRIVACIDAD:
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
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas1"></canvas>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="limpiar_firma();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . str_replace(" - ", "<br/>", strtoupper($matricula[0]["alumno"])) . '<label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . strtoupper($usuario_data[0]["usuariodata"]) . '<br/>' . $usuario_data[0]["usuarioDni"] . '<label/>
                   </div>'
                . ' </div>'
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
                        <label for="checkPrivacidad"> PRIVACIDAD:
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
                        <button type="button" class="btn btn-default" onclick="limpiar_firma();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI">
                       <label><label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
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
              <label>Nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderado" class="form-control select2" style="width: 100%" disabled value="' . $apoderado[0]["nombres"] . '" />
            </div>
        </div>
        
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>DNI</label>
            </div>
            <div class="col-md-6">
                <input id="txtDniApoderado" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["dni"] . '" 
                    maxlength="12" onkeypress="return solo_numeros(event);"/>
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
              <label>Telefono</label>
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
              <label>Telefono</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderadoN" type="text" class="form-control" style="width: 100%" maxlength="9" 
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

function operacion_registrar_apoderado() {
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

function operacion_editar_apoderado() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumnoCod = strip_tags(trim($_POST["a_txtAlumnCodigo"]));
    $codigo = strip_tags(trim($_POST["a_txtApoderadoCod"]));
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
                fnc_modificar_apoderado($conexion, $codigo, $dni, $correo, $telefono);
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
        $html .= '<label>' . strtoupper($apoderado[0]["nombres"]) . '<br>' . $dni . '</label>';
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

function operacion_eliminar_solicitud() {
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
    $fecha1 = strip_tags(trim($_POST["s_fecha_inicio"]));
    $fecha2 = strip_tags(trim($_POST["s_fecha_fin"]));
    $s_semaforo = strip_tags(trim($_POST["s_semaforo"]));
    $fechaInicio = convertirFecha($fecha1);
    $fechaFin = convertirFecha($fecha2);
    $lista = fnc_buscar_semaforo_docentes($conexion, $s_sede, $fechaInicio, $fechaFin, $s_semaforo);
    $html = "";
    $aux = 1;
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
                    . "<td >" . $value["docente"] . "</td>"
                    . "<td>" . $value["grado"] . "</td>"
                    . "<td style='text-align:center'>" . $value["cantidad"] . "</td>"
                    . "<td style='text-align:center'>" . $value["cantidad_faltantes"] . "</td>"
                    . "<td style='text-align:center'>" . $value["cantidad_realizados"] . "</td>"
                    . "<td style='text-align:center'>" . $value["porcentaje"] . "</td>"
                    . "<td style='text-align:center;$color'><i class='fas fa-circle nav-icon' style='font-size:23px;'></i></td>"
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

function formulario_detalle_tipo_solicitud_sub() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_tipo_sol = strip_tags(trim($_POST["sol_tipo"]));
    $sm_sol_matricula = strip_tags(trim($_POST["sol_matricula"]));
    $sm_sol_categoria = strip_tags(trim($_POST["sol_categoria"]));
    $sm_sol_subcategoria = strip_tags(trim($_POST["sol_subcategoria"]));
    $usuario_data = fnc_datos_usuario($conexion, p_usuario);
    $matricula = fnc_alumno_matricula_detalle($conexion, $sm_sol_matricula);
    $html = '<input type="hidden" id="txt_sede_sub" value="' . $matricula[0]["sedeId"] . '">';
    if ($sm_tipo_sol === "1") {
        $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad_sub"> PRIVACIDAD:
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
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_sub();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . str_replace(" - ", "<br/>", strtoupper($matricula[0]["alumno"])) . '<label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador-sub" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2_sub"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador_sub();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
                       <label>' . strtoupper($usuario_data[0]["usuariodata"]) . '<br/>' . $usuario_data[0]["usuarioDni"] . '<label/>
                   </div>'
                . ' </div>'
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
                        <label for="checkPrivacidad_sub"> PRIVACIDAD:
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
                        <button type="button" class="btn btn-default" onclick="limpiar_firma();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI_sub">
                       <label><label/>
                   </div>'
                . '</div>'
                . '<div class="col-md-2" style="margin-bottom: 0px;">'
                . '</div>'
                . '<div class="col-md-5" style="margin-bottom: 0px;">'
                . '<div id="signature-pad-entrevistador-sub" class="signature-pad" >
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid; " id="canvas2_sub"></canvas>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;">
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
              <label>Nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderado_sub" class="form-control select2" style="width: 100%" disabled value="' . $apoderado[0]["nombres"] . '" />
            </div>
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
              <label>Telefono</label>
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
                fnc_modificar_apoderado($conexion, $codigo, $dni, $correo, $telefono);
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
        $html .= '<label>' . strtoupper($apoderado[0]["nombres"]) . '<br>' . $dni . '</label>';
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
              <label>Telefono</label>
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

function formulario_carga_solicitudes() {
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
                <input type="text" id="searchAlumno_edi" class="typeahead form-control" style="size:12px;text-transform: uppercase;" value="" autocomplete="off">
            </div>
            <div class="col-md-4">
                <input type="hidden" id="matric_edi" value="' . $lista_solicitud[0]["matricula"] . '"/>
                <label id="dataAlumno_edi" style="font-size: 16px;">' . $lista_solicitud[0]["alumno_busq"] . '</label>
            </div>
        </div>
        <div class="row space-div">
        <div class="col-md-2" style="margin-bottom: 0px;">
            <label>Categoria: </label>
        </div>
        <div class="col-md-4">';
        $html .= '<select id="cbbCategoria_edi" class="form-control select2" style="width: 100%" onchange="cargar_subcategorias_edi(this)">
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
        $html .= '<select id="cbbSubcategoria_edi" class="form-control select2" style="width: 100%">
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
        if ($lista_solicitud[0]["ent_id"] === "1") {
            $html .= '<div class="card-header">
                <h3 class="card-title">FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div>
              <div class="card-body">
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div"> 
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad_edi"> PRIVACIDAD:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad_edi" style="transform : scale(1.8);" ' . ($lista_solicitud[0]['privacidad'] == '1' ? 'checked' : '') . '>
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
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_edi" placeholder="">' . $lista_solicitud[0]["motivo"] . '</textarea>
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
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEstudiante_edi" placeholder="">' . $lista_solicitud[0]["plan_estudiante"] . '</textarea></div>
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
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdos_edi" rows="3" placeholder="">' . $lista_solicitud[0]["acuerdos"] . '</textarea></div>
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
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">
                        <input type="hidden" id="firma1" value="' . $imagen_soli[0]["id"] . '"/>
                    <div class="description">Firma del estudiante</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img1" src="' . "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas1_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_edi();">Limpiar firma</button>
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
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >
                        <input type="hidden" id="firma2" value="' . $imagen_soli2[0]["id"] . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img2" src="' . "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas2_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador_edi();">Limpiar firma</button>
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
                    <div class="col-md-12 icheck-success d-inline">
                        <label for="checkPrivacidad_edi"> PRIVACIDAD:
                        </label>&nbsp;&nbsp;&nbsp;
                        <input type="checkbox" id="checkPrivacidad_edi" style="transform : scale(1.8);" ' . ($lista_solicitud[0]['privacidad'] == '1' ? 'checked' : '') . '>
                    </div>
                </div>
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
            $html .= '<select id="cbbTipoApoderado_edi" class="form-control select2" style="width: 100%" onchange="mostrar_info_apoderado_edi(this)">
                <option value="">-- Seleccione --</option>';
            if (count($lista_apoderados) > 0) {
                $selected_apoderado = "";
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $lista_solicitud[0]["apoderado"]) {
                        $selected_apoderado = " selected ";
                    } else {
                        $selected_apoderado = "";
                    }
                    $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_apoderado . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
                }
            }
            $html .= ' <option value="-1" >-- Otro --</option></select>';
            $html .= '</div><div class="col-md-3" id="divEditarInfoApoderado_edi">
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-editar-apoderado-edi" data-backdrop="static" data-alumno="' . $lista_solicitud[0]["aluId"] . '" data-info-apoderado="' . $lista_solicitud[0]["apoderado"] . '">Editar</button>
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
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_edi" placeholder="">' . $lista_solicitud[0]["motivo"] . '</textarea>';
            $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
                </div>
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div class="row space-div">
                    <div class="col-md-12"><textarea class="form-control" rows="3" id="txtInforme_edi" placeholder="">' . $lista_solicitud[0]["informe"] . '</textarea></div>
                </div>

                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del padre, madre o apoderado: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanPadre_edi" placeholder="">' . $lista_solicitud[0]["plan_padre"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanDocente_edi" placeholder="">' . $lista_solicitud[0]["plan_docente"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por los padres: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosPadres_edi" rows="3" placeholder="">' . $lista_solicitud[0]["acuerdos1"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por el colegio: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosColegio_edi" rows="3" placeholder="">' . $lista_solicitud[0]["acuerdos2"] . '</textarea></div>
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
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">
                        <input type="hidden" id="firma1" value="' . $imagen_soli[0]["id"] . '"/>
                    <div class="description">Firma del padre, madre o apoderado</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img1" src="' . "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas1_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_edi();">Limpiar firma</button>
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI_edi">
                       <label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '<label/>
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
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >
                        <input type="hidden" id="firma2" value="' . $imagen_soli2[0]["id"] . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img2" src="' . "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas2_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
                    <div>
                        <button type="button" class="btn btn-default" onclick="limpiar_firma_entrevistador_edi();">Limpiar firma</button>
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
        $html .= '<option value="">-- Seleccione --</option>';
        if (count($lista_apoderados) > 0) {
            $selected_apoderado = "";
            foreach ($lista_apoderados as $lista) {
                $html .= '<option value="' . $lista["codigo"] . '" >' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
            }
        }
        $html .= ' <option value="-1" >-- Otro --</option>***';
    }
    $html .= $matricula[0]["sede"] . "***" . str_replace(" - ", "<br/>", strtoupper($matricula[0]["alumno"])) . "***";
    $html .= '<div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Correo: </label>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-2">
                        <label>Teléfono: </label>
                    </div>
                    <div class="col-md-3"></div>';
    echo $html;
}

function formulario_carga_info_apoderado_edi() {
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
            $html .= '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-editar-apoderado-edi" data-backdrop="static" '
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

function formulario_nuevo_apoderado_edi() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $alumno = strip_tags(trim($_POST["sm_alumno"]));
    $tipos_apoderados = fnc_lista_tipos_apoderados($conexion, "");
    $html = '<div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tipo</label>
            </div>
            <div class="col-md-6">';
    $html .= '<input type="hidden" id="txtAlumnoCodiN_edi" class="form-control" value="' . $alumno . '"/>';
    $html .= '<select id="cbbTipoApoderadoN_edi" class="form-control" style="width: 100%" disabled>
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
                <input id="txtDniN_edi" class="form-control" style="width: 100%" maxlength="12" 
                onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Apellidos y nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderadoN_edi" class="form-control" style="width: 100%;text-transform: uppercase;"
                   onkeypress="return solo_letras(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Correo</label>
            </div>
            <div class="col-md-6">
                <input id="txtCorreoApoderadoN_edi" type="text" class="form-control" style="width: 100%"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Telefono</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderadoN_edi" type="text" class="form-control" style="width: 100%" maxlength="9" 
                onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Direcci&oacute;n</label>
            </div>
            <div class="col-md-6">
                <input id="txtDireccionN_edi" type="text" class="form-control" style="width: 100%;text-transform: uppercase;"/>
            </div>
        </div>
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Distrito</label>
            </div>
            <div class="col-md-6">
                <input id="txtDistritoN_edi" type="text" class="form-control" style="width: 100%;text-transform: uppercase;"
                onkeypress="return solo_letras(event);"/>
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
        $html = '<div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Tipo</label>
            </div>
            <input id="txtAlumnCodigo_edi" type="hidden" class="form-control" value="' . $alumnoCod . '" />
            <div class="col-md-6">';
        $html .= '<select id="cbbTipoApoderado_edi" class="form-control select2" style="width: 100%" disabled>
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
              <label>Nombres</label>
            </div>
            <div class="col-md-6">
                <input id="txtNombresApoderado_edi" class="form-control select2" style="width: 100%" disabled value="' . $apoderado[0]["nombres"] . '" />
            </div>
        </div>
        
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>DNI</label>
            </div>
            <div class="col-md-6">
                <input id="txtDniApoderado_edi" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["dni"] . '" 
                    maxlength="12" onkeypress="return solo_numeros(event);"/>
            </div>
        </div>
        <input id="txtApoderadoCod_edi" type="hidden" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["id"] . '" />
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Correo</label>
            </div>
            <div class="col-md-6">
                <input id="txtCorreoApoderado_edi" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["correo"] . '" />
            </div>
        </div>
        
        <div class="row space-div">
            <div class="col-md-3" style="margin-bottom: 0px;">
              <label>Telefono</label>
            </div>
            <div class="col-md-6">
                <input id="txtTelfApoderado_edi" type="text" class="form-control select2" style="width: 100%" value="' . $apoderado[0]["telefono"] . '" 
                    maxlength="9" onkeypress="return solo_numeros(event);"/>
            </div>
        </div>';
    }
    echo $html;
}

function operacion_editar_apoderado_edi() {
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
        $html .= '<label>' . strtoupper($apoderado[0]["nombres"]) . '<br>' . $dni . '</label>';
    }
    echo $html;
}

function operacion_registrar_apoderado_edi() {
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
        $html .= '<select id="cbbCategoria_edi" class="form-control select2" style="width: 100%" onchange="cargar_subcategorias_edi(this)" disabled>
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
        $html .= '<select id="cbbSubcategoria_edi" class="form-control select2" style="width: 100%" disabled>
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
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_edi" placeholder="" disabled>' . $lista_solicitud[0]["motivo"] . '</textarea>
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
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEstudiante_edi" placeholder="" disabled>' . $lista_solicitud[0]["plan_estudiante"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del entrevistador(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanEntrevistador_edi" placeholder="" disabled>' . $lista_solicitud[0]["plan_entrevistador"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdos_edi" rows="3" placeholder="" disabled>' . $lista_solicitud[0]["acuerdos"] . '</textarea></div>
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
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">
                        <input type="hidden" id="firma1" value="' . $imagen_soli[0]["id"] . '"/>
                    <div class="description">Firma del estudiante</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img1" src="' . "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <canvas style="width: 80%;cursor:pointer;border: 1px black solid;display:none" id="canvas1_edi" height="152" width="531"></canvas>
                        <br/>
                    </div>
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
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >
                        <input type="hidden" id="firma2" value="' . $imagen_soli2[0]["id"] . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img2" src="' . "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
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
            $html .= '<select id="cbbTipoApoderado_edi" class="form-control select2" style="width: 100%" disabled>
                <option value="">-- Seleccione --</option>';
            if (count($lista_apoderados) > 0) {
                $selected_apoderado = "";
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $lista_solicitud[0]["apoderado"]) {
                        $selected_apoderado = " selected ";
                    } else {
                        $selected_apoderado = "";
                    }
                    $html .= '<option value="' . $lista["codigo"] . '" ' . $selected_apoderado . '>' . $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]) . '</option>';
                }
            }
            $html .= ' <option value="-1" >-- Otro --</option></select>';
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
                    <div class="col-md-4"><textarea class="form-control" rows="3" id="txtMotivo_edi" placeholder="" disabled>' . $lista_solicitud[0]["motivo"] . '</textarea>';
            $html .= '  </div>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Fecha y hora: </label>
                    </div>
                    <div class="col-md-3"><span>' . $lista_solicitud[0]["fecha"] . '</span></div>
                </div>
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div class="row space-div">
                    <div class="col-md-12"><textarea class="form-control" rows="3" id="txtInforme_edi" placeholder="" disabled>' . $lista_solicitud[0]["informe"] . '</textarea></div>
                </div>

                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del padre, madre o apoderado: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanPadre_edi" placeholder="" disabled>' . $lista_solicitud[0]["plan_padre"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" rows="3" id="txtPlanDocente_edi" placeholder="" disabled>' . $lista_solicitud[0]["plan_docente"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por los padres: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosPadres_edi" rows="3" placeholder="" disabled>' . $lista_solicitud[0]["acuerdos1"] . '</textarea></div>
                </div>
                <div class="row space-div">
                    <div class="col-md-3" style="margin-bottom: 0px;">
                        <label>Acuerdos - Acciones a realizar por el colegio: </label>
                    </div>
                    <div class="col-md-9"><textarea class="form-control" id="txtAcuerdosColegio_edi" rows="3" placeholder="" disabled>' . $lista_solicitud[0]["acuerdos2"] . '</textarea></div>
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
            $html .= '<div id="signature-pad-edi" class="signature-pad" style="margin-left: 20px;">
                        <input type="hidden" id="firma1" value="' . $imagen_soli[0]["id"] . '"/>
                    <div class="description">Firma del padre, madre o apoderado</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img1" src="' . "./php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
                        <br/>
                    </div>
                   </div>
                   <div style="margin-left: 20px;">
                   </div>
                   <div style="margin-left: 20px;" id="divApoderadoNombreDNI_edi">
                       <label>' . strtoupper($apoderado[0]["nombre"]) . '<br/>' . $apoderado[0]["dni"] . '<label/>
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
            $html .= '<div id="signature-pad-entrevistador-edi" class="signature-pad" >
                        <input type="hidden" id="firma2" value="' . $imagen_soli2[0]["id"] . '"/>
                    <div class="description">Firma del entrevistador</div>
                    <div class="signature-pad--body">
                        <img id="ruta_img2" src="' . "./php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width: 80%;cursor:pointer;border: 1px black solid;" height="152"/>
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
        $html .= '<div class="row space-div">
        <div class="col-md-12" style="margin-bottom: 0px;">
            <input type="hidden" id="hdnCodiSolicitud" class="form-control" value="' . $s_solicitud . '"/>
            <label>&iquest;Esta seguro de eliminar la ' . $entre_sub . ' con c&oacute;digo ' . $lista_solicitud[0]["codigo"] . ' del alumno ' . $lista_solicitud[0]["alumno"] . '?</label>
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
                    echo "<option value='" . $iconos . "' data-content=" . '"' . "<i class='fas fa-circle nav-icon' style='font-size:18px;color:" . $iconos . "'>&nbsp;&nbsp;" . $iconos . "</i>" . '"' . "></option>";
                }
                ?>
            </select>
        </div>
    </div>
    <?php
}

function proceso_registro_nueva_sede() {
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
                    echo "<option value='" . $icono . "' $selectedsede data-content=" . '"' . "<i class='fas fa-circle nav-icon' style='font-size:18px;color:" . $icono . "' >&nbsp;&nbsp;" . $icono . "</i>" . '"' . "></option>";
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
        fnc_editar_sede($conexion, $m_codigoEdi, strtoupper($m_nombreEdi), $m_descripcion, $m_imagen, $m_estado);
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
        fnc_eliminar_sede($conexion, $u_codiSedeIdEli);
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
            fnc_eliminar_matriculas_sede($conexion, $u_codiSedeIdEli, $u_anio);
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
    $html = '<div class="row space-div">
            <div class="col-md-2" style="margin-bottom: 0px;">
              <label>Entrevista / Subentrevista</label>
            </div>
            <div class="col-md-10">';
    $html .= '<select id="cbbTipoSolicitudCodisDes" class="form-control select2" style="width: 100%" onchange="cargar_solicitudes_a_descargar(this)">
                <option value="" >-- Seleccione una opción para descargar --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value="' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
        </div>
        <div id="divDetalleDescargarEntrevista"></div>';
    echo $html;
}

function formulario_enviar_solicitud() {
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
    $html .= '<select id="cbbTipoSolicitudCodisEnv" class="form-control select2" style="width: 100%" onchange="cargar_solicitudes_a_enviar(this)">
                <option value="" >-- Seleccione --</option>';
    foreach ($lista_sol as $value) {
        $html .= '<option value="' . $value["id"] . '" >' . $value["detalle"] . '</option>';
    }
    $html .= '</select></div>
        </div>
        <div id="divDetalleEnviarEntrevista"></div>';
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
        $html = '1***<input type="hidden" id="hdnCodiSolicitudEnv" class="form-control" value="' . $s_solicitud . '"/>'
                . '<div class="row space-div"><div id="checkboxesEnv">';
        foreach ($lista_correos as $lista) {
            $html .= '<div class="checkbox">
                        <label style="color:#007bff">
                          <input type="checkbox" value="' . $lista["codigo"] . '**' . $lista["correo"] . '**' . $lista["persona"] . '">
                          ' . $lista["dato"] . '
                        </label>
                      </div>';
        }
        $html .= '</div></div>';
    } else {
        $html = '0***<div class="row space-div">
           <span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Nota: La ' . $entre_sub . ' con c&oacute;digo ' . $lista_solicitud[0]["codigo"] . ' no tiene correo registrados del estudiante y/o apoderado(s).</span>';
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
    if ($sedeCodigo == "1" && ($perfil == "1" || $perfil == "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
        }
    }
    $lista_solicitudes = fnc_lista_solicitudes($conexion, $sedeCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2), $usuarioCodi, $privacidad);
    $html = "";
    $num = 1;
    if (count($lista_solicitudes) > 0) {
        foreach ($lista_solicitudes as $lista) {
            $solicitudCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
            $html .= "<tr>
                    <td>" . $num . "</td>
                    <td>" . $lista["sede"] . "</td>
                    <td>" . $lista["fecha"] . "</td>
                    <td>" . $lista["grado"] . "</td>
                    <td>" . $lista["nroDocumento"] . "</td>
                    <td>" . $lista["alumno"] . "</td>
                    <td>" . $lista["entrevista"] . "</td>";
            if ($perfil == "1" || $perfil == "5") {
                $html .= "<td>" . $lista["privacidad"] . "</td>";
            }
            $html .= "
                    <td>" . $lista["estado"] . "</td>
                    <td align='center'>"
                    . "<i class='nav-icon fas fa-plus green' title='Nueva Subentrevista' data-toggle='modal' data-target='#modal-subentrevista' data-backdrop='static' data-entrevista='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                    . "<i class='nav-icon fas fa-file-pdf rojo' title='Descargar' data-toggle='modal' data-target='#modal-descargar' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                    . "<i class='nav-icon fas fa-info-circle celeste' title='Detalle' data-toggle='modal' data-target='#modal-detalle-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "' data-grupo_nombre='" . $lista["id"] . "'></i>&nbsp;&nbsp;&nbsp;"
                    . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                    . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                    . "<i class='nav-icon fas fa-paper-plane azul' title='Enviar al correo' data-toggle='modal' data-target='#modal-enviar-solicitud' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;" .
                    "</td>"
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

    $sedeCodi = "";
    $usuarioCodi = "";
    $privacidad = "";
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
        }
    }
    $lista_no_entrevistados = fnc_buscar_alumnos_no_entrevistados($conexion, $sedeCodi, $usuarioCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2));
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
    if ($sedeCodigo == "1" && ($perfil === "1" || $perfil === "5")) {
        $sedeCodi = "0";
        $usuarioCodi = "";
        $privacidad = "0,1";
    } else {
        $privacidad = "0";
        if ($perfil === "1") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
        } elseif ($perfil === "2") {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = p_usuario;
        } else {
            $sedeCodi = $sedeCodigo;
            $usuarioCodi = "";
        }
    }
    $lista_entrevistas = fnc_lista_solicitudes_y_subsolicitudes($conexion, $sedeCodi, $usuarioCodi, convertirFecha($s_fecha1), convertirFecha($s_fecha2), $privacidad);
    $html = "";
    $num = 1;
    $aux = 1;
    if (count($lista_entrevistas) > 0) {
        foreach ($lista_entrevistas as $lista) {
            $html .= "<tr>
                                        <td>" . $num . "</td>
                                        <td>" . $lista["tipo"] . "</td>
                                        <td>" . $lista["sede"] . "</td>
                                        <td width='57px'>" . $lista["fecha"] . "</td>
                                        <td>" . $lista["grado"] . "</td>
                                        <td>" . $lista["nroDocumento"] . "</td>
                                        <td width='200px'>" . $lista["alumno"] . "</td>
                                        <td>" . $lista["entrevista"] . "</td>";
            if ($perfil == "1" || $perfil == "5") {
                $html .= "<td>" . $lista["privacidad"] . "</td>";
            }
            $html .= "<td width='200px'>" . $lista["usuario"] . "</td>" .
                    "<td>" . $lista["categoria"] . "</td>" .
                    "<td>" . $lista["subcategoria"] . "</td>" .
                    "<td>" . $lista["duracion"] . "</td>" .
                    "<td>" . $lista["estado"] . "</td>" .
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

    $html = "<div class='card card-info'>
              <div class='card-header'>
                <h3 class='card-title'>Entrevistas registradas</h3>
              </div>
              <div class='card-body'>
                <div class='input-group mb-12'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>@</span>
                  </div>
                </div>
            </div>";
    echo $html;
}

function formulario_grafico_semaforo_docente() {
    $con = new DB(1111);
    $conexion = $con->connect();

    $html = "<div class='card card-success'>
              <div class='card-header'>
                <h3 class='card-title'>Semaforo docentes</h3>
              </div>
              <div class='card-body'>
                <div class='input-group mb-12'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>@</span>
                  </div>
                </div>
            </div>";
    echo $html;
}

function formulario_grafico_alumnos_no_registrados() {
    $con = new DB(1111);
    $conexion = $con->connect();

    $html = "<div class='card card-warning'>
              <div class='card-header'>
                <h3 class='card-title'>Mis alumnos no entrevistados</h3>
              </div>
              <div class='card-body'>
                <div class='input-group mb-12'>
                  <div class='input-group-prepend'>
                    <span class='input-group-text'>@</span>
                  </div>
                </div>
            </div>";
    echo $html;
}
