<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$codigo_user = $_SESSION["psi_user"]["id"];
$lista_grupos = fnc_lista_grupos($conexion, "", "");
$userData = fnc_datos_usuario($conexion, $codigo_user);
$sedesData = fnc_sedes_x_perfil($conexion, $userData[0]["sedeId"]);
$perfil = $userData[0]["perfil"];
$sedeCodi = "";
$usuarioCodi = "";
if ($userData[0]["sedeId"] == "1" && ($perfil == "1" || $perfil == "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "";
} else {
    if ($perfil === "1") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "";
    } elseif ($perfil === "2") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = $codigo_user;
    } else {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "";
    }
}
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
                    <div class="col-4">

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-6 col-12">
                                <div class="form-group" style="margin-bottom: 0px;">
                                    <label> Sede: </label>
                                </div>

                                <select id="cbbSedesAlu" data-show-content="true" class="form-control" style="width: 100%">
                                    <?php
                                    if (count($sedesData) > 1) {
                                        ?>
                                        <option value="-1">-- Seleccione --</option>
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
                        </div><br/>
                        <div class="row">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label> Subir archivo de Alumnos: </label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-12">
                                <input type="hidden" id="hdnCodiAU" value="<?php echo $codigo; ?>"/>
                                <form method="post" id="upload_formAlumnos" enctype="multipart/form-data" >
                                    <input type="file" name="select_fileAlumnos" id="select_fileAlumnos" style="" />
                                    <input type="submit" name="uploadAlumnos" id="upload1" style="border-color: #1cc88a!important;color: white;" class="btn btn-success" value="Cargar Excel"/>
                                </form>
                            </div>
                            <div class="col-sm-6 col-12" style="">
                                <div class="alert" id="messageAlumnos" style=""></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label> Descargar archivo de Alumnos: </label> <a href="php/aco_downloads/Listado_Alumnos_CBB.xlsx" download class="a_styles">Descargar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <table id="tableCargaAlumnos" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th>C&oacute;digo del grupo</th>
                                    <th>Usuario que registr&oacute;</th>
                                    <th>Fecha de registro</th>
                                    <th>Correo del usuario</th>
                                    <th>Estado</th>
                                    <th>Opci&oacute;n</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $html = "";
                                $num = 1;
                                foreach ($lista_grupos as $lista) {
                                    $grupoCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                                    $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["codigo"] . "</td>
                                        <td>" . $lista["usuario"] . "</td>
                                        <td>" . $lista["fechaRegistro"] . "</td>
                                        <td>" . $lista["correo"] . "</td>
                                        <td>" . $lista["estado"] . "</td>
                                        <td align='center'>"
                                            . "<i class='nav-icon fas fa-info-circle celeste' title='Grupo Detalle' data-toggle='modal' data-target='#modal-detalle-carga-alumno' data-backdrop='static' data-grupo='" . $grupoCod . "' data-grupo_nombre='" . $lista["codigo"] . "'></i>&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-trash rojo' title='Eliminar Grupo' data-toggle='modal' data-target='#modal-eliminar-carga-alumno' data-backdrop='static' data-grupo='" . $grupoCod . "'></i>&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-undo azul' title='Activar Grupo' data-toggle='modal' data-target='#modal-activar-carga-alumno' data-backdrop='static' data-grupo = '" . $grupoCod . "'></i>" . "</td>
                                            </tr>";
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

<div class="modal fade" id="modal-carga-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-carga-alumno">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pre carga data Alumno</h4>
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
                    <button type="button" id="btnConfirmacionCargaAlumno" class="btn btn-primary swalDefaultError" 
                            data-toggle="modal" data-target="#modal-confirmar-carga-alumnos" data-backdrop="static"
                            >Confirmar Carga de Alumnos</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-confirmar-carga-alumnos" role="dialog" aria-hidden="true" aria-labelledby="modal-confirmar-carga-alumnos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmar Pre carga data Alumno</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cerrar_ventana_confirmacion_alumno()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="cerrar_ventana_confirmacion_alumno()">Cerrar</button>
                <div style="float: right">
                    <label></label>
                    <button type="button" id="btnRegistrarCargaAlumno" class="btn btn-primary swalDefaultError" 
                            onclick="return registrar_carga_alumnos()">Cargar Alumnos</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-detalle-carga-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-detalle-carga-alumno">
    <div class="modal-dialog" style="max-width: 90%;
         ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Grupo: </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" >Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-carga-alumno" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-carga-alumno">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar carga de Alumnos</h4>
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
                    <button type="button" id="btnEliminarCargaAlumno" class="btn btn-primary swalDefaultError" onclick="return eliminar_carga_alumnos()">Eliminar carga Alumnos</button>
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
        $('body').css('overflow', 'auto');
        /*Para la carga del excel*/
        $("#upload_formAlumnos").on('submit', function (event) {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000
            });
            var sede = $("#cbbSedesAlu").select().val();
            $("#uploadAlumnos").attr("disabled", true);
            if (sede === "-1") {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "Seleccione la sede",
                    showConfirmButton: false
                });
                $("#uploadAlumnos").attr("disabled", false);
                $("#messageAlumnos").html("");
                $("#messageAlumnos").removeClass("alert alert-danger-color");
                event.preventDefault();
            } else {
                $("#messageAlumnos").html("");
                $("#messageAlumnos").removeClass("alert alert-danger-color");
                event.preventDefault();
                var data_form = new FormData(this);
                data_form.append("cbb_sede", sede);
                $("#messageAlumnos").addClass("alert alert-info-color");
                $.ajax({
                    url: "php/aco_php/loadCargaAlumnos.php",
                    method: "POST",
                    data: data_form,
                    dataType: "JSON",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function (objeto) {
                        $("#messageAlumnos").append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
                        $("#messageAlumnos").css("display", "block");
                        $("#messageAlumnos").removeClass("alert alert-danger-color");
                        $("#messageAlumnos").addClass("alert alert-info-color");
                    },
                    success: function (data) {
                        /*$("#messageAlumnos").css("display", "block");
                         $("#messageAlumnos").removeClass("alert alert-info-color");*/
                        if (data.message === "" && data.resp === "1") {
                            $("#messageAlumnos").addClass("alert alert-success-color");
                            $("#messageAlumnos").html("Exito en la carga!");
                            $.ajax({
                                url: "php/aco_php/controller.php",
                                method: "POST",
                                data: {
                                    opcion: 'load_modal_carga_alumnos'
                                },
                                beforeSend: function () {},
                                success: function (data) {
                                    $("#modal-carga-alumno .modal-body").html(data);
                                    $("#modal-carga-alumno").modal({backdrop: 'static', keyboard: false}, 'show');
                                    $("#tablePreCargaAlumnos").DataTable({
                                        "responsive": true,
                                        "lengthChange": true,
                                        "autoWidth": true,
                                        "paging": true,
                                        "searching": true,
                                        "ordering": true,
                                        "info": true,
                                        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                                        "buttons": ["new", "colvis"]
                                    }).buttons().container().appendTo('#tablePreCargaAlumnos_wrapper .col-md-6:eq(0)');
                                    $('#modal-confirmar-carga-alumnos').on('show.bs.modal', function (event) {
                                        var modal = $(this);
                                        var button = $(event.relatedTarget);
                                        mostrar_confirmacion_carga_alumnos(modal);
                                    });

                                }
                            });
                        } else if (data.message !== "" && (data.resp === "0" || data.resp === "2")) {
                            $("#messageAlumnos").html(data.message);
                        } else {
                            $("#messageAlumnos").html("Error en la carga de Alumnos!");
                        }
                        $("#messageAlumnos").removeClass("alert-success");
                        $("#messageAlumnos").removeClass("alert-danger");
                        $("#messageAlumnos").addClass(data.class_name);
                        $("#uploadAlumnos").attr("disabled", false);
                    }
                });
            }
        });

        /* Modales de Carga de Alumnos */
        $('#modal-detalle-carga-alumno').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var grupo = button.data('grupo');
            var nombre = button.data('grupo_nombre');
            mostrar_detalle_grupo(modal, grupo, nombre);
        });
        $('#modal-eliminar-carga-alumno').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var grupo = button.data('grupo');
            eliminar_detalle_grupo(modal, grupo);
        });
        $('#modal-activar-carga-alumno').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var grupo = button.data('grupo');
            activar_detalle_grupo(modal, grupo);
        });

        $("#tableCargaAlumnos").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableUsuarios_wrapper .col-md-6:eq(0)');
    });
</script>