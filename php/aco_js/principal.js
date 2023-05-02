var nameIdMap = {};
var canvas1 = {};
var canvas2 = {};
var signaturePad = {};
var signaturePad_entrevistador = {};
var canvas1_sub = {};
var canvas2_sub = {};
var signaturePad_sub = {};
var signaturePad_entrevistador_sub = {};
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
    //checkList = checkList.slice(0, checkList.length - 1);
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

function cerrar_ventana_confirmacion_alumno() {
    $("#modal-carga-alumno").css("overflow-y", "auto");
}

function cerrar_ventana_editar_apoderado() {
    $("#modal-nueva-solicitud").css("overflow-y", "auto");
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

function mostrar_nueva_solicitud(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nueva_solicitud",
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
            var array = [];
            $("#btnRegistrarSolicitud").attr("disabled", true);
            $('input#searchAlumno').typeahead({
                hint: true,
                highlight: true,
                minLength: 4,
                source: function (query, result) {
                    $.ajax({
                        url: 'php/aco_php/buscar_alumno.php',
                        method: 'POST',
                        data: {query: query},
                        dataType: "json",
                        success: function (data) {
                            //result(getOptionsFromJson(data));
                            array = [];
                            result($.map(data, function (item) {
                                array.push({'value': item.value, 'label': item.label});
                                return item.label;
                            }));
                        },

                    });
                },
                updater: function (item) {
                    const found = array.find(el => el.label === item);
                    $("#matric").val(found.value);
                    $("#dataAlumno").html(item);
                    mostrar_tipo_solicitud("");
                }
            });
            modal.find('input[id="searchAlumno"]').focus();
        }
    });
}

function mostrar_info_apoderado(codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_info_apoderado",
            alu_cod: $("#txtAlumCodig").val(),
            tip_apod: codigo.value
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var arreglo = datos.split("****");
            $('#detalleApoderado').html(arreglo[0]);
            $("#divEditarInfoApoderado").html(arreglo[1]);
            $("#divApoderadoNombreDNI").html(arreglo[2]);
            $("#cbbTipoApoderado").removeClass("is-invalid");
            if (codigo.value === "-1") {
                mostrar_nuevo_apoderado($("#modal-nuevo-apoderado"), $.trim($("#txtAlumCodig").val()));
            }
        }
    });
}

function mostrar_editar_apoderado(modal, alumno, apoderado) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_apoderado",
            sm_alumno: alumno,
            sm_apoderado: apoderado
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $("#txtDniApoderado").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtCorreoApoderado").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtTelfApoderado").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
        }
    });
}

function mostrar_nuevo_apoderado(modal, alumno) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nuevo_apoderado",
            sm_alumno: alumno
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            modal.modal({backdrop: 'static', keyboard: false}, 'show');
            $("#txtDniN").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtNombresApoderadoN").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtCorreoApoderadoN").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtTelfApoderadoN").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDireccionN").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDistritoN").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
        }
    });
}

function editar_apoderado() {
    $("#btnEditarApoderado").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var txtAlumnCodigo = $("#txtAlumnCodigo");
    var txtApoderadoCod = $("#txtApoderadoCod");
    var txtDniApoderado = $("#txtDniApoderado");
    var txtCorreoApoderado = $("#txtCorreoApoderado");
    var txtTelfApoderado = $("#txtTelfApoderado");
    var mensaje = "";
    if ($.trim(txtDniApoderado.val()) == "") {
        mensaje += "Ingrese en DNI<br>";
        $("#txtDniApoderado").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderado.val()) == "") {
        mensaje += "Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderado").addClass("is-invalid");
    }
    if ($.trim(txtTelfApoderado.val()) == "") {
        mensaje += "Ingrese el teléfono<br>";
        $("#txtTelfApoderado").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_editar_apoderado",
                a_txtAlumnCodigo: $.trim(txtAlumnCodigo.val()),
                a_txtApoderadoCod: $.trim(txtApoderadoCod.val()),
                a_txtDniApoderado: $.trim(txtDniApoderado.val()),
                a_txtCorreoApoderado: $.trim(txtCorreoApoderado.val()),
                a_txtTelfApoderado: $.trim(txtTelfApoderado.val())
            },
            beforeSend: function (objeto) {
                $("#modal-editar-apoderado").find('.modal-footer div label').html("");
                $("#modal-editar-apoderado").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[0] === "1") {
                    $("#modal-editar-apoderado").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#cbbTipoApoderado").html(resp[2]);
                        $("#detalleApoderado").html(resp[3]);
                        $("#divApoderadoNombreDNI").html(resp[4]);
                        $('#modal-editar-apoderado').modal('hide');
                        $("#btnEditarApoderado").attr("disabled", false);
                        //$('.modal-backdrop').remove();
                        $("#modal-nueva-solicitud").css("overflow-y", "auto");
                    }, 3000);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    $("#btnEditarApoderado").attr("disabled", false);
                }
            }
        });
    }
}

