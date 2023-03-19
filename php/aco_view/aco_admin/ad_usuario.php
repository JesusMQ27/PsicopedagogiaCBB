<?php
require_once '../../aco_conect/DB.php';
require_once '../../aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();
$nombre = $_POST["nombre_opcion"];
$codigo = $_POST["codigo_menu"];
$lista_usuarios = fnc_lista_usuarios($conexion, "");
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
                        <input type="hidden" id="hdnCodiAU" value="<?php echo $codigo; ?>"/>
                        <button type="submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-nuevo-usuario" data-backdrop="static">Nuevo usuario</button>
                    </div>
                </div><br>
                <table id="tableUsuarios" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nro.</th>
                            <th>Tipo de usuario</th>
                            <th>Tipo de documento</th>
                            <th>Nro. de documento</th>
                            <th>Nombres</th>
                            <th>Correo</th>
                            <th>Estado</th>
                            <th>Opci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $html = "";
                        $num = 1;
                        foreach ($lista_usuarios as $lista) {
                            $usuarioCod = fnc_generate_random_string(6) . "-" . $lista["id"] . "/" . fnc_generate_random_string(6);
                            $html .= "<tr>
                                <td>" . $num . "</td>
                                        <td>" . $lista["perfilNombre"] . "</td>
                                        <td>" . $lista["tipoDoc"] . "</td>
                                        <td>" . $lista["numDoc"] . "</td>
                                        <td>" . $lista["fullnombre"] . "</td>
                                        <td>" . $lista["correo"] . "</td>
                                        <td>" . $lista["estado_nombre"] . "</td>
                                        <td align='center'>"
                                    . "<i class='nav-icon fas fa-edit naranja' title='Editar' data-toggle='modal' data-target='#modal-editar-usuario' data-backdrop='static' data-usuario='" . $usuarioCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-trash rojo' title='Eliminar' data-toggle='modal' data-target='#modal-eliminar-usuario' data-backdrop='static' data-usuario='" . $usuarioCod . "'></i>&nbsp;&nbsp;"
                                    . "<i class='nav-icon fas fa-paper-plane azul' title='Cambiar contraseña' data-toggle='modal' data-target='#modal-cambiar-contrasena-usuario' data-backdrop='static' data-usuario='" . $usuarioCod . "'></i>" . "</td>
                                      </tr>";
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

<script>
    $(function () {
        $("#tableUsuarios").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": true,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            "buttons": ["new", "colvis"]
        }).buttons().container().appendTo('#tableUsuarios_wrapper .col-md-6:eq(0)');
    });
</script>