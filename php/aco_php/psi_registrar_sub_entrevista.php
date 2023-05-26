<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';

session_start();
$psi_usuario = $_SESSION["psi_user"]["id"];

$con = new DB(1111);
$conexion = $con->connect();
$sm_codigo = strip_tags(trim($_POST["sm_codigo"]));
$s_codi_entre_sub = strip_tags(trim($_POST["s_codi_entre_sub"]));
$s_docAlumno = strip_tags(trim($_POST["s_docAlumno"]));
$sol_matricu = strip_tags(trim($_POST["s_matricula"]));
$s_sede = strip_tags(trim($_POST["s_sede"]));
$codigo_usuario = $_SESSION["psi_user"]["id"];
$s_solicitud_tipo = strip_tags(trim($_POST["s_solicitud_tipo"]));
$s_categoria = strip_tags(trim($_POST["s_categoria"]));
$s_subcategoria = strip_tags(trim($_POST["s_subcategoria"]));
$s_sexo = strip_tags(trim($_POST["s_sexo"]));
$s_motivo = strip_tags(trim($_POST["s_motivo"]));
$s_planEstudiante = strip_tags(trim($_POST["s_planEstudiante"]));
$s_planEntrevistador = strip_tags(trim($_POST["s_planEntrevistador"]));
$s_acuerdos = strip_tags(trim($_POST["s_acuerdos"]));
$s_informe = strip_tags(trim($_POST["s_informe"]));
$s_planPadre = strip_tags(trim($_POST["s_planPadre"]));
$s_planDocente = strip_tags(trim($_POST["s_planDocente"]));
$s_acuerdosPadres = strip_tags(trim($_POST["s_acuerdosPadres"]));
$s_acuerdosColegio = strip_tags(trim($_POST["s_acuerdosColegio"]));
$s_apoderado = strip_tags(trim($_POST["s_apoderado"]));
$s_privacidad = strip_tags(trim($_POST["s_privacidad"]));
$s_img1 = $_POST['s_dataURL1_sub'];
$s_img2 = $_POST['s_dataURL2_sub'];
$s_hora_total = strip_tags(trim($_POST["s_hora_total_sub"]));
$arreglo_matricula = explode("*", $sol_matricu);
$sol_matricula = $arreglo_matricula[0];
$sol_alumno = $arreglo_matricula[1];
try {
    fnc_modificar_alumno_datos($conexion, $sol_alumno, $s_sexo);
    $sol_codigo = "sub_" . $s_docAlumno . "_" . fnc_generate_random_string(6);
    $cadena = "('" . $s_codi_entre_sub . "','" . $sol_codigo . "','" . $sol_matricula . "','" . $codigo_usuario . "','" . $s_solicitud_tipo . "','" . $s_subcategoria .
            "','" . $s_motivo . "',NOW(),'" . $s_sede . "','" . $s_planEstudiante . "','" . $s_planEntrevistador . "','"
            . $s_acuerdos . "','" . $s_informe . "','" . $s_planPadre . "','" . $s_planDocente . "','" . $s_acuerdosPadres . "','" . $s_acuerdosColegio . "','" . $s_apoderado . "','" . $s_privacidad . "','" . $s_hora_total . "','1')";
    $solicitud_id = fnc_registrar_sub_solicitud_estudiante($conexion, $cadena);
    if ($solicitud_id) {
        if (strpos($s_img1, 'data:image/png;base64') === 0) {
            $s_img1 = str_replace('data:image/png;base64,', '', $s_img1);
            $s_img1 = str_replace(' ', '+', $s_img1);
            $data = base64_decode($s_img1);
            if ($s_solicitud_tipo == "1") {
                $file = '../aco_firmas/img_' . $sol_matricula . "_" . uniqid() . '.png';
            } else {
                $file = '../aco_firmas/img_' . $s_apoderado . "_" . uniqid() . '.png';
            }
            if (file_put_contents($file, $data)) {
                $cadena_imag1 = "('" . $solicitud_id . "','" . $sol_matricula . "','" . $psi_usuario . "','" . $s_apoderado .
                        "','" . $file . "',NOW(),'1','1')";
                fnc_registrar_sub_solicitud_firmas($conexion, $cadena_imag1);
            } else {
                echo "***0***Error al registrar la imagen del entrevistado.***<br/>";
                exit();
            }
        }

        if (strpos($s_img2, 'data:image/png;base64') === 0) {
            $s_img2 = str_replace('data:image/png;base64,', '', $s_img2);
            $s_img2 = str_replace(' ', '+', $s_img2);
            $data2 = base64_decode($s_img2);
            $file2 = '../aco_firmas/img_' . $psi_usuario . "_" . uniqid() . '.png';

            if (file_put_contents($file2, $data2)) {
                $cadena_imag2 = "('" . $solicitud_id . "','" . $sol_matricula . "','" . $psi_usuario . "','" . $s_apoderado .
                        "','" . $file2 . "',NOW(),'2','1')";
                fnc_registrar_sub_solicitud_firmas($conexion, $cadena_imag2);
            } else {
                echo "***0***Error al registrar la imagen del entrevistador.***<br/>";
                exit();
            }
        }
    }
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
    echo "***1***Solicitud de Subentrevista registrada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
} catch (Exception $exc) {
    echo "***0***Error al registrar la solicitud.***<br/>";
}