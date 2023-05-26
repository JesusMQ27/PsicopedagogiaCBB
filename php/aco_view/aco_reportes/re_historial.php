<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
session_start();
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$codigo_user = $_SESSION["psi_user"]["id"];
$perfil = $_SESSION["psi_user"]["perfCod"];
$sedeCodigo = $_SESSION["psi_user"]["sedCod"];

$sedesData = fnc_sedes_x_perfil($conexion, $sedeCodigo);
$fechas = fnc_fechas_rango($conexion);
$sedeCodi = "";
$usuarioCodi = "";
$privacidad = "";
if ($sedeCodigo == "1" && ($perfil == "1" || $perfil == "5")) {
    $sedeCodi = "0";
    $usuarioCodi = "";
    $privacidad = "0,1";
} else {
    $privacidad = "0";
    if ($perfil === "1" && $perfil === "5") {
        $sedeCodi = $sedeCodigo;
        $usuarioCodi = "";
        $privacidad = "0,1";
    } elseif ($perfil === "2") {
        $sedeCodi = $sedeCodigo;
        $usuarioCodi = $codigo_user;
    } else {
        $sedeCodi = $sedeCodigo;
        $usuarioCodi = "";
    }
}
//$lista_solicitudes = fnc_lista_solicitudes_y_subsolicitudes($conexion, $sedeCodi, $usuarioCodi, $fechas[0]["date_ayer"], $fechas[0]["date_hoy"], $privacidad);
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
                    <div class="col-md-2" style="margin-bottom: 0px;">
                        <label>Buscar alumno: </label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="searchAlumnoH" class="typeahead form-control" style="size:12px;text-transform: uppercase;" value="" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" id="matricH" value=""/>
                        <label id="dataAlumnoH" style="font-size: 16px;"></label>
                    </div>
                </div><br>
                <div id="divHistorial">

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(function () {
        $('input#searchAlumnoH').typeahead({
            hint: true,
            highlight: true,
            minLength: 4,
            source: function (query, result) {
                $.ajax({
                    url: 'php/aco_php/buscar_alumno.php',
                    method: 'POST',
                    data: {query: query},
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
                $("#matricH").val(found.value);
                $("#dataAlumnoH").html(item);
                mostrar_historial_alumno(found.value);
            }
        });
        setTimeout(function () {
            $('input[id="searchAlumnoH"]').focus();
        }, 500);
    });
</script>