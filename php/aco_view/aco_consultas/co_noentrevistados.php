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
$privacidad = "";
$disabled = "";
if ($userData[0]["sedeId"] == "1" && ($perfilId === "1" || $perfilId === "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "0";
    $privacidad = "0,1";
    $disabled = "";
} else {
    $privacidad = "0";
    if ($perfilId === "1") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "0";
        $disabled = "";
    } elseif ($perfilId === "2") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = $codigo_user;
        $disabled = " disabled ";
    } else {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "0";
        $disabled = "";
    }
}

$lista_semaforo = fnc_lista_semaforo($conexion, '', '1');
$lista_bimestre = fnc_lista_bimestre($conexion, '', '1');
$lista_niveles = fnc_lista_niveles($conexion, '', '1');
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
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
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
                </div><br>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Bimestre: </label>
                        </div>
                        <select id="cbbBimestre" data-show-content="true" class="form-control" style="width: 100%">
                            <option value="0">-- Todos --</option>
                            <?php
                            foreach ($lista_bimestre as $bimestre) {
                                echo "<option value='" . $bimestre["id"] . "' >" . $bimestre["nombre"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
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
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
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
                </div><br>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;" id="docenteData">
                            <label id="dataDocente" >Docente:</label>
                            <input type="hidden" id="docen" value=""/>
                        </div>
                        <input type="text" id="searchDocente" class="typeahead form-control" style="size:12px;text-transform: uppercase;" value="" autocomplete="off" <?php echo $disabled; ?>>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                        <button class="btn btn-success" id="btnNuevoSolicitud" style="bottom: 0px;margin-top: 30px" 
                                onclick="buscar_alumnos_no_entrevistados()">
                            <i class="fa fa-search"></i>
                            Buscar</button>
                        &nbsp;&nbsp;
                        <button class="btn btn-info" id="btnNuevoLimpiar" style="bottom: 0px;margin-top: 30px" 
                                onclick="limpiar_campos_no_entrevistados()">
                            <i class="fa fa-search"></i>
                            Limpiar</button>
                    </div>
                </div><br>
                <div class="col-12">
                    <div class="table-responsive" id="divSolicitudesRegistradas">
                        <table id="tableNoEntrevistados" class="table table-bordered table-hover" style="width: 100%">
                            <thead>
                                <tr>
                                    <th>Nro.</th>
                                    <th>Tipo</th>
                                    <th>Fecha</th>
                                    <th>Sede</th>
                                    <th>Docente</th>
                                    <th style='width:200px'>Grado</th>
                                    <th>DNI</th>
                                    <th style='width:300px'>Alumno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $lista = fnc_buscar_alumnos_no_entrevistados($conexion, $sedeCodi, $fechas[0]["date_ayer"], $fechas[0]["date_hoy"], "0", "0", "0", "0", $usuarioCodi);
                                $html = "";
                                $aux = 1;
                                if (count($lista) > 0) {
                                    foreach ($lista as $value) {
                                        $html .= "<tr >"
                                                . "<td>$aux</td>"
                                                . "<td>" . $value["tipo"] . "</td>"
                                                . "<td >" . $value["fecha"] . "</td>"
                                                . "<td>" . $value["sede"] . "</td>"
                                                . "<td>" . $value["docente"] . "</td>"
                                                . "<td>" . $value["grado"] . "</td>"
                                                . "<td>" . $value["dni"] . "</td>"
                                                . "<td>" . $value["alumno"] . "</td>"
                                                . "</tr>";
                                        $aux++;
                                    }
                                } else {
                                    $html = ' <tr class = "odd"><td valign = "top" colspan = "9" class = "dataTables_empty">No hay datos disponibles en la tabla</td></tr>';
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
        $("#tableNoEntrevistados").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "title": "hols",
            "buttons": ["copy",
                {
                    extend: 'csv',
                    text: 'CSV',
                    title: 'Lista semaforo docentes'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Lista semaforo docentes'
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title: 'Lista semaforo docentes'
                }, "colvis"]
                    //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    //"buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableNoEntrevistados_wrapper .col-md-6:eq(0)');

        var array = [];
        $('input#searchDocente').typeahead({
            hint: true,
            highlight: true,
            minLength: 4,
            source: function (query, result) {
                $.ajax({
                    url: 'php/aco_php/buscar_docente.php',
                    method: 'POST',
                    data: {
                        query: query,
                        s_sede: $("#cbbSedes").select().val(),
                        s_seccion: $("#cbbSeccion").select().val()
                    },
                    dataType: "json",
                    success: function (data) {
                        //result(getOptionsFromJson(data));
                        array = [];
                        result($.map(data, function (item) {
                            array.push({'value': item.value, 'label': item.label});
                            return item.label;
                        }));
                    },
                });
            },
            updater: function (item) {
                const found = array.find(el => el.label === item);
                $("#docen").val(found.value);
                $("#dataDocente").html('Docente: ' + item + '');
            }
        });
    });

</script>