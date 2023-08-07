<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$lista_perfiles = fnc_lista_tipo_usuarios($conexion, "", "");
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
                        <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevo-perfil" data-backdrop="static">Nuevo perfil</button>
                    </div>
                </div><br>
                <table id="tablePerfiles" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>C&oacute;digo</th>
                            <th>Descricpi&oacute;n</th>
                            <th>Estado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $html = "";
                        $num = 1;
                        foreach ($lista_perfiles as $lista) {
                            $perfilCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                            $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["codigo"] . "</td>
                                        <td>" . $lista["nombre"] . "</td>
                                        <td>" . $lista["estado"] . "</td>
                                        <td align='center'>"
                                    . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-perfil' data-backdrop='static' data-perfil='" . $perfilCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-perfil' data-backdrop='static' data-perfil='" . $perfilCod . "'></i>&nbsp;&nbsp;"
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

</section>

<div class="modal fade" id="modal-nuevo-perfil" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-perfil">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Nuevo Perfil</h4>
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
                    <button type="button" id="btnRegistrarPerfil" class="btn btn-primary swalDefaultError" onclick="return registrar_perfil()">Registrar Perfil</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-perfil" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-perfil">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Perfil</h4>
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
                    <button type="button" id="btnEditarPerfil" class="btn btn-primary swalDefaultError" onclick="return editar_perfil()">Editar Perfil</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-perfil" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-perfil">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Perfil</h4>
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
                    <button type="button" id="btnEliminarPerfil" class="btn btn-primary swalDefaultError" onclick="return eliminar_perfil()">Eliminar Perfil</button>
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
        /*Modales de Administrar Perfiles*/
        $('#modal-nuevo-perfil').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            mostrar_registra_nuevo_perfil(modal);
        });
        $('#modal-editar-perfil').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var perfil = button.data('perfil');
            mostrar_editar_perfil(modal, perfil);
        });
        $('#modal-eliminar-perfil').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var perfil = button.data('perfil');
            mostrar_eliminar_perfil(modal, perfil);
        });
        $("#tablePerfiles").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tablePerfiles_wrapper .col-md-6:eq(0)');
    });
</script>