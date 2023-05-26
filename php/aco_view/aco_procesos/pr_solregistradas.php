<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
session_start();
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$codigo_user = $_SESSION["psi_user"]["id"];
$userData = fnc_datos_usuario($conexion, $codigo_user);
$sedesData = fnc_sedes_x_perfil($conexion, $userData[0]["sedeId"]);
$perfil = $userData[0]["perfil"];
$fechas = fnc_fechas_rango($conexion);
$sedeCodi = "";
$usuarioCodi = "";
$privacidad = "";
$grados = "";
if ($userData[0]["sedeId"] == "1" && ($perfil == "1" || $perfil == "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "";
    $privacidad = "0,1";
    $grados = "";
} else {
    $privacidad = "0";
    if ($perfil === "1" || $perfil === "5") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "";
        $grados = "";
        $privacidad = "0,1";
    } elseif ($perfil === "2") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = $codigo_user;
        $lista_grados = fnc_secciones_por_usuario($conexion, $codigo_user);
        if (count($lista_grados) > 0) {
            $grados = $lista_grados[0]["seccion"];
        } else {
            $grados = "";
        }
    } else {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "";
        $grados = "";
    }
}

$lista_solicitudes = fnc_lista_solicitudes($conexion, $sedeCodi, $fechas[0]["date_ayer"], $fechas[0]["date_hoy"], $usuarioCodi, $privacidad, $grados);
?>

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo $nombre; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Principal</a></li>
                    <li class="breadcrumb-item active"><?php echo $nombre; ?></li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content">
    <div class="container-fluid">
        <!-- COLOR PALETTE -->
        <div class="card card-default color-palette-box">
            <div class="card-body">
                <div class="row">
                    <input type="hidden" id="hdnCodiSR" value="<?php echo $codigo; ?>"/>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Sede: </label>
                        </div>

                        <select id="cbbSedes" data-show-content="true" class="form-control" style="width: 100%">
                            <?php
                            if (count($sedesData) > 1) {
                                ?>
                                <option value="1">-- Todos --</option>
                                <?php
                                $selectemenu = "";

                                foreach ($sedesData as $sedes) {
                                    echo "<option value='" . $sedes["id"] . "' >" . $sedes["nombre"] . "</option>";
                                }
                                ?>
                                <?php
                            } elseif (count($sedesData) === 1) {
                                echo "<option value='" . $sedesData[0]["id"] . "' selected>" . $sedesData[0]["nombre"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Fecha Inicio: </label>
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>&nbsp;
                            <input type="text" class="form-control pull-right" id="fecha1" value="<?php echo $fechas[0]["ayer"]; ?>" readonly >
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Fecha Fin: </label>
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>&nbsp;
                            <input type="text" class="form-control pull-right" id="fecha2" value="<?php echo $fechas[0]["hoy"]; ?>" readonly >
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-4 col-sm-6 col-12">
                        <button class="btn btn-success" id="btnBuscarSolicitudes" style="bottom: 0px;margin-top: 30px" 
                                onclick="buscar_entrevistas();">
                            <i class="fa fa-search"></i>
                            Buscar</button>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <button class="btn btn-primary" id="btnNuevoSolicitud" style="bottom: 0px;margin-top: 30px" 
                                data-toggle='modal' data-target='#modal-nueva-solicitud' data-backdrop='static'>
                            <i class="fa fa-list-alt"></i>
                            Nueva Entrevista</button>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-12" id="divSolicitudesRegistradas">
                        <table id="tableSolicitudesRegistradas" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th>Sede</th>
                                    <th>Fecha</th>
                                    <th>Grado</th>
                                    <th>Nro. documento</th>
                                    <th>Alumno</th>
                                    <th>Tipo de entrevista</th>
                                    <?php
                                    if ($perfil == "1" || $perfil == "5") {
                                        ?>
                                        <th>Privacidad</th>
                                        <?php
                                    }
                                    ?>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th>Opci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 13px">
                                <?php
                                $html = "";
                                $num = 1;
                                foreach ($lista_solicitudes as $lista) {
                                    $solicitudCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                                    if (strlen($lista["motivo"]) > 30) {
                                        $str_motivo = substr($lista["motivo"], 0, 30) . "...";
                                    } else {
                                        $str_motivo = $lista["motivo"];
                                    }
                                    $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["sede"] . "</td>
                                        <td>" . $lista["fecha"] . "</td>
                                        <td>" . $lista["grado"] . "</td>
                                        <td>" . $lista["nroDocumento"] . "</td>
                                        <td style='width:250px'>" . $lista["alumno"] . "</td>
                                        <td>" . $lista["entrevista"] . "</td>";
                                    if ($perfil == "1" || $perfil == "5") {
                                        $html .= "<td>" . $lista["privacidad"] . "</td>";
                                    }
                                    $html .= "<td style='width:100px'>" . $str_motivo . "</td>";
                                    $html .= "<td>" . $lista["estado"] . "</td>
                                        <td align='center' style='width:150px'>"
                                            . "<i class='nav-icon fas fa-plus green' title='Nueva Subentrevista' data-toggle='modal' data-target='#modal-subentrevista' data-backdrop='static' data-entrevista='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-file-pdf rojo' title='Descargar' data-toggle='modal' data-target='#modal-descargar' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-info-circle celeste' title='Detalle' data-toggle='modal' data-target='#modal-detalle-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "' data-grupo_nombre='" . $lista["id"] . "'></i>&nbsp;&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
                                    if ($perfil === "1" || $perfil === "5") {
                                        $html .= "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;";
                                    }
                                    $html .= "<i class='nav-icon fas fa-paper-plane azul' title='Enviar al correo' data-toggle='modal' data-target='#modal-enviar-solicitud' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;" .
                                            "</td>"
                                            . "</tr>";
                                    $num++;
                                }
                                echo $html;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-nueva-solicitud" role="dialog" aria-hidden="true" aria-labelledby="modal-nueva-solicitud">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Solicitud de Entrevista</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return reiniciar_cronometro()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="return reiniciar_cronometro()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnRegistrarSolicitud" class="btn btn-primary swalDefaultError"
                            onclick="return registrar_solicitud()">Registrar</button>
                </div>
            </div>
            <div style="float:left;position:absolute;" class="row" onLoad="">
                <div class="col-xs-3" style="position: fixed;bottom: 10px;right: 10px;bottom: 60px;text-align: right;padding: 5px 10px;" id="cronometro">
                    <div id="fecha" style="display: none;">
                        <span id="hora"></span> <span id="puntos1">:</span> <span id="minuto"></span> <span id="puntos2">:</span> <span id="segundo"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-apoderado" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-apoderado">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Apoderado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_editar_apoderado()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_editar_apoderado()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnEditarApoderado" class="btn btn-primary swalDefaultError" 
                            onclick="return editar_apoderado()">Editar</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-nuevo-apoderado" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-apoderado">    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Apoderado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_nuevo_apoderado()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_nuevo_apoderado()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnNuevoApoderado" class="btn btn-primary swalDefaultError" 
                            onclick="return registrar_nuevo_apoderado()">Registrar apoderado</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-subentrevista" role="dialog" aria-hidden="true" aria-labelledby="modal-subentrevista">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Solicitud de Subentrevista</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="return reiniciar_cronometro_2()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="return reiniciar_cronometro_2()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnRegistrarSubSolicitud" class="btn btn-success swalDefaultError"
                            onclick="return registrar_sub_solicitud()">Registrar</button>
                </div>
            </div>
            <div style="float:left;position:absolute;" class="row" onLoad="">
                <div class="col-xs-3" style="position: fixed;bottom: 10px;right: 10px;bottom: 60px;text-align: right;padding: 5px 10px;" id="cronometro_s">
                    <div id="fecha_s" style="display: none;">
                        <span id="hora_s"></span> <span id="puntos1_s">:</span> <span id="minuto_s"></span> <span id="puntos2_s">:</span> <span id="segundo_s"></span>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-editar-apoderado-sub" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-apoderado-sub">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Apoderado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_editar_apoderado_sub()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_editar_apoderado_sub()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnEditarApoderadoSub" class="btn btn-primary swalDefaultError" 
                            onclick="return editar_apoderado_sub()">Editar</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-nuevo-apoderado-sub" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-apoderado-sub">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Apoderado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_nuevo_apoderado_sub()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_nuevo_apoderado_sub()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnNuevoApoderadoSub" class="btn btn-primary swalDefaultError" 
                            onclick="return registrar_nuevo_apoderado_sub()">Registrar apoderado</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-detalle-solicitud-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-detalle-solicitud-alumno">
    <div class="modal-dialog" style="max-width: 80%;
         ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Detalle de solicitud </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <!--<button type="button" id="btnImprimirSolicitud" class="btn btn-primary swalDefaultError" 
                            onclick="return imprimir_ficha_entrevista();"
                            >Imprimir</button>-->
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-solicitud-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-solicitud-alumno">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnEditarSolicitud" class="btn btn-primary swalDefaultError"
                            onclick="return editar_solicitud()">Editar</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modal-editar-apoderado-edi" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-apoderado-edi">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Apoderado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_editar_apoderado_edi()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_editar_apoderado_edi()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnEditarApoderadoEdi" class="btn btn-primary swalDefaultError" 
                            onclick="return editar_apoderado_edi()">Editar</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-nuevo-apoderado-edi" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-apoderado-edi">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo Apoderado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_nuevo_apoderado_edi()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_nuevo_apoderado_edi()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnNuevoApoderadoEdi" class="btn btn-primary swalDefaultError" 
                            onclick="return registrar_nuevo_apoderado_edi()">Registrar apoderado</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-eliminar-solicitud-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-solicitud-alumno">
    <div class="modal-dialog" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar solicitud</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnEliminarSolicitudAlumno" class="btn btn-primary swalDefaultError" onclick="return eliminar_solicitud()">Eliminar solicitud</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-descargar" role="dialog" aria-hidden="true" aria-labelledby="modal-descargar">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Descargar PDF</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div style="float: right">
                    <label></label>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-enviar-solicitud" role="dialog" aria-hidden="true" aria-labelledby="modal-enviar-solicitud">
    <div class="modal-dialog" style="max-width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar al correo</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnEnviarSolicitudAlumno" class="btn btn-primary swalDefaultError" onclick="return enviar_solicitud()">Enviar solicitud</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function () {
        /*var conteo = new Date(timeLimit * 60000);
         var estado = 0;
         //var intervalo = window.setInterval(mostrar_hora, 1); // Frecuencia de actualización
         intervaloRegresivo = setInterval(regresiva, 1000, conteo);
         var i = 0; // Esta variable me ayudará a definir los estados de intervalo
         document.getElementById('hora').style.color = "black";
         document.getElementById('minuto').style.color = "black";
         document.getElementById('segundo').style.color = "black";
         document.getElementById('puntos1').style.color = "black";
         document.getElementById('puntos2').style.color = "black";
         document.getElementById('hora').classList.remove("parpadea");
         document.getElementById('minuto').classList.remove("parpadea");
         document.getElementById('segundo').classList.remove("parpadea");
         document.getElementById('puntos1').classList.remove("parpadea");
         document.getElementById('puntos2').classList.remove("parpadea");*/

        function regresiva(conteo, estado) {
            if (conteo.getTime() > 0 && estado === 0) {
                conteo.setTime(conteo.getTime() - 1000);
            } else if (conteo.getTime() === 0) {
                estado = 1;
                //clearInterval(intervaloRegresivo);
                //alert("Fin");
            }
            var minutos = (conteo.getMinutes() + '').length;
            var segundo = (conteo.getSeconds() + '').length;
            document.getElementById('hora').innerHTML = '00';

            // Inserta los minutos almacenados en clock en el span con id minuto
            if (minutos === 1) {
                document.getElementById('minuto').innerHTML = '0' + conteo.getMinutes();
            } else {
                document.getElementById('minuto').innerHTML = conteo.getMinutes();
            }

            // Inserta los segundos almacenados en clock en el span con id segundo
            if (segundo === 1) {
                document.getElementById('segundo').innerHTML = '0' + conteo.getSeconds();
            } else {
                document.getElementById('segundo').innerHTML = conteo.getSeconds();
            }
            if (estado === 0) {
                if (conteo.getMinutes() < 5 && conteo.getSeconds() <= 59) {
                    document.getElementById('hora').style.color = "#F61406";
                    document.getElementById('minuto').style.color = "#F61406";
                    document.getElementById('segundo').style.color = "#F61406";
                    document.getElementById('puntos1').style.color = "#F61406";
                    document.getElementById('puntos2').style.color = "#F61406";
                }
            } else if (estado === 1) {
                conteo.setTime(conteo.getTime() + 1000);
                document.getElementById('hora').classList.add("parpadea");
                document.getElementById('minuto').classList.add("parpadea");
                document.getElementById('segundo').classList.add("parpadea");
                document.getElementById('puntos1').classList.add("parpadea");
                document.getElementById('puntos2').classList.add("parpadea");
            }
        }


        /*var conteo_s = new Date(timeLimitSub * 60000);
         var estado_s = 0;
         //var intervalo = window.setInterval(mostrar_hora_s, 1); // Frecuencia de actualización
         intervaloRegresivo_s = setInterval(regresiva_s, 1000, conteo_s, estado_s);
         var i = 0; // Esta variable me ayudará a definir los estados de intervalo
         document.getElementById('hora_s').style.color = "black";
         document.getElementById('minuto_s').style.color = "black";
         document.getElementById('segundo_s').style.color = "black";
         document.getElementById('puntos1_s').style.color = "black";
         document.getElementById('puntos2_s').style.color = "black";
         document.getElementById('hora_s').classList.remove("parpadea");
         document.getElementById('minuto_s').classList.remove("parpadea");
         document.getElementById('segundo_s').classList.remove("parpadea");
         document.getElementById('puntos1_s').classList.remove("parpadea");
         document.getElementById('puntos2_s').classList.remove("parpadea");*/

        function regresiva_s(conteo_s, estado_s) {
            if (conteo_s.getTime() > 0 && estado_s === 0) {
                conteo_s.setTime(conteo_s.getTime() - 1000);
            } else if (conteo_s.getTime() === 0) {
                estado_s = 1;
                //clearInterval(intervaloRegresivo);
                //alert("Fin");
            }
            var minutos_s = (conteo_s.getMinutes() + '').length;
            var segundo_s = (conteo_s.getSeconds() + '').length;
            document.getElementById('hora_s').innerHTML = '00';

            // Inserta los minutos almacenados en clock en el span con id minuto
            if (minutos_s === 1) {
                document.getElementById('minuto_s').innerHTML = '0' + conteo_s.getMinutes();
            } else {
                document.getElementById('minuto_s').innerHTML = conteo_s.getMinutes();
            }

            // Inserta los segundos almacenados en clock en el span con id segundo
            if (segundo_s === 1) {
                document.getElementById('segundo_s').innerHTML = '0' + conteo_s.getSeconds();
            } else {
                document.getElementById('segundo_s').innerHTML = conteo_s.getSeconds();
            }
            if (estado_s === 0) {
                if (conteo_s.getMinutes() < 5 && conteo_s.getSeconds() <= 59) {
                    document.getElementById('hora_s').style.color = "#F61406";
                    document.getElementById('minuto_s').style.color = "#F61406";
                    document.getElementById('segundo_s').style.color = "#F61406";
                    document.getElementById('puntos1_s').style.color = "#F61406";
                    document.getElementById('puntos2_s').style.color = "#F61406";
                }
            } else if (estado_s === 1) {
                conteo_s.setTime(conteo_s.getTime() + 1000);
                document.getElementById('hora_s').classList.add("parpadea_s");
                document.getElementById('minuto_s').classList.add("parpadea_s");
                document.getElementById('segundo_s').classList.add("parpadea_s");
                document.getElementById('puntos1_s').classList.add("parpadea_s");
                document.getElementById('puntos2_s').classList.add("parpadea_s");
            }
        }

        $("#fecha1").daterangepicker({
            autoApply: true,
            showButtonPanel: false,
            singleDatePicker: true,
            showDropdowns: true,
            linkedCalendar: false,
            autoUpdateInput: false,
            showCustomRangeLabel: false,
            locale: {
                format: "DD/MM/YYYY"
            }
        }, function (start) {

        }).on('apply.daterangepicker', function (ev, start) {
            $("#fecha1").val(start.endDate.format('DD/MM/YYYY'));
            var fechaConcar1 = $("#fecha1").val();
            var array_fecha1 = fechaConcar1.split("/");
            var day_1 = parseInt(array_fecha1[0]);
            var month_1 = parseInt(array_fecha1[1]);
            var year_1 = parseInt(array_fecha1[2]);
            var str_msj = "";
            var str_can = "";
            if (month_1 == 12) {
                var lastDate = new Date(year_1, month_1 + 1, 0);
                var lastDay = lastDate.getDate();
                str_can = lastDay - day_1;
                str_msj = "day";
            } else {
                str_can = 12;
                str_msj = "month";
            }

            $("#fecha2").daterangepicker({
                autoApply: true,
                singleDatePicker: true,
                showDropdowns: true,
                linkedCalendar: false,
                autoUpdateInput: false,
                showCustomRangeLabel: false,
                starDate: start.endDate.format("DD/MM/YYYY"),
                minDate: start.endDate.format("DD/MM/YYYY"),
                maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),
                locale: {
                    format: "DD/MM/YYYY"
                }
            }, function (start, end, label) {
            }).on('apply.daterangepicker', function (ev, start) {
                $("#fecha2").val(start.endDate.format("DD/MM/YYYY"));
            });
        });
        $("#fecha1").on('click mousedown ', function () {
            $(".calendar-table").find('.today').removeClass("active start-date active end-date");
        });
        //$("#fecha1").click();

        /* Modales de Solicitudes */
        $('#modal-nueva-solicitud').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            timeLimit = 40; //tiempo en minutos
            var conteo2 = new Date(timeLimit * 60000);
            var estado2 = 0;
            document.getElementById('hora').classList.remove("parpadea");
            document.getElementById('minuto').classList.remove("parpadea");
            document.getElementById('segundo').classList.remove("parpadea");
            document.getElementById('puntos1').classList.remove("parpadea");
            document.getElementById('puntos2').classList.remove("parpadea");
            document.getElementById('hora').innerHTML = '';
            document.getElementById('minuto').innerHTML = '';
            document.getElementById('segundo').innerHTML = '';
            setTimeout(function () {
                $("#fecha").show();
            }, 1000);
            clearInterval(intervaloRegresivo);
            intervaloRegresivo = setInterval(regresiva, 1000, conteo2, estado2);
            mostrar_nueva_solicitud(modal);
        });
        $('#modal-editar-apoderado').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var alumno = button.data('alumno');
            var info_apoderado = button.data('info-apoderado');
            mostrar_editar_apoderado(modal, alumno, info_apoderado);
        });

        $('#modal-subentrevista').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var entrevista = button.data('entrevista');
            timeLimitSub = 40; //tiempo en minutos
            var conteo_s = new Date(timeLimitSub * 60000);
            var estado_s = 0;
            document.getElementById('hora_s').classList.remove("parpadea_s");
            document.getElementById('minuto_s').classList.remove("parpadea_s");
            document.getElementById('segundo_s').classList.remove("parpadea_s");
            document.getElementById('puntos1_s').classList.remove("parpadea_s");
            document.getElementById('puntos2_s').classList.remove("parpadea_s");
            document.getElementById('hora_s').innerHTML = '';
            document.getElementById('minuto_s').innerHTML = '';
            document.getElementById('segundo_s').innerHTML = '';
            setTimeout(function () {
                $("#fecha_s").show();
            }, 1000);
            clearInterval(intervaloRegresivo_s);
            intervaloRegresivo_s = setInterval(regresiva_s, 1000, conteo_s, estado_s);
            mostrar_nueva_sub_solicitud(modal, entrevista);
        });
        $('#modal-editar-apoderado-sub').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var alumno = button.data('alumno');
            var info_apoderado = button.data('info-apoderado');
            mostrar_editar_apoderado_sub(modal, alumno, info_apoderado);
        });

        $('#modal-detalle-solicitud-alumno').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var solicitud = button.data('solicitud');
            mostrar_detalle_solicitud(modal, solicitud);
        });

        $('#modal-editar-solicitud-alumno').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var solicitud = button.data('solicitud');
            mostrar_editar_solicitud(modal, solicitud);
        });
        $('#modal-editar-apoderado-edi').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var alumno = button.data('alumno');
            var info_apoderado = button.data('info-apoderado');
            mostrar_editar_apoderado_edi(modal, alumno, info_apoderado);
        });

        $('#modal-eliminar-solicitud-alumno').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var solicitud = button.data('solicitud');
            mostrar_eliminar_solicitud(modal, solicitud);
        });
        $('#modal-descargar').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var solicitud = button.data('solicitud');
            mostrar_descargar_solicitud(modal, solicitud);
        });
        $('#modal-enviar-solicitud').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var solicitud = button.data('solicitud');
            mostrar_enviar_solicitud(modal, solicitud);
        });


        $("#tableSolicitudesRegistradas").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableSolicitudesRegistradas_wrapper .col-md-6:eq(0)');
    });

</script>