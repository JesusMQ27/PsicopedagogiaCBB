<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
session_start();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$codigo_user = $_SESSION["psi_user"]["id"];
$lista_grupos = fnc_lista_grupos($conexion, "", "");
$userData = fnc_datos_usuario($conexion, $codigo_user);
$sedesData = fnc_sedes_x_perfil($conexion, $userData[0]["sedeId"]);
$perfil = $userData[0]["perfil"];
$sedeCodi = "";
$usuarioCodi = "";
$acceso = "";
if ($userData[0]["sedeId"] == "1" && ($perfil == "1" || $perfil == "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "";
    $acceso = "1";
} else {
    $acceso = "0";
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
$lista_anios = fnc_lista_bimestre_semaforo($conexion, "", "");
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
                        <input type="hidden" id="hdnCodiSema" value="<?php echo $codigo; ?>"/>
                        <?php if ($acceso === "1") { ?>
                            <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevo-semaforo" data-backdrop="static">Nuevo Semaforo</button>
                        <?php } ?>
                    </div>
                </div><br>
                <table id="tableSemaforo" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>C&oacute;digo</th>
                            <th>A&ntilde;o</th>
                            <th>Bimestre</th>
                            <th>Semaforo</th>
                            <th>Rangos</th>
                            <th>Estado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $html = "";
                        $num = 1;
                        foreach ($lista_anios as $lista) {
                            $anioCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                            $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td align='center'>" . $lista["codigo"] . "</td>
                                        <td align='center'>" . $lista["nombre"] . "</td>
                                        <td align='center'>" . $lista["bimestre"] . "</td>
                                        <td align='center'>" . str_replace(",", "<br/>", $lista["semaforo"]) . "</td>
                                        <td align='center'>" . str_replace(",", "<br/>", $lista["rango"]) . "</td>
                                        <td align='center'>" . $lista["estado"] . "</td>
                                        <td align='center'>" . "  <i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-semaforo' data-backdrop='static' data-anio='" . $anioCod . "'></i>&nbsp;&nbsp;" . "</td>";
                            $html .= "</tr>";
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

<div class="modal fade" id="modal-nuevo-semaforo" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevo-semaforo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Semaforo por bimestre</h4>
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
                    <button type="button" id="btnRegistrarSemaforo" class="btn btn-primary swalDefaultError" onclick="return registrar_semaforo()">Registrar Semaforo</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-semaforo" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-semaforo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Semaforo por año</h4>
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
                    <button type="button" id="btnEditarSemaforo" class="btn btn-primary swalDefaultError" onclick="return editar_semaforo()">Editar Semaforo</button>
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
        $('#modal-nuevo-semaforo').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            mostrar_registra_nuevo_semaforo(modal);
        });
        $('#modal-editar-semaforo').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var anio = button.data('anio');
            mostrar_editar_semaforo(modal, anio);
        });
        $("#tableSemaforo").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableSemaforo_wrapper .col-md-6:eq(0)');
    });
</script>