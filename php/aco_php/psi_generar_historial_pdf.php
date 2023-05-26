
<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once '../aco_library/dompdf/autoload.inc.php';
session_start();

use Dompdf\Dompdf;

$con = new DB(1111);
$conexion = $con->connect();
$dompdf = new Dompdf();
$s_menu = strip_tags(trim($_GET["codi"]));
$sm_matricula = strip_tags(trim($_GET["matricula"]));
$s_historial = $_GET["historial"];
$s_campos = $_GET["campos"];
$array = explode("*", $sm_matricula);
$matricula = $array[0];
$alumno = $array[1];

$entre_sub = "";
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
            #tablaHistorica {
                border-collapse: collapse;
                margin: 25px 0;
                font-size: 0.9em;
                font-family: sans-serif;
                min-width: 400px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            }
            #tablaHistorica .tr{
                height: 60px;
                background-color: #3f62e1;
                color: #ffffff;
                text-align: left;
            }
                        </style>
        </head>
        <body>';
$nombre_pdf = "Historial_alumno";
$cadena_historial = "";
$cadena_h = "'" . str_replace(",", "','", $s_historial) . "'";
$s_campos = '1,2,' . $s_campos;
$lista_historial = fnc_historial_solicitudes_alumno($conexion, $cadena_h, $alumno, $s_campos);
$array_campos = explode(",", $s_campos);
if (count($lista_historial) > 0) {
    $li_alumno = fnc_datos_alumno($conexion, $alumno);
    $html .= '<div>
                <img src="../../php/aco_img/logo_2.png" width="128px" height="45px" style="float:left;position: relative;top: 0;left: 0;right: 0;margin: 0 auto;"/>
            </div>
            <div class="card-header" style="">
                <h4 style="text-align:center;text-decoration: underline">REPORTE HISTORIAL ALUMNO</h4>
              </div>
              <div class="card-body">';
    $html .= '<div class="row space-div">
                <table style="font-size:13px">
                    <tr>
                        <td><label>ALUMNO: </label></td>
                        <td><label>' . $li_alumno[0]["alumno"] . '</label></td>
                    </tr>
                    <tr>
                        <td><label>DNI: </label></td>
                        <td><label>' . $li_alumno[0]["dni"] . '</label></td>
                    </tr>
                 </table>';
    $html .= '</div>';
    $html .= '<div class="row space-div">
            <table id="tablaHistorica" style="font-size:9px">
                ';
    $aumentar = 1;
    foreach ($lista_historial as $lista) {
        if ($aumentar == 1) {
            $html .= '<tr class="tr">';
            for ($i = 0; $i < count($array_campos); $i++) {
                $html .= '<td>' . $lista["" . $array_campos[$i]] . '</td>';
            }
            $html .= '</tr>';
        } else {
            $html .= '<tr>';
            for ($i = 0; $i < count($array_campos); $i++) {
                $html .= '<td>' . $lista["" . $array_campos[$i]] . '</td>';
            }
            $html .= '</tr>';
        }
        $aumentar++;
    }
    $html .= '';
    $html .= '<tr>';
    $html .= '</tr>
            </table>
        </div>';
    $html .= '' . '</body>
</html>';
}
//echo $html;
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'lnandscape'); //vertical
$dompdf->set_paper("A4", "landscape"); //esta es otra forma de ponerlo horizontal
$dompdf->render();
$pdf_nombre = $nombre_pdf . "_" . fnc_generate_random_string(5) . ".pdf";
$dompdf->stream($pdf_nombre, array("Attachment" => 1));
