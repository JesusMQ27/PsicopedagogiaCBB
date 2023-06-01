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

$sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
$u_tipoUsuario = strip_tags(trim($_POST["u_tipoUsuario"]));
$u_tipoDoc = strip_tags(trim($_POST["u_tipoDoc"]));
$u_numDoc = strip_tags(trim($_POST["u_numDoc"]));
$u_paterno = strip_tags(trim($_POST["u_paterno"]));
$u_materno = strip_tags(trim($_POST["u_materno"]));
$u_nombres = strip_tags(trim($_POST["u_nombres"]));
$u_correo = strip_tags(trim($_POST["u_correo"]));
$u_telefono = strip_tags(trim($_POST["u_telefono"]));
$u_sede = strip_tags(trim($_POST["u_sede"]));
$u_sexo = strip_tags(trim($_POST["u_sexo"]));
$valicant_ndoc = fnc_lista_tipo_documentos($conexion, $u_tipoDoc);
$vali_ndoc = fnc_validar_existe_nro_documento($conexion, $u_tipoDoc, $u_numDoc);
$vali_correo = fnc_validar_exite_correo($conexion, $u_correo);
$str_mensaje = "";

if (count($valicant_ndoc) > 0) {
    if ($valicant_ndoc[0]["cantidad"] * 1 !== strlen($u_numDoc) * 1) {
        echo "***0***La cantidad de dígitos para el tipo de documento " . $valicant_ndoc[0]["nombre"] . " debe ser " . $valicant_ndoc[0]["cantidad"] . ".<br/>";
    } else {
        if (count($vali_ndoc) > 0) {
            echo "***0***El número de documento ya esta registrado, favor de ingresar otro.<br/>";
        } else {
            if (count($vali_correo) > 0) {
                echo "***0***El correo electrónico ya esta registrado, favor de ingresar otro.<br/>";
            } else {
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
                $u_token = fnc_generate_token();
                $clave = fnc_generate_random_string(3) . $u_numDoc . fnc_generate_random_string(5);
                $u_clave = hash("sha256", md5($clave));
                $registrar_usuario = fnc_registrar_nuevo_usuario($conexion, $u_tipoUsuario, $u_tipoDoc, $u_numDoc, strtoupper($u_paterno), strtoupper($u_materno), strtoupper($u_nombres), strtolower($u_correo), $u_clave, $u_telefono, $u_sede, $u_sexo, $u_token);
                if ($registrar_usuario) {
                    $url_inicio = fnc_obtener_url_sistema();

                    $str_mensaje_correo = "Hola " . $u_nombres . " " . $u_paterno . " " . $u_materno . " <br/><br/>Tu registro en el Sistema Integral de Acompañamiento al Estudiante - SIAE ha sido exitoso.<br/><br/>"
                            . "Ahora puedes iniciar sesión con las siguientes credenciales:<br/><br/>"
                            . "Nombre de usuario: " . $u_numDoc . "<br/>"
                            . "Contraseña: " . $clave . "<br/><br/>"
                            . "También puedes ingresar haciendo clic en este enlace <a href='$url_inicio' style='color:#0051B5;cursor:pointer'>Iniciar Sesión</a>";
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
                    $mail->Body = utf8_decode($str_mensaje_correo);
                    $mail->addAttachment('../aco_img/CBB.png');
                    $mail->addAddress($u_correo);
                    //$mail->addBCC('salvaro@ich.edu.pe');
                    $exito = $mail->Send();
                    $value = 0;
                    if ($exito == 1) {
                        //echo "Enviado:<br>";
                        $value = 1;
                        $resp_mensaje = "***1***Hemos enviado un correo electrónico a la dirección " . $u_correo . " con tus credenciales.***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
                    } else {
                        //echo "Error<br>" . $mail->ErrorInfo;
                        $value = 0;
                        $resp_mensaje = "***0***Error al enviar correo.***";
                    }
                    $mail->smtpClose();
                    echo $resp_mensaje;
                } else {
                    echo "***0***Error al registrar nuevo usuario.***<br/>";
                }
            }
        }
    }
} else {
    echo "***0***Error al verificar dígitos del número de documento.***<br/>";
}
