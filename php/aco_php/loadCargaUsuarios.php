<?php

session_start();
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300); //3 minutess
require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once '../aco_library/PHPExcel/PHPExcel.php';
$uploadDir = '../aco_uploads/';
// Allowed file types 
$allowTypes = array('xls', 'xlsx');

// Default response 
$response = array(
    'message' => '',
    'uploaded-image' => '',
    'class_name' => ''
);
$uploadStatus = 0;
if (isset($_FILES['select_fileUsuarios'])) {
    $arreglo_data = array();
    $file_name = $_FILES['select_fileUsuarios']['name'];
    $file_size = $_FILES['select_fileUsuarios']['size'];
    $file_tmp = $_FILES['select_fileUsuarios']['tmp_name'];
    //$fileType = pathinfo($file_tmp, PATHINFO_EXTENSION);
    $targetFilePath = $uploadDir . $file_name;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    // Allow certain file formats to upload}
    if (in_array($fileType, $allowTypes)) {
        // Upload file to the server 
        if (move_uploaded_file($file_tmp, $targetFilePath)) {
            $uploadedFile = $file_name;
            $con = new DB(1111);
            $conexion = $con->connect();
            $lista_sede = fnc_lista_sede($conexion, "");
            $lista_perfiles = fnc_lista_perfiles($conexion, "");
            $lista_secciones = fnc_lista_secciones_grados($conexion, "0", "0");
            $codigo = $_SESSION["psi_user"]["id"];
            $reader = PHPExcel_IOFactory::createReaderForFile($targetFilePath);
            $excel_Obj = $reader->load($targetFilePath);
            //Leer hoja 0
            $worksheet = $excel_Obj->getSheet('0');
            $highestRow = $worksheet->getHighestRow();
            $colomncount = $worksheet->getHighestDataColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($colomncount);
            $rows = array();
            $array_data = array();
            $number = 1;
            //Validar si existe error en la correlacion
            for ($row = 7; $row <= $highestRow; ++$row) {//lista de carga excel
                /* $tipoUsuarios = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                  $tipoDocumento = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                  $nroDocumento = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                  $apellidoPaterno = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                  $apellidoMaterno = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                  $nombres = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                  $correo = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                  $nivel = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                  $plana = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                  $data = $worksheet->getCellByColumnAndRow(9, $row); */
                $tipoUsuarios = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $tipoDocumento = "DNI";
                $nroDocumento = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $sede = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $apellidoPaterno = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $apellidoMaterno = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $nombres = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $correo = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $nivel = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $plana = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                //$data = $worksheet->getCellByColumnAndRow(9, $row);
                $fechaIngreso = $worksheet->getCellByColumnAndRow(6, $row);
                $seccion = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $horas = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                $codigo_perfil = array();
                $codigo_sede = array();
                $codigo_perfil = fnc_find_array("descr", $tipoUsuarios, $lista_perfiles);
                $codigo_sede = fnc_find_array("descr", $sede, $lista_sede);
                if (!strtotime($fechaIngreso)) {
                    if (PHPExcel_Shared_Date::isDateTime($fechaIngreso)) {
                        $cellValue = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $dateValue = PHPExcel_Shared_Date::ExcelToPHP($cellValue);
                        $fechaIngreso = date('Y-m-d', $dateValue);
                    } else {
                        $fechaIngreso = "";
                    }
                }
                $seccion_codigos = "";
                if (trim($seccion) !== "") {
                    $arreglo_seccion = explode("-", $seccion);
                    if (count($arreglo_seccion) > 1) {
                        for ($a = 0; $a < count($arreglo_seccion); $a++) {
                            $get_seccion = fnc_find_array("descr", $arreglo_seccion[$a], $lista_secciones);
                            $seccion_codigos .= $get_seccion["codigo"] . "-";
                        }
                        $seccion_codigos = substr($seccion_codigos, 0, -1);
                    } else {
                        $get_seccion = fnc_find_array("descr", $seccion, $lista_secciones);
                        $seccion_codigos = $get_seccion["codigo"];
                    }
                }
                //agregarmos los usuarios
                if (trim($tipoDocumento) !== "") {
                    array_push($array_data, [
                        "usu_num" => $number,
                        "usu_tip_usu" => $tipoUsuarios,
                        "usu_tip_doc" => $tipoDocumento,
                        "usu_num_doc" => $nroDocumento,
                        "usu_ape_pat" => $apellidoPaterno,
                        "usu_ape_mat" => $apellidoMaterno,
                        "usu_nom" => $nombres,
                        "usu_cor" => $correo,
                        "usu_niv" => $nivel,
                        "usu_pla" => $plana,
                        "usu_fec_ing" => $fechaIngreso,
                        "usu_seccion" => $seccion,
                        "usu_horas" => $horas,
                        "usu_perfil_codigo" => $codigo_perfil["id"],
                        "usu_sede_codigo" => $codigo_sede["id"],
                        "usu_seccion_codigos" => $seccion_codigos,
                        "usu_estado" => '1']);
                }
                $number++;
            }
            // Empieza a registrar la informacion de usuarios
            fnc_drop_tabla_tmp_carga_usuarios($conexion, $codigo);
            fun_crear_tabla_tmp_carga_usuarios($conexion, $codigo);
            $cadena = "";
            $respuesta = "";
            if (count($array_data) > 0) {
                foreach ($array_data as $dato) {
                    $cadena .= '("' . $dato['usu_num'] . '","' . $dato['usu_tip_usu'] . '","' . $dato['usu_tip_doc'] . '","' . $dato['usu_num_doc'] . '","' . $dato['usu_ape_pat'] . '","' .
                            $dato['usu_ape_mat'] . '","' . $dato['usu_nom'] . '","' . $dato['usu_cor'] . '","' . $dato['usu_niv'] . '","' .
                            $dato['usu_pla'] . '","' . $dato['usu_fec_ing'] . '","' . $dato['usu_seccion'] . '","' . $dato['usu_horas'] . '","' . $dato["usu_perfil_codigo"] . '","' . $dato["usu_sede_codigo"] . '","' . $dato["usu_seccion_codigos"] . '","' . $dato['usu_estado'] . '"),';
                }
                $cadena2 = substr($cadena, 0, -1);

                func_inserta_data_tabla_tmp_carga_usuarios($conexion, "tmp_cbb_carga_usuarios_" . $codigo, $cadena2);
            }
            if ($respuesta === "") {
                $response['class_name'] = 'alert-success';
                $response['message'] = $respuesta;
                $response['resp'] = '1';
                $response['uploaded-image'] = '';
            } else {
                $response['class_name'] = 'alert-danger-color';
                $response['message'] = $respuesta;
                $response['resp'] = '2';
                $response['uploaded-image'] = '';
            }
        } else {
            $uploadStatus = 0;
            $response['message'] = 'Hubo un error al cargar el archivo ' . $fileType . "";
            $response['uploaded-image'] = '';
            $response['resp'] = '0';
            $response['class_name'] = 'alert-danger-color';
        }
    } else {
        $uploadStatus = 0;
        $response['message'] = 'Solamente archivos ' . implode('/', $allowTypes) . ' se pueden cargar.';
        $response['uploaded-image'] = '';
        $response['resp'] = '0';
        $response['class_name'] = 'alert-danger-color';
    }
} else {
    $uploadStatus = 0;
    $response['message'] = 'Error al cargar archivo.';
    $response['uploaded-image'] = '';
    $response['resp'] = '0';
    $response['class_name'] = 'alert-danger-color';
}
echo json_encode($response);
