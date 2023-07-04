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
$s_usuario = strip_tags(trim($_POST["s_codigo"]));
$s_contraNueva = strip_tags(trim($_POST["s_contraNueva"]));
$s_contraConfirmar = strip_tags(trim($_POST["s_contraConfirmar"]));
$usuario_data = fnc_usuario_datos($conexion, $s_usuario);
if (count($usuario_data) > 0) {
    fnc_modificar_usuario_pass($conexion, $s_usuario);
    $nueva_contraseña = hash("sha256", md5($s_contraNueva));
    $insertar = fnc_insertar_usuario_pass($conexion, $s_usuario, $usuario_data[0]["clave"], $s_contraNueva, $nueva_contraseña);
    if ($insertar) {
        $u_numDocumento = $usuario_dta[0]["numDoc"];
        $clave = $s_contraNueva;
        $u_clave = $nueva_contraseña;
        $u_correo = $usuario_data[0]["correo"];
        $u_nombreCompleto = $usuario_data[0]["nombrecompleto"];

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
        $url_inicio = fnc_obtener_url_sistema();
        $str_mensaje_correo = "Hola " . $u_nombreCompleto . " <br/><br/>Se ha realizado el cambio de tu contraseña para el acceso al Sistema Integral de Acompañamiento al Estudiante - SIAE.<br/><br/>"
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

        // $mail->Username = 'salvaro@ich.edu.pe';
        //$mail->Password = "995131543";
        $mail->Subject = utf8_decode("Cambio de contraseña - Sistema de acompañamiento al estudiante - SIAE");
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