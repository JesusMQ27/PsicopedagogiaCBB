
<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once '../aco_library/dompdf/autoload.inc.php';
session_start();

use Dompdf\Dompdf;

$con = new DB(1111);
$conexion = $con->connect();
$dompdf = new Dompdf();
$s_solicitud = $_GET['val'];
$eu_codgrupo = explode("-", $s_solicitud);
$solicitud = explode("/", $eu_codgrupo[1]);
$solicitud_data = fnc_solicitud_alumno($conexion, $solicitud[0]);
$str_html = "";
$html = "";
$html2 = "";
$nombre_pdf = "";
if (count($solicitud_data) > 0) {
    $html = "
<style> 
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        border-radius: 0.25rem;
    }
    .card-header{
        border-bottom: 1px solid rgba(0,0,0,.125);padding: 0.15rem 0.30rem;
        position: relative;border-top-left-radius: 0.25rem;
        border-top-right-radius: 0.25rem;background-color: #007bff;color: #fff;
    }
    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }
    .space-div {
        padding-bottom: 7px;
    }
    .col-md-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
    }
    .col-md-9 {
        -ms-flex: 0 0 75%;
        flex: 0 0 75%;
        max-width: 75%;
    }
    label {
        margin-bottom: 0.5rem;
        font-weight: 700;
    }
</style> ";


    $html .= '<div >
        <div >
           <h4 class="card-title w-100" style="text-align: center;">DETALLE DE SOLICITUD DE ENTREVISTA</h4>
        </div>
        <div>
            <label>Tipo de Solicitud: </label>' . $solicitud_data[0]["entrevista"] . '
        </div><br>' .
            '<div >
            <label>Categoria: </label><span>' . $solicitud_data[0]["categoria"] . '</span>
       </div><br>
       <div>
            <label>Subcategoria: </label><span>' . $solicitud_data[0]["subcategoria"] . '</span>
       </div><br>';
    $html .= '<div class="card card-primary">';
    if ($solicitud_data[0]["entreId"] === "1") {
        $nombre_pdf = "entrevista_estudiante";
        $html2 = '<div class="card-header">
                <h3 >FICHA DE ENTREVISTA A ESTUDIANTE</h3>
              </div><br/><br/>
              <div ><br/>
                <h5>I. DATOS INFORMATIVOS:</h5>
                    <div>
                        <label>Nombre del estudiante: </label><span>' . $solicitud_data[0]["alumno"] . '</span>
                    </div><br>
                    <div>
                        <label>Grado, sección y nivel: </label>
                    <span>' . $solicitud_data[0]["grado"] . '</span>
                    </div><br>
                    <div >
                        <label>Entrevistador: </label><span>' . $solicitud_data[0]["usuario"] . '</span>
                    </div><br>
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Sede: </label><span>' . $solicitud_data[0]["sede"] . '</span>
                    </div><br>
                    <div >
                        <label>Motivo de la entrevista: </label><span>' . $solicitud_data[0]["motivo"] . '</span>
                    </div><br>
                    <div >
                        <label>Fecha y hora: </label><span>' . $solicitud_data[0]["fecha"] . '</span>
                    </div>
                <h5>II. DESARROLLO DE LA ENTREVISTA:</h5>
                <div >
                    <div >
                        <label>Planteamiento del estudiante: </label><span>' . $solicitud_data[0]["plan_estu"] . '</span></div>
                </div><br>
                <div >
                    <div >
                        <label>Planteamiento del entrevistador(a): </label><span>' . $solicitud_data[0]["plan_entre"] . '</span></div>
                </div><br>
                <div>
                    <div >
                        <label>Acuerdos: </label><span>' . $solicitud_data[0]["acuerdos"] . '</span></div>
                </div>
              </div>';
    } elseif ($solicitud_data[0]["entreId"] === "2") {
        $nombre_pdf = "entrevista_padres";
        $html2 = '<div class="card-header">
                <h3 >FICHA DE ENTREVISTA A PADRES DE FAMILIA</h3>
              </div><br/><br/>
              <div><br/><br/><br/>
                <h5>I. DATOS INFORMATIVOS:</h5>
                <div>
                     <label>Nombre del estudiante: </label><span>' . $solicitud_data[0]["alumno"] . '</span>
                </div><br>
                <div>
                     <label>Grado, sección y nivel: </label><span>' . $solicitud_data[0]["grado"] . '</span>
                </div><br>
                <div>
                    <label>Nombre del padre/madre/apoderado: </label>
                    <span>';
        if (trim($solicitud_data[0]["padre"]) === '' && trim($solicitud_data[0]["madre"]) === "") {
            $html2 .= '';
        } else if (trim($solicitud_data[0]["padre"]) === '' && trim($solicitud_data[0]["madre"]) != "") {
            $html2 .= trim($solicitud_data[0]["padre"]) . trim($solicitud_data[0]["madre"]);
        } else {
            $html2 .= trim($solicitud_data[0]["padre"]) . "<br>" . trim($solicitud_data[0]["madre"]);
        }
        $html2 .= '</span>
                   </div><br>
                    <div>
                        <label>Teléfono, correo: </label><span>';
        if (trim($solicitud_data[0]["data_padre"]) === '' && trim($solicitud_data[0]["data_madre"]) === "") {
            $html2 .= '';
        } else if (trim($solicitud_data[0]["data_padre"]) === '' && trim($solicitud_data[0]["data_madre"]) != "") {
            $html2 .= trim($solicitud_data[0]["data_padre"]) . trim($solicitud_data[0]["data_madre"]);
        } else {
            $html2 .= trim($solicitud_data[0]["data_padre"]) . "<br>" . trim($solicitud_data[0]["data_madre"]);
        }
        $html2 .= '</span>
                </div><br>
                <div >
                     <label>Entrevistador: </label><span>' . $solicitud_data[0]["usuario"] . '</span>
                </div><br>
                <div>
                     <label>Sede: </label><span>' . $solicitud_data[0]["sede"] . '</span>
                </div><br>
                <div>
                     <label>Motivo de la entrevista: </label><span>' . $solicitud_data[0]["motivo"] . '</span>'
                . '</div><br>';
        $html2 .= '<div >
                        <label>Fecha y hora: </label><span>' . $solicitud_data[0]["fecha"] . '</span>
                </div>
                <h5>II. INFORME QUE SE LE HARÁ LLEGAR AL PADRE/MADRE/APODERADO:</h5>
                <div>
                    <div ><span>' . $solicitud_data[0]["informe"] . '</span>
                </div>
                <h5>III. DESARROLLO DE LA ENTREVISTA::</h5>
                <div>
                     <label>Planteamiento del padre, madre o apoderado: </label><span >' . $solicitud_data[0]["plan_padre"] . '</span>
                </div><br>
                <div >
                     <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label><span>' . $solicitud_data[0]["plan_docen"] . '</span>
                </div><br>
                <div >
                     <label>Acuerdos - Acciones a realizar por los padres: </label><span>' . $solicitud_data[0]["acuerdos1"] . '</span>
                </div><br>
                <div>
                    <label>Acuerdos - Acciones a realizar por el colegio: </label><span>' . $solicitud_data[0]["acuerdos2"] . '</span>
                </div>
              </div>';
    }
}
$str_html = $html . $html2 . "</div>";
//echo $str_html;
$dompdf->loadHtml($str_html);
$dompdf->setPaper('A4', 'lnandscape');
$dompdf->render();
$pdf_nombre = $nombre_pdf . "_" . fnc_generate_random_string(5) . ".pdf";
$dompdf->stream($pdf_nombre);
