
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
            /*Modales de Administrar Usuarios*/
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
            $('#modal-cambiar-contrasena-usuario').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var usuario = button.data('usuario');
                cambiar_clave_usuario(modal, usuario);
            });

            /*Modales de Administrar Menus*/
            $('#modal-nuevo-menu').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                mostrar_registra_nuevo_menu(modal);
            });
            $('#modal-editar-menu').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var menu = button.data('menu');
                mostrar_editar_menu(modal, menu);
            });
            $('#modal-eliminar-menu').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var menu = button.data('menu');
                mostrar_eliminar_menu(modal, menu);
            });

            /*Modales de Administrar Perfiles*/
            $('#modal-nuevo-perfil').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                mostrar_registra_nuevo_perfil(modal);
            });
            $('#modal-editar-perfil').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var usuario = button.data('perfil');
                mostrar_editar_perfil(modal, usuario);
            });
            $('#modal-eliminar-perfil').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var usuario = button.data('perfil');
                mostrar_eliminar_perfil(modal, usuario);
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
    if ($.trim(numDoc.val()) == "") {
        mensaje += "Ingrese el número de documento<br>";
    }
    if ($.trim(paterno.val()) == "") {
        mensaje += "Ingrese el apellido paterno<br>";
    }
    if ($.trim(materno.val()) == "") {
        mensaje += "Ingrese el apellido materno<br>";
    }
    if ($.trim(nombres.val()) == "") {
        mensaje += "Ingrese el(los) nombre(s)<br>";
    }
    if ($.trim(correo.val()) == "") {
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
    $("#btnEditarUsuario").attr("disabled", true);
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
    if ($.trim(numDoc.val()) == "") {
        mensaje += "Ingrese el número de documento<br>";
    }
    if ($.trim(paterno.val()) == "") {
        mensaje += "Ingrese el apellido paterno<br>";
    }
    if ($.trim(materno.val()) == "") {
        mensaje += "Ingrese el apellido materno<br>";
    }
    if ($.trim(nombres.val()) == "") {
        mensaje += "Ingrese el(los) nombre(s)<br>";
    }
    if ($.trim(correo.val()) == "") {
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

function cambiar_clave_usuario(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_cambiar_clave_usuario",
            u_cc_codigo: codigo
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

function envio_clave_usuario() {
    $("#btnCambiarContrasenaUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiUsuaCam = $("#hdnCodiUsuaCam");
    $.ajax({
        url: "php/aco_php/psi_cambiar_contrasena_usuario.php",
        dataType: "html",
        type: "POST",
        data: {
            sm_codigo: $.trim(codSMenu.val()),
            u_hdnCodiUsuaCam: $.trim(hdnCodiUsuaCam.val())
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
                    $('#modal-cambiar-contrasena-usuario').modal('hide');
                    $("#btnCambiarContrasenaUsuario").attr("disabled", false);
                    cargar_opcion(lista_sm[0], lista_sm[1], lista_sm[2]);
                }, 5500);
            } else {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $("#btnCambiarContrasenaUsuario").attr("disabled", false);
                }, 5500);
            }
        }
    });
}

function mostrar_registra_nuevo_menu(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevo_menu"
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbImagen').selectpicker();
        }
    });
}

function registrar_menu() {
    $("#btnRegistrarMenu").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var descripcion = $("#txtDescripcion");
    var imagen = $("#cbbImagen");
    var mensaje = "";
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción del menú<br>";
    }
    if ($.trim(imagen.select().val()) == 0) {
        mensaje += "Ingrese la imagen del menú<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarMenu").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_registro_nuevo_menu",
                sm_codigo: $.trim(codSMenu.val()),
                m_descripcion: $.trim(descripcion.val()),
                m_imagen: $.trim(imagen.select().val())
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
                        $('#modal-nuevo-menu').modal('hide');
                        $("#btnRegistrarMenu").attr("disabled", false);
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
                        $("#btnRegistrarMenu").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_menu(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_menu",
            u_em_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbImagenEdi').selectpicker();
        }
    });
}

function editar_menu() {
    $("#btnEditarMenu").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var codMenu = $("#hdnCodiMenu");
    var descripcionEdi = $("#txtDescripcionEdi");
    var imagenEdi = $("#cbbImagenEdi");
    var estadoMeEdi = $("#cbbEstadoMeEdi");
    var mensaje = "";
    if ($.trim(descripcionEdi.val()) == "") {
        mensaje += "Ingrese la descripción del menú<br>";
    }
    if ($.trim(imagenEdi.select().val()) == 0) {
        mensaje += "Ingrese la imagen del menú<br>";
    }
    if ($.trim(estadoMeEdi.select().val()) == -1) {
        mensaje += "Ingrese el estado del menú<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarMenu").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_menu",
                sm_codigo: $.trim(codSMenu.val()),
                m_codigoEdi: $.trim(codMenu.val()),
                m_descripcionEdi: $.trim(descripcionEdi.val()),
                m_imagenEdi: $.trim(imagenEdi.select().val()),
                m_estadoMeEdi: $.trim(estadoMeEdi.select().val())
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
                        $('#modal-editar-menu').modal('hide');
                        $("#btnEditarMenu").attr("disabled", false);
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
                        $("#btnEditarMenu").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_eliminar_menu(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_menu",
            u_elmenu_codigo: codigo
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

function eliminar_menu() {
    $("#btnEliminarMenu").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiMenuEli = $("#hdnCodiMenuEli");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_menu",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiMenuIdEli: $.trim(hdnCodiMenuEli.val())
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
                    $('#modal-eliminar-menu').modal('hide');
                    $("#btnEliminarMenu").attr("disabled", false);
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
                    $("#btnEliminarMenu").attr("disabled", false);
                }, 4500);
            }
        }
    });
}


function mostrar_registra_nuevo_perfil() {
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

function registrar_perfil() {

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