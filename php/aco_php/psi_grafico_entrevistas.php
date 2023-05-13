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
$lista = fnc_lista_solicitudes_grafico_linear($conexion, $sedeCodi, $privacidad);
?>
<html>
    <head>
        <meta charset="UTF-8">

    </head>
    <body>
        <div class='card card-info'>
            <div class='card-header'>
                <h3 class='card-title'>Entrevistas registradas</h3>
            </div>
            <div class='card-body'>
                <div class="row ">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="form-group" style="margin-bottom: 0px;">
                            <label> Sede: </label>
                        </div>
                        <input type="hidden" id="txtPrivacidad" value="<?php echo $privacidad; ?>"/>
                        <select id="cbbSedes" data-show-content="true" class="form-control" style="width: 100%" onchange="entrevistas_registradas_grafico_barras_sede(this)">
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
                    <div id="line-chart"></div>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    $(document).ready(function () {
        lineChart();
        $(window).resize(function () {
            window.lineChart.redraw();
        });
    });
    function lineChart() {
        const monthNames = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
            "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"
        ];
        window.lineChart = Morris.Line({
            element: 'line-chart',
            data: [
<?php foreach ($lista as $value) {
    ?>
                    {y: '<?php echo $value["mes"] . "-" . $value["nombreMes"]; ?>', a: <?php echo $value["cantidad"]; ?>, b: <?php echo $value["cantidad_estudiantes"]; ?>, c: <?php echo $value["cantidad_padres"]; ?>},
<?php } ?>
            ],
            xkey: 'y',
            parseTime: false,
            ykeys: ['a', 'b', 'c'],
            xLabelFormat: function (x) {
                var index = parseInt(x.src.y);
                return monthNames[index];
            },
            xLabels: "month",
            labels: ['Total', 'Entrevista a Estudiantes', 'Entrevista a Padres de Familia'],
            lineColors: ['#1e88e5', '#dc3545', '#3dbeee'],
            hideHover: 'auto'
        }
        );
    }
</script>
