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
$perfilId = $userData[0]["perfil"];
$fechas = fnc_fechas_rango($conexion);
$sedeCodi = "";
$usuarioCodi = "";
if ($perfilId === "1" || $perfilId === "2") {
    $sedeCodi = "0";
    $usuarioCodi = "";
} else if ($perfilId === "3" || $perfilId === "4") {
    $sedeCodi = $sedesData[0]["id"];
    $usuarioCodi = "";
} else if ($perfilId === "2") {
    $sedeCodi = $sedesData[0]["id"];
    $usuarioCodi = $userData[0]["usuCodi"];
}
$lista_solicitudes = fnc_lista_solicitudes($conexion, $sedeCodi, $fechas[0]["date_ayer"], $fechas[0]["date_hoy"], $usuarioCodi);
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
                                <option value="0">-- Todos --</option>
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
                                    <th>Fecha</th>
                                    <th>Grado</th>
                                    <th>Nro. documento</th>
                                    <th>Alumno</th>
                                    <th>Tipo de entrevista</th>
                                    <th>Estado</th>
                                    <th>Opci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $html = "";
                                $num = 1;
                                foreach ($lista_solicitudes as $lista) {
                                    $solicitudCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                                    $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["fecha"] . "</td>
                                        <td>" . $lista["grado"] . "</td>
                                        <td>" . $lista["nroDocumento"] . "</td>
                                        <td>" . $lista["alumno"] . "</td>
                                        <td>" . $lista["entrevista"] . "</td>
                                        <td>" . $lista["estado"] . "</td>
                                        <td align='center'>"
                                            . "<i class='nav-icon fas fa-plus green' title='Nueva Subentrevista' data-toggle='modal' data-target='#modal-subentrevista' data-backdrop='static' data-entrevista='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                                            . "<a href='php/aco_php/psi_generar_pdf.php?val=$solicitudCod'><i class='nav-icon fas fa-file-pdf rojo' title='Descargar'></i></a>&nbsp;&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-info-circle celeste' title='Detalle' data-toggle='modal' data-target='#modal-detalle-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "' data-grupo_nombre='" . $lista["id"] . "'></i>&nbsp;&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-solicitud-alumno' data-backdrop='static' data-solicitud='" . $solicitudCod . "'></i>&nbsp;&nbsp;&nbsp;" .
                                            "</tr>";
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
                    <button type="button" id="btnRegistrarSolicitud" class="btn btn-primary swalDefaultError"
                            onclick="return registrar_solicitud()">Registrar</button>
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
                    <button type="button" id="btnRegistrarSubSolicitud" class="btn btn-success swalDefaultError"
                            onclick="return registrar_sub_solicitud()">Registrar</button>
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
                <h4 class="modal-title">Detalle de solicitud de entrevista </h4>
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
                    <button type="button" id="btnImprimirSolicitud" class="btn btn-primary swalDefaultError" 
                            onclick="return imprimir_ficha_entrevista();"
                            >Imprimir</button>
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
    <div class="modal-dialog modal-lg">
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
<div class="modal fade" id="modal-activar-carga-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-activar-carga-alumno">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Activar carga de Alumnos</h4>
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
                    <button type="button" id="btnActivarCargaAlumno" class="btn btn-primary swalDefaultError" onclick="return activar_carga_alumnos()">Activar carga Alumnos</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
    $(function () {
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
                str_can = 3;
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