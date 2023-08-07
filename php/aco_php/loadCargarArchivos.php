<?php

//marita
session_start();
ini_set('memory_limit', '256M');
ini_set('max_execution_time', 300); //3 minutess
ini_set('post_max_size', '10M');
require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once '../aco_library/PHPExcel/PHPExcel.php';

$uploadDir = '../aco_archivos/';
// Allowed file types 
$allowTypes = array('xls', 'xlsx', 'doc', 'docx', 'pdf', 'jpg', 'pptx', 'ppt', 'png');

// Default response 
$response = array(
    'message' => '',
    'uploaded-image' => '',
    'class_name' => ''
);
$uploadStatus = 0;
if ($_FILES['select_file']['size'] > 10485760) {
    $response['message'] = 'Error el archivo cargado pesa (' . ($_FILES['select_file']['size'] / 1000000) . ' MB). El archivo no puede pesar mas de 10 MB.';
    $response['uploaded-image'] = '';
    $response['resp'] = '0';
    $response['class_name'] = 'alert alert-danger-color';
} else {
    if (isset($_FILES['select_file'])) {
        $con = new DB(1111);
        $conexion = $con->connect();
        $arreglo_data = array();
        $solicitud = $_POST["solicitud"];
        $sm_codigoEdi = strip_tags(trim($_POST["sm_codigo"]));
        $eu_solicitud = explode("-", $solicitud);
        $soli_codi = explode("/", $eu_solicitud[1]);
        $file_name = $_FILES['select_file']['name'];
        $file_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file_name);
        $file_size = $_FILES['select_file']['size'];
        $file_tmp = $_FILES['select_file']['tmp_name'];
        //$fileType = pathinfo($file_tmp, PATHINFO_EXTENSION);
        $fileType = pathinfo($_FILES['select_file']['name'], PATHINFO_EXTENSION);
        $tiempo = fnc_obtener_fecha_actual($conexion);
        $fecha = $tiempo[0]["fecha"];
        $fileName = $file_name . "_" . $fecha . "." . $fileType;
        $targetFilePath = $uploadDir . $fileName;
        // Allow certain file formats to upload}
        if (in_array($fileType, $allowTypes)) {
            // Upload file to the server 
            if (move_uploaded_file($file_tmp, $targetFilePath)) {
                $uploadedFile = $file_name;
                $archivoId = fnc_registrar_solicitudes_archivos($conexion, $soli_codi[0], $fileName, $fileType, '1');
                if ($archivoId) {
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
                    $sql_auditoria = fnc_registrar_solicitudes_archivos_auditoria($soli_codi[0], $fileName, $fileType, '1');
                    $sql_insert = ' "' . $str_menu_id . '", "' . $str_menu_nombre . '", "' . "loadCargarArchivos.php" . '", "' . "fnc_registrar_solicitudes_archivos" . '","' . $sql_auditoria . '","' . "INSERT" . '","' . "tb_solicitudes_archivos" . '","' . $_SESSION["psi_user"]["id"] . '",NOW(),"1"';
                    fnc_registrar_auditoria($conexion, $sql_insert);

                    $response['class_name'] = 'alert alert-success-color';
                    $response['message'] = "Se registro el archivo correctamente!";
                    $response['resp'] = '1';
                    $response['uploaded-image'] = '';
                } else {
                    $response['class_name'] = 'alert alert-danger-color';
                    $response['message'] = "Error al registrar archivo.";
                    $response['resp'] = '2';
                    $response['uploaded-image'] = '';
                }
            } else {
                $uploadStatus = 0;
                $response['message'] = 'Hubo un error al cargar el archivo ' . $fileType . "";
                $response['uploaded-image'] = '';
                $response['resp'] = '0';
                $response['class_name'] = 'alert alert-danger-color';
            }
        } else {
            $uploadStatus = 0;
            $response['message'] = 'Solamente archivos ' . implode('/', $allowTypes) . ' se pueden cargar.';
            $response['uploaded-image'] = '';
            $response['resp'] = '0';
            $response['class_name'] = 'alert alert-danger-color';
        }
    } else {
        $uploadStatus = 0;
        $response['message'] = 'Error al cargar archivo.';
        $response['uploaded-image'] = '';
        $response['resp'] = '0';
        $response['class_name'] = 'alert alert-danger-color';
    }
}
echo json_encode($response);
