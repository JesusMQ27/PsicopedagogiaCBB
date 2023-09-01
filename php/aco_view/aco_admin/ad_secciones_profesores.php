<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
session_start();
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$perfil = $_SESSION["psi_user"]["perfCod"];
$sedeCodigo = $_SESSION["psi_user"]["sedCod"];
if ($sedeCodigo === "1") {
    $sedeCodigo = "";
}
$lista_usuarios = fnc_lista_usuarios($conexion, "", $sedeCodigo);
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
                        <label>Buscar docente: </label>
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="searchDocenteH" class="typeahead form-control" style="size:12px;text-transform: uppercase;" value="" autocomplete="off">
                    </div>
                    <div class="col-md-4">
                        <input type="hidden" id="usuarioH" value=""/>
                        <label id="dataDocenteH" style="font-size: 16px;"></label>
                    </div>
                </div><br>
                <div id="divSecciones">
                </div>
            </div>
        </div>
    </div>

</section>

<div class="modal fade" id="modal-nueva-seccion-docente" role="dialog" aria-hidden="true" aria-labelledby="modal-nueva-seccion-docente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Nueva Secci&oacute;n</h4>
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
                    <button type="button" id="btnRegistrarSeccionDocente" class="btn btn-primary swalDefaultError" onclick="return registrar_seccion_docente()">Registrar nueva secci&oacute;n</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-eliminar-seccion-docente" role="dialog" aria-hidden="true" aria-labelledby="modal-eliminar-seccion-docente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Eliminar Secci&oacute;n del Docente</h4>
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
                    <button type="button" id="btnEliminarSeccionDocente" class="btn btn-primary swalDefaultError" onclick="return eliminar_seccion_docente()">Eliminar Secci&oacute;n</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-activar-seccion-docente" role="dialog" aria-hidden="true" aria-labelledby="modal-activar-seccion-docente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Activar Secci&oacute;n del Docente</h4>
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
                    <button type="button" id="btnActivarSeccionDocente" class="btn btn-primary swalDefaultError" onclick="return activar_seccion_docente()">Activar Secci&oacute;n</button>
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
        $('input#searchDocenteH').typeahead({
            hint: true,
            highlight: true,
            minLength: 4,
            source: function (query, result) {
                $.ajax({
                    url: 'php/aco_php/buscar_docentes.php',
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
                $("#usuarioH").val(found.value);
                $("#dataDocenteH").html(item);
                mostrar_secciones_docente(found.value);
            }
        });
        setTimeout(function () {
            $('input[id="searchDocenteH"]').focus();
        }, 500);

        $('#modal-nueva-seccion-docente').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var docente = button.data('docente');
            mostrar_registra_seccion_docente(modal, docente);
        });
        $('#modal-eliminar-seccion-docente').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var docente = button.data('docente');
            var dictado = button.data('dictado');
            mostrar_eliminar_seccion_docente(modal, docente, dictado);
        });
        $('#modal-activar-seccion-docente').on('show.bs.modal', function (event) {
            var modal = $(this);
            var button = $(event.relatedTarget);
            var docente = button.data('docente');
            var dictado = button.data('dictado');
            mostrar_activar_seccion_docente(modal, docente, dictado);
        });
    });
</script>