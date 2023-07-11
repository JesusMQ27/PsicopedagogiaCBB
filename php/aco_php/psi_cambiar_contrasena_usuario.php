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
$u_hdnCodiUsua = strip_tags(trim($_POST["u_hdnCodiUsuaCam"]));
$usuario_dta = fnc_lista_usuarios($conexion, $u_hdnCodiUsua, "");
if (count($usuario_dta) > 0) {
    $u_numDocumento = $usuario_dta[0]["numDoc"];
    $clave = fnc_generate_random_string(3) . $u_numDocumento . fnc_generate_random_string(5);
    $u_clave = hash("sha256", md5($clave));
    $u_correo = $usuario_dta[0]["correo"];
    $u_nombreCompleto = $usuario_dta[0]["nombrecompleto"];

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
    $cambiar_contra = fnc_cambiar_contrasena_usuario($conexion, $u_hdnCodiUsua, $usuario_dta[0]["token"], $u_clave);
    if ($cambiar_contra) {
        $sql_auditoria = fnc_cambiar_contrasena_usuario_auditoria($u_hdnCodiUsua, $usuario_dta[0]["token"], $u_clave);
        $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_cambiar_contrasena_usuario.php" . '", "' . "fnc_cambiar_contrasena_usuario" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_usuario" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
        fnc_registrar_auditoria($conexion, $sql_insert);
        
        $url_inicio = fnc_obtener_url_sistema();
        $str_mensaje_correo = "Hola " . $u_nombreCompleto . " <br/><br/>Se ha realizado un reiniciado de tu contraseña para el acceso al Sistema Integral de Acompañamiento al Estudiante - SIAE.<br/><br/>"
                . "Ahora puedes iniciar sesión con las siguientes credenciales:<br/><br/>"
                . "Nombre de usuario: " . $u_numDocumento . "<br/>"
                . "Contraseña: " . $clave . "<br/><br/>"
                . "Y puedes ingresar haciendo clic en este enlace <a href='$url_inicio' style='color:#0051B5;cursor:pointer'>Iniciar Sesión</a>";

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

        $mail->Subject = utf8_decode("Reinicio de contraseña - Sistema de acompañamiento al estudiante - SIAE");
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
            $resp_mensaje = "***1***Se ha enviado un correo electrónico a la dirección " . $u_correo . " con las credenciales de " . $u_nombreCompleto . "" . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
        } else {
            //echo "Error<br>" . $mail->ErrorInfo;
            $value = 0;
            $resp_mensaje = "***0***Error al enviar correo.***<br/>";
        }
        $mail->smtpClose();
        echo $resp_mensaje;
    } else {
        echo "***0***Error al cambiar la contraseña del Cliente.***<br/>";
    }
} else {
    echo "***0***Error al cambiar la contraseña del Cliente.***<br/>";
}