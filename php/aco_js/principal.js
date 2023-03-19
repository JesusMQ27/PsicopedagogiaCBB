/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function cargar_opcion(codigo, ruta, nombre) {
    $.ajax({
        url: ruta,
        dataType: "html",
        type: "POST",
        data: {
            codigo_menu: codigo,
            nombre_opcion: nombre
        },
        beforeSend: function (objeto) {
            $("#panelRegistroMatricula").append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#contenido").html(datos);
            $('#modal-nuevo-usuario').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                mostrar_registra_nuevo_usuario(modal);
            });

            $('#modal-editar-usuario').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var usuario = button.data('usuario');
                mostrar_editar_usuario(modal, usuario);
            });

            $('#modal-eliminar-usuario').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var usuario = button.data('usuario');
                mostrar_eliminar_usuario(modal, usuario);
            });

            $('#modal-restaurar-contrasena-usuario').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var usuario = button.data('usuario');
                cambiar_contrasena_usuario(modal, usuario);
            });
        }
    });
}

function mostrar_registra_nuevo_usuario(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevo_usuario"
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
        }
    });
}

function registrar_usuario() {
    $("#btnRegistrarUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var tipoUsuario = $("#cbbTipoUsuario");
    var tipoDoc = $("#cbbTipoDoc");
    var numDoc = $("#txtNumDoc");
    var paterno = $("#txtPaterno");
    var materno = $("#txtMaterno");
    var nombres = $("#txtNombres");
    var correo = $("#txtCorreo");
    var telefono = $("#txtTelefono");
    var sede = $("#cbbSede");
    var sexo = $("#cbbSexo");
    var mensaje = "";
    if ($.trim(tipoUsuario.select().val()) == 0) {
        mensaje += "Ingrese el tipo de usuario<br>";
    }
    if ($.trim(tipoDoc.select().val()) == 0) {
        mensaje += "Ingrese el tipo de documento<br>";
    }
    if ($.trim(numDoc.val()) == 0) {
        mensaje += "Ingrese el número de documento<br>";
    }
    if ($.trim(paterno.val()) == 0) {
        mensaje += "Ingrese el apellido paterno<br>";
    }
    if ($.trim(materno.val()) == 0) {
        mensaje += "Ingrese el apellido materno<br>";
    }
    if ($.trim(nombres.val()) == 0) {
        mensaje += "Ingrese el(los) nombre(s)<br>";
    }
    if ($.trim(correo.val()) == 0) {
        mensaje += "Ingrese el correo<br>";
    } else {
        if (valida_correo(correo)) {

        } else {
            mensaje += "Ingrese un correo electrónico válido<br>";
        }
    }
    if ($.trim(sede.select().val()) == 0) {
        mensaje += "Ingrese la sede<br>";
    }
    if ($.trim(sexo.select().val()) == 0) {
        mensaje += "Ingrese el sexo<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarUsuario").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/psi_registrar_usuario.php",
            dataType: "html",
            type: "POST",
            data: {
                sm_codigo: $.trim(codSMenu.val()),
                u_tipoUsuario: $.trim(tipoUsuario.select().val()),
                u_tipoDoc: $.trim(tipoDoc.select().val()),
                u_numDoc: $.trim(numDoc.val()),
                u_paterno: $.trim(paterno.val()),
                u_materno: $.trim(materno.val()),
                u_nombres: $.trim(nombres.val()),
                u_correo: $.trim(correo.val()),
                u_telefono: $.trim(telefono.val()),
                u_sede: $.trim(sede.select().val()),
                u_sexo: $.trim(sexo.select().val())
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-usuario').modal('hide');
                        $("#btnRegistrarUsuario").attr("disabled", false);
                        cargar_opcion(lista_sm[0], lista_sm[1], lista_sm[2]);
                    }, 4500);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#btnRegistrarUsuario").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_usuario(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_usuario",
            u_e_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
        }
    });
}

