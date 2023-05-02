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
                    <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Sede: </label>
                        </div>
                        <select id="cbbSemaforo" data-show-content="true" class="form-control" style="width: 100%">
                            <option value="0">-- Todos --</option>
                            <option value='1' >Verde</option>
                            <option value='2' >Ambar</option>
                            <option value='3' >Rojo</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <button class="btn btn-primary" id="btnNuevoSolicitud" style="bottom: 0px;margin-top: 30px" 
                                onclick="buscar_semaforo_docente()">
                            <i class="fa fa-search"></i>
                            Buscar</button>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-12" id="divSolicitudesRegistradas">
                        <table id="tableSemaforo" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th>Sede</th>
                                    <th style='width:300px'>Docente</th>
                                    <th style='width:200px'>Grado</th>
                                    <th>Cantidad total de estudiantes</th>
                                    <th>Cantidad faltante</th>
                                    <th >Semaforo</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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