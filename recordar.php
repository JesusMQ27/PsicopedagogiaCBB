<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Sistema de acompa&ntilde;amiento al estudiante - SIAE</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <div class="text-center"><img src="php/aco_img/logo_3.png" alt="" style="width: 70%"/></div><br>
                    <a href="javascript:void(0)" class="h5"><b>Sistema Integral de acompa&ntilde;amiento al estudiante </b><b>SIAE</b></a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">¿Olvidaste tu contrase&ntilde;a? Aqu&iacute; puedes recuperar f&aacute;cilmente una nueva contrase&ntilde;a.</p>
                    <div class="input-group mb-3">
                        <input type="email" id="txtCorreo" class="form-control" placeholder="Correo institucional" onkeypress="return enterLoginRecuperar(event);">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div id="mensajeRecordar"></div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" onclick="RecuperarContrasena()">Enviar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                    <p class="mt-3 mb-1">
                        <a href="index.php">Acceso al sistema</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="dist/js/adminlte.min.js"></script>
        <script type="text/javascript" src="php/aco_js/login.js"></script>
    </body>
</html>
