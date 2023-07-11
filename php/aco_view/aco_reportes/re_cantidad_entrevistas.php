<?php
//marita
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
                        <select id="cbbBimestre" data-show-content="true" class="form-control" style="width: 100%">
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
                                onclick="buscar_cantidad_entrevistas_subentrevistas();">
                            <i class="fa fa-search"></i>
                            Buscar</button>&nbsp;&nbsp;
                        <button class="btn btn-info" id="btnNuevoLimpiar" style="bottom: 0px;margin-top: 30px" 
                                onclick="limpiar_campos_cantidad_entrevistas_subentrevistas()">
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
                                    <th>Categoria</th>
                                    <th>Subcategoria</th>
                                    <th>Cantidad entrevistas</th>
                                    <th>Cantidad subentrevistas</th>
                                    <th>Total</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $html = "";
                                $num = 1;
                                $lista_cantidad_entrevistas = fnc_lista_cantidad_entrevistas($conexion, $sedeCodi, $bimestre_select_id, "0", "0", "0");
                                foreach ($lista_cantidad_entrevistas as $lista) {
                                    $html .= "<tr>
                                        <td>" . $num . "</td>
                                        <td>" . $lista["categoria"] . "</td>
                                        <td >" . $lista["subcategoria"] . "</td>
                                        <td style='text-align:center'>" . $lista["cantidad_entrevista"] . "</td>
                                        <td style='text-align:center'>" . $lista["cantidad_subentrevista"] . "</td>
                                        <td style='text-align:center'>" . $lista["total"] . "</td>
                                        <td style='text-align:center'>" . $lista["porcentaje"] . "</td>"
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
        </div>
    </div>
</section>

<script>
    $(function () {
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
                    title: 'Lista Cantidad de entrevitas y subentrevistas'
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    title: 'Lista Cantidad de entrevitas y subentrevistas'
                },
                {
                    extend: 'print',
                    text: 'Imprimir',
                    title: 'Lista Cantidad de entrevitas y subentrevistas'
                }, "colvis"]
                    //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    //"buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableCantidadEntrevistas_wrapper .col-md-6:eq(0)');
    });
</script>