function registrar_nuevo_apoderado() {
    $("#btnNuevoApoderado").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var txtAlumnoCodiN = $("#txtAlumnoCodiN");
    var cbbTipoApoderadoN = $("#cbbTipoApoderadoN");
    var txtDniN = $("#txtDniN");
    var txtNombresApoderadoN = $("#txtNombresApoderadoN");
    var txtCorreoApoderadoN = $("#txtCorreoApoderadoN");
    var txtTelfApoderadoN = $("#txtTelfApoderadoN");
    var txtDireccionN = $("#txtDireccionN");
    var txtDistritoN = $("#txtDistritoN");
    var mensaje = "";
    if ($.trim(txtDniN.val()) == "") {
        mensaje += "*Ingrese en DNI<br>";
        $("#txtDniN").addClass("is-invalid");
    } else {
        if ($.trim(txtDniN.val()).length < 8) {
            mensaje += "*Ingrese un DNI válido<br>";
        }
    }
    if ($.trim(txtNombresApoderadoN.val()) == "") {
        mensaje += "*Ingrese los apellidos y nombres<br>";
        $("#txtNombresApoderadoN").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderadoN.val()) == "") {
        mensaje += "*Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderadoN").addClass("is-invalid");
    } else {
        if (!valida_correo(txtCorreoApoderadoN)) {
            mensaje += "*Ingrese un correo electrónico válido<br>";
        }
    }
    if ($.trim(txtTelfApoderadoN.val()) == "") {
        mensaje += "*Ingrese el teléfono<br>";
        $("#txtTelfApoderadoN").addClass("is-invalid");
    }
    if ($.trim(txtDireccionN.val()) == "") {
        mensaje += "*Ingrese la dirección<br>";
        $("#txtDireccionN").addClass("is-invalid");
    }
    if ($.trim(txtDistritoN.val()) == "") {
        mensaje += "*Ingrese el distrito<br>";
        $("#txtDistritoN").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_registrar_apoderado",
                a_txtAlumnoCodiN: $.trim(txtAlumnoCodiN.val()),
                a_cbbTipoApoderadoN: $.trim(cbbTipoApoderadoN.select().val()),
                a_txtDniN: $.trim(txtDniN.val()),
                a_txtNombresApoderadoN: $.trim(txtNombresApoderadoN.val()),
                a_txtCorreoApoderadoN: $.trim(txtCorreoApoderadoN.val()),
                a_txtTelfApoderadoN: $.trim(txtTelfApoderadoN.val()),
                a_txtDireccionN: $.trim(txtDireccionN.val()),
                a_txtDistritoN: $.trim(txtDistritoN.val())
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-apoderado").find('.modal-footer div label').html("");
                $("#modal-nuevo-apoderado").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[0] === "1") {
                    $("#modal-nuevo-apoderado").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#cbbTipoApoderado").html(resp[2]);
                        $("#divEditarInfoApoderado").html(resp[3]);
                        $("#detalleApoderado").html(resp[4]);
                        $("#divApoderadoNombreDNI").html(resp[5]);
                        $('#modal-nuevo-apoderado').modal('hide');
                        $("#btnNuevoApoderado").attr("disabled", false);
                        //$('.modal-backdrop').remove();
                        $("#modal-nueva-solicitud").css("overflow-y", "auto");
                    }, 3000);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    $("#btnNuevoApoderado").attr("disabled", false);
                }
            }
        });
    }
}

function cerrar_ventana_nuevo_apoderado() {
    $("#modal-nueva-solicitud").css("overflow-y", "auto");
}

function getOptionsFromJson(json) {
    $.each(json, function (i, v) {
        nameIdMap[v.label] = v.value;
    });
    return $.map(json, function (n, i) {
        return n.label;
    });
}

function cargar_subcategorias(categoria) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_subcategorias",
            cat_cod: categoria.value
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $('#cbbSubcategoria').html(datos);
        }
    });
}

function mostrar_tipo_solicitud(dato) {
    var mensaje = "";
    var codSMenu = $("#hdnCodiSR");
    var dataAlumno = $("#dataAlumno");
    var matricu = $("#matric");
    var tipo = $("#cbbTipoSolicitud");
    var cbbCategoria = $("#cbbCategoria");
    var cbbSubcategoria = $("#cbbSubcategoria");
    if ($.trim(tipo.select().val()) == '' || $.trim(matricu.val()) == '') {
        $('#divEntrevista').html("");
        $("#btnRegistrarSolicitud").attr("disabled", true);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_detalle_tipo_solicitud",
                sol_matricula: matricu.val(),
                sol_tipo: tipo.select().val(),
                sol_categoria: cbbCategoria.select().val(),
                sol_subcategoria: cbbSubcategoria.select().val()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $('#divEntrevista').html(datos);
                $("#btnRegistrarSolicitud").attr("disabled", false);
                var wrapper = document.getElementById("signature-pad");
                canvas1 = wrapper.querySelector("canvas");
                signaturePad = new SignaturePad(canvas1, {
                    backgroundColor: 'rgb(255, 255, 255)'
                });
                var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador");
                canvas2 = wrapper_entrevistador.querySelector("canvas");
                signaturePad_entrevistador = new SignaturePad(canvas2, {
                    backgroundColor: 'rgb(255, 255, 255)'
                });
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas1.width = canvas1.offsetWidth * ratio;
                    canvas1.height = canvas1.offsetHeight * ratio;
                    canvas1.getContext("2d").scale(ratio, ratio);
                    signaturePad.clear();
                }

                function resizeCanvas2() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas2.width = canvas2.offsetWidth * ratio;
                    canvas2.height = canvas2.offsetHeight * ratio;
                    canvas2.getContext("2d").scale(ratio, ratio);
                    signaturePad_entrevistador.clear();
                }
                window.onresize = resizeCanvas;
                resizeCanvas();
                window.onresize = resizeCanvas2;
                resizeCanvas2();

                canvas1.addEventListener("click", function (event) {
                    if (!signaturePad.isEmpty()) {
                        canvas1.style.border = '1px solid black';
                    }
                });
                canvas2.addEventListener("click", function (event) {
                    if (!signaturePad_entrevistador.isEmpty()) {
                        canvas2.style.border = '1px solid black';
                    }
                });

                $("#cbbSubcategoria").change(function () {
                    var value = $(this).val();
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });

                $("#cbbCategoria").change(function () {
                    var value = $(this).val();
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });

                $("#txtMotivo").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanEstudiante").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanEntrevistador").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdos").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtInforme").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanPadre").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanDocente").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdosPadres").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdosColegio").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();

            }
        });
    }
}

function limpiar_firma() {
    signaturePad.clear();
}

function limpiar_firma_entrevistador() {
    signaturePad_entrevistador.clear();
}

