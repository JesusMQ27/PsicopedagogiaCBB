<!DOCTYPE html>
<?php
// put your code here<?php
require_once '../aco_conect/DB.php';
require_once '../aco_fun/aco_fun.php';
$con = new DB(1111);
session_start();
$conexion = $con->connect();
$codigo_user = $_SESSION["psi_user"]["id"];
$userData = fnc_datos_usuario($conexion, $codigo_user);
$sedesData = fnc_sedes_x_perfil($conexion, $userData[0]["sedeId"]);
$perfilId = $userData[0]["perfil"];
$fechas = fnc_fechas_rango($conexion);
$sedeCodi = "";
$usuarioCodi = "";
$privacidad = "";
if ($userData[0]["sedeId"] == "1" && ($perfilId === "1" || $perfilId === "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "";
    $privacidad = "0,1";
} else {
    $privacidad = "0";
    if ($perfilId === "1") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = "";
    } elseif ($perfilId === "2") {
        $sedeCodi = $userData[0]["sedeId"];
        $usuarioCodi = $codigo_user;
    } else {
        $sedeCodi = $sedeCodigo;
        $usuarioCodi = "";
    }
}
$lista = fnc_buscar_alumnos_no_entrevistados_graficos_barras($conexion, $sedeCodi, "");
?>
<html>
    <head>
        <meta charset="UTF-8">

    </head>
    <body>
        <div class='card card-warning'>
            <div class='card-header'>
                <h3 class='card-title'>Mis alumnos no entrevistados</h3>
            </div>
            <div class='card-body'>
                <div class="row ">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Sede: </label>
                        </div>

                        <select id="cbbSedes" data-show-content="true" class="form-control" style="width: 100%" onchange="alumnos_no_entrevistados_grafico_barras_sede(this)">
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
                </div><br>
                <div class="col-md-12">
                    <div id="bar-chart"></div>
                </div>
                <div class="col-md-12">
                    <div id="donut-chart"></div>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    $(document).ready(function () {
    barChart();
    donutChart();
    $(window).resize(function () {
    window.barChart.redraw();
    window.donutChart.redraw();
    });
    });
    function barChart() {
    window.barChart = Morris.Bar({
    element: 'bar-chart',
            data: [
<?php foreach ($lista as $value) {
    ?>
                {y: '<?php echo $value["grado"]; ?>', a: <?php echo $value["cantidad"]; ?>, b: <?php echo $value["no_entre"]; ?>, c: <?php echo $value["si_entre"]; ?>},
<?php } ?>
            ],
            xkey: 'y',
            ykeys: ['a', 'b', 'c'],
            labels: ['Total', 'No entrevistados', 'Entrevistados'],
            lineColors: ['#34495E', '#26B99A', '#3dbeee'],
            resize: true,
            redraw: true
    }
    );
    }

    function donutChart() {
    window.donutChart = Morris.Donut({
    element: 'donut-chart',
            data: [
<?php foreach ($lista as $value) {
    ?>
                {label: '<?php echo $value["grado"]; ?>', value: <?php echo $value["si_entre"]; ?>},
<?php } ?>
            ],
            resize: true,
            redraw: true
    });
    }
</script>
