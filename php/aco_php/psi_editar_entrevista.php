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
$s_codi_solicitud = strip_tags(trim($_POST["s_codi_solicitud"]));
$s_docAlumno = strip_tags(trim($_POST["s_docAlumno"]));
$sol_matricu = strip_tags(trim($_POST["s_matricula"]));
$s_sede = strip_tags(trim($_POST["s_sede"]));
$codigo_usuario = $_SESSION["psi_user"]["id"];
$s_solicitud_tipo = strip_tags(trim($_POST["s_solicitud_tipo"]));
$s_categoria = strip_tags(trim($_POST["s_categoria"]));
$s_subcategoria = strip_tags(trim($_POST["s_subcategoria"]));
$s_sexo_alu = strip_tags(trim($_POST["s_sexo_alu"]));
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
$s_img1 = $_POST['s_dataURL1'];
$s_img2 = $_POST['s_dataURL2'];
$s_img1_1 = trim($_POST["s_dataURL1_1"]);
$arreglo_matricula = explode("*", $sol_matricu);
$sol_matricula = $arreglo_matricula[0];
$sol_alumno = $arreglo_matricula[1];

try {
    $arreglo = explode("-", $s_codi_solicitud);
    $tipo = $arreglo[0];
    $id = $arreglo[1];

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

    $s_motivo = str_replace('"', "", $s_motivo);
    $s_planEstudiante = str_replace('"', "", $s_planEstudiante);
    $s_planEntrevistador = str_replace('"', "", $s_planEntrevistador);
    $s_acuerdos = str_replace('"', "", $s_acuerdos);
    $s_informe = str_replace('"', "", $s_informe);
    $s_planPadre = str_replace('"', "", $s_planPadre);
    $s_planDocente = str_replace('"', "", $s_planDocente);
    $s_acuerdosPadres = str_replace('"', "", $s_acuerdosPadres);
    $s_acuerdosColegio = str_replace('"', "", $s_acuerdosColegio);
    $s_apoderado = str_replace('"', "", $s_apoderado);

    if ($tipo === "ent") {//entrevista
        $modicar_datos = fnc_modificar_alumno_datos($conexion, $sol_alumno, $s_sexo_alu);
        if ($modicar_datos) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_modificar_alumno_datos_auditoria($sol_alumno, $s_sexo_alu);
                $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_alumno_datos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_alumno" . '","' . $psi_usuario . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql);
            }
        }

        $modificar = fnc_modificar_solicitud_entrevista($conexion, $id, $sol_matricula, $codigo_usuario, $s_solicitud_tipo, $s_subcategoria, $s_motivo, "NOW()", $s_sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $s_privacidad, '1');
        if ($modificar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_modificar_solicitud_entrevista_auditoria($id, $sol_matricula, $codigo_usuario, $s_solicitud_tipo, $s_subcategoria, $s_motivo, "NOW()", $s_sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $s_privacidad, '1');
                $sql_auditoria = str_replace("'", "", $sql_auditoria);
                $sql_auditoria = str_replace('"', "'", $sql_auditoria);
                $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_entrevista" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes" . '","' . $psi_usuario . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql);
            }
        }

        if ($s_img1_1 !== "") {
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
                    $busca_firma = fnc_buscar_solicitud_entrevista_firmas($conexion, $id, "1");
                    if (count($busca_firma) > 0) {
                        $modificar_firma1 = fnc_modificar_solicitud_entrevista_firmas($conexion, $id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, $file, "NOW()", "1");
                        if ($modificar_firma1) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_modificar_solicitud_entrevista_firmas_auditoria($id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, $file, "NOW()", "1");
                                $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql);
                            }
                        }
                    } else {
                        $cadena_imag1 = "('" . $id . "','" . $sol_matricula . "','" . $codigo_usuario . "','" . $s_apoderado .
                                "','" . $file . "',NOW(),'1','1')";
                        $registrar_firmas = fnc_registrar_solicitud_firmas($conexion, $cadena_imag1);
                        if ($registrar_firmas) {
                            if (count($submenu) > 0) {
                                $sql_auditoria = fnc_registrar_solicitud_firmas_auditoria($cadena_imag1);
                                $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_registrar_solicitud_firmas" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                                fnc_registrar_auditoria($conexion, $sql);
                            }
                        }
                    }
                } else {
                    echo "***0***Error al editar la imagen del entrevistado.***<br/>";
                    exit();
                }
            } else {
                $modificar_firma11 = fnc_modificar_solicitud_entrevista_firmas($conexion, $id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img1), "NOW()", "1");
                if ($modificar_firma11) {
                    if (count($submenu) > 0) {
                        $sql_auditoria = fnc_modificar_solicitud_entrevista_firmas_auditoria($id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img1), "NOW()", "1");
                        $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                        fnc_registrar_auditoria($conexion, $sql);
                    }
                }
            }
        }
        if (strpos($s_img2, 'data:image/png;base64') === 0) {
            $s_img2 = str_replace('data:image/png;base64,', '', $s_img2);
            $s_img2 = str_replace(' ', '+', $s_img2);
            $data2 = base64_decode($s_img2);
            $file2 = '../aco_firmas/img_' . $psi_usuario . "_" . uniqid() . '.png';

            if (file_put_contents($file2, $data2)) {
                $busca_firma2 = fnc_buscar_solicitud_entrevista_firmas($conexion, $id, "2");
                if (count($busca_firma2) > 0) {
                    $modificar_firma2 = fnc_modificar_solicitud_entrevista_firmas($conexion, $id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, $file2, "NOW()", "1");
                    if ($modificar_firma2) {
                        if (count($submenu) > 0) {
                            $sql_auditoria = fnc_modificar_solicitud_entrevista_firmas_auditoria($id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, $file, "NOW()", "1");
                            $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql);
                        }
                    }
                } else {
                    $cadena_imag2 = "('" . $id . "','" . $sol_matricula . "','" . $codigo_usuario . "','" . $s_apoderado .
                            "','" . $file2 . "',NOW(),'2','1')";
                    $registrar_firmas = fnc_registrar_solicitud_firmas($conexion, $cadena_imag2);
                    if ($registrar_firmas) {
                        if (count($submenu) > 0) {
                            $sql_auditoria = fnc_registrar_solicitud_firmas_auditoria($cadena_imag1);
                            $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_registrar_solicitud_firmas" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql);
                        }
                    }
                }
            } else {
                echo "***0***Error al editar la imagen del entrevistador.***<br/>";
                exit();
            }
        } else {

            $sql_auditoriam = fnc_modificar_solicitud_entrevista_firmas($conexion, $id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img2), "NOW()", "1");
            if ($sql_auditoriam) {
                if (count($submenu) > 0) {
                    $sql_auditoria = fnc_modificar_solicitud_entrevista_firmas_auditoria($id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img2), "NOW()", "1");
                    $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                    fnc_registrar_auditoria($conexion, $sql);
                }
            }
        }
    } elseif ($tipo === "sub") {//subentrevista
        $modicar_datos2 = fnc_modificar_alumno_datos($conexion, $sol_alumno, $s_sexo_alu);
        if ($modicar_datos2) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_modificar_alumno_datos_auditoria($sol_alumno, $s_sexo_alu);
                $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_alumno_datos" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_alumno" . '","' . $psi_usuario . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql);
            }
        }

        $modificar = fnc_modificar_solicitud_sub_entrevista($conexion, $id, $sol_matricula, $codigo_usuario, $s_solicitud_tipo, $s_subcategoria, $s_motivo, "NOW()", $s_sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $s_privacidad, '1');
        if ($modificar) {
            if (count($submenu) > 0) {
                $sql_auditoria = fnc_modificar_solicitud_sub_entrevista_auditoria($id, $sol_matricula, $codigo_usuario, $s_solicitud_tipo, $s_subcategoria, $s_motivo, "NOW()", $s_sede, $s_planEstudiante, $s_planEntrevistador, $s_acuerdos, $s_informe, $s_planPadre, $s_planDocente, $s_acuerdosPadres, $s_acuerdosColegio, $s_apoderado, $s_privacidad, '1');
                $sql_auditoria = str_replace("'", "", $sql_auditoria);
                $sql_auditoria = str_replace('"', "'", $sql_auditoria);
                $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_sub_entrevista" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes" . '","' . $psi_usuario . '",NOW(),"1"';
                fnc_registrar_auditoria($conexion, $sql);
            }
        }
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
                $busca_firma_1 = fnc_buscar_solicitud_sub_entrevista_firmas($conexion, $id, "1");
                if (count($busca_firma_1) > 0) {
                    $modificar_sub_firma = fnc_modificar_solicitud_sub_entrevista_firmas($conexion, $id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, $file, "NOW()", "1");
                    if ($modificar_sub_firma) {
                        if (count($submenu) > 0) {
                            $sql_auditoria = fnc_modificar_solicitud_sub_entrevista_firmas_auditoria($id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, $file, "NOW()", "1");
                            $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_sub_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql);
                        }
                    }
                } else {
                    $cadena_imag_1 = "('" . $id . "','" . $sol_matricula . "','" . $codigo_usuario . "','" . $s_apoderado .
                            "','" . $file . "',NOW(),'1','1')";
                    $registrar_sub_firmas = fnc_registrar_sub_solicitud_firmas($conexion, $cadena_imag_1);
                    if ($registrar_sub_firmas) {
                        if (count($submenu) > 0) {
                            $sql_auditoria = fnc_registrar_sub_solicitud_firmas_auditoria($cadena_imag_1);
                            $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_registrar_sub_solicitud_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql);
                        }
                    }
                }
            } else {
                echo "***0***Error al editar la imagen del entrevistado.***<br/>";
                exit();
            }
        } else {
            $modificar_sub_firma = fnc_modificar_solicitud_sub_entrevista_firmas($conexion, $id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img1), "NOW()", "1");
            if ($modificar_sub_firma) {
                if (count($submenu) > 0) {
                    $sql_auditoria = fnc_modificar_solicitud_sub_entrevista_firmas_auditoria($id, "1", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img1), "NOW()", "1");
                    $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_sub_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                    fnc_registrar_auditoria($conexion, $sql);
                }
            }
        }

        if (strpos($s_img2, 'data:image/png;base64') === 0) {
            $s_img2 = str_replace('data:image/png;base64,', '', $s_img2);
            $s_img2 = str_replace(' ', '+', $s_img2);
            $data2 = base64_decode($s_img2);
            $file2 = '../aco_firmas/img_' . $psi_usuario . "_" . uniqid() . '.png';

            if (file_put_contents($file2, $data2)) {
                $busca_firma_2 = fnc_buscar_solicitud_sub_entrevista_firmas($conexion, $id, "2");
                if (count($busca_firma_2) > 0) {
                    $modificar_sub_firma = fnc_modificar_solicitud_sub_entrevista_firmas($conexion, $id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, $file2, "NOW()", "1");
                    if ($modificar_sub_firma) {
                        if (count($submenu) > 0) {
                            $sql_auditoria = fnc_modificar_solicitud_sub_entrevista_firmas_auditoria($id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, $file2, "NOW()", "1");
                            $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_sub_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql);
                        }
                    }
                } else {
                    $cadena_imag_2 = "('" . $id . "','" . $sol_matricula . "','" . $codigo_usuario . "','" . $s_apoderado .
                            "','" . $file2 . "',NOW(),'2','1')";
                    $registrar_sub_firmas = fnc_registrar_sub_solicitud_firmas($conexion, $cadena_imag_2);
                    if ($registrar_sub_firmas) {
                        if (count($submenu) > 0) {
                            $sql_auditoria = fnc_registrar_sub_solicitud_firmas_auditoria($cadena_imag_2);
                            $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_registrar_sub_solicitud_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                            fnc_registrar_auditoria($conexion, $sql);
                        }
                    }
                }
            } else {
                echo "***0***Error al editar la imagen del entrevistador.***<br/>";
                exit();
            }
        } else {
            $modificar_sub_firma = fnc_modificar_solicitud_sub_entrevista_firmas($conexion, $id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img2), "NOW()", "1");
            if ($modificar_sub_firma) {
                if (count($submenu) > 0) {
                    $sql_auditoria = fnc_modificar_solicitud_sub_entrevista_firmas_auditoria($id, "2", $sol_matricula, $codigo_usuario, $s_apoderado, str_replace("./php/", "../", $s_img2), "NOW()", "1");
                    $sql = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "psi_editar_entrevista.php" . '", "' . "fnc_modificar_solicitud_sub_entrevista_firmas" . '","' . $sql_auditoria . '","' . "UPDATE" . '","' . "tb_sub_solicitudes_firmas" . '","' . $psi_usuario . '",NOW(),"1"';
                    fnc_registrar_auditoria($conexion, $sql);
                }
            }
        }
    }

    echo "***1***Solicitud de Entrevista editada correctamente." . "***" . $str_menu_id . "--" . $str_submenu . "--" . $str_menu_nombre . "";
} catch (Exception $exc) {
    echo "***0***Error al editada la solicitud.***<br/>";
}