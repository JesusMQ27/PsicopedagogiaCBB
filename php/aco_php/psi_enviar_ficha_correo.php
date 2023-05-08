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
require_once '../aco_library/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

session_start();
$con = new DB(1111);
$conexion = $con->connect();

$sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
$s_solicitud = strip_tags(trim($_POST["u_codiSolicitud"]));
$s_correos = $_POST["u_lista_correos"];
$array = explode("-", $s_solicitud);
$entre_sub = "";

if (count($s_correos) > 0) {
    $dompdf = new Dompdf();
    $html = '<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            .card-header:{
                background-color: #007bff;
                border-bottom: 1px solid rgba(0,0,0,.125);
                padding: 0.75rem 1.25rem;
                position: relative;
                border-top-left-radius: 0.25rem;
                border-top-right-radius: 0.25rem;
            }
            .label: {
                font-weight:bold;
            }
            </style>
        </head>
        <body>';
    $nombre_pdf = "";
    if ($array[0] === "ent") {
        $entre_sub = "Entrevista";
    } else {
        $entre_sub = "Sub Entrevista";
    }
    $lista_solicitud = fnc_obtener_solicitud_x_codigo($conexion, $array[0], $array[1]);

    if (count($lista_solicitud) > 0) {
        $tipos_entrevistas = fnc_lista_tipo_entrevistas($conexion, "");
        $lista_categorias = fnc_lista_categorias($conexion, $lista_solicitud[0]["categoria"]);
        $lista_subcategorias = fnc_lista_subcategorias($conexion, $lista_solicitud[0]["categoria"], $lista_solicitud[0]["subcategorgia"]);
        /*    $html .= '<div class="card card-warning" id="divSubEntrevista_edi">'; */
        if ($lista_solicitud[0]["ent_id"] === "1") {
            $nombre_pdf = "entrevista_estudiante";
            $html .= '<div>
                <img src="../../php/aco_img/logo_2.png" width="128px" height="45px" style="float:left;position: relative;top: 0;left: 0;right: 0;margin: 0 auto;"/>
            </div>
            <div class="card-header" style="">
                <h4 style="text-align:center;text-decoration: underline">FICHA DE ENTREVISTA A ESTUDIANTE</h4>
              </div>
              <div class="card-body">';
            $html .= '<br/><div class="row space-div">
            <table style="font-size:12px">
                <tr>
                    <td>C&oacute;digo de ' . $entre_sub . ': </td>
                    <td>' . $lista_solicitud[0]["codigo"] . '</td>
                </tr>
                <tr>
                    <td>Categoria: </td>
                    <td>';
            $html .= "<label>" . $lista_categorias[0]["nombre"] . "</label>";
            $html .= '</td>
                </tr>
                <tr>
                    <td>Subcategoria:  </td>
                    <td>';
            if (count($lista_subcategorias) > 0) {
                $selected_subcate = "";
                foreach ($lista_subcategorias as $lista) {
                    if ($lista["id"] === $lista_solicitud[0]["subcategorgia"]) {
                        $selected_subcate = $lista["nombre"];
                    }
                }
                $html .= "<label>" . $selected_subcate . "</label>";
            }
            $html .= '</td>
                </tr>
                <tr>
                    <td>Tipo de entrevista: </td>
                    <td>';
            if (count($tipos_entrevistas) > 0) {
                $selected_tips = "";
                foreach ($tipos_entrevistas as $lista) {
                    if ($lista["id"] === $lista_solicitud[0]["ent_id"]) {
                        $selected_tips = $lista["nombre"];
                    }
                }
                $html .= "<label>" . $selected_tips . "</label>";
            }
            $html .= '</td>
                </tr>
            </table>
        </div>';
            $html .= '<h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div">
                <table style="font-size:12px">
                    <tr>
                        <td><label>Nombre del estudiante: </label></td>
                        <td>' . strtoupper($lista_solicitud[0]["alumno"]) . '</td>
                    </tr>
                    <tr>
                        <td><label>Grado, sección y nivel: </label></td>
                        <td>' . $lista_solicitud[0]["grado"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Entrevistador: </label></td>
                        <td>' . $lista_solicitud[0]["usuario"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Sede: </label></td>
                        <td>' . $lista_solicitud[0]["sede"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Motivo de la entrevista: </label></td>
                        <td>' . $lista_solicitud[0]["motivo"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Fecha y hora: </label></td>
                        <td>' . $lista_solicitud[0]["fecha"] . '</td>
                    </tr>
                 </table>';
            $html .= '</div>
                <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
                <div class="row space-div">
                <table style="font-size:12px">
                    <tr>
                        <td><label>Planteamiento del estudiante: </label></td>
                        <td>' . $lista_solicitud[0]["plan_estudiante"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Planteamiento del entrevistador(a): </label></td>
                        <td>' . $lista_solicitud[0]["plan_entrevistador"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Acuerdos: </label></td>
                        <td>' . $lista_solicitud[0]["acuerdos"] . '</td>
                    </tr>
                </table>
              </div>'
                    . '<div class="row space-div">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $html .= '<table style="text-align:center">
                    <tr>
                        <td>
                            <img id="ruta_img1" src="' . "../../php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width:50%" />
                        </td>
                        <td>
                            <img id="ruta_img2" src="' . "../../php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width:50%"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px">------------------------------------------------------------------<br/>'
                    . str_replace(" - ", "<br/>", strtoupper($lista_solicitud[0]["alumno"])) .
                    '</td>
                        <td style="font-size:12px">------------------------------------------------------------------<br/>' .
                    strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"]
                    . '</td>
                    </tr>
                    </table>';
            $html .= ''
                    . '</div>';
        } elseif ($lista_solicitud[0]["ent_id"] === "2") {
            $nombre_pdf = "entrevista_padres";
            $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], "");
            $apoderado = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], $lista_solicitud[0]["apoderado"]);
            $html .= '<div>
                <img src="../../php/aco_img/logo_2.png" width="128px" height="45px" style="float:left;position: relative;top: 0;left: 0;right: 0;margin: 0 auto;"/>
            </div>
            <div class="card-header" style="">
                <h4 style="text-align:center;text-decoration: underline">FICHA DE ENTREVISTA A PADRES DE FAMILIA</h4>
              </div>
              <div class="card-body">';
            $html .= '<br/><div class="row space-div">
            <table style="font-size:12px">
                <tr>
                    <td>C&oacute;digo de ' . $entre_sub . ': </td>
                    <td>' . $lista_solicitud[0]["codigo"] . '</td>
                </tr>
                <tr>
                    <td>Categoria: </td>
                    <td>';
            $html .= "<label>" . $lista_categorias[0]["nombre"] . "</label>";
            $html .= '</td>
                </tr>
                <tr>
                    <td>Subcategoria:  </td>
                    <td>';
            if (count($lista_subcategorias) > 0) {
                $selected_subcate = "";
                foreach ($lista_subcategorias as $lista) {
                    if ($lista["id"] === $lista_solicitud[0]["subcategorgia"]) {
                        $selected_subcate = $lista["nombre"];
                    }
                    $html .= "<label>" . $selected_subcate . "</label>";
                }
            }
            $html .= '</td>
                </tr>
                <tr>
                    <td>Tipo de entrevista: </td>
                    <td>';
            if (count($tipos_entrevistas) > 0) {
                $selected_tips = "";
                foreach ($tipos_entrevistas as $lista) {
                    if ($lista["id"] === $lista_solicitud[0]["ent_id"]) {
                        $selected_tips = $lista["nombre"];
                    }
                    $html .= "<label>" . $selected_tips . "</label>";
                }
            }
            $html .= '</td>
                </tr>
            </table>
        </div>';
            $html .= '<h5>I. DATOS INFORMATIVOS:</h5>
                <div class="row space-div">
                <table style="font-size:12px">
                    <tr>
                        <td><label>Nombre del estudiante: </label></td>
                        <td>' . strtoupper($lista_solicitud[0]["alumno"]) . '</td>
                    </tr>
                    <tr>
                        <td><label>Grado, sección y nivel: </label></td>
                        <td>' . $lista_solicitud[0]["grado"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Nombre del padre/madre/apoderado: </label></td>
                        <td>';
            if (count($lista_apoderados) > 0) {
                $selected_apoderado = "";
                foreach ($lista_apoderados as $lista) {
                    if ($lista["codigo"] == $lista_solicitud[0]["apoderado"]) {
                        $selected_apoderado = $lista["tipo"] . ' - ' . $lista["dni"] . ' - ' . strtoupper($lista["nombre"]);
                    }
                    $html .= '<label> ' . $selected_apoderado . '' . '</label>';
                }
            }
            $html .= '</td>
                    </tr>
                    <tr>
                        <td><label>Correo: </label></td>
                        <td>' . $apoderado[0]["correo"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Teléfono: </label></td>
                        <td>' . $apoderado[0]["telefono"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Entrevistador: </label></td>
                        <td>' . strtoupper($lista_solicitud[0]["usuario"]) . '</td>
                    </tr>
                    <tr>
                        <td><label>Sede: </label></td>
                        <td>' . $lista_solicitud[0]["sede"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Motivo de la entrevista: </label></td>
                        <td>' . $lista_solicitud[0]["motivo"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Fecha y hora: </label></td>
                        <td>' . $lista_solicitud[0]["fecha"] . '</td>
                    </tr>
                 </table>
               </div>';

            $html .= '               
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div class="row space-div" style="font-size:12px">
                    <label>' . $lista_solicitud[0]["informe"] . '</label>
                </div>
                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div class="row space-div">
                  <table style="font-size:12px">
                    <tr>
                        <td><label>Planteamiento del padre, madre o apoderado: </label></td>
                        <td>' . $lista_solicitud[0]["plan_padre"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label></td>
                        <td>' . $lista_solicitud[0]["plan_docente"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Acuerdos - Acciones a realizar por los padres: </label></td>
                        <td>' . $lista_solicitud[0]["acuerdos1"] . '</td>
                    </tr>
                    <tr>
                        <td><label>Acuerdos - Acciones a realizar por el colegio: </label></td>
                        <td>' . $lista_solicitud[0]["acuerdos2"] . '</td>
                    </tr>
                </table>
              </div>';
            $html .= '<div class="row space-div">';
            $imagen_soli = "";
            if ($array[0] === "ent") {
                $imagen_soli = fnc_obtener_firma_entrevista($conexion, $array[1], "1");
            } else {
                $imagen_soli = fnc_obtener_firma_subentrevista($conexion, $array[1], "1");
            }
            $imagen_soli2 = "";
            if ($array[0] === "ent") {
                $imagen_soli2 = fnc_obtener_firma_entrevista($conexion, $array[1], "2");
            } else {
                $imagen_soli2 = fnc_obtener_firma_subentrevista($conexion, $array[1], "2");
            }
            $html .= '<table style="text-align:center">
                    <tr>
                        <td>
                            <img id="ruta_img1" src="' . "../../php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width:50%" />
                        </td>
                        <td>
                            <img id="ruta_img2" src="' . "../../php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width:50%"/>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size:12px">------------------------------------------------------------------<br/>'
                    . str_replace(" - ", "<br/>", strtoupper($lista_solicitud[0]["alumno"])) .
                    '</td>
                        <td style="font-size:12px">------------------------------------------------------------------<br/>' .
                    strtoupper($lista_solicitud[0]["usuario"]) . '<br/>' . $lista_solicitud[0]["dni"]
                    . '</td>
                    </tr>
                    </table>';
            $html .= ''
                    . '</div>';
        }
        //</div>
        $html .= ''
                . '</body>
