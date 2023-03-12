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
        ocultarMensajeLogin();
    } else {
        $.ajax({
            url: "php/aco_php/psi_verificar.php",
            dataType: "html",
            type: "POST",
            data: {
                psi_usuario: usua.val(),
                psi_contrasena: contra.val()
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
                    ocultarMensajeLogin();
                }
            }
        });
    }
}

function ocultarMensajeLogin() {
    $(document).ready(function () {
        setTimeout(function () {
            $("#mensaje").fadeOut(3000);
        }, 5000);
    });
}
