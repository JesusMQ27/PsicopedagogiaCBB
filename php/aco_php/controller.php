<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';

session_start();
$psi_usuario = $_SESSION["psi_user"]["id"];
define("p_usuario", $psi_usuario);

if (isset($_POST['opcion'])) {
    $opcion = $_POST['opcion'];
    $opcion();
}

function formulario_registro_nuevo_usuario() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "", "");
    $l_tipo_documentos = fnc_lista_tipo_documentos($conexion, "");
    $l_sedes = fnc_lista_sede($conexion, "");
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
    $usuario_dta = fnc_lista_usuarios($conexion, $eu_codi[0]);
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "", "");
    $l_tipo_documentos = fnc_lista_tipo_documentos($conexion, "");
    $l_sedes = fnc_lista_sede($conexion, "");
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
    $usuario_dta = fnc_lista_usuarios($conexion, $u_codiUsuIdEdi);
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
    $usuario_dta = fnc_lista_usuarios($conexion, $eu_codi[0]);
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
                    if ($array_da[0] === "0") {//nuevo registro perfil
                        $str_inserts .= "('" . $perf_codigo . "','" . $array_da[1] . "','1'),";
                    } else {//modificar perfil
                        $str_updates .= "" . $array_da[0] . ",";
                    }
                }
                $str_inserts = substr($str_inserts, 0, -1);
                $str_updates = substr($str_updates, 0, -1);
                if ($str_updates !== "") {
                    fnc_editar_accesos_perfil($conexion, $str_updates, $perf_codigo);
                    fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
                } else {
                    fnc_editar_accesos_perfil_todos($conexion, $str_updates, $perf_codigo);
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
    $html = "<input type='hidden' id='hdnNumeral' value='" . $codigo . "'><div class='col-md-12 table-responsive' id='divPreCargaAlumnos' >"
            . "<table id='tablePreCargaAlumnos' class='table' style='font-size: 13px;width:100% !important'>"
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
            $validacion = substr($validacion, 0, -1);
            $str_validacion = str_replace("*", "<br/>", $validacion);
        } else {
            $str_validacion = $validacion;
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
            . "<span class='text-bold text'>Alumnos nuevos:</span> <span class='badge bg-info'>" . $count1 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Alumnos ya registrados:</span><span class='badge bg-danger'>" . $count2 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Total de alumnos: </span><span class='badge bg-success'>" . (count($lista)) . "</span>";
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
                    $cadenaInsertAlumno = '("' . $value["dni"] . '","' . $value["nombres"] . '","' . $value["cod_alumno"] . '","' .
                            $value["nom_padre"] . '","' . $value["cor_padre"] . '","' . $value["cel_padre"] . '","' .
                            $value["nom_madre"] . '","' . $value["cor_madre"] . '","' . $value["cel_madre"] . '","' .
                            $value["apo_dir"] . '","' . $value["dis_apo"] . '","' . $value["cor_alu"] . '","1")';
                    if ($value["actual_alu_id"] == 0) {
                        $aluCodigo = fnc_registrar_data_tmp_a_alumno($conexion, $cadenaInsertAlumno);
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
            . "<table id='tableGrupoDetalle' class='table' style='font-size: 13px;width:100% !important'>"
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
            . "<table id='tablePreCargaUsuarios' class='table' style='font-size: 13px;width:100% !important'>"
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
            . "<span class='text-bold text'>Usuarios nuevos:</span> <span class='badge bg-info'>" . $count1 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Usuarios ya registrados:</span><span class='badge bg-danger'>" . $count2 . "</span>&nbsp;&nbsp;|&nbsp;&nbsp;"
            . "<span class='text-bold text'>Total de usuarios: </span><span class='badge bg-success'>" . (count($lista)) . "</span><br/>"
            . "<span><i class='nav-icon fa fa-info-circle' style='color: skyblue'></i> Nota: Solo se cargaran los usuarios que tienen correo electrónico.</span>&nbsp;&nbsp;|&nbsp;&nbsp;<span class='text-bold text'>Cantidad de usuarios con correos: </span><span class='badge bg-success'>" . ($count_correos) . "</span>";
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
            . "<table id='tableGrupoDetalleUsuarios' class='table' style='font-size: 13px;width:100% !important'>"
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
