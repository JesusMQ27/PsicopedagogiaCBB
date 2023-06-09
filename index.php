<?php
session_start();
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
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <!-- /.login-logo -->
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <div class="text-center"><img src="php/aco_img/logo_3.png" alt="" style="width: 70%"/></div><br>
                    <a href="javascript:void(0)" class="h5"><b>Sistema Integral de acompa&ntilde;amiento al estudiante </b><b>SIAE</b></a>
                </div>
                <div class="card-body">
                    <p class="login-box-msg">Ingresa tu usuario y contrase&ntilde;a para iniciar sesi&oacute;n</p>

                    <div class="input-group mb-3">
                        <input type="text" id="txtUsuario" class="form-control" placeholder="Usuario" onkeypress="return enterLogin(event);"
                               value="<?php
                               if (isset($_COOKIE["login_usuario"]) && $_COOKIE["login_usuario"] !== "") {
                                   echo $_COOKIE["login_usuario"];
                               }
                               ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="txtClave" class="form-control" placeholder="Contraseña" onkeypress="return enterLogin(event);"
                               value="<?php
                               if (isset($_COOKIE["usuario_password"]) && $_COOKIE["usuario_password"] !== "") {
                                   echo $_COOKIE["usuario_password"];
                               }
                               ?>"  >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div id="mensaje"></div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember" <?php if (isset($_COOKIE["login_usuario"]) && $_COOKIE["login_usuario"] !== "") { ?> checked
                                       <?php } ?> >
                                <label for="remember">
                                    Recordar
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block" onclick="AccederSistema();">Ingresar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.social-auth-links -->

                    <p class="mb-1">
                        <a href="recordar.php">Olvid&eacute; mi contrase&ntilde;a</a>
                    </p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
