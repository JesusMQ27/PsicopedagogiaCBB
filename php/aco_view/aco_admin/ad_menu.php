<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$lista_menus = fnc_lista_menus($conexion, "", "");
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
                        <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevo-menu" data-backdrop="static">Nuevo Men&uacute;</button>
                    </div>
                </div><br>
                <table id="tableMenus" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>C&oacute;digo</th>
                            <th>Descricpi&oacute;n</th>
                            <th>Imagen</th>
                            <th>Estado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $html = "";
                        $num = 1;
                        foreach ($lista_menus as $lista) {
                            $menuCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                            $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["codigo"] . "</td>
                                        <td>" . $lista["nombre"] . "</td>
                                        <td align='center'><i style='font-size:23px' class='" . $lista["imagen"] . "'></i></td>
                                        <td>" . $lista["estado"] . "</td>
                                        <td align='center'>"
                                    . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-menu' data-backdrop='static' data-menu='" . $menuCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-menu' data-backdrop='static' data-menu='" . $menuCod . "'></i>&nbsp;&nbsp;"
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

<div class="modal fade" id="modal-nuevo-menu" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-menu">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Nuevo Men&uacute;</h4>
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
                    <button type="button" id="btnRegistrarMenu" class="btn btn-primary swalDefaultError" onclick="return registrar_menu()">Registrar Men&uacute;</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-menu" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-menu">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Men&uacute;</h4>
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
                    <button type="button" id="btnEditarMenu" class="btn btn-primary swalDefaultError" onclick="return editar_menu()">Editar Men&uacute;</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-menu" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-menu">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Men&uacute;</h4>
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
                    <button type="button" id="btnEliminarMenu" class="btn btn-primary swalDefaultError" onclick="return eliminar_menu()">Eliminar Men&uacute;</button>
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
        /*Modales de Administrar Menus*/
        $('#modal-nuevo-menu').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            mostrar_registra_nuevo_menu(modal);
        });
        $('#modal-editar-menu').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var menu = button.data('menu');
            mostrar_editar_menu(modal, menu);
        });
        $('#modal-eliminar-menu').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var menu = button.data('menu');
            mostrar_eliminar_menu(modal, menu);
        });
        $("#tableMenus").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableMenus_wrapper .col-md-6:eq(0)');
    });
</script>