function registrar_solicitud() {
    $("#btnRegistrarSolicitud").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var solicitud_tipo = $("#cbbTipoSolicitud").val();
    var docAlumno = $("#dataAlumno").html().split(" - ");
    var matricula = $("#matric").val();
    var sede = $("#txt_sede").val();
    var categoria = $("#cbbCategoria").select().val();
    var subcategoria = $("#cbbSubcategoria").select().val();
    var txtMotivo = "";
    var planEstudiante = "";
    var planEntrevistador = "";
    var acuerdos = "";
    var informe = "";
    var planPadre = "";
    var planDocente = "";
    var acuerdosPadres = "";
    var acuerdosColegio = "";
    var apoderado = "";
    var mensaje = "";
    var wrapper = document.getElementById("signature-pad");
    var canvas_1 = wrapper.querySelector("canvas");
    var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador");
    var canvas_2 = wrapper_entrevistador.querySelector("canvas");
    if (categoria === "") {
        mensaje += "*Seleccione la categoria<br>";
        $("#cbbCategoria").addClass("is-invalid");
    }
    if (subcategoria === "") {
        mensaje += "*Seleccione la subcategoria<br>";
        $("#cbbSubcategoria").addClass("is-invalid");
    }

    if (solicitud_tipo === "1") {
        txtMotivo = $("#txtMotivo").val();
        planEstudiante = $("#txtPlanEstudiante").val();
        planEntrevistador = $("#txtPlanEntrevistador").val();
        acuerdos = $("#txtAcuerdos").val();
        informe = "";
        planPadre = "";
        planDocente = "";
        acuerdosPadres = "";
        acuerdosColegio = "";
        apoderado = "";
        if ($.trim(txtMotivo) == "") {
            mensaje += "*Ingrese el motivo de solicitud<br>";
            $("#txtMotivo").addClass("is-invalid");
        }
        if ($.trim(planEstudiante) == '') {
            mensaje += "*Ingrese el Planteamiento del estudiante<br>";
            $("#txtPlanEstudiante").addClass("is-invalid");
        }
        if ($.trim(planEntrevistador) == '') {
            mensaje += "*Ingrese el Planteamiento del entrevistador(a)<br>";
            $("#txtPlanEntrevistador").addClass("is-invalid");
        }
        if ($.trim(acuerdos) == '') {
            mensaje += "*Ingrese los Acuerdos<br>";
            $("#txtAcuerdos").addClass("is-invalid");
        }
        if (signaturePad.isEmpty()) {
            mensaje += "*Ingrese la firma del estudiante<br>";
            canvas_1.style.border = '1px solid #dc3545';
        }
        if (signaturePad_entrevistador.isEmpty()) {
            mensaje += "*Ingrese la firma del entrevistador<br>";
            canvas_2.style.border = '1px solid #dc3545';
        }
    } else {
        txtMotivo = $("#txtMotivo").val();
        planEstudiante = "";
        planEntrevistador = "";
        acuerdos = "";
        informe = $("#txtInforme").val();
        planPadre = $("#txtPlanPadre").val();
        planDocente = $("#txtPlanDocente").val();
        acuerdosPadres = $("#txtAcuerdosPadres").val();
        acuerdosColegio = $("#txtAcuerdosColegio").val();
        apoderado = $("#cbbTipoApoderado").select().val();
        if ($.trim(apoderado) < 0 || $.trim(apoderado) === "") {
            mensaje += "*Seleccione el apoderado<br>";
            $("#cbbTipoApoderado").addClass("is-invalid");
        }
        if ($.trim(txtMotivo) == "") {
            mensaje += "*Ingrese el motivo de solicitud<br>";
            $("#txtMotivo").addClass("is-invalid");
        }
        if ($.trim(informe) == '') {
            mensaje += "*Ingrese el Informe<br>";
            $("#txtInforme").addClass("is-invalid");
        }
        if ($.trim(planPadre) == '') {
            mensaje += "*Ingrese el Planteamiento del padre, madre <br>";
            $("#txtPlanPadre").addClass("is-invalid");
        }
        if ($.trim(planDocente) == '') {
            mensaje += "*Ingrese el Planteamiento del docente<br>";
            $("#txtPlanDocente").addClass("is-invalid");
        }
        if ($.trim(acuerdosPadres) == '') {
            mensaje += "*Ingrese las acciones a realizar por los padres<br>";
            $("#txtAcuerdosPadres").addClass("is-invalid");
        }
        if ($.trim(acuerdosColegio) == '') {
            mensaje += "*Ingrese las acciones a realizar por el colegio<br>";
            $("#txtAcuerdosColegio").addClass("is-invalid");
        }
        /*if (signaturePad.isEmpty()) {
         mensaje += "Ingrese la firma del padre, madre o apoderado<br>";
         canvas.style.border = '1px solid #dc3545';
         }*/
        if (signaturePad_entrevistador.isEmpty()) {
            mensaje += "*Ingrese la firma del entrevistador<br>";
            canvas_2.style.border = '1px solid #dc3545';
        }
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarSolicitud").attr("disabled", false);
    } else {
        var codSMenu = $("#hdnCodiSR");
        var canvas1 = document.getElementById('canvas1');
        var dataURL1 = canvas1.toDataURL();
        var canvas2 = document.getElementById('canvas2');
        var dataURL2 = canvas2.toDataURL();
        $.ajax({
            url: "php/aco_php/psi_registrar_entrevista.php",
            dataType: "html",
            type: "POST",
            data: {
                sm_codigo: $.trim(codSMenu.val()),
                s_docAlumno: $.trim(docAlumno[0]),
                s_solicitud_tipo: solicitud_tipo,
                s_matricula: matricula,
                s_sede: sede,
                s_categoria: categoria,
                s_subcategoria: subcategoria,
                s_motivo: txtMotivo,
                s_planEstudiante: planEstudiante,
                s_planEntrevistador: planEntrevistador,
                s_acuerdos: acuerdos,
                s_informe: informe,
                s_planPadre: planPadre,
                s_planDocente: planDocente,
                s_acuerdosPadres: acuerdosPadres,
                s_acuerdosColegio: acuerdosColegio,
                s_apoderado: apoderado,
                s_dataURL1: dataURL1,
                s_dataURL2: dataURL2
            },
            beforeSend: function (objeto) {
                $("#modal-nueva-solicitud").find('.modal-footer div label').html("");
                $("#modal-nueva-solicitud").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nueva-solicitud").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nueva-solicitud').modal('hide');
                        $("#btnRegistrarSolicitud").attr("disabled", false);
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
                        $("#btnRegistrarSolicitud").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }

}


function mostrar_detalle_solicitud(modal, solicitud) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_detalle_solicitud",
            s_solicitud: solicitud
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

function mostrar_eliminar_solicitud(modal, solicitud) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_solicitud",
            s_solicitud: solicitud
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

function eliminar_solicitud() {
    $("#btnEliminarSolicitudAlumno").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSR");
    var hdnCodiGrupoEli = $("#hdnCodiSoliAlu");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_solicitud",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiSoliEliAlu: $.trim(hdnCodiGrupoEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-solicitud-alumno").find('.modal-footer div label').html("");
            $("#modal-eliminar-solicitud-alumno").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-solicitud-alumno").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-solicitud-alumno').modal('hide');
                    $("#btnEliminarSolicitudAlumno").attr("disabled", false);
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
                    $("#btnEliminarSolicitudAlumno").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function imprimir_ficha_entrevista() {
    var mode = 'iframe'; //popup
    var close = mode == "popup";
    var options = {
        mode: mode,
        popClose: close,
        standard: "html5",
        popTitle: 'relatorio',
        extraCss: './build/scss/mixins/_cards.scss,./plugins/fontawesome-free/css/all.min.css,./dist/css/adminlte.min.css,./php/aco_css/principal.css,./plugins/bootstrap/css/bootstrap-theme.min.css',
        extraHead: '',
        retainAttr: ["id", "class", "style"],
        printDelay: 500, // tempo de atraso na impressao
        printAlert: true, };
    $("div#modal-detalle-solicitud-alumno div.modal-body").printArea(options);
}

function buscar_semaforo_docente() {
    $("#btnRegistrarSolicitud").attr("disabled", true);
    var sede = $("#cbbSedes").select().val();
    var fecha_inicio = $("#fecha1").val();
    var fecha_fin = $("#fecha2").val();
    var semaforo = $("#cbbSemaforo").select().val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_buscar_semaforo_docentes",
            s_sede: sede,
            s_fecha_inicio: fecha_inicio,
            s_fecha_fin: fecha_fin,
            s_semaforo: semaforo
        },
        beforeSend: function (objeto) {
            $("#modal-nueva-solicitud-detalle").find('.modal-footer div label').html("");
            $("#modal-nueva-solicitud-detalle").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableSemaforo").find('tbody').html(datos);
        }
    });

}

