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
$lista_anios = fnc_lista_anio_bimestres($conexion, "", "1");
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
                        <input type="hidden" id="hdnCodiBi" value="<?php echo $codigo; ?>"/>
                        <?php if ($acceso === "1") { ?>
                            <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevos-bimestres" data-backdrop="static">Nuevos Bimestres</button>
                        <?php } ?>
                    </div>
                </div><br>
                <table id="tableAnios" class="table table-bordered table-hover" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>C&oacute;digo</th>
                            <th>A&ntilde;o</th>
                            <th>Bimestres</th>
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
                                        <td align='center'>" . str_replace(",", "<br/>", $lista["bimestres"]) . "</td>
                                        <td align='center'>" . str_replace(",", "<br/>", $lista["rango"]) . "</td>
                                        <td align='center'>" . $lista["estado"] . "</td>
                                        <td align='center'>" . "  <i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-bimestres' data-backdrop='static' data-anio='" . $anioCod . "'></i>&nbsp;&nbsp;" . "</td>";
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

<div class="modal fade" id="modal-nuevos-bimestres" role="dialog" aria-hidden="true" aria-labelledby="modal-nuevos-bimestres">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Bimestres por a&ntilde;o</h4>
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
                    <button type="button" id="btnRegistrarBimestres" class="btn btn-primary swalDefaultError" onclick="return registrar_bimestres()">Registrar Bimestres</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-editar-bimestres" role="dialog" aria-hidden="true" aria-labelledby="modal-editar-bimestres">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Bimestres por año</h4>
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
                    <button type="button" id="btnEditarBimestres" class="btn btn-primary swalDefaultError" onclick="return editar_bimestres()">Editar Bimestres</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    $(function () {

        /*Modales de Administrar Menus*/
        $('#modal-nuevos-bimestres').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            mostrar_registra_nuevos_bimestres(modal);
        });
        $('#modal-editar-bimestres').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var anio = button.data('anio');
            mostrar_editar_bimestres(modal, anio);
        });
        $("#tableAnios").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableAnios_wrapper .col-md-6:eq(0)');
    });
</script>