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

if (isset($_FILES['select_fileAlumnos'])) {
    $arreglo_data = array();
    $file_name = $_FILES['select_fileAlumnos']['name'];
    $file_size = $_FILES['select_fileAlumnos']['size'];
    $file_tmp = $_FILES['select_fileAlumnos']['tmp_name'];
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
            $codigo = $_SESSION["psi_user"]["id"];
            $reader = PHPExcel_IOFactory::createReaderForFile($targetFilePath);
            $excel_Obj = $reader->load($targetFilePath);
            //Leer hoja 0
            $worksheet = $excel_Obj->getSheet('1');
            $highestRow = $worksheet->getHighestRow();
            $colomncount = $worksheet->getHighestDataColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($colomncount);
            $rows = array();
            $array_data = array();
            $number = 1;
            //Validar si existe error en la correlacion
            for ($row = 3; $row <= $highestRow; ++$row) {//lista de carga excel
                $tipoAlumnos = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $cargosGenerados = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $montoPagado = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $montoPendiente = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $online = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $fechaMatOnline = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $codAlumno = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $nombresEstudiante = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $grado = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $seccion = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                $sede = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                $dni = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                $nombrePadre = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                $correoPadre = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                $nombreMadre = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
                $correoMadre = $worksheet->getCellByColumnAndRow(15, $row)->getValue();
                $celularPadre = $worksheet->getCellByColumnAndRow(16, $row)->getValue();
                $celularMadre = $worksheet->getCellByColumnAndRow(17, $row)->getValue();
                $apoderadoDireccion = $worksheet->getCellByColumnAndRow(18, $row)->getValue();
                $distritoApoderado = $worksheet->getCellByColumnAndRow(19, $row)->getValue();
                $correoEstudiante = $worksheet->getCellByColumnAndRow(20, $row)->getValue();
                //agregarmos los alumnos
                if (trim($nombresEstudiante) !== "") {
                    array_push($array_data, [
                        "car_num" => $number,
                        "car_tip_alu" => $tipoAlumnos,
                        "car_car_gene" => $cargosGenerados,
                        "car_mon_pag" => $montoPagado,
                        "car_mon_pen" => $montoPendiente,
                        "car_online" => $online,
                        "car_cod_alumno" => $codAlumno,
                        "car_nombres" => $nombresEstudiante,
                        "car_grado" => $grado,
                        "car_seccion" => $seccion,
                        "car_sede" => $sede,
                        "car_dni" => $dni,
                        "car_nom_padre" => $nombrePadre,
                        "car_cor_padre" => $correoPadre,
                        "car_nom_madre" => $nombreMadre,
                        "car_cor_madre" => $correoMadre,
                        "car_cel_padre" => $celularPadre,
                        "car_cel_madre" => $celularMadre,
                        "car_apo_dir" => $apoderadoDireccion,
                        "car_dis_apo" => $distritoApoderado,
                        "car_cor_alu" => $correoEstudiante,
                        "car_estado" => '1']);
                }
                $number++;
            }
            // Empieza a registrar la informacion de alumnos
            fnc_drop_tabla_tmp_carga_alumnos($conexion, $codigo);
            fun_crear_tabla_tmp_carga_alumnos($conexion, $codigo);
            $cadena = "";
            $respuesta = "";
            if (count($array_data) > 0) {
                foreach ($array_data as $dato) {
                    $cadena .= '("' . $dato['car_num'] . '","' . $dato['car_tip_alu'] . '","' . $dato['car_car_gene'] . '","' . $dato['car_mon_pag'] . '","' . $dato['car_mon_pen'] . '","' .
                            $dato['car_online'] . '","' . $dato['car_cod_alumno'] . '","' . $dato['car_nombres'] . '","' . $dato['car_grado'] . '","' .
                            $dato['car_seccion'] . '","' . $dato['car_sede'] . '","' . $dato['car_dni'] . '","' . $dato['car_nom_padre'] . '","' .
                            $dato['car_cor_padre'] . '","' . $dato['car_nom_madre'] . '","' . $dato['car_cor_madre'] . '","' . $dato['car_cel_padre'] . '","' . $dato['car_cel_madre'] . '","' .
                            $dato['car_apo_dir'] . '","' . $dato['car_dis_apo'] . '","' . $dato['car_cor_alu'] . '","' . $dato['car_estado'] . '"),';
                }
                $cadena2 = substr($cadena, 0, -1);

                func_inserta_data_tabla_tmp_carga_alumnos($conexion, "tmp_cbb_carga_alumnos_" . $codigo, $cadena2);
            }
            if (trim($respuesta) === "") {
                $response['class_name'] = 'alert-success';
                $response['message'] = $respuesta;
                $response['resp'] = '1';
                $response['uploaded-image'] = '';
            } else {
                $response['class_name'] = 'alert-danger';
                $response['message'] = $respuesta;
                $response['resp'] = '2';
                $response['uploaded-image'] = '';
            }
        } else {
            $uploadStatus = 0;
            $response['message'] = 'Hubo un error al cargar el archivo ' . $fileType . "";
            $response['uploaded-image'] = '';
            $response['resp'] = '0';
            $response['class_name'] = 'alert-danger';
        }
    } else {
        $uploadStatus = 0;
        $response['message'] = 'Solamente archivos ' . implode('/', $allowTypes) . ' se pueden cargar.';
        $response['uploaded-image'] = '';
        $response['resp'] = '0';
        $response['class_name'] = 'alert-danger';
    }
} else {
    $uploadStatus = 0;
    $response['message'] = 'Error al cargar archivo.';
    $response['uploaded-image'] = '';
    $response['resp'] = '0';
    $response['class_name'] = 'alert-danger';
}
echo json_encode($response);