function mostrar_nueva_sub_solicitud(modal, entrevista) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nueva_subsolicitud",
            s_entrevista: entrevista
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            modal.find('.modal-body').html(datos);
            var array = [];
            $("#btnRegistrarSubSolicitud").attr("disabled", true);
            $('input#searchAlumno_sub').typeahead({
                hint: true,
                highlight: true,
                minLength: 4,
                source: function (query, result) {
                    $.ajax({
                        url: 'php/aco_php/buscar_alumno.php',
                        method: 'POST',
                        data: {query: query},
                        dataType: "json",
                        success: function (data) {
                            //result(getOptionsFromJson(data));
                            array = [];
                            result($.map(data, function (item) {
                                array.push({'value': item.value, 'label': item.label});
                                return item.label;
                            }));
                        },

                    });
                },
                updater: function (item) {
                    const found = array.find(el => el.label === item);
                    $("#matric_sub").val(found.value);
                    $("#dataAlumno_sub").html(item);
                    mostrar_tipo_solicitud_sub("");
                }
            });
            modal.find('input[id="searchAlumno_sub"]').focus();
        }
    });
}

function cargar_subcategorias_sub(categoria) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_subcategorias",
            cat_cod: categoria.value
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $('#cbbSubcategoria_sub').html(datos);
        }
    });
}

function mostrar_tipo_solicitud_sub(dato) {
    var mensaje = "";
    var codSMenu = $("#hdnCodiSR");
    var dataAlumno = $("#dataAlumno_sub");
    var matricu = $("#matric_sub");
    var tipo = $("#cbbTipoSolicitud_sub");
    var cbbCategoria = $("#dataAlumno_sub");
    var cbbSubcategoria = $("#cbbSubcategoria_sub");
    if ($.trim(tipo.select().val()) == '' || $.trim(matricu.val()) == '') {
        $('#divSubEntrevista').html("");
        $("#btnRegistrarSubSolicitud").attr("disabled", true);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_detalle_tipo_solicitud_sub",
                sol_matricula: matricu.val(),
                sol_tipo: tipo.select().val(),
                sol_categoria: cbbCategoria.select().val(),
                sol_subcategoria: cbbSubcategoria.select().val()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $('#divSubEntrevista').html(datos);
                $("#btnRegistrarSubSolicitud").attr("disabled", false);
                var wrapper = document.getElementById("signature-pad-sub");
                canvas1_sub = wrapper.querySelector("canvas");
                signaturePad_sub = new SignaturePad(canvas1_sub, {
                    backgroundColor: 'rgb(255, 255, 255)'
                });
                var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador-sub");
                canvas2_sub = wrapper_entrevistador.querySelector("canvas");
                signaturePad_entrevistador_sub = new SignaturePad(canvas2_sub, {
                    backgroundColor: 'rgb(255, 255, 255)'
                });
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas1_sub.width = canvas1_sub.offsetWidth * ratio;
                    canvas1_sub.height = canvas1_sub.offsetHeight * ratio;
                    canvas1_sub.getContext("2d").scale(ratio, ratio);
                    signaturePad_sub.clear();
                }

                function resizeCanvas2() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas2_sub.width = canvas2_sub.offsetWidth * ratio;
                    canvas2_sub.height = canvas2_sub.offsetHeight * ratio;
                    canvas2_sub.getContext("2d").scale(ratio, ratio);
                    signaturePad_entrevistador_sub.clear();
                }
                window.onresize = resizeCanvas;
                resizeCanvas();
                window.onresize = resizeCanvas2;
                resizeCanvas2();

                canvas1_sub.addEventListener("click", function (event) {
                    if (!signaturePad_sub.isEmpty()) {
                        canvas1_sub.style.border = '1px solid black';
                    }
                });
                canvas2_sub.addEventListener("click", function (event) {
                    if (!signaturePad_entrevistador_sub.isEmpty()) {
                        canvas2_sub.style.border = '1px solid black';
                    }
                });
                $("#cbbSubcategoria_sub").change(function () {
                    var value = $(this).val();
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });
                $("#cbbCategoria_sub").change(function () {
                    var value = $(this).val();
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });
                $("#txtMotivo_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanEstudiante_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanEntrevistador_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdos_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtInforme_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanPadre_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanDocente_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdosPadres_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdosColegio_sub").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();

            }
        });
    }
}

function limpiar_firma_sub() {
    signaturePad_sub.clear();
}

function limpiar_firma_entrevistador_sub() {
    signaturePad_entrevistador_sub.clear();
}

function registrar_sub_solicitud() {
    $("#btnRegistrarSubSolicitud").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codi_entre_sub = $("#codi_entre_sub").val();
    var solicitud_tipo = $("#cbbTipoSolicitud_sub").val();
    var docAlumno = $("#dataAlumno_sub").html().split(" - ");
    var matricula = $("#matric_sub").val();
    var sede = $("#txt_sede_sub").val();
    var categoria = $("#cbbCategoria_sub").select().val();
    var subcategoria = $("#cbbSubcategoria_sub").select().val();
    var txtMotivo = "";
    var planEstudiante = "";
    var planEntrevistador = "";
    var acuerdos = "";
    var informe = "";
    var planPadre = "";
    var planDocente = "";
    var acuerdosPadres = "";
    var acuerdosColegio = "";
    var apoderado = "";
    var mensaje = "";
    var wrapper = document.getElementById("signature-pad-sub");
    var canvas_1 = wrapper.querySelector("canvas");
    var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador-sub");
    var canvas_2 = wrapper_entrevistador.querySelector("canvas");
    if (categoria === "") {
        mensaje += "*Seleccione la categoria<br>";
        $("#cbbCategoria_sub").addClass("is-invalid");
    }
    if (subcategoria === "") {
        mensaje += "*Seleccione la subcategoria<br>";
        $("#cbbSubcategoria_sub").addClass("is-invalid");
    }

    if (solicitud_tipo === "1") {
        txtMotivo = $("#txtMotivo_sub").val();
        planEstudiante = $("#txtPlanEstudiante_sub").val();
        planEntrevistador = $("#txtPlanEntrevistador_sub").val();
        acuerdos = $("#txtAcuerdos_sub").val();
        informe = "";
        planPadre = "";
        planDocente = "";
        acuerdosPadres = "";
        acuerdosColegio = "";
        apoderado = "";
        if ($.trim(txtMotivo) == "") {
            mensaje += "*Ingrese el motivo de solicitud<br>";
            $("#txtMotivo_sub").addClass("is-invalid");
        }
        if ($.trim(planEstudiante) == '') {
            mensaje += "*Ingrese el Planteamiento del estudiante<br>";
            $("#txtPlanEstudiante_sub").addClass("is-invalid");
        }
        if ($.trim(planEntrevistador) == '') {
            mensaje += "*Ingrese el Planteamiento del entrevistador(a)<br>";
            $("#txtPlanEntrevistador_sub").addClass("is-invalid");
        }
        if ($.trim(acuerdos) == '') {
            mensaje += "*Ingrese los Acuerdos<br>";
            $("#txtAcuerdos_sub").addClass("is-invalid");
        }
        if (signaturePad_sub.isEmpty()) {
            mensaje += "*Ingrese la firma del estudiante<br>";
            canvas_1.style.border = '1px solid #dc3545';
        }
        if (signaturePad_entrevistador_sub.isEmpty()) {
            mensaje += "*Ingrese la firma del entrevistador<br>";
            canvas_2.style.border = '1px solid #dc3545';
        }
    } else {
        txtMotivo = $("#txtMotivo_sub").val();
        planEstudiante = "";
        planEntrevistador = "";
        acuerdos = "";
        informe = $("#txtInforme_sub").val();
        planPadre = $("#txtPlanPadre_sub").val();
        planDocente = $("#txtPlanDocente_sub").val();
        acuerdosPadres = $("#txtAcuerdosPadres_sub").val();
        acuerdosColegio = $("#txtAcuerdosColegio_sub").val();
        apoderado = $("#cbbTipoApoderado_sub").select().val();
        if ($.trim(apoderado) < 0 || $.trim(apoderado) === "") {
            mensaje += "*Seleccione el apoderado<br>";
            $("#cbbTipoApoderado_sub").addClass("is-invalid");
        }
        if ($.trim(txtMotivo) == "") {
            mensaje += "*Ingrese el motivo de solicitud<br>";
            $("#txtMotivo_sub").addClass("is-invalid");
        }
        if ($.trim(informe) == '') {
            mensaje += "*Ingrese el Informe<br>";
            $("#txtInforme_sub").addClass("is-invalid");
        }
        if ($.trim(planPadre) == '') {
            mensaje += "*Ingrese el Planteamiento del padre, madre <br>";
            $("#txtPlanPadre_sub").addClass("is-invalid");
        }
        if ($.trim(planDocente) == '') {
            mensaje += "*Ingrese el Planteamiento del docente<br>";
            $("#txtPlanDocente_sub").addClass("is-invalid");
        }
        if ($.trim(acuerdosPadres) == '') {
            mensaje += "*Ingrese las acciones a realizar por los padres<br>";
            $("#txtAcuerdosPadres_sub").addClass("is-invalid");
        }
        if ($.trim(acuerdosColegio) == '') {
            mensaje += "*Ingrese las acciones a realizar por el colegio<br>";
            $("#txtAcuerdosColegio_sub").addClass("is-invalid");
        }
        /*if (signaturePad.isEmpty()) {
         mensaje += "Ingrese la firma del padre, madre o apoderado<br>";
         canvas.style.border = '1px solid #dc3545';
         }*/
        if (signaturePad_entrevistador_sub.isEmpty()) {
            mensaje += "*Ingrese la firma del entrevistador<br>";
            canvas_2.style.border = '1px solid #dc3545';
        }
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarSubSolicitud").attr("disabled", false);
    } else {
        var codSMenu = $("#hdnCodiSR");
        var canvas1 = document.getElementById('canvas1_sub');
        var dataURL1 = canvas1.toDataURL();
        var canvas2 = document.getElementById('canvas2_sub');
        var dataURL2 = canvas2.toDataURL();
        $.ajax({
            url: "php/aco_php/psi_registrar_sub_entrevista.php",
            dataType: "html",
            type: "POST",
            data: {
                sm_codigo: $.trim(codSMenu.val()),
                s_codi_entre_sub: codi_entre_sub,
                s_docAlumno: $.trim(docAlumno[0]),
                s_solicitud_tipo: solicitud_tipo,
                s_matricula: matricula,
                s_sede: sede,
                s_categoria: categoria,
                s_subcategoria: subcategoria,
                s_motivo: txtMotivo,
                s_planEstudiante: planEstudiante,
                s_planEntrevistador: planEntrevistador,
                s_acuerdos: acuerdos,
                s_informe: informe,
                s_planPadre: planPadre,
                s_planDocente: planDocente,
                s_acuerdosPadres: acuerdosPadres,
                s_acuerdosColegio: acuerdosColegio,
                s_apoderado: apoderado,
                s_dataURL1_sub: dataURL1,
                s_dataURL2_sub: dataURL2
            },
            beforeSend: function (objeto) {
                $("#modal-subentrevista").find('.modal-footer div label').html("");
                $("#modal-subentrevista").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-subentrevista").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-subentrevista').modal('hide');
                        $("#btnRegistrarSubSolicitud").attr("disabled", false);
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
                        $("#btnRegistrarSubSolicitud").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_info_apoderado_sub(codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_info_apoderado_sub",
            alu_cod: $("#txtAlumCodig_sub").val(),
            tip_apod: codigo.value
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var arreglo = datos.split("****");
            $('#detalleApoderado_sub').html(arreglo[0]);
            $("#divEditarInfoApoderado_sub").html(arreglo[1]);
            $("#divApoderadoNombreDNI_sub").html(arreglo[2]);
            $("#cbbTipoApoderado_sub").removeClass("is-invalid");
            if (codigo.value === "-1") {
                mostrar_nuevo_apoderado_sub($("#modal-nuevo-apoderado-sub"), $.trim($("#txtAlumCodig_sub").val()));
            }
        }
    });
}

function cerrar_ventana_editar_apoderado_sub() {
    $("#modal-subentrevista").css("overflow-y", "auto");
}

function cerrar_ventana_nuevo_apoderado_sub() {
    $("#modal-subentrevista").css("overflow-y", "auto");
}

function mostrar_editar_apoderado_sub(modal, alumno, apoderado) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_apoderado_sub",
            sm_alumno: alumno,
            sm_apoderado: apoderado
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $("#txtDniApoderado_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtCorreoApoderado_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtTelfApoderado_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
        }
    });
}

function editar_apoderado_sub() {
    $("#btnEditarApoderadoSub").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var txtAlumnCodigo = $("#txtAlumnCodigo_sub");
    var txtApoderadoCod = $("#txtApoderadoCod_sub");
    var txtDniApoderado = $("#txtDniApoderado_sub");
    var txtCorreoApoderado = $("#txtCorreoApoderado_sub");
    var txtTelfApoderado = $("#txtTelfApoderado_sub");
    var mensaje = "";
    if ($.trim(txtDniApoderado.val()) == "") {
        mensaje += "Ingrese en DNI<br>";
        $("#txtDniApoderado_sub").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderado.val()) == "") {
        mensaje += "Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderado_sub").addClass("is-invalid");
    }
    if ($.trim(txtTelfApoderado.val()) == "") {
        mensaje += "Ingrese el teléfono<br>";
        $("#txtTelfApoderado_sub").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarApoderadoSub").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_editar_apoderado_sub",
                a_txtAlumnCodigo_sub: $.trim(txtAlumnCodigo.val()),
                a_txtApoderadoCod_sub: $.trim(txtApoderadoCod.val()),
                a_txtDniApoderado_sub: $.trim(txtDniApoderado.val()),
                a_txtCorreoApoderado_sub: $.trim(txtCorreoApoderado.val()),
                a_txtTelfApoderado_sub: $.trim(txtTelfApoderado.val())
            },
            beforeSend: function (objeto) {
                $("#modal-editar-apoderado-sub").find('.modal-footer div label').html("");
                $("#modal-editar-apoderado-sub").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[0] === "1") {
                    $("#modal-editar-apoderado-sub").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#cbbTipoApoderado_sub").html(resp[2]);
                        $("#detalleApoderado_sub").html(resp[3]);
                        $("#divApoderadoNombreDNI_sub").html(resp[4]);
                        $('#modal-editar-apoderado-sub').modal('hide');
                        $("#btnEditarApoderadoSub").attr("disabled", false);
                        //$('.modal-backdrop').remove();
                        $("#modal-subentrevista").css("overflow-y", "auto");
                    }, 3000);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    $("#btnEditarApoderadoSub").attr("disabled", false);
                }
            }
        });
    }
}

function mostrar_nuevo_apoderado_sub(modal, alumno) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nuevo_apoderado_sub",
            sm_alumno: alumno
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            modal.modal({backdrop: 'static', keyboard: false}, 'show');
            $("#txtDniN_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtNombresApoderadoN_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtCorreoApoderadoN_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtTelfApoderadoN_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDireccionN_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDistritoN_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
        }
    });
}

function registrar_nuevo_apoderado_sub() {
    $("#btnNuevoApoderadoSub").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var txtAlumnoCodiN = $("#txtAlumnoCodiN_sub");
    var cbbTipoApoderadoN = $("#cbbTipoApoderadoN_sub");
    var txtDniN = $("#txtDniN_sub");
    var txtNombresApoderadoN = $("#txtNombresApoderadoN_sub");
    var txtCorreoApoderadoN = $("#txtCorreoApoderadoN_sub");
    var txtTelfApoderadoN = $("#txtTelfApoderadoN_sub");
    var txtDireccionN = $("#txtDireccionN_sub");
    var txtDistritoN = $("#txtDistritoN_sub");
    var mensaje = "";
    if ($.trim(txtDniN.val()) == "") {
        mensaje += "*Ingrese en DNI<br>";
        $("#txtDniN_sub").addClass("is-invalid");
    } else {
        if ($.trim(txtDniN.val()).length < 8) {
            mensaje += "*Ingrese un DNI válido<br>";
        }
    }
    if ($.trim(txtNombresApoderadoN.val()) == "") {
        mensaje += "*Ingrese los apellidos y nombres<br>";
        $("#txtNombresApoderadoN_sub").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderadoN.val()) == "") {
        mensaje += "*Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderadoN_sub").addClass("is-invalid");
    } else {
        if (!valida_correo(txtCorreoApoderadoN)) {
            mensaje += "*Ingrese un correo electrónico válido<br>";
        }
    }
    if ($.trim(txtTelfApoderadoN.val()) == "") {
        mensaje += "*Ingrese el teléfono<br>";
        $("#txtTelfApoderadoN_sub").addClass("is-invalid");
    }
    if ($.trim(txtDireccionN.val()) == "") {
        mensaje += "*Ingrese la dirección<br>";
        $("#txtDireccionN_sub").addClass("is-invalid");
    }
    if ($.trim(txtDistritoN.val()) == "") {
        mensaje += "*Ingrese el distrito<br>";
        $("#txtDistritoN_sub").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnNuevoApoderadoSub").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_registrar_apoderado_sub",
                a_txtAlumnoCodiN_sub: $.trim(txtAlumnoCodiN.val()),
                a_cbbTipoApoderadoN_sub: $.trim(cbbTipoApoderadoN.select().val()),
                a_txtDniN_sub: $.trim(txtDniN.val()),
                a_txtNombresApoderadoN_sub: $.trim(txtNombresApoderadoN.val()),
                a_txtCorreoApoderadoN_sub: $.trim(txtCorreoApoderadoN.val()),
                a_txtTelfApoderadoN_sub: $.trim(txtTelfApoderadoN.val()),
                a_txtDireccionN_sub: $.trim(txtDireccionN.val()),
                a_txtDistritoN_sub: $.trim(txtDistritoN.val())
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-apoderado-sub").find('.modal-footer div label').html("");
                $("#modal-nuevo-apoderado-sub").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[0] === "1") {
                    $("#modal-nuevo-apoderado-sub").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#cbbTipoApoderado_sub").html(resp[2]);
                        $("#divEditarInfoApoderado_sub").html(resp[3]);
                        $("#detalleApoderado_sub").html(resp[4]);
                        $("#divApoderadoNombreDNI_sub").html(resp[5]);
                        $('#modal-nuevo-apoderado-sub').modal('hide');
                        $("#btnNuevoApoderadoSub").attr("disabled", false);
                        //$('.modal-backdrop').remove();
                        $("#modal-subentrevista").css("overflow-y", "auto");
                    }, 3000);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    $("#btnNuevoApoderadoSub").attr("disabled", false);
                }
            }
        });
    }
}

function mostrar_editar_solicitud(modal, solicitud) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_solicitud",
            s_solicitud: solicitud
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

function cargar_solicitudes_a_editar(solicitud) {
    if (solicitud.value === "") {
        $('#divDetalleEditar').html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_carga_solicitudes",
                sol_cod: solicitud.value
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $('#divDetalleEditar').html(datos);
                var array = [];
                $('input#searchAlumno_edi').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 4,
                    source: function (query, result) {
                        $.ajax({
                            url: 'php/aco_php/buscar_alumno.php',
                            method: 'POST',
                            data: {query: query},
                            dataType: "json",
                            success: function (data) {
                                //result(getOptionsFromJson(data));
                                array = [];
                                result($.map(data, function (item) {
                                    array.push({'value': item.value, 'label': item.label});
                                    return item.label;
                                }));
                            },

                        });
                    },
                    updater: function (item) {
                        const found = array.find(el => el.label === item);
                        $("#matric_edi").val(found.value);
                        $("#dataAlumno_edi").html(item);
                        mostrar_tipo_solicitud_edi("");
                    }
                });
            }
        });
    }
}

function cargar_subcategorias_edi(categoria) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_subcategorias",
            cat_cod: categoria.value
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $('#cbbSubcategoria_edi').html(datos);
        }
    });
}

function mostrar_tipo_solicitud_edi(dato) {
    var mensaje = "";
    var codSMenu = $("#hdnCodiSR");
    var dataAlumno = $("#dataAlumno_edi");
    var matricu = $("#matric_edi");
    var tipo = $("#cbbTipoSolicitud_edi");
    var cbbCategoria = $("#dataAlumno_edi");
    var cbbSubcategoria = $("#cbbSubcategoria_edi");
    if ($.trim(tipo.select().val()) == '' || $.trim(matricu.val()) == '') {
        $('#divSubEntrevista_edi').html("");
        $("#btnEditarSolicitud").attr("disabled", true);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_detalle_tipo_solicitud_edi",
                sol_matricula: matricu.val(),
                sol_tipo: tipo.select().val(),
                sol_categoria: cbbCategoria.select().val(),
                sol_subcategoria: cbbSubcategoria.select().val()
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var arreglo = datos.split("***");
                $("#nombre_estu_edi").html(arreglo[0]);
                $("#grado_estu_id").html(arreglo[1]);
                if (tipo.select().val() === "2") {
                    $("#cbbTipoApoderado_edi").html(arreglo[2]);
                }
                $("#sede_estu_id").html(arreglo[3]);
                if (tipo.select().val() === "1") {
                    $("#divApoderadoNombreDNI_edi").html(arreglo[4]);
                } else {
                    $("#divApoderadoNombreDNI_edi").html("");
                }
                $("#detalleApoderado_edi").html(arreglo[5]);
            }
        });
    }
}

function mostrar_info_apoderado_edi(codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_info_apoderado_edi",
            alu_cod: $("#txtAlumCodig_edi").val(),
            tip_apod: codigo.value
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var arreglo = datos.split("****");
            $('#detalleApoderado_edi').html(arreglo[0]);
            $("#divEditarInfoApoderado_edi").html(arreglo[1]);
            $("#divApoderadoNombreDNI_edi").html(arreglo[2]);
            $("#cbbTipoApoderado_edi").removeClass("is-invalid");
            if (codigo.value === "-1") {
                mostrar_nuevo_apoderado_edi($("#modal-nuevo-apoderado-edi"), $.trim($("#txtAlumCodig_edi").val()));
            }
        }
    });
}

function cerrar_ventana_editar_apoderado_edi() {
    $("#modal-editar-solicitud-alumno").css("overflow-y", "auto");
}

function cerrar_ventana_nuevo_apoderado_edi() {
    $("#modal-editar-solicitud-alumno").css("overflow-y", "auto");
}

function mostrar_nuevo_apoderado_edi(modal, alumno) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nuevo_apoderado_edi",
            sm_alumno: alumno
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            modal.modal({backdrop: 'static', keyboard: false}, 'show');
            $("#txtDniN_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtNombresApoderadoN_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtCorreoApoderadoN_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtTelfApoderadoN_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDireccionN_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDistritoN_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
        }
    });
}

