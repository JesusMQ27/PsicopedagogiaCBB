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

$p_correo = strip_tags(trim($_POST["psi_correo"]));
if (filter_var($p_correo, FILTER_VALIDATE_EMAIL)) {
    $con_e = fnc_existe_correo($conexion, $p_correo);
    if (count($con_e) > 0) {
        $token = fnc_generate_token_contrasena($conexion, $con_e[0]["usu_id"]);
        $ramdom1 = fnc_generate_random_string(8);
        $ramdom = $ramdom1 . "/" . $con_e[0]["usu_id"] . "-" . fnc_generate_random_string(7);
        $url = "http://" . $_SERVER["SERVER_NAME"] . "/CBB_sistema/login/cambia_pass.php?iden=" . $ramdom . "&token=" . $token;

        $str_mensaje = "Hola " . $con_e[0]["nombrecompleto"] . " <br/><br/>Se ha solicitado un reinicio de contraseña.<br/><br/>"
                . "Para restaurar la contraseña, visita la siguiente dirección: <a href='$url' style='color:#0051B5;cursor:pointer'>Cambiar contraseña</a><br/>";

        $mail = new PHPMailer(true);
        //Ingresando parametros
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com'; //gmail SMTP server
        $mail->SMTPAuth = true;
        //$mail->SMTPDebug = 1;
        $mail->IsHTML(true);
        $mail->Username = "jesusmq2127@gmail.com";
        $mail->Password = "bvymsayekgfgtekj";
        $mail->SMTPSecure = "tls";
        $mail->Port = 465; //SMTP port
        $mail->SMTPSecure = "ssl";

        // $mail->Username = 'salvaro@ich.edu.pe';
        //$mail->Password = "995131543";
        $mail->Subject = utf8_decode("Sistema de acompañamiento al estudiante - SIAE");
        $mail->setFrom("soporteSistemaSIAE@cbb.edu.pe");
        $mail->Body = utf8_decode($str_mensaje);
        $mail->addAttachment('../aco_img/CBB.png');
        $mail->addAddress($p_correo);
        //$mail->addBCC('salvaro@ich.edu.pe');
        $exito = $mail->Send();
        $value = 0;
        $resp_mensaje = "";
        if ($exito == 1) {
            //echo "Enviado:<br>";
            $value = 1;
            $resp_mensaje = "****1****Hemos enviado un correo electrónico a la dirección " . $p_correo . " para restablecer tu contraseña.";
        } else {
            //echo "Error<br>" . $mail->ErrorInfo;
            $value = 0;
            $resp_mensaje = "****0****Error al enviar correo.";
        }
        $mail->smtpClose();
        echo $resp_mensaje;
    } else {
        echo '****0****No existe el correo electrónico.';
    }
} else {
    echo '****0****Debe ingresar un correo electrónico válido.';
}
?>