<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once '../aco_library/PHPMailer/includes/Exception.php';
require_once '../aco_library/PHPMailer/includes/PHPMailer.php';
require_once '../aco_library/PHPMailer/includes/SMTP.php';

session_start();
$con = new DB(1111);
$conexion = $con->connect();
$sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
$u_codPerson = strip_tags(trim($_POST["u_codPerson"]));
$cadenaInsertGrupo = "";
$cadenaInsertUsuario = "";
$cadenaInsertDictado = "";
$str_usuarios = "";
$valueCount = 0;
try {
    $arreglo_usuarios = array();
    $usuario_array = array();
    $lista = func_lista_data_tabla_tmp_carga_usuarios_correos($conexion, $u_codPerson);
    if (count($lista) > 0) {
        $str_codigo = "Grupo" . "_" . fnc_generate_random_string(7);
        $cadenaInsertGrupo = "('" . $str_codigo . "','" . $u_codPerson . "',NOW(),'1')";
        $grupo_creado = fnc_registrar_grupo_usuario($conexion, $cadenaInsertGrupo);
        if ($grupo_creado) {
            foreach ($lista as $value) {
                $u_token = fnc_generate_token();
                $clave = fnc_generate_random_string(3) . $value["dni"] . fnc_generate_random_string(5);
                $u_clave = hash("sha256", md5($clave));
                $cadenaInsertUsuario = '("' . $value["tipDocId"] . '","' . $value["dni"] . '","' . $value["paterno"] . '","' .
                        $value["materno"] . '","' . $value["nombres"] . '","' . $value["correo"] . '","' .
                        $u_clave . '","' . $value["codigo_perfil"] . '",NOW(),"' . $u_token . '","1")';
                $str_usuarios .= $value["nombres"] . " " . $value["paterno"] . " " . $value["materno"] . "*" .
                        $value["dni"] . "*" .
                        $u_clave . "*" .
                        $value["correo"] . "/";
                if ($value["actual_usu_id"] == 0) {
                    $usuCodigo = fnc_registrar_data_tmp_a_usuario($conexion, $cadenaInsertUsuario);
                    if ($usuCodigo && trim($value["usu_seccion"]) == "") {
                        $arreglo_secciones = explode("-", $value["codigo_seccion"]);
                        for ($i = 0; $i < count($arreglo_secciones); $i++) {
                            $cadenaInsertDictado = "('" . $usuCodigo . "','" . $value["registrar_nivel_id"] . "','" . $value["registrar_plana_id"] . "','" .
                                    $value["fecha_ingreso"] . "','" . $grupo_creado . "','" . $value["codigo_sede"] . "','" . $arreglo_secciones[$i] . "','1')";
                            fnc_registrar_usuario_dictado($conexion, $cadenaInsertDictado);
                        }
                    }
                } else {
                    if (trim($value["validar_dictado"]) == "") {
                        $arreglo_secciones = explode("-", $value["codigo_seccion"]);
                        for ($i = 0; $i < count($arreglo_secciones); $i++) {
                            $cadenaInsertDictado = "('" . $value["actual_usu_id"] . "','" . $value["registrar_nivel_id"] . "','" . $value["registrar_plana_id"] . "','" .
                                    $value["fecha_ingreso"] . "','" . $grupo_creado . "','" . $value["codigo_sede"] . "','" . $arreglo_secciones[$i] . "','1')";
                            fnc_registrar_usuario_dictado($conexion, $cadenaInsertDictado);
                        }
                    }
                }
            }
        }
        $str_usuarios = substr($str_usuarios, 0, -1);
        $arreglo_usuarios = explode("/", $str_usuarios);
        if (count($arreglo_usuarios) > 0) {
            //aqui se envia al correo
            $mail = new PHPMailer(true);
            //Ingresando parametros
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->IsSMTP();
            $mail->Host = 'smtp.gmail.com'; //gmail SMTP server
            $mail->SMTPAuth = true;
            //$mail->SMTPDebug = 1;
            $mail->IsHTML(true);
            $mail->Username = "jesusmq2127@gmail.com";
            $mail->Password = fnc_contrasena_php_mailer();
            $mail->SMTPSecure = "tls";
            $mail->Port = 465; //SMTP port
            $mail->SMTPSecure = "ssl";

            // $mail->Username = 'salvaro@ich.edu.pe';
            //$mail->Password = "995131543";
            $mail->Subject = utf8_decode("Registro de usuario - Sistema de acompañamiento al estudiante - SIAE");
            $mail->setFrom("soporteSistemaSIAE@cbb.edu.pe");
            $mail->addAttachment('../aco_img/CBB.png');
            $url_inicio = "http://" . $_SERVER["SERVER_NAME"] . "/SistSIAE/index.php";

            for ($i = 0; $i < count($arreglo_usuarios); $i++) {
                $usuario_array = explode("*", $arreglo_usuarios[$i]);
                $str_mensaje_correo = "Hola " . $usuario_array[0] . " " . " <br/><br/>Tu registro en el Sistema Integral de Acompañamiento al Estudiante - SIAE ha sido exitoso.<br/><br/>"
                        . "Ahora puedes iniciar sesión con las siguientes credenciales:<br/><br/>"
                        . "Nombre de usuario: " . $usuario_array[1] . "<br/>"
                        . "Contraseña: " . $usuario_array[2] . "<br/><br/>"
                        . "También puedes ingresar haciendo clic en este enlace <a href='$url_inicio' style='color:#0051B5;cursor:pointer'>Iniciar Sesión</a>";
                $mail->Body = utf8_decode($str_mensaje_correo);
                $mail->addAddress($usuario_array[3]);
                $valueCount++;
            }
            $exito = $mail->Send();
            if ($exito == 1) {
                $valueCount = $valueCount + 0;
            } else {
                $valueCount = 0;
            }
            $mail->smtpClose();
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
    if ($valueCount == count($arreglo_usuarios)) {
        //echo "Enviado:<br>";
        echo "***1***Usuarios cargados correctamente y se envió sus credenciales a sus correos respectivos." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        //echo "Error<br>" . $mail->ErrorInfo;
        echo "***0***Usuarios cargados correctamente pero hubo errores en envio de correo.***<br/>";
    }
} catch (Exception $exc) {
    echo "***0***Error al cargar usuarios.***<br/>";
}