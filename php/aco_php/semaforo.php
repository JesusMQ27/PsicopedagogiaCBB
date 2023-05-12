<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
$s_sede = strip_tags(trim($_GET["s_sede"]));
$fecha1 = strip_tags(trim($_GET["s_fecha_inicio"]));
$fecha2 = strip_tags(trim($_GET["s_fecha_fin"]));
$s_semaforo = strip_tags(trim($_GET["s_semaforo"]));
$draw = $_GET['draw'];
$row = $_GET['start'];
$lenght = $_GET['length'];
$fechaInicio = convertirFecha($fecha1);
$fechaFin = convertirFecha($fecha2);
$lista = fnc_buscar_semaforo_docentes($conexion, $s_sede, $fechaInicio, $fechaFin, $s_semaforo);
$html = "";
$aux = 1;
$data = array();
$totalRecords = 0;
$totalRecordwithFilter = 0;
if (count($lista) > 0) {
    foreach ($lista as $value) {
        if ($value["color"] == "Rojo") {
            $color = "color:red";
        } else if ($value["color"] == "Ambar") {
            $color = "color:#ff7e00 ";
        } else if ($value["color"] == "Verde") {
            $color = "color:green";
        }
        $data[] = array(
            "data1" => $aux,
            "data2" => $value["sede"],
            "data3" => $value["docente"],
            "data4" => $value["grado"],
            "data5" => $value["cantidad"],
            "data6" => $value["cantidad_faltantes"],
            "data7" => $value["cantidad_realizados"],
            "data8" => $value["porcentaje"],
            "data9" => "<i class='fas fa-circle nav-icon' style='font-size:23px;$color'></i>",
        );
        $aux++;
    }
    $totalRecords = count($lista);
    $totalRecordwithFilter = count($lista);
} else {
    $data = array();
    $totalRecords = 0;
    $totalRecordwithFilter = 0;
}
// Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