</html>';
    }
//echo $html;
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'lnandscape');
    $dompdf->render();
    $pdf_nombre = $nombre_pdf . "_" . fnc_generate_random_string(5) . ".pdf";
    //$dompdf->stream($pdf_nombre, array("Attachment" => 1));

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

    $exito = 0;
    //aqui se envia al correo
    $mail = new PHPMailer(true);
    //Ingresando parametros
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->IsSMTP();
    $mail->Host = 'smtp.gmail.com'; //gmail SMTP server
    $mail->SMTPAuth = true;
    //$mail->SMTPDebug = 1;

    $mail->Username = "jesusmq2127@gmail.com";
    $mail->Password = fnc_contrasena_php_mailer();
    $mail->SMTPSecure = "tls";
    $mail->Port = 465; //SMTP port
    $mail->SMTPSecure = "ssl";

    for ($i = 0; $i < count($s_correos); $i++) {
        $array_cor = explode("**", $s_correos[$i]);
        // $mail->Username = 'salvaro@ich.edu.pe';
        //$mail->Password = "995131543";
        $mail->Subject = utf8_decode("Envio de solicitud - Sistema de acompañamiento al estudiante - SIAE");
        $mail->IsHTML(true);
        $mail->setFrom("soporteSistemaSIAE@cbb.edu.pe");
        $str_mensaje_correo = "Hola " . strtoupper($array_cor[2]) . " <br/><br/>Se ha enviado la entrevista.<br/><br/>"
                . "";
        $mail->Body = utf8_decode($str_mensaje_correo);
        $mail->AddStringAttachment($dompdf->output(), $pdf_nombre, 'base64', 'application/pdf');
        $mail->addAttachment('../aco_img/CBB.png');

        $correo_envio = $array_cor[1];
        $mail->addAddress($correo_envio);
        $exito = $mail->Send();
        $mail->clearAddresses();
        //$mail->addBCC('salvaro@ich.edu.pe');
    }

    $value = 0;
    if ($exito == 1) {
        //echo "Enviado:<br>";
        $value = 1;
        $resp_mensaje = "***1***Se ha enviado la solicitud al(a los) correo(s) electrónico(s) correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
    } else {
        //echo "Error<br>" . $mail->ErrorInfo;
        $value = 0;
        $resp_mensaje = "***0***Error al enviar correo.***<br/>";
    }
    $mail->smtpClose();
    echo $resp_mensaje;
} else {
    echo "***0***Error al enviar la solicitus por correo.***<br/>";
}