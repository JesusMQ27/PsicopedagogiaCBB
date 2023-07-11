<?php
require_once '../aco_conect/DB.php';
require_once '../aco_fun/aco_fun.php';
$con = new DB(1111);
session_start();
$conexion = $con->connect();

$s_codigo_menu = strip_tags(trim($_POST["codigo_menu"]));
$s_nombre_opcion = strip_tags(trim($_POST["nombre_opcion"]));
$s_usuario = strip_tags(trim($_POST["s_usuario"]));
?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1><?php echo $s_nombre_opcion; ?></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Principal</a></li>
                    <li class="breadcrumb-item active"><?php echo $s_nombre_opcion; ?></li>
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
                <div class="col-lg-6 col-md-4 col-sm-6 col-12" style="display: block;margin-left: auto;margin-right: auto;width: 40%;">
                    <div class="mb-12">
                        <div class="alert" style="background-color: rgba(23,162,184,.9)!important;color: white">
                            <span>
                                <i class="nav-icon fa fa-info-circle" ></i>&nbsp;<span style="font-size: 16px">Nota: Las contraseñas deben tener un mínimo 7 caracteres.</span>&nbsp;&nbsp;
                            </span>
                        </div>
                    </div>
                    <input type="hidden" id="hdnCodiCC" value="<?php echo $s_codigo_menu; ?>"/>
                    <input type="hidden" id="hdnUsuario" class="form-control" value="<?php echo $s_usuario; ?>">
                    <div class="input-group mb-3">
                        <input type="password" id="txtContraNueva" class="form-control" placeholder="Nueva contraseña" onkeypress="return enter_cambiar_contra(event);">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" id="txtContraConfirmar" class="form-control" placeholder="Confirmar Contraseña" onkeypress="return enter_cambiar_contra(event);">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <label for="chkShowPassword">
                            <input id="chkShowPassword" type="checkbox" onclick="mostrarClaves(this)" />
                            Mostrar contrase&ntilde;as</label>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" id="btnCambiarContrasenaUsuario" class="btn btn-primary btn-block" onclick="cambiar_contrasena_usuario()">Modificar contrase&ntilde;a</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function mostrarClaves(chkShowPassword) {
        if (chkShowPassword.checked) {
            $('input[id=txtContraNueva]').attr('type', 'text');
            $('input[id=txtContraConfirmar]').attr('type', 'text');
        } else {
            $('input[id=txtContraNueva]').attr('type', 'password');
            $('input[id=txtContraConfirmar]').attr('type', 'password');
        }
    }

</script>