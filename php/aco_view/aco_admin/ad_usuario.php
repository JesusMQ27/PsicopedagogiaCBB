<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$lista_usuarios = fnc_lista_usuarios($conexion, "");
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
                    <div class="col-2">
                        <input type="hidden" id="hdnCodiAU" value="<?php echo $codigo; ?>"/>
                        <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevo-usuario" data-backdrop="static">Nuevo usuario</button>
                    </div>
                </div><br>
                <table id="tableUsuarios" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Tipo de usuario</th>
                            <th>Tipo de documento</th>
                            <th>Nro. de documento</th>
                            <th>Nombres</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $html = "";
                        $num = 1;
                        foreach ($lista_usuarios as $lista) {
                            $usuarioCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                            $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["perfilNombre"] . "</td>
                                        <td>" . $lista["tipoDoc"] . "</td>
                                        <td>" . $lista["numDoc"] . "</td>
                                        <td>" . $lista["fullnombre"] . "</td>
                                        <td>" . $lista["correo"] . "</td>
                                        <td>" . $lista["estado_nombre"] . "</td>
                                        <td align='center'>"
                                    . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-usuario' data-backdrop='static' data-usuario='" . $usuarioCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-usuario' data-backdrop='static' data-usuario='" . $usuarioCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-paper-plane azul' title='Cambiar contraseña' data-toggle='modal' data-target='#modal-cambiar-contrasena-usuario' data-backdrop='static' data-usuario='" . $usuarioCod . "'></i>" . "</td>
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

</section>

<div class="modal fade" id="modal-nuevo-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Nuevo Usuario</h4>
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
                    <button type="button" id="btnRegistrarUsuario" class="btn btn-primary swalDefaultError" onclick="return registrar_usuario()">Registrar Usuario</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Usuario</h4>
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
                    <button type="button" id="btnEditarUsuario" class="btn btn-primary swalDefaultError" onclick="return editar_usuario()">Editar Usuario</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Usuario</h4>
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
                    <button type="button" id="btnEliminarUsuario" class="btn btn-primary swalDefaultError" onclick="return eliminar_usuario()">Eliminar Usuario</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-cambiar-contrasena-usuario" role="dialog" aria-hidden="true" aria-labelledby="modal-cambiar-contrasena-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cambiar Contrase&ntilde;a</h4>
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
                    <button type="button" id="btnCambiarContrasenaUsuario" class="btn btn-primary swalDefaultError" onclick="return envio_clave_usuario()">Cambiar Contrase&ntilde;a</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function () {
        /*Modales de Administrar Usuarios*/
        $('#modal-nuevo-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            mostrar_registra_nuevo_usuario(modal);
        });
        $('#modal-editar-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var usuario = button.data('usuario');
            mostrar_editar_usuario(modal, usuario);
        });
        $('#modal-eliminar-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var usuario = button.data('usuario');
            mostrar_eliminar_usuario(modal, usuario);
        });
        $('#modal-cambiar-contrasena-usuario').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var usuario = button.data('usuario');
            cambiar_clave_usuario(modal, usuario);
        });

        $("#tableUsuarios").DataTable({
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