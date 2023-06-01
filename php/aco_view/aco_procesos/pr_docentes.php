<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$codigo_user = $_SESSION["psi_user"]["id"];
$lista_grupos = fnc_lista_grupos_usuarios($conexion, "", "");
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
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label> Subir archivo de Usuarios: </label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-12">
                                <input type="hidden" id="hdnCodiAU" value="<?php echo $codigo; ?>"/>
                                <form method="post" id="upload_formUsuarios" enctype="multipart/form-data" >
                                    <input type="file" name="select_fileUsuarios" id="select_fileUsuarios" style="" />
                                    <input type="submit" name="uploadUsuarios" id="upload1" style="border-color: #1cc88a!important;color: white;" class="btn btn-success" value="Cargar Excel"/>
                                </form>
                            </div>
                            <div class="col-sm-6 col-12" style="">
                                <div class="alert" id="messageUsuarios" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group" style="margin-bottom: 0px;">
                                <label> Descargar archivo de Usuarios: </label> <a href="php/aco_downloads/Listado_Usuarios_CBB.xlsx" download class="a_styles">Descargar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <table id="tableCargaUsuarios" class="table table-bordered table-hover">
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
                                            . "<i class='nav-icon fas fa-info-circle celeste' title='Detalle' data-toggle='modal' data-target='#modal-detalle-carga-usuario' data-backdrop='static' data-grupo='" . $grupoCod . "' data-grupo_nombre='" . $lista["codigo"] . "'></i>&nbsp;&nbsp;"
                                            . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-carga-usuario' data-backdrop='static' data-grupo='" . $grupoCod . "'></i>&nbsp;&nbsp;"
                                            . "<i class = 'nav-icon fas fa-undo azul' title='Activar Grupo' data-toggle='modal' data-target='#modal-activar-carga-usuario' data-backdrop='static' data-grupo = '" . $grupoCod . "'></i>" . "</td>
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

<div class="modal fade" id="modal-carga-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-carga-usuario">
    <div class="modal-dialog" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pre carga data Usuario</h4>
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
                    <button type="button" id="btnConfirmacionCargaUsuario" class="btn btn-primary swalDefaultError" 
                            data-toggle="modal" data-target="#modal-confirmar-carga-usuarios" data-backdrop="static"
                            >Confirmar Cargar Usuarios</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-confirmar-carga-usuarios" role="dialog" aria-hidden="true" aria-labelledby="modal-confirmar-carga-usuarios">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmar Pre carga data Usuario</h4>
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
                    <button type="button" id="btnRegistrarCargaUsuario" class="btn btn-primary swalDefaultError" 
                            onclick="return registrar_carga_usuarios()">Cargar Usuarios</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-detalle-carga-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-detalle-carga-usuario">
    <div class="modal-dialog" style="max-width: 90%;">
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-carga-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-carga-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar carga de Usuarios</h4>
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
                    <button type="button" id="btnEliminarCargaUsuario" class="btn btn-primary swalDefaultError" onclick="return eliminar_carga_usuarios()">Eliminar carga Usuarios</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-activar-carga-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-activar-carga-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Activar carga de Usuarios</h4>
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
                    <button type="button" id="btnActivarCargaUsuario" class="btn btn-primary swalDefaultError" onclick="return activar_carga_usuarios()">Activar carga Usuarios</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function () {
        /*Para la carga del excel*/
        $("#upload_formUsuarios").on('submit', function (event) {
            $("#uploadUsuarios").attr("disabled", true);
            $("#messageUsuarios").html("");
            event.preventDefault();
            $.ajax({
                url: "php/aco_php/loadCargaUsuarios.php",
                method: "POST",
                data: new FormData(this),
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function (objeto) {
                    $("#messageUsuarios").append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
                    $("#messageUsuarios").css("display", "block");
                },
                success: function (data) {
                    $("#messageUsuarios").css("display", "block");
                    if (data.message === "" && data.resp === "1") {
                        $("#messageUsuarios").html("Exito en la carga!");
                        $.ajax({
                            url: "php/aco_php/controller.php",
                            method: "POST",
                            data: {
                                opcion: 'load_modal_carga_usuarios'
                            },
                            beforeSend: function () {},
                            success: function (data) {
                                $("#modal-carga-usuario .modal-body").html(data);
                                $("#modal-carga-usuario").modal({backdrop: 'static', keyboard: false}, 'show');
                                $("#tablePreCargaUsuarios").DataTable({
                                    "responsive": true,
                                    "lengthChange": true,
                                    "autoWidth": true,
                                    "paging": true,
                                    "searching": true,
                                    "ordering": true,
                                    "info": true,
                                    //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                                    "buttons": ["new", "colvis"]
                                }).buttons().container().appendTo('#tablePreCargaUsuarios_wrapper .col-md-6:eq(0)');
                                $('#modal-confirmar-carga-usuarios').on('show.bs.modal', function (event) {
                                    var modal = $(this);
                                    var button = $(event.relatedTarget);
                                    mostrar_confirmacion_carga_usuarios(modal);
                                });
                            }
                        });
                    } else if (data.message !== "" && (data.resp === "0" || data.resp === "2")) {
                        $("#messageUsuarios").html(data.message);
                    } else {
                        $("#messageUsuarios").html("Error en la correlatividad de comprobantes!");
                    }
                    $("#messageAlumnos").removeClass("alert-success");
                    $("#messageAlumnos").removeClass("alert-danger");
                    $("#messageUsuarios").addClass(data.class_name);
                    $("#uploadUsuarios").attr("disabled", false);
                }
            });
        });

        /*Modales de Carga de Usuarios*/
        $('#modal-detalle-carga-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var grupo = button.data('grupo');
            var nombre = button.data('grupo_nombre');
            mostrar_detalle_grupo_usuarios(modal, grupo, nombre);
        });
        $('#modal-eliminar-carga-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var grupo = button.data('grupo');
            eliminar_detalle_grupo_usuario(modal, grupo);
        });
        $('#modal-activar-carga-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var grupo = button.data('grupo');
            activar_detalle_grupo_usuario(modal, grupo);
        });

        $("#tableCargaUsuarios").DataTable({
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