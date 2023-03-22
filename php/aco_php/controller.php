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
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "");
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
    $l_tipo_usuarios = fnc_lista_tipo_usuarios($conexion, "");
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
                    $editar_usuario = fnc_editar_usuario($conexion, $u_codiUsuIdEdi, $u_tipoUsuarioEdi, $u_tipoDocEdi, $u_numDocEdi, strtoupper($u_paternoEdi), strtoupper($u_maternoEdi), strtoupper($u_nombresEdi), strtolower($u_correoEdi), $u_telefonoEdi, $u_sedeEdi, $u_sexoEdi, $u_estadoEdi);
                    if ($editar_usuario) {
                        echo "***1***Usuario editado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
                    } else {
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
    $eliminar_usuario = fnc_eliminar_usuario($conexion, $u_codiUsuIdEli);
    if ($eliminar_usuario) {
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
    } else {
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
    <?php
}

function proceso_registro_nuevo_menu() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $sm_codigoMenu = strip_tags(trim($_POST["sm_codigo"]));
    $m_descripcion = strip_tags(trim($_POST["m_descripcion"]));
    $m_imagen = strip_tags(trim($_POST["m_imagen"]));
    $m_codigo = substr($m_descripcion, 0, 5) . "_" . fnc_generate_random_string(5);
    $registrar_menu = fnc_registrar_menu($conexion, $m_codigo, strtoupper($m_descripcion), $m_imagen);
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
    $m_estadoMe = strip_tags(trim($_POST["m_estadoMeEdi"]));

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
    $registrar_menu = fnc_editar_menu($conexion, $m_codigoEdi, $strCodigo, strtoupper($m_descripcion), $m_imagen, $m_estadoMe);
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
        echo "***1***Menú editado correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        echo "***0***Error al editado menú.***<br/>";
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
    $eliminar_menu = fnc_eliminar_menu($conexion, $u_codiMenuIdEli);
    if ($eliminar_menu) {
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
    } else {
        echo "***0***Error al eliminar menú.***<br/>";
    }
}

function formulario_registro_nuevo_perfil() {
    $con = new DB(1111);
    $conexion = $con->connect();
    $l_sedes = fnc_lista_sede($conexion, "");
    ?>
    <div class="row space-div">
        <div class="col-md-6" style="margin-bottom: 0px;">
            <label>Descripci&oacute;n: </label>
        </div>
        <div class="col-md-6">
            <input type="text" id="txtPaterno" class="form-control" style="width: 100%;text-transform: uppercase;" onkeypress="return solo_letras(event);"/>
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
    <?php
}