function editar_usuario() {
    $("#btnRegistrarUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiUsua = $("#hdnCodiUsua");
    var tipoUsuario = $("#cbbTipoUsuarioEdi");
    var tipoDoc = $("#cbbTipoDocEdi");
    var numDoc = $("#txtNumDocEdi");
    var paterno = $("#txtPaternoEdi");
    var materno = $("#txtMaternoEdi");
    var nombres = $("#txtNombresEdi");
    var correo = $("#txtCorreoEdi");
    var telefono = $("#txtTelefonoEdi");
    var sede = $("#cbbSedeEdi");
    var sexo = $("#cbbSexoEdi");
    var estado = $("#cbbEstadoEdi");
    var mensaje = "";
    if ($.trim(tipoUsuario.select().val()) == 0) {
        mensaje += "Ingrese el tipo de usuario<br>";
    }
    if ($.trim(tipoDoc.select().val()) == 0) {
        mensaje += "Ingrese el tipo de documento<br>";
    }
    if ($.trim(numDoc.val()) == 0) {
        mensaje += "Ingrese el número de documento<br>";
    }
    if ($.trim(paterno.val()) == 0) {
        mensaje += "Ingrese el apellido paterno<br>";
    }
    if ($.trim(materno.val()) == 0) {
        mensaje += "Ingrese el apellido materno<br>";
    }
    if ($.trim(nombres.val()) == 0) {
        mensaje += "Ingrese el(los) nombre(s)<br>";
    }
    if ($.trim(correo.val()) == 0) {
        mensaje += "Ingrese el correo<br>";
    } else {
        if (valida_correo(correo)) {

        } else {
            mensaje += "Ingrese un correo electrónico válido<br>";
        }
    }
    if ($.trim(sede.select().val()) == 0) {
        mensaje += "Ingrese la sede<br>";
    }
    if ($.trim(sexo.select().val()) == 0) {
        mensaje += "Ingrese el sexo<br>";
    }
    if ($.trim(estado.select().val()) == -1) {
        mensaje += "Ingrese el estado<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarUsuario").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_editar_usuario",
                sm_codigo: $.trim(codSMenu.val()),
                u_codiUsuIdEdi: $.trim(hdnCodiUsua.val()),
                u_tipoUsuarioEdi: $.trim(tipoUsuario.select().val()),
                u_tipoDocEdi: $.trim(tipoDoc.select().val()),
                u_numDocEdi: $.trim(numDoc.val()),
                u_paternoEdi: $.trim(paterno.val()),
                u_maternoEdi: $.trim(materno.val()),
                u_nombresEdi: $.trim(nombres.val()),
                u_correoEdi: $.trim(correo.val()),
                u_telefonoEdi: $.trim(telefono.val()),
                u_sedeEdi: $.trim(sede.select().val()),
                u_sexoEdi: $.trim(sexo.select().val()),
                u_estadoEdi: $.trim(estado.select().val())
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-usuario').modal('hide');
                        $("#btnEditarUsuario").attr("disabled", false);
                        cargar_opcion(lista_sm[0], lista_sm[1], lista_sm[2]);
                    }, 4500);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#btnEditarUsuario").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_eliminar_usuario(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_usuario",
            u_el_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
        }
    });
}

function eliminar_usuario() {
    $("#btnEliminarUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiUsuaEli = $("#hdnCodiUsuaEli");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_usuario",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiUsuIdEli: $.trim(hdnCodiUsuaEli.val())
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-usuario').modal('hide');
                    $("#btnEliminarUsuario").attr("disabled", false);
                    cargar_opcion(lista_sm[0], lista_sm[1], lista_sm[2]);
                }, 4500);
            } else {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $("#btnEliminarUsuario").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function cambiar_contrasena_usuario(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_cambiar_contrasena_usuario",
            u_e_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
        }
    });
}



function valida_correo(id) {
// Define our regular expression.
    var validEmail = /^\w+([.-_+]?\w+)*@\w+([.-]?\w+)*(\.\w{2,10})+$/;
    // Using test we can check if the text match the pattern
    if (validEmail.test(id.val())) {
        return true;
    } else {
        return false;
    }
}

function solo_numeros(e) {
    var key = window.Event ? e.which : e.keyCode;
    return ((key >= 48 && key <= 57) || (key == 46) || (key == 8));
}

function solo_letras(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8 || tecla == 0) {
        return true;
    }
    patron = /^[a-zA-Z.\x09\xD1\xF1\s]+$/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}