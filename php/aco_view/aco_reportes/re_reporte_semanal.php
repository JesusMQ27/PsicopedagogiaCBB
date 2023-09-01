<?php
//marita2
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
$perfil = $userData[0]["perfil"];
$fechas = fnc_fechas_rango($conexion);
$sedeCodi = "";
$usuarioCodi = "";
$privacidad = "";
if ($userData[0]["sedeId"] == "1" && ($perfil == "1" || $perfil == "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "0";
    $privacidad = "0,1";
} else {
    $privacidad = "0";
    if ($perfil === "1" || $perfil === "5") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "0";
        $privacidad = "0,1";
    } elseif ($perfil === "2") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = $codigo_user;
    } else {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "0";
    }
}

$lista_bimestre = fnc_lista_bimestre($conexion, '', '1');
$lista_niveles = fnc_lista_niveles($conexion, '', '1');
$bimestre_select_id = "";
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
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Bimestre: </label>
                        </div>
                        <select id="cbbBimestre" data-show-content="true" class="form-control" style="width: 100%" onchange="cargar_rango_fechas(this)">
                            <option value="0">-- Seleccione --</option>
                            <?php
                            $selected_bimes = "";
                            foreach ($lista_bimestre as $bimestre) {
                                if ($bimestre["estado"] === "1") {
                                    $selected_bimes = " selected ";
                                    $bimestre_select_id = $bimestre["id"];
                                } else {
                                    $selected_bimes = "";
                                }
                                echo "<option value='" . $bimestre["id"] . "' $selected_bimes>" . $bimestre["nombre"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    $fecha_ini = "";
                    $fecha_fin = "";
                    if ($bimestre_select_id !== "") {
                        $data_bimestre = fnc_lista_rango_fechas_bimestre($conexion, $bimestre_select_id);
                        if (count($data_bimestre) > 0) {
                            $fecha_ini = $data_bimestre[0]["inicio"];
                            $fecha_fin = $data_bimestre[0]["fin"];
                        } else {
                            $fecha_ini = "";
                            $fecha_fin = "";
                        }
                    } else {
                        $fecha_ini = "";
                        $fecha_fin = "";
                    }
                    ?>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Fecha Inicio: </label>
                        </div>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>&nbsp;
                            <input type="text" class="form-control pull-right" id="fecha1" value="<?php echo $fecha_ini; ?>" readonly >
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
                            <input type="text" class="form-control pull-right" id="fecha2" value="<?php echo $fecha_fin; ?>" readonly >
                        </div>
                    </div>
                </div><br>
                <div class="row">
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Nivel: </label>
                        </div>
                        <select id="cbbNivel" data-show-content="true" class="form-control" style="width: 100%" onchange="cargar_selector_grado(this)">
                            <option value="0">-- Todos --</option>
                            <?php
                            foreach ($lista_niveles as $nivel) {
                                echo "<option value='" . $nivel["codigo"] . "' >" . $nivel["nombre"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Grado: </label>
                        </div>
                        <select id="cbbGrado" data-show-content="true" class="form-control" style="width: 100%" onchange="cargar_selector_seccion(this)">
                            <option value="0">-- Todos --</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Secci&oacute;n: </label>
                        </div>
                        <select id="cbbSeccion" data-show-content="true" class="form-control" style="width: 100%">
                            <option value="0">-- Todos --</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <button class="btn btn-success" id="btnBuscarAuditoria" style="bottom: 0px;margin-top: 30px" 
                                onclick="buscar_reporte_semanal();">
                            <i class="fa fa-search"></i>
                            Buscar</button>&nbsp;&nbsp;
                        <button class="btn btn-info" id="btnNuevoLimpiar" style="bottom: 0px;margin-top: 30px" 
                                onclick="limpiar_reporte_semanal()">
                            <i class="fa fa-search"></i>
                            Limpiar</button> 
                    </div>
                </div><br>
                <div class="col-12">
                    <div class="table-responsive" id="divEntrevistas">
                        <table id="tableCantidadEntrevistas" class="table table-bordered table-hover" style="font-size: 12px;width: 100%">
                            <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th>Sede</th>
                                    <th>Nivel</th>
                                    <th>Grado</th>
                                    <th>Secci&oacute;n</th>
                                    <th>Cantidad de entrevistas</th>
                                    <th>Cantidad de subentrevistas</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $html = "";
                                $num = 1;
                                $s_fecha_ini = fnc_fecha_a_YY_MM_DD($fecha_ini);
                                $s_fecha_fin = fnc_fecha_a_YY_MM_DD($fecha_fin);
                                if ($bimestre_select_id !== "") {
                                    $lista_reporte_semanal = fnc_lista_reporte_semanal($conexion, $sedeCodi, $bimestre_select_id, $s_fecha_ini, $s_fecha_fin, "0", "0", "0");
                                    foreach ($lista_reporte_semanal as $lista) {
                                        $html .= "<tr>
                                        <td>" . $num . "</td>
                                        <td>" . $lista["sede"] . "</td>
                                        <td>" . $lista["nivel"] . "</td>
                                        <td >" . $lista["grado"] . "</td>
                                        <td >" . $lista["seccion"] . "</td>
                                        <td style='text-align:center'>" . $lista["cantidad_entrevistas"] . "</td>
                                        <td style='text-align:center'>" . $lista["cantidad_subentrevistas"] . "</td>
                                        <td style='text-align:center'>" . $lista["total"] . "</td>"
                                                . "</tr>";
                                        $num++;
                                    }
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

<script>
    $(function () {
        $('body').css('overflow', 'auto');
        var fechaConcar = $("#fecha1").val();
        var fechaConcar2 = $("#fecha2").val();
        var array_fecha = fechaConcar.split("/");
        var day = parseInt(array_fecha[0]);
        var month = parseInt(array_fecha[1]) - 1;
        var year = parseInt(array_fecha[2]);
        var array_fecha2 = fechaConcar2.split("/");
        var day2 = parseInt(array_fecha2[0]);
        var month2 = parseInt(array_fecha2[1]) - 1;
        var year2 = parseInt(array_fecha2[2]);
        $("#fecha1").daterangepicker({
            autoApply: true,
            showButtonPanel: false,
            singleDatePicker: true,
            showDropdowns: true,
            linkedCalendar: false,
            autoUpdateInput: false,
            showCustomRangeLabel: false,
            minDate: new Date(year, month, day),
            maxDate: new Date(year2, month2, day2),
            locale: {
                format: "DD/MM/YYYY"
            }
        }).on('apply.daterangepicker', function (ev, start) {
            $("#fecha1").val(start.endDate.format('DD/MM/YYYY'));
        });
        $("#fecha2").daterangepicker({
            autoApply: true,
            singleDatePicker: true,
            showDropdowns: true,
            linkedCalendar: false,
            autoUpdateInput: false,
            showCustomRangeLabel: false,
            maxDate: new Date(year2, month2, day2),
            minDate: new Date(year, month, day),
            starDate: new Date(year, month, day),
            locale: {
                format: "DD/MM/YYYY"
            }
        }).on('apply.daterangepicker', function (ev, start) {
            $("#fecha2").val(start.endDate.format('DD/MM/YYYY'));
        });
        $("#tableCantidadEntrevistas").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "buttons": ["copy",
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'Lista Cantidad de entrevitas y subentrevistas del ' + $("#fecha1").val() + ' al ' + $("#fecha2").val()
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Lista Cantidad de entrevitas y subentrevistas del ' + $("#fecha1").val() + ' al ' + $("#fecha2").val()
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title: 'Lista Cantidad de entrevitas y subentrevistas del ' + $("#fecha1").val() + ' al ' + $("#fecha2").val()
                }, "colvis"]
                    //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    //"buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableCantidadEntrevistas_wrapper .col-md-6:eq(0)');
    }
    );
</script>