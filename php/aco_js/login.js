$(document).ready(function () {
    $("#txtUsuario").focus();
});


function enterLogin(evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 13) {
        AccederSistema();
    }
}

function AccederSistema() {
    var usua = $("#txtUsuario");
    var contra = $("#txtClave");
    var remember = $("#remember")[0].checked;
    var mensaje = "";
    var cant = 0;
    var txt = "";

    $("#mensaje").html("");
    $("#mensaje").hide();

    if ($.trim(usua.val()) == "") {
        mensaje += "Ingrese el usuario<br>";
        cant++;
        txt = usua;
    }
    if ($.trim(contra.val()) == "") {
        mensaje += "Ingrese la contrase\361a<br>";
        cant++;
        txt = contra;
    }
    if (mensaje != "") {
        //mostrarMensajeLogin();
        $("#mensaje").show();
        $("#mensaje").addClass("alert alert-danger");
        $("#mensaje").html(mensaje);
        if (cant == 1) {
            txt.focus();
        }
        ocultarMensaje("#mensaje");
    } else {
        $.ajax({
            url: "php/aco_php/psi_verificar.php",
            dataType: "html",
            type: "POST",
            data: {
                psi_usuario: usua.val(),
                psi_contrasena: contra.val(),
                psi_remember: remember
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
            success: function (datos) {
                if (datos == 1) {
                    window.location.href = "principal.php";
                } else {
                    $("#mensaje").show();
                    $("#mensaje").addClass("alert alert-danger");
                    $("#mensaje").html(datos);
                    ocultarMensaje("#mensaje");
                }
            }
        });
    }
}

function ocultarMensaje(id) {
    $(document).ready(function () {
        setTimeout(function () {
            $(id).fadeOut(3000);
        }, 5000);
    });
}

function enterLoginRecuperar(evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 13) {
        RecuperarContrasena();
    }
}

function RecuperarContrasena() {
    var correo = $("#txtCorreo");
    var mensaje = "";
    var cant = 0;
    var txt = "";

    $("#mensajeRecordar").html("");
    $("#mensajeRecordar").hide();

    if ($.trim(correo.val()) == "") {
        mensaje += "Ingrese el Correo Institucional<br>";
        cant++;
        txt = correo;
    }
    if (mensaje != "") {
        //mostrarMensajeLogin();
        $("#mensajeRecordar").show();
        $("#mensajeRecordar").addClass("alert alert-danger");
        $("#mensajeRecordar").html(mensaje);
        if (cant == 1) {
            txt.focus();
        }
        ocultarMensaje("#mensajeRecordar");
    } else {
        $.ajax({
            url: "php/aco_php/psi_verificar_contrasena.php",
            dataType: "html",
            type: "POST",
            data: {
                psi_correo: correo.val()
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
            success: function (datos) {
                var resp = datos.split("****");
                if (resp[1] == 1) {
                    $("#mensajeRecordar").show();
                    $("#mensajeRecordar").removeClass("alert alert-danger");
                    $("#mensajeRecordar").addClass("alert alert-success");
                    $("#mensajeRecordar").html(resp[2]);
                    ocultarMensaje("#mensajeRecordar");
                    $("#txtCorreo").val("");
                } else {
                    $("#mensajeRecordar").show();
                    $("#mensajeRecordar").removeClass("alert alert-success");
                    $("#mensajeRecordar").addClass("alert alert-danger");
                    $("#mensajeRecordar").html(resp[2]);
                    ocultarMensaje("#mensajeRecordar");
                }
            }
        });
    }
}

function enterCambiarContra(evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 13) {
        CambiarContrasena();
    }
}

function CambiarContrasena() {
    var codigo = $("#hdnUser");
    var token = $("#hdnToken");
    var nuevaContra = $("#txtNuevaContra");
    var confirContra = $("#txtConfirContra");
    var mensaje = "";
    var cant = 0;
    var txt = "";

    $("#mensajeContrasena").html("");
    $("#mensajeContrasena").hide();

    if ($.trim(nuevaContra.val()) == "") {
        mensaje += "Ingrese la nueva contraseña<br>";
        cant++;
        txt = nuevaContra;
    }
    if ($.trim(confirContra.val()) == "") {
        mensaje += "Ingrese confirmar la contraseña<br>";
        cant++;
        txt = confirContra;
    }

    if ($.trim(nuevaContra.val()) !== "" && $.trim(confirContra.val()) !== "") {
        if ($.trim(nuevaContra.val().length) < 7 && $.trim(confirContra.val().length) < 7) {
            mensaje += "Las contraseñas deben tener más de 6 caracteres<br>";
            cant++;
            txt = nuevaContra;
        } else {
            if ($.trim(nuevaContra.val()) !== $.trim(confirContra.val())) {
                mensaje += "Las contraseñas no coinciden<br>";
                cant++;
                txt = nuevaContra;
            }
        }
    }

    if (mensaje != "") {
        //mostrarMensajeLogin();
        $("#mensajeContrasena").show();
        $("#mensajeContrasena").addClass("alert alert-danger");
        $("#mensajeContrasena").html(mensaje);
        if (cant == 1) {
            txt.focus();
        }
        ocultarMensaje("#mensajeContrasena");
    } else {
        $.ajax({
            url: "../php/aco_php/psi_cambiar_contrasena.php",
            dataType: "html",
            type: "POST",
            data: {
                psi_id: codigo.val(),
                psi_token: token.val(),
                psi_nueva_contra: nuevaContra.val(),
                psi_confirmar_contra: confirContra.val()
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
            success: function (datos) {
                var resp = datos.split("**");
                if (resp[0] == "1") {
                    $("#mensajeContrasena").show();
                    $("#mensajeContrasena").removeClass("alert alert-danger");
                    $("#mensajeContrasena").addClass("alert alert-success");
                    $("#mensajeContrasena").html(resp[1]);
                    ocultarMensaje("#mensajeContrasena");
                    $("#hdnUser").val("");
                    $("#hdnToken").val("");
                    $("#txtNuevaContra").val("");
                    $("#txtConfirContra").val("");
                    $("#txtNuevaContra").attr('disabled','disabled');
                    $("#txtConfirContra").attr('disabled','disabled');
                    $(document).ready(function () {
                        setTimeout(function () {
                            location.reload();
                        }, 6000);
                    });
                } else {
                    $("#mensajeContrasena").show();
                    $("#mensajeContrasena").removeClass("alert alert-success");
                    $("#mensajeContrasena").addClass("alert alert-danger");
                    $("#mensajeContrasena").html(resp[1]);
                    ocultarMensaje("#mensajeContrasena");
                }
            }
        });
    }
}