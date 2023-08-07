<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$lista_grados_secciones = fnc_lista_grados_x_secciones($conexion, "", "");
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
                        <input type="hidden" id="hdnCodiGS" value="<?php echo $codigo; ?>"/>
                        <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevo-gradoseccion" data-backdrop="static">Nuevo Grado y Secci&oacute;n</button>
                    </div>
                </div><br>
                <table id="tableGradoSeccion" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Grado</th>
                            <th>Secciones</th>
                            <th>Estado de grado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $html = "";
                        $num = 1;
                        foreach ($lista_grados_secciones as $lista) {
                            $gradoCod = fnc_generate_random_string(6) . "-" . $lista["gradoId"] . "/" . fnc_generate_random_string(6);
                            $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["grado"] . "</td>
                                        <td>" . $lista["secciones"] . "</td>
                                        <td>" . $lista["grado_estado"] . "</td>
                                        <td align='center'>"
                                    . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-gradoseccion' data-backdrop='static' data-gradoseccion='" . $gradoCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-gradoseccion' data-backdrop='static' data-gradoseccion='" . $gradoCod . "'></i>&nbsp;&nbsp;"
                                    . "</td></tr>";
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

<div class="modal fade" id="modal-nuevo-gradoseccion" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-gradoseccion" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Nuevo Grado y Secci&oacute;n</h4>
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
                    <button type="button" id="btnRegistrarGradoSeccion" class="btn btn-primary swalDefaultError" onclick="return registrar_gradoseccion()">Registrar Grado y Secci&oacute;n</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-gradoseccion" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-gradoseccion" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Grado y Secci&oacute;n</h4>
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
                    <button type="button" id="btnEditarGradoSeccion" class="btn btn-primary swalDefaultError" onclick="return editar_gradoseccion()">Editar Grado y Secci&oacute;n</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-gradoseccion" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-gradoseccion">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Grado y Secci&oacute;n</h4>
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
                    <button type="button" id="btnEliminarGradoSeccion" class="btn btn-primary swalDefaultError" onclick="return eliminar_gradoseccion()">Eliminar Grado y Secci&oacute;n</button>
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
        /*Modales de Administrar Grado y Seccion*/
        $('#modal-nuevo-gradoseccion').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            mostrar_registra_nuevo_gradoseccion(modal);
        });
        $('#modal-editar-gradoseccion').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var gradoseccion = button.data('gradoseccion');
            mostrar_editar_gradoseccion(modal, gradoseccion);
        });
        $('#modal-eliminar-gradoseccion').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var gradoseccion = button.data('gradoseccion');
            mostrar_eliminar_gradoseccion(modal, gradoseccion);
        });
        $("#tableGradoSeccion").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableGradoSeccion_wrapper .col-md-6:eq(0)');


    });
</script>