
<?php

require_once '../../php/aco_conect/DB.php';
require_once '../../php/aco_fun/aco_fun.php';
require_once '../aco_library/dompdf/autoload.inc.php';
session_start();

use Dompdf\Dompdf;

$con = new DB(1111);
$conexion = $con->connect();
$dompdf = new Dompdf();
$s_solicitud = strip_tags(trim($_GET["sol_cod"]));
$array = explode("-", $s_solicitud);
$entre_sub = "";
$html = '<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
             header {
                position: fixed;
                top: -30px;
                left: 0px;
                right: 0px;
                height: 45px;
                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                color: white;
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed; 
                bottom: 0px; 
                left: 0px; 
                right: 0px;
                height: 230px; 

                /** Extra personal styles **/
                /*background-color: #03a9f4;*/
                /*color: white;*/
                text-align: center;
                line-height: 35px;
            }
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
            main{
                position: relative;
                top: 20px;
                bottom: 0px; 
                left: 0px; 
                right: 0px;
                page-break-after: always;
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
    if ($lista_solicitud[0]["ent_id"] === "1") {
        $nombre_pdf = "entrevista_estudiante";
        $html .= '<header>
                <div style="">
                    <img src="../../php/aco_img/logo_2.png" width="128px" height="45px" style="float:left;position: relative;top: 0;left: 0;right: 0;margin: 0 auto;"/>
                </div>
            </header>';
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


        $html .= '<main>
              <div class="card-header" style="">
                <h4 style="text-align:center;text-decoration: underline">FICHA DE ENTREVISTA A ESTUDIANTE</h4>
              </div>
              <div class="card-body">';
        $html .= '<div class="row space-div">
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
                <div class="row space-div" style="font-size:12px">
                    <label>Planteamiento del estudiante: </label>
                </div>';
        $html .= '<div class="row space-div" style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["plan_estudiante"] . '</div><br/>
                <div class="row space-div" style="font-size:12px">
                    <label>Planteamiento del entrevistador(a): </label>
                </div>
                <div class="row space-div" style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["plan_entrevistador"] . '</div><br/>
                <div class="row space-div" style="font-size:12px">
                    <label>Acuerdos: </label>
                </div>
                <span style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["acuerdos"] . '</span>'
                . '</main>';
        $html .= ''
                . '';
        $html .= '<footer><table style="text-align:center">
                    <tr>
                        <td>
                            <img id="ruta_imgprint1" src="' . "../../php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width:50%" />
                        </td>
                        <td>
                            <img id="ruta_imgprint2" src="' . "../../php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width:50%"/>
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
                    </table></footer>';
    } elseif ($lista_solicitud[0]["ent_id"] === "2") {
        $nombre_pdf = "entrevista_padres";
        $lista_apoderados = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], "");
        $apoderado = fnc_lista_apoderados_de_alumno($conexion, $lista_solicitud[0]["aluId"], $lista_solicitud[0]["apoderado"]);
        $html .= '<header>
                <div>
                    <img src="../../php/aco_img/logo_2.png" width="128px" height="45px" style="float:left;position: relative;top: 0;left: 0;right: 0;margin: 0 auto;"/>
                </div>
            </header>
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
                <div class="row space-div" style="font-size:12px">
                    <label>Planteamiento del padre, madre o apoderado: </label>
                </div>
                <div class="row space-div" style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["plan_padre"] . '</div><br/>
                <div class="row space-div" style="font-size:12px">
                    <label>Planteamiento del docente, tutor/a, psicólogo(a), director(a): </label>
                </div>
                <div class="row space-div" style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["plan_docente"] . '</div><br/>
                <div class="row space-div" style="font-size:12px">
                    <label>Acuerdos - Acciones a realizar por los padres: </label>
                </div>
                <div class="row space-div" style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["acuerdos1"] . '</div><br/>
                <div class="row space-div" style="font-size:12px">
                    <label>Acuerdos - Acciones a realizar por el colegio: </label>
                </div>
                <div class="row space-div" style="font-size:12px;text-align: justify;text-justify: inter-word;">' . $lista_solicitud[0]["acuerdos2"] . '</div>
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
        $html .= '<footer><table style="text-align:center">
                    <tr>
                        <td>
                            <img id="ruta_imgprint1" src="' . "../../php/" . str_replace("../", "", $imagen_soli[0]["imagen"]) . '" style="width:50%" />
                        </td>
                        <td>
                            <img id="ruta_imgprint2" src="' . "../../php/" . str_replace("../", "", $imagen_soli2[0]["imagen"]) . '" style="width:50%"/>
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
                    </table></footer>';
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
$dompdf->stream($pdf_nombre, array("Attachment" => 1));
