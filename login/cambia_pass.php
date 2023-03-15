<?php
require_once '../php/aco_conect/DB.php';
require_once '../php/aco_fun/aco_fun.php';
$con = new DB(1111);
$conexion = $con->connect();

if (empty($_GET["iden"])) {
    header("Location: ../index.php");
}

if (empty($_GET["token"])) {
    header("Location: ../index.php");
}

$user_id = strip_tags(trim($_GET["iden"]));
$token = strip_tags(trim($_GET["token"]));
$id = explode("/", $user_id);
$iddata = $id[1];
$usuarioId = explode("-", $iddata);
$verifica = fnc_verificar_token_pass($conexion, $usuarioId[0], $token);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de acompa&ntilde;amiento al estudiante - SIAE</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="javascript:void(0)" class="h5"><b>Cambiar contrase&ntilde;a</b></a>
                </div>
                <div class="card-body">
                    <?php
                    if (count($verifica) > 0) {
                        ?>
                        <p class="login-box-msg">Aqu&iacute; puedes cambiar tu contrase&ntilde;a por una nueva.</p>
                        <input type="hidden" id="hdnUser" class="form-control" value="<?php echo $usuarioId[0]; ?>">
                        <input type="hidden" id="hdnToken" class="form-control" value="<?php echo $token; ?>">
                        <div class="input-group mb-3">
                            <input type="password" id="txtNuevaContra" class="form-control" placeholder="Nueva contraseña" onkeypress="return enterCambiarContra(event);">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" id="txtConfirContra" class="form-control" placeholder="Confirmar Contraseña" onkeypress="return enterCambiarContra(event);">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div id="mensajeContrasena"></div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block" onclick="CambiarContrasena()">Modificar contrase&ntilde;a</button>
                            </div>
                            <!-- /.col -->
                        </div>
                        <?php
                    } else {
                        ?>
                        <p class="login-box-msg">No se pudo verificar los datos.</p>
                        <?php
                    }
                    ?>

                    <p class="mt-3 mb-1">
                        <a href="../index.php">Acceso al sistema</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="../plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../dist/js/adminlte.min.js"></script>
        <script type="text/javascript" src="../php/aco_js/login.js"></script>
    </body>
</html>