function mostrar_editar_apoderado_edi(modal, alumno, apoderado) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_apoderado_edi",
            sm_alumno: alumno,
            sm_apoderado: apoderado
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $("#txtDniApoderado_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtCorreoApoderado_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtTelfApoderado_edi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
        }
    });
}

function editar_apoderado_edi() {
    $("#btnEditarApoderadoEdi").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var txtAlumnCodigo = $("#txtAlumnCodigo_edi");
    var txtApoderadoCod = $("#txtApoderadoCod_edi");
    var txtDniApoderado = $("#txtDniApoderado_edi");
    var txtCorreoApoderado = $("#txtCorreoApoderado_edi");
    var txtTelfApoderado = $("#txtTelfApoderado_edi");
    var mensaje = "";
    if ($.trim(txtDniApoderado.val()) == "") {
        mensaje += "Ingrese en DNI<br>";
        $("#txtDniApoderado_edi").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderado.val()) == "") {
        mensaje += "Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderado_edi").addClass("is-invalid");
    }
    if ($.trim(txtTelfApoderado.val()) == "") {
        mensaje += "Ingrese el teléfono<br>";
        $("#txtTelfApoderado_edi").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarApoderadoEdi").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_editar_apoderado_edi",
                a_txtAlumnCodigo_edi: $.trim(txtAlumnCodigo.val()),
                a_txtApoderadoCod_edi: $.trim(txtApoderadoCod.val()),
                a_txtDniApoderado_edi: $.trim(txtDniApoderado.val()),
                a_txtCorreoApoderado_edi: $.trim(txtCorreoApoderado.val()),
                a_txtTelfApoderado_edi: $.trim(txtTelfApoderado.val())
            },
            beforeSend: function (objeto) {
                $("#modal-editar-apoderado-edi").find('.modal-footer div label').html("");
                $("#modal-editar-apoderado-edi").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[0] === "1") {
                    $("#modal-editar-apoderado-edi").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#cbbTipoApoderado_edi").html(resp[2]);
                        $("#detalleApoderado_edi").html(resp[3]);
                        $("#divApoderadoNombreDNI_edi").html(resp[4]);
                        $('#modal-editar-apoderado-edi').modal('hide');
                        $("#btnEditarApoderadoEdi").attr("disabled", false);
                        //$('.modal-backdrop').remove();
                        $("#modal-editar-solicitud-alumno").css("overflow-y", "auto");
                    }, 3000);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    $("#btnEditarApoderadoEdi").attr("disabled", false);
                }
            }
        });
    }
}

function registrar_nuevo_apoderado_edi() {
    $("#btnNuevoApoderadoEdi").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var txtAlumnoCodiN = $("#txtAlumnoCodiN_edi");
    var cbbTipoApoderadoN = $("#cbbTipoApoderadoN_edi");
    var txtDniN = $("#txtDniN_edi");
    var txtNombresApoderadoN = $("#txtNombresApoderadoN_edi");
    var txtCorreoApoderadoN = $("#txtCorreoApoderadoN_edi");
    var txtTelfApoderadoN = $("#txtTelfApoderadoN_edi");
    var txtDireccionN = $("#txtDireccionN_edi");
    var txtDistritoN = $("#txtDistritoN_edi");
    var mensaje = "";
    if ($.trim(txtDniN.val()) == "") {
        mensaje += "*Ingrese en DNI<br>";
        $("#txtDniN_edi").addClass("is-invalid");
    } else {
        if ($.trim(txtDniN.val()).length < 8) {
            mensaje += "*Ingrese un DNI válido<br>";
        }
    }
    if ($.trim(txtNombresApoderadoN.val()) == "") {
        mensaje += "*Ingrese los apellidos y nombres<br>";
        $("#txtNombresApoderadoN_edi").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderadoN.val()) == "") {
        mensaje += "*Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderadoN_edi").addClass("is-invalid");
    } else {
        if (!valida_correo(txtCorreoApoderadoN)) {
            mensaje += "*Ingrese un correo electrónico válido<br>";
        }
    }
    if ($.trim(txtTelfApoderadoN.val()) == "") {
        mensaje += "*Ingrese el teléfono<br>";
        $("#txtTelfApoderadoN_edi").addClass("is-invalid");
    }
    if ($.trim(txtDireccionN.val()) == "") {
        mensaje += "*Ingrese la dirección<br>";
        $("#txtDireccionN_edi").addClass("is-invalid");
    }
    if ($.trim(txtDistritoN.val()) == "") {
        mensaje += "*Ingrese el distrito<br>";
        $("#txtDistritoN_edi").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnNuevoApoderadoEdi").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_registrar_apoderado_edi",
                a_txtAlumnoCodiN_edi: $.trim(txtAlumnoCodiN.val()),
                a_cbbTipoApoderadoN_edi: $.trim(cbbTipoApoderadoN.select().val()),
                a_txtDniN_edi: $.trim(txtDniN.val()),
                a_txtNombresApoderadoN_edi: $.trim(txtNombresApoderadoN.val()),
                a_txtCorreoApoderadoN_edi: $.trim(txtCorreoApoderadoN.val()),
                a_txtTelfApoderadoN_edi: $.trim(txtTelfApoderadoN.val()),
                a_txtDireccionN_edi: $.trim(txtDireccionN.val()),
                a_txtDistritoN_edi: $.trim(txtDistritoN.val())
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-apoderado-edi").find('.modal-footer div label').html("");
                $("#modal-nuevo-apoderado-edi").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[0] === "1") {
                    $("#modal-nuevo-apoderado-edi").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $("#cbbTipoApoderado_edi").html(resp[2]);
                        $("#divEditarInfoApoderado_edi").html(resp[3]);
                        $("#detalleApoderado_edi").html(resp[4]);
                        $("#divApoderadoNombreDNI_edi").html(resp[5]);
                        $('#modal-nuevo-apoderado-edi').modal('hide');
                        $("#btnNuevoApoderadoEdi").attr("disabled", false);
                        //$('.modal-backdrop').remove();
                        $("#modal-editar-solicitud-alumno").css("overflow-y", "auto");
                    }, 3000);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[1],
                        showConfirmButton: false
                    });
                    $("#btnNuevoApoderadoEdi").attr("disabled", false);
                }
            }
        });
    }
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