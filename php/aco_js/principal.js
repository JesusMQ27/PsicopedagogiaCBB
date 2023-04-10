
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
            beforeSend: function (objeto) {
                $("#modal-nuevo-usuario").find('.modal-footer div label').html("");
                $("#modal-nuevo-usuario").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevo-menu").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-usuario').modal('hide');
                        $('.modal-backdrop').remove();
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
            beforeSend: function (objeto) {
                $("#modal-editar-menu").find('.modal-footer div label').html("");
                $("#modal-editar-menu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-menu").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-usuario').modal('hide');
                        $('.modal-backdrop').remove();
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
        beforeSend: function (objeto) {
            $("#modal-eliminar-usuario").find('.modal-footer div label').html("");
            $("#modal-eliminar-usuario").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-usuario").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-usuario').modal('hide');
                    $('.modal-backdrop').remove();
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
        beforeSend: function (objeto) {
            $("#modal-cambiar-contrasena-usuario").find('.modal-footer div label').html("");
            $("#modal-cambiar-contrasena-usuario").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-cambiar-contrasena-usuario").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-cambiar-contrasena-usuario').modal('hide');
                    $('.modal-backdrop').remove();
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
    var carpeta = $("#txtCarpeta");
    var mensaje = "";
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción del menú<br>";
    }
    if ($.trim(imagen.select().val()) == 0) {
        mensaje += "Ingrese la imagen del menú<br>";
    }
    if ($.trim(carpeta.val()) == "") {
        mensaje += "Ingrese el nombre de la carpeta<br>";
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
                m_imagen: $.trim(imagen.select().val()),
                m_carpeta: $.trim(carpeta.val())
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-menu").find('.modal-footer div label').html("");
                $("#modal-nuevo-menu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevo-menu").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-menu').modal('hide');
                        $("#btnRegistrarMenu").attr("disabled", false);
                        $('.modal-backdrop').remove();
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
    var carpetaEdi = $("#txtCarpetaEdi");
    var estadoMeEdi = $("#cbbEstadoMeEdi");
    var mensaje = "";
    if ($.trim(descripcionEdi.val()) == "") {
        mensaje += "Ingrese la descripción del menú<br>";
    }
    if ($.trim(imagenEdi.select().val()) == 0) {
        mensaje += "Ingrese la imagen del menú<br>";
    }
    if ($.trim(carpetaEdi.val()) == "") {
        mensaje += "Ingrese el nombre de la carpeta<br>";
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
                m_carpetaEdi: $.trim(carpetaEdi.val()),
                m_estadoMeEdi: $.trim(estadoMeEdi.select().val())
            },
            beforeSend: function (objeto) {
                $("#modal-editar-menu").find('.modal-footer div label').html("");
                $("#modal-editar-menu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-menu").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-menu').modal('hide');
                        $("#btnEditarMenu").attr("disabled", false);
                        $('.modal-backdrop').remove();
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
        beforeSend: function (objeto) {
            $("#modal-eliminar-menu").find('.modal-footer div label').html("");
            $("#modal-eliminar-menu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-menu").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-menu').modal('hide');
                    $("#btnEliminarMenu").attr("disabled", false);
                    $('.modal-backdrop').remove();
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

function mostrar_registra_nuevo_submenu(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevo_submenu"
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbSubImagen').selectpicker();
        }
    });
}

function registrar_submenu() {
    $("#btnRegistrarSubmenu").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var descripcion = $("#txtDescripcionSub");
    var menu = $("#cbbMenus");
    var imagen = $("#cbbSubImagen");
    var link = $("#txtLinkSub");
    var mensaje = "";
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción del submenú<br>";
    }
    if ($.trim(menu.select().val()) == 0) {
        mensaje += "Ingrese el menú<br>";
    }
    if ($.trim(imagen.select().val()) == 0) {
        mensaje += "Ingrese la imagen del submenú<br>";
    }
    if ($.trim(link.val()) == "") {
        mensaje += "Ingrese el link del submenú<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarSubmenu").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_registro_nuevo_submenu",
                sm_codigo: $.trim(codSMenu.val()),
                s_descripcion: $.trim(descripcion.val()),
                s_menu: $.trim(menu.val()),
                s_imagen: $.trim(imagen.select().val()),
                s_link: $.trim(link.val())
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-submenu").find('.modal-footer div label').html("");
                $("#modal-nuevo-submenu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevo-submenu").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-submenu').modal('hide');
                        $("#btnRegistrarSubmenu").attr("disabled", false);
                        $('.modal-backdrop').remove();
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
                        $("#btnRegistrarSubmenu").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_submenu(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_submenu",
            u_esub_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbSubImagenEdi').selectpicker();
        }
    });
}

function editar_submenu() {
    $("#btnEditarSubmenu").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var codiSubMenu = $("#hdnCodiSubMenu");
    var descripcion = $("#txtDescripcionSubEdi");
    var menu = $("#cbbMenusEdi");
    var imagen = $("#cbbSubImagenEdi");
    var link = $("#txtLinkSubEdi");
    var estado = $("#cbbEstadosubEdi");
    var mensaje = "";
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción del submenú<br>";
    }
    if ($.trim(menu.select().val()) == 0) {
        mensaje += "Ingrese el menú<br>";
    }
    if ($.trim(imagen.select().val()) == 0) {
        mensaje += "Ingrese la imagen del submenú<br>";
    }
    if ($.trim(link.val()) == "") {
        mensaje += "Ingrese el link del submenú<br>";
    }
    if ($.trim(estado.select().val()) == -1) {
        mensaje += "Ingrese el estado del submenú<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarSubmenu").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_submenu",
                sub_codisubmenu: $.trim(codiSubMenu.val()),
                sm_codigo: $.trim(codSMenu.val()),
                sub_descripcion: $.trim(descripcion.val()),
                sub_menu: $.trim(menu.val()),
                sub_imagen: $.trim(imagen.select().val()),
                sub_link: $.trim(link.val()),
                sub_estado: $.trim(estado.val())
            },
            beforeSend: function (objeto) {
                $("#modal-editar-submenu").find('.modal-footer div label').html("");
                $("#modal-editar-submenu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-submenu").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-submenu').modal('hide');
                        $("#btnEditarSubmenu").attr("disabled", false);
                        $('.modal-backdrop').remove();
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
                        $("#btnEditarSubmenu").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}


function mostrar_eliminar_submenu(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_submenu",
            u_elsub_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
        }
    });
}

function eliminar_submenu() {
    $("#btnEliminarSubmenu").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiSubmenuEli = $("#hdnCodiSubmenuEli");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_submenu",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiSubmenuIdEli: $.trim(hdnCodiSubmenuEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-submenu").find('.modal-footer div label').html("");
            $("#modal-eliminar-submenu").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-submenu").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-submenu').modal('hide');
                    $("#btnEliminarSubmenu").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnEliminarSubmenu").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_registra_nuevo_perfil(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevo_perfil"
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
    $("#btnRegistrarPerfil").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var descripcion = $("#txtDescripcionPer");
    var checkList = "";
    var mensaje = "";
    $('#listFieldset').find('input[type=checkbox]:checked').each(function () {
        var value = (this.checked ? $(this).val() : "");
        //var id = $(this).attr("id");
        checkList += value + "*";
    });
    checkList = checkList.slice(0, checkList.length - 1);
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción del perfil<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarPerfil").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_nuevo_perfil",
                sm_codigo: $.trim(codSMenu.val()),
                per_descripcion: $.trim(descripcion.val()),
                per_lista: $.trim(checkList)
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-perfil").find('.modal-footer div label').html("");
                $("#modal-nuevo-perfil").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevo-perfil").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-perfil').modal('hide');
                        $("#btnRegistrarPerfil").attr("disabled", false);
                        $('.modal-backdrop').remove();
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
                        $("#btnRegistrarPerfil").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_perfil(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_perfil",
            u_eperf_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbSubImagenEdi').selectpicker();
        }
    });
}

function editar_perfil() {
    $("#btnEditarPerfil").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var codiPerfil = $("#hdnCodiPerfil");
    var descripcion = $("#txtDescripcionPerEdi");
    var estado = $("#cbbEstadoPerEdi");
    var checkList = "";
    var mensaje = "";
    $('#listFieldsetEdi').find('input[type=checkbox]:checked').each(function () {
        var value = (this.checked ? $(this).val() : "");
        //var id = $(this).attr("id");
        checkList += value + "*";
    });
    checkList = checkList.slice(0, checkList.length - 1);
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción del perfil<br>";
    }
    if ($.trim(estado.select().val()) == -1) {
        mensaje += "Ingrese el estado del perfil<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarPerfil").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_perfil",
                perf_codigo: $.trim(codiPerfil.val()),
                sm_codigo: $.trim(codSMenu.val()),
                perf_descripcion: $.trim(descripcion.val()),
                perf_estado: $.trim(estado.val()),
                perf_lista: $.trim(checkList)
            },
            beforeSend: function (objeto) {
                $("#modal-editar-perfil").find('.modal-footer div label').html("");
                $("#modal-editar-perfil").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-perfil").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-perfil').modal('hide');
                        $("#btnEditarPerfil").attr("disabled", false);
                        $('.modal-backdrop').remove();
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
                        $("#btnEditarPerfil").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_eliminar_perfil(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_perfil",
            u_eliperf_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
        }
    });
}

function eliminar_perfil() {
    $("#btnEliminarPerfil").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiPerfilEli = $("#hdnCodiPerfilEli");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_perfil",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiPerfilIdEli: $.trim(hdnCodiPerfilEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-perfil").find('.modal-footer div label').html("");
            $("#modal-eliminar-perfil").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-perfil").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-perfil').modal('hide');
                    $("#btnEliminarPerfil").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnEliminarPerfil").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_confirmacion_carga_alumnos(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_confirmacion_carga_alumnos",
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
        }
    });
}

function registrar_carga_alumnos() {
    $("#btnRegistrarCargaAlumno").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var cod = $("#hdnNumeral").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_registrar_carga_alumnos",
            sm_codigo: $.trim(codSMenu.val()),
            u_codPerson: $.trim(cod)
        },
        beforeSend: function (objeto) {
            $("#modal-confirmar-carga-alumnos").find('.modal-footer div label').html("");
            $("#modal-confirmar-carga-alumnos").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                //$("#modal-confirmar-carga-alumnos").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-confirmar-carga-alumnos').modal('hide');
                    $('#modal-carga-alumno').modal('hide');
                    $("#btnRegistrarCargaAlumno").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnRegistrarCargaAlumno").attr("disabled", false);
                }, 4500);
            }
            $("#modal-confirmar-carga-alumnos").find('.modal-footer div label').html("");
        }
    });
}

function mostrar_detalle_grupo(modal, codigo, nombre) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_detalle_grupo",
            u_gru_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
            modal.find('.modal-header .modal-title').html('Grupo: ' + nombre);
            $("#tableGrupoDetalle").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                "buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableGrupoDetalle_wrapper .col-md-6:eq(0)');
        }
    });
}

function eliminar_detalle_grupo(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_detalle_grupo",
            u_gru_codigo: codigo
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

function eliminar_carga_alumnos() {
    $("#btnEliminarCargaAlumno").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiGrupoEli = $("#hdnCodiGrupo");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_detalle_grupo",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiGrupoIdEli: $.trim(hdnCodiGrupoEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-carga-alumno").find('.modal-footer div label').html("");
            $("#modal-eliminar-carga-alumno").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-carga-alumno").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-carga-alumno').modal('hide');
                    $("#btnEliminarCargaAlumno").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnEliminarCargaAlumno").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function activar_detalle_grupo(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_activar_detalle_grupo",
            u_gru_codigo: codigo
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

function activar_carga_alumnos() {
    $("#btnActivarCargaAlumno").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiGrupoEli = $("#hdnCodiGrupoAc");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_activar_detalle_grupo",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiGrupoIdEli: $.trim(hdnCodiGrupoEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-activar-carga-alumno").find('.modal-footer div label').html("");
            $("#modal-activar-carga-alumno").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-activar-carga-alumno").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-activar-carga-alumno').modal('hide');
                    $("#btnActivarCargaAlumno").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnActivarCargaAlumno").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function registrar_carga_usuarios() {
    $("#btnRegistrarCargaUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var cod = $("#hdnNumeralUsu").val();
    $.ajax({
        url: "php/aco_php/psi_registrar_usuarios.php",
        dataType: "html",
        type: "POST",
        data: {
            sm_codigo: $.trim(codSMenu.val()),
            u_codPerson: $.trim(cod)
        },
        beforeSend: function (objeto) {
            $("#modal-confirmar-carga-usuarios").find('.modal-footer div label').html("");
            $("#modal-confirmar-carga-usuarios").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-confirmar-carga-usuarios").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-confirmar-carga-usuarios').modal('hide');
                    $("#btnRegistrarCargaUsuario").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnRegistrarCargaUsuario").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_detalle_grupo_usuarios(modal, codigo, nombre) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_detalle_grupo_usuarios",
            u_gru_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
            modal.find('.modal-header .modal-title').html('Grupo: ' + nombre);
            $("#tableGrupoDetalleUsuarios").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                "buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableGrupoDetalleUsuarios_wrapper .col-md-6:eq(0)');
        }
    });
}

function eliminar_detalle_grupo_usuario(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_detalle_grupo_usuario",
            u_gru_codigo: codigo
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

function eliminar_carga_usuarios() {
    $("#btnEliminarCargaUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiGrupoEli = $("#hdnCodiGrupoUsu");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_detalle_grupo_usuario",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiGrupoIdEliUsua: $.trim(hdnCodiGrupoEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-carga-usuario").find('.modal-footer div label').html("");
            $("#modal-eliminar-carga-usuario").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-carga-usuario").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-carga-usuario').modal('hide');
                    $("#btnEliminarCargaUsuario").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnEliminarCargaUsuario").attr("disabled", false);
                }, 4500);
            }
        }
    });
}


function activar_detalle_grupo_usuario(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_activar_detalle_grupo_usuario",
            u_gru_codigo: codigo
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


function activar_carga_usuarios() {
    $("#btnActivarCargaUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiAU");
    var hdnCodiGrupoEli = $("#hdnCodiGrupoUsu");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_activar_detalle_grupo_usuario",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiGrupoIdEliUsua: $.trim(hdnCodiGrupoEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-activar-carga-usuario").find('.modal-footer div label').html("");
            $("#modal-activar-carga-usuario").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-activar-carga-usuario").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-activar-carga-usuario').modal('hide');
                    $("#btnActivarCargaUsuario").attr("disabled", false);
                    $('.modal-backdrop').remove();
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
                    $("#btnActivarCargaUsuario").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_confirmacion_carga_usuarios(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_confirmacion_carga_usuarios",
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
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