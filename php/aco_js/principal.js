var nameIdMap = {};
var canvas1 = {};
var canvas2 = {};
var signaturePad = {};
var signaturePad_entrevistador = {};
var canvas1_sub = {};
var canvas2_sub = {};
var signaturePad_sub = {};
var signaturePad_entrevistador_sub = {};
var canvas1_edi = {};
var canvas2_edi = {};
var signaturePad_edi = {};
var signaturePad_entrevistador_edi = {};
var intervaloRegresivo;
var intervaloRegresivo_s;
var timeLimit = 1; //tiempo en minutos
var timeLimitSub = 1; //tiempo en minutos
var estado1;
var estado_s1;
var tmr;
var tmr2;
var tmr_sub;
var tmr_sub2;
var tmr_edi;
var tmr_edi2;
var resetIsSupported = false;
var SigWeb_1_6_4_0_IsInstalled = false; //SigWeb 1.6.4.0 and above add the Reset() and GetSigWebVersion functions
var SigWeb_1_7_0_0_IsInstalled = false; //SigWeb 1.7.0.0 and above add the GetDaysUntilCertificateExpires() function

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

function mostrar_modulo_x_alerta(modulo) {
    var arreglo = [
        {
            codigo: 7,
            menu: 2,
            ruta: './php/aco_view/aco_procesos/pr_solregistradas.php',
            nombre: 'Entrevistas Registradas'
        },
        {
            codigo: 8,
            menu: 3,
            ruta: './php/aco_view/aco_consultas/co_semaforo.php',
            nombre: 'Semáforo Docentes'
        },
        {
            codigo: 9,
            menu: 3,
            ruta: './php/aco_view/aco_consultas/co_noentrevistados.php',
            nombre: 'Mis Alumnos no Entrevistados'
        }
    ];
    $("#menuSistema li").each(function (index, item) {
        $(this).removeClass("menu-is-opening");
        $(this).removeClass("menu-open");
        $(this).find("ul").hide();
    });
    var objeto = arreglo.find(o => o.codigo === modulo);
    if (objeto !== undefined) {
        $("#menu-" + objeto.menu).addClass("menu-is-opening");
        $("#menu-" + objeto.menu).addClass("menu-open");
        $("#menu-" + objeto.menu).find("ul").show();
        cargar_opcion(objeto.codigo, objeto.ruta, objeto.nombre);
    }

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
            var array_correo = correo.val().split("@");
            /*if ($.trim(array_correo[1]) !== "cbb.edu.pe" && $.trim(array_correo[1]) !== "ich.edu.pe") {
             mensaje += "Ingrese correo electrónico institucional (@cbb.edu.pe o ich.edu.pe)<br>";
             }*/
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
            var array_correo = correo.val().split("@");
            if ($.trim(array_correo[1]) !== "cbb.edu.pe" && $.trim(array_correo[1]) !== "ich.edu.pe") {
                mensaje += "Ingrese correo electrónico institucional (@cbb.edu.pe o ich.edu.pe)<br>";
            }
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
            setTimeout(function () {
                modal.find('input[id="searchAlumno"]').focus();
            }, 500);
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
            $("#txtNombresApoderado").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
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
    var txtNombresApoderado = $("#txtNombresApoderado");
    var txtDniApoderado = $("#txtDniApoderado");
    var txtCorreoApoderado = $("#txtCorreoApoderado");
    var txtTelfApoderado = $("#txtTelfApoderado");
    var mensaje = "";
    if ($.trim(txtNombresApoderado.val()) == "") {
        mensaje += "*Ingrese los apellidos y nombres<br>";
        $("#txtNombresApoderado").addClass("is-invalid");
    }
    if ($.trim(txtDniApoderado.val()) == "") {
        mensaje += "*Ingrese en DNI<br>";
        $("#txtDniApoderado").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderado.val()) == "") {
        mensaje += "*Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderado").addClass("is-invalid");
    }
    /*if ($.trim(txtTelfApoderado.val()) == "") {
     mensaje += "*Ingrese el teléfono<br>";
     $("#txtTelfApoderado").addClass("is-invalid");
     }*/
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarApoderado").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_editar_apoderado",
                a_txtAlumnCodigo: $.trim(txtAlumnCodigo.val()),
                a_txtApoderadoCod: $.trim(txtApoderadoCod.val()),
                a_txtNombresApoderado: $.trim(txtNombresApoderado.val()),
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
    /*if ($.trim(txtTelfApoderadoN.val()) == "") {
     mensaje += "*Ingrese el teléfono<br>";
     $("#txtTelfApoderadoN").addClass("is-invalid");
     }*/
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
        $("#btnNuevoApoderado").attr("disabled", false);
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
                canvas1 = document.getElementById("canvas1");
                /*signaturePad = new SignaturePad(canvas1, {
                 backgroundColor: 'rgb(255, 255, 255)'
                 });*/
                var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador");
                canvas2 = document.getElementById("canvas2");
                /*signaturePad_entrevistador = new SignaturePad(canvas2, {
                 backgroundColor: 'rgb(255, 255, 255)'
                 });*/
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas1.width = canvas1.offsetWidth * ratio;
                    canvas1.height = canvas1.offsetHeight * ratio;
                    canvas1.getContext("2d").scale(ratio, ratio);
                    //signaturePad.clear();
                }

                function resizeCanvas2() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas2.width = canvas2.offsetWidth * ratio;
                    canvas2.height = canvas2.offsetHeight * ratio;
                    canvas2.getContext("2d").scale(ratio, ratio);
                    //signaturePad_entrevistador.clear();
                }
                window.onresize = resizeCanvas;
                resizeCanvas();
                window.onresize = resizeCanvas2;
                resizeCanvas2();
                /*canvas1.addEventListener("click", function (event) {
                 if (!isCanvasBlank(document.getElementById('canvas1'))) {
                 canvas1.style.border = '1px solid black';
                 }
                 });
                 canvas2.addEventListener("click", function (event) {
                 if (!isCanvasBlank(document.getElementById('canvas2'))) {
                 canvas2.style.border = '1px solid black';
                 }
                 });*/
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
                $("#cbbSexo").change(function () {
                    var value = $(this).val();
                    if (value !== 0 && $(this).is(".is-invalid")) {
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

/*function limpiar_firma() {
 signaturePad.clear();
 }*/

/*function limpiar_firma_entrevistador() {
 signaturePad_entrevistador.clear();
 }*/

function buscar_entrevistas() {
    var sede = $("#cbbSedes").select().val();
    var fecha1 = $("#fecha1").val();
    var fecha2 = $("#fecha2").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "mostrar_busqueda_entrevistas",
            s_sede: sede,
            s_fecha1: fecha1,
            s_fecha2: fecha2
        },
        beforeSend: function (objeto) {
            $("#modal-nueva-solicitud").find('.modal-footer div label').html("");
            $("#modal-nueva-solicitud").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableSolicitudesRegistradas").DataTable().destroy();
            $("#tableSolicitudesRegistradas tbody").html(datos);
            $("#tableSolicitudesRegistradas").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                "buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableSolicitudesRegistradas_wrapper .col-md-6:eq(0)');
        }
    });
}

function registrar_solicitud_jesus() {
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
    var sexo_estu = $("#cbbSexo").select().val();
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
    var blank = isCanvasBlank(document.getElementById('canvas1'));
    //var blank2 = isCanvasBlank(document.getElementById('canvas2'));
    SetTabletState(0, tmr, 0);
    SetTabletState(0, tmr2, 0);
    clearInterval(tmr);
    clearInterval(tmr2);
    var checkPrivacidad = $("#checkPrivacidad").is(':checked');
    var hora = $("#hora");
    var minuto = $("#minuto");
    var segundo = $("#segundo");
    var hora_total = "";
    if (hora.hasClass('parpadea')) {//paso los 40 min
        hora_total = sumarFechas(minutos_a_hora(timeLimit), hora.html(), minuto.html(), segundo.html());
    } else {//esta dentro los 40 min establecidos
        var res_horas = restarFechas(minutos_a_hora(timeLimit), $.trim(hora.html()), $.trim(minuto.html()), $.trim(segundo.html()));
        hora_total = res_horas;
    }
    var privacidad_value = "";
    if (checkPrivacidad) {
        privacidad_value = "1";
    } else {
        privacidad_value = "0";
    }
    if (categoria === "") {
        mensaje += "*Seleccione la categoria<br>";
        $("#cbbCategoria").addClass("is-invalid");
    }
    if (subcategoria === "") {
        mensaje += "*Seleccione la subcategoria<br>";
        $("#cbbSubcategoria").addClass("is-invalid");
    }
    if ($.trim(sexo_estu) === "0") {
        mensaje += "*Seleccione el sexo<br>";
        $("#cbbSexo").addClass("is-invalid");
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
        //if (signaturePad.isEmpty()) {
        //    mensaje += "*Ingrese la firma del estudiante<br>";
        //    canvas_1.style.border = '1px solid #dc3545';
        //}
        /*if (blank2 == true) {
         mensaje += "*Ingrese la firma del entrevistador<br>";
         canvas_2.style.border = '1px solid #dc3545';
         }*/
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
//if (signaturePad.isEmpty()) {
//mensaje += "Ingrese la firma del padre, madre o apoderado<br>";
//canvas.style.border = '1px solid #dc3545';
//}
        /*if (blank2 === true) {
         mensaje += "*Ingrese la firma del entrevistador<br>";
         canvas_2.style.border = '1px solid #dc3545';
         }*/
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
        var txt_perfil = $("#txt_perfil").val();
        var canvas1 = document.getElementById('canvas1');
        var dataURL1 = canvas1.toDataURL();
        var dataURL2 = "";
        if (txt_perfil === "3") {
            dataURL2 = document.getElementById('canvas2_img').getAttribute('src');
        } else {
            var canvas2 = document.getElementById('canvas2');
            dataURL2 = canvas2.toDataURL();
        }
        var dataURL1_1 = "aaa";
        if (blank === true) {
            dataURL1_1 = "";
        }
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
                s_sexo: sexo_estu,
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
                s_privacidad: privacidad_value,
                s_dataURL1: dataURL1,
                s_dataURL2: dataURL2,
                dataURL1_1: dataURL1_1,
                s_hora_total: hora_total
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

function registrar_solicitud() {//Guadalupe
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
    var sexo_estu = $("#cbbSexo").select().val();
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
    var txt_perfil = $("#txt_perfil").val();
    var txtCantiFirma = $("#txtCantiFirma").val();
    var wrapper = document.getElementById("signature-pad");
    var canvas_1 = wrapper.querySelector("canvas");
    var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador");
    var canvas_2 = wrapper_entrevistador.querySelector("canvas");
    var blank = isCanvasBlank(document.getElementById('canvas1'));
    var blank2 = "";
    if (txt_perfil === "3" && solicitud_tipo === "1" && txtCantiFirma > 0) {
        blank2 = "";
    } else {
        blank2 = isCanvasBlank(document.getElementById('canvas2'));
    }
    SetTabletState(0, tmr, 0);
    SetTabletState(0, tmr2, 0);
    clearInterval(tmr);
    clearInterval(tmr2);
    var checkPrivacidad = $("#checkPrivacidad").is(':checked');
    var hora = $("#hora");
    var minuto = $("#minuto");
    var segundo = $("#segundo");
    var hora_total = "";
    if (hora.hasClass('parpadea')) {//paso los 40 min
        hora_total = sumarFechas(minutos_a_hora(timeLimit), hora.html(), minuto.html(), segundo.html());
    } else {//esta dentro los 40 min establecidos
        var res_horas = restarFechas(minutos_a_hora(timeLimit), $.trim(hora.html()), $.trim(minuto.html()), $.trim(segundo.html()));
        hora_total = res_horas;
    }
    var privacidad_value = "";
    if (checkPrivacidad) {
        privacidad_value = "1";
    } else {
        privacidad_value = "0";
    }
    if (categoria === "") {
        mensaje += "*Seleccione la categoria<br>";
        $("#cbbCategoria").addClass("is-invalid");
    }
    if (subcategoria === "") {
        mensaje += "*Seleccione la subcategoria<br>";
        $("#cbbSubcategoria").addClass("is-invalid");
    }
    if ($.trim(sexo_estu) === "0") {
        mensaje += "*Seleccione el sexo<br>";
        $("#cbbSexo").addClass("is-invalid");
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
        //if (signaturePad.isEmpty()) {
        //    mensaje += "*Ingrese la firma del estudiante<br>";
        //    canvas_1.style.border = '1px solid #dc3545';
        //}
        if (txtCantiFirma===0 && txt_perfil===3) {
            if (blank2 == true) {
                mensaje += "*Ingrese la firma del entrevistador<br>";
                canvas_2.style.border = '1px solid #dc3545';
            }
        } else if(txt_perfil!==3){
            if (blank2 == true) {
                mensaje += "*Ingrese la firma del entrevistador<br>";
                canvas_2.style.border = '1px solid #dc3545';
            }
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
//if (signaturePad.isEmpty()) {
//mensaje += "Ingrese la firma del padre, madre o apoderado<br>";
//canvas.style.border = '1px solid #dc3545';
//}
        //if (txt_perfil !== "3" && solicitud_tipo !== "1") {
            if (blank2 === true) {
                mensaje += "*Ingrese la firma del entrevistador<br>";
                canvas_2.style.border = '1px solid #dc3545';
            }
        //}
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
        var dataURL2 = "";
        if (txt_perfil === "3" && solicitud_tipo === "1" && txtCantiFirma > 0) {
            dataURL2 = document.getElementById('canvas2_img').getAttribute('src');
        } else {
            var canvas2 = document.getElementById('canvas2');
            dataURL2 = canvas2.toDataURL();
        }
        var dataURL1_1 = "aaa";
        if (blank === true) {
            dataURL1_1 = "";
        }

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
                s_sexo: sexo_estu,
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
                s_privacidad: privacidad_value,
                s_dataURL1: dataURL1,
                s_dataURL2: dataURL2,
                dataURL1_1: dataURL1_1,
                s_hora_total: hora_total
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
            var select = $("#cbbTipoSolicitudCodis option").length;
            if (select === 2) {
                $('select[id=cbbTipoSolicitudCodis] option:eq(1)').attr('selected', 'selected');
                cargar_solicitudes_a_detallar("");
            }
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
            $("#cbbTipoSolicitudCodisElis").change(function () {
                var value = $(this).val();
                if (value !== '' && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            var select = $("#cbbTipoSolicitudCodisElis option").length;
            if (select === 2) {
                $('select[id=cbbTipoSolicitudCodisElis] option:eq(1)').attr('selected', 'selected');
                cargar_solicitudes_a_eliminar("");
            }
        }
    });
}

function eliminar_solicitud() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var cbbTipoSolicitudCodis = $("#cbbTipoSolicitudCodisElis").select().val();
    if (cbbTipoSolicitudCodis === "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Seleccione la Entrevista / Subentrevista',
            showConfirmButton: false
        });
        $("#cbbTipoSolicitudCodisElis").addClass("is-invalid");
    } else {
        $("#btnEliminarSolicitudAlumno").attr("disabled", true);
        var codSMenu = $("#hdnCodiSR");
        var hdnCodiSolicitud = $("#hdnCodiSolicitud");
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_eliminar_solicitud",
                sm_codigo: $.trim(codSMenu.val()),
                u_codiSolicitud: $.trim(hdnCodiSolicitud.val())
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
    //var fecha_inicio = $("#fecha1").val();
    //var fecha_fin = $("#fecha2").val();
    var semaforo = $("#cbbSemaforo").select().val();
    var bimestre = $("#cbbBimestre").select().val();
    var nivel = $("#cbbNivel").select().val();
    var grado = $("#cbbGrado").select().val();
    var seccion = $("#cbbSeccion").select().val();
    var docente = $("#docen").val();
    if ($.trim(docente) == "") {
        docente = "0";
    }
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_buscar_semaforo_docentes",
            s_sede: sede,
            //s_fecha_inicio: fecha_inicio,
            //s_fecha_fin: fecha_fin,
            s_semaforo: semaforo,
            s_bimestre: bimestre,
            s_nivel: nivel,
            s_grado: grado,
            s_seccion: seccion,
            s_docente: docente
        },
        beforeSend: function (objeto) {
            $("#modal-nueva-solicitud-detalle").find('.modal-footer div label').html("");
            $("#modal-nueva-solicitud-detalle").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableSemaforo").DataTable().destroy();
            $("#tableSemaforo tbody").html(datos);
            $("#tableSemaforo").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "buttons": ["copy",
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Lista semaforo docentes'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Lista semaforo docentes'
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Lista semaforo docentes'
                    }, "colvis"]
                        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        //"buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableSemaforo_wrapper .col-md-6:eq(0)');
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
            setTimeout(function () {
                modal.find('input[id="searchAlumno_sub"]').focus();
            }, 500);
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
                /*signaturePad_sub = new SignaturePad(canvas1_sub, {
                 backgroundColor: 'rgb(255, 255, 255)'
                 });*/
                var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador-sub");
                canvas2_sub = wrapper_entrevistador.querySelector("canvas");
                /*signaturePad_entrevistador_sub = new SignaturePad(canvas2_sub, {
                 backgroundColor: 'rgb(255, 255, 255)'
                 });*/
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas1_sub.width = canvas1_sub.offsetWidth * ratio;
                    canvas1_sub.height = canvas1_sub.offsetHeight * ratio;
                    canvas1_sub.getContext("2d").scale(ratio, ratio);
                    //signaturePad_sub.clear();
                }

                function resizeCanvas2() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas2_sub.width = canvas2_sub.offsetWidth * ratio;
                    canvas2_sub.height = canvas2_sub.offsetHeight * ratio;
                    canvas2_sub.getContext("2d").scale(ratio, ratio);
                    //signaturePad_entrevistador_sub.clear();
                }
                window.onresize = resizeCanvas;
                resizeCanvas();
                window.onresize = resizeCanvas2;
                resizeCanvas2();
                /*canvas1_sub.addEventListener("click", function (event) {
                 if (!signaturePad_sub.isEmpty()) {
                 canvas1_sub.style.border = '1px solid black';
                 }
                 });
                 canvas2_sub.addEventListener("click", function (event) {
                 if (!signaturePad_entrevistador_sub.isEmpty()) {
                 canvas2_sub.style.border = '1px solid black';
                 }
                 });*/
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
                $("#cbbSexo_sub").change(function () {
                    var value = $(this).val();
                    if (value !== "0" && $(this).is(".is-invalid")) {
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

/*function limpiar_firma_sub() {
 signaturePad_sub.clear();
 }
 
 function limpiar_firma_entrevistador_sub() {
 signaturePad_entrevistador_sub.clear();
 }*/

function registrar_sub_solicitud_jesus() {
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
    var sexo = $("#cbbSexo_sub").select().val();
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
    var blank = isCanvasBlank(document.getElementById('canvas1_sub'));
    var blank2 = isCanvasBlank(document.getElementById('canvas2_sub'));
    SetTabletState(0, tmr_sub, 0);
    SetTabletState(0, tmr_sub2, 0);
    clearInterval(tmr_sub);
    clearInterval(tmr_sub2);
    var checkPrivacidad = $("#checkPrivacidad_sub").is(':checked');
    var privacidad_value = "";
    var hora = $("#hora_s");
    var minuto = $("#minuto_s");
    var segundo = $("#segundo_s");
    var hora_total = "";
    if (hora.hasClass('parpadea_s')) {//paso los 40 min
        hora_total = sumarFechas(minutos_a_hora(timeLimit), hora.html(), minuto.html(), segundo.html());
    } else {//esta dentro los 40 min establecidos
        var res_horas = restarFechas(minutos_a_hora(timeLimit), $.trim(hora.html()), $.trim(minuto.html()), $.trim(segundo.html()));
        hora_total = res_horas;
    }
    if (checkPrivacidad) {
        privacidad_value = "1";
    } else {
        privacidad_value = "0";
    }
    if (categoria === "") {
        mensaje += "*Seleccione la categoria<br>";
        $("#cbbCategoria_sub").addClass("is-invalid");
    }
    if (subcategoria === "") {
        mensaje += "*Seleccione la subcategoria<br>";
        $("#cbbSubcategoria_sub").addClass("is-invalid");
    }
    if (sexo === "0") {
        mensaje += "*Seleccione el sexo<br>";
        $("#cbbSexo_sub").addClass("is-invalid");
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
        //if (signaturePad_sub.isEmpty()) {
        //    mensaje += "*Ingrese la firma del estudiante<br>";
        //    canvas_1.style.border = '1px solid #dc3545';
        //}
        /*if (blank2 == true) {
         mensaje += "*Ingrese la firma del entrevistador<br>";
         canvas_2.style.border = '1px solid #dc3545';
         }*/
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
        /*if (blank2 === true) {
         mensaje += "*Ingrese la firma del entrevistador<br>";
         canvas_2.style.border = '1px solid #dc3545';
         }*/
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
        var txt_perfil = $("#txt_perfil_sub").val();
        var canvas1 = document.getElementById('canvas1_sub');
        var dataURL1 = canvas1.toDataURL();
        var dataURL2 = "";
        if (txt_perfil === "3") {
            dataURL2 = document.getElementById('canvas2_img_edi').getAttribute('src');
        } else {
            var canvas2 = document.getElementById('canvas2_sub');
            dataURL2 = canvas2.toDataURL();
        }
        var dataURL1_1 = "aaa";
        if (blank === true) {
            dataURL1_1 = "";
        }
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
                s_sexo: sexo,
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
                s_privacidad: privacidad_value,
                s_dataURL1_sub: dataURL1,
                s_dataURL2_sub: dataURL2,
                dataURL1_sub1: dataURL1_1,
                s_hora_total_sub: hora_total
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

function registrar_sub_solicitud() {//Guadalupe
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
    var sexo = $("#cbbSexo_sub").select().val();
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
    var txt_perfil = $("#txt_perfil_sub").val();
    var wrapper = document.getElementById("signature-pad-sub");
    var canvas_1 = wrapper.querySelector("canvas");
    var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador-sub");
    var canvas_2 = wrapper_entrevistador.querySelector("canvas");
    var blank = isCanvasBlank(document.getElementById('canvas1_sub'));
    var blank2 = "";
    if (txt_perfil === "3" && solicitud_tipo === "1") {
        blank2 = "";
    } else {
        blank2 = isCanvasBlank(document.getElementById('canvas2_sub'));
    }
    SetTabletState(0, tmr_sub, 0);
    SetTabletState(0, tmr_sub2, 0);
    clearInterval(tmr_sub);
    clearInterval(tmr_sub2);
    var checkPrivacidad = $("#checkPrivacidad_sub").is(':checked');
    var privacidad_value = "";
    var hora = $("#hora_s");
    var minuto = $("#minuto_s");
    var segundo = $("#segundo_s");
    var hora_total = "";
    if (hora.hasClass('parpadea_s')) {//paso los 40 min
        hora_total = sumarFechas(minutos_a_hora(timeLimit), hora.html(), minuto.html(), segundo.html());
    } else {//esta dentro los 40 min establecidos
        var res_horas = restarFechas(minutos_a_hora(timeLimit), $.trim(hora.html()), $.trim(minuto.html()), $.trim(segundo.html()));
        hora_total = res_horas;
    }
    if (checkPrivacidad) {
        privacidad_value = "1";
    } else {
        privacidad_value = "0";
    }
    if (categoria === "") {
        mensaje += "*Seleccione la categoria<br>";
        $("#cbbCategoria_sub").addClass("is-invalid");
    }
    if (subcategoria === "") {
        mensaje += "*Seleccione la subcategoria<br>";
        $("#cbbSubcategoria_sub").addClass("is-invalid");
    }
    if (sexo === "0") {
        mensaje += "*Seleccione el sexo<br>";
        $("#cbbSexo_sub").addClass("is-invalid");
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
        //if (signaturePad_sub.isEmpty()) {
        //    mensaje += "*Ingrese la firma del estudiante<br>";
        //    canvas_1.style.border = '1px solid #dc3545';
        //}
        if (txt_perfil !== "3" && solicitud_tipo !== "1") {
            if (blank2 == true) {
                mensaje += "*Ingrese la firma del entrevistador<br>";
                canvas_2.style.border = '1px solid #dc3545';
            }
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
        //if (txt_perfil !== "3" && solicitud_tipo !== "1") {
        if (blank2 === true) {
            mensaje += "*Ingrese la firma del entrevistador<br>";
            canvas_2.style.border = '1px solid #dc3545';
        }
        //}
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
        var dataURL2 = "";
        if (txt_perfil === "3" && solicitud_tipo === "1") {
            dataURL2 = document.getElementById('canvas2_img_edi').getAttribute('src');
        } else {
            var canvas2 = document.getElementById('canvas2_sub');
            dataURL2 = canvas2.toDataURL();
        }
        var dataURL1_1 = "aaa";
        if (blank === true) {
            dataURL1_1 = "";
        }
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
                s_sexo: sexo,
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
                s_privacidad: privacidad_value,
                s_dataURL1_sub: dataURL1,
                s_dataURL2_sub: dataURL2,
                dataURL1_sub1: dataURL1_1,
                s_hora_total_sub: hora_total
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
            $("#txtNombresApoderado_sub").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
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
    var txtNombresApoderado = $("#txtNombresApoderado_sub");
    var txtDniApoderado = $("#txtDniApoderado_sub");
    var txtCorreoApoderado = $("#txtCorreoApoderado_sub");
    var txtTelfApoderado = $("#txtTelfApoderado_sub");
    var mensaje = "";
    if ($.trim(txtNombresApoderado.val()) == "") {
        mensaje += "Ingrese Apellidos y nombres<br>";
        $("#txtNombresApoderado_sub").addClass("is-invalid");
    }
    if ($.trim(txtDniApoderado.val()) == "") {
        mensaje += "Ingrese en DNI<br>";
        $("#txtDniApoderado_sub").addClass("is-invalid");
    }
    if ($.trim(txtCorreoApoderado.val()) == "") {
        mensaje += "Ingrese el correo electrónico<br>";
        $("#txtCorreoApoderado_sub").addClass("is-invalid");
    }
    /*if ($.trim(txtTelfApoderado.val()) == "") {
     mensaje += "Ingrese el teléfono<br>";
     $("#txtTelfApoderado_sub").addClass("is-invalid");
     }*/
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
                a_txtNombresApoderado_sub: $.trim(txtNombresApoderado.val()),
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
    /*if ($.trim(txtTelfApoderadoN.val()) == "") {
     mensaje += "*Ingrese el teléfono<br>";
     $("#txtTelfApoderadoN_sub").addClass("is-invalid");
     }*/
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
            $("#cbbTipoSolicitudCodisEdi").change(function () {
                var value = $(this).val();
                if (value !== '' && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            var select = $("#cbbTipoSolicitudCodisEdi option").length;
            if (select === 2) {
                $('select[id=cbbTipoSolicitudCodisEdi] option:eq(1)').attr('selected', 'selected');
                cargar_solicitudes_a_editar("");
            }
        }
    });
}

function cargar_solicitudes_a_editar(solicitud) {
    var soli = $("#cbbTipoSolicitudCodisEdi").select().val();
    if (soli === "") {
        $('#divDetalleEditar').html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_carga_solicitudes",
                sol_cod: soli
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
                //var canvas1_edi_val = $("#canvas1_edi").is(':visible');
                //var canvas2_edi_val = $("#canvas_2edi").is(':visible');
                //if (canvas1_edi_val === true) {
                /*var wrapper = document.getElementById("signature-pad-edi");
                 canvas1_edi = wrapper.querySelector("canvas");
                 signaturePad_edi = new SignaturePad(canvas1_edi, {
                 backgroundColor: 'rgb(255, 255, 255)'
                 });
                 function resizeCanvas() {
                 var ratio = Math.max(window.devicePixelRatio || 1, 1);
                 canvas1_edi.width = canvas1_edi.offsetWidth * ratio;
                 canvas1_edi.height = canvas1_edi.offsetHeight * ratio;
                 canvas1_edi.getContext("2d").scale(ratio, ratio);
                 signaturePad_edi.clear();
                 }
                 window.onresize = resizeCanvas;
                 resizeCanvas();
                 canvas1_edi.addEventListener("click", function (event) {
                 if (!signaturePad_edi.isEmpty()) {
                 canvas1_edi.style.border = '1px solid black';
                 }
                 });
                 //}
                 //if (canvas2_edi_val === true) {
                 var wrapper_entrevistador = document.getElementById("signature-pad-entrevistador-edi");
                 canvas2_edi = wrapper_entrevistador.querySelector("canvas");
                 signaturePad_entrevistador_edi = new SignaturePad(canvas2_edi, {
                 backgroundColor: 'rgb(255, 255, 255)'
                 });
                 function resizeCanvas2() {
                 var ratio = Math.max(window.devicePixelRatio || 1, 1);
                 canvas2_edi.width = canvas2_edi.offsetWidth * ratio;
                 canvas2_edi.height = canvas2_edi.offsetHeight * ratio;
                 canvas2_edi.getContext("2d").scale(ratio, ratio);
                 signaturePad_entrevistador_edi.clear();
                 }
                 window.onresize = resizeCanvas2;
                 resizeCanvas2();
                 canvas2_edi.addEventListener("click", function (event) {
                 if (!signaturePad_entrevistador_edi.isEmpty()) {
                 canvas2_edi.style.border = '1px solid black';
                 }
                 });*/
                //}

                $("#cbbSubcategoria_edi").change(function () {
                    var value = $(this).val();
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });
                $("#cbbCategoria_edi").change(function () {
                    var value = $(this).val();
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });
                $("#cbbSexo_edi").change(function () {
                    var value = $(this).val();
                    if (value != 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                });
                $("#txtMotivo_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanEstudiante_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanEntrevistador_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdos_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtInforme_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanPadre_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtPlanDocente_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdosPadres_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
                $("#txtAcuerdosColegio_edi").keyup(function () {
                    var value = $(this).val().length;
                    if (value > 0 && $(this).is(".is-invalid")) {
                        $(this).removeClass("is-invalid");
                    }
                }).keyup();
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

function editar_solicitud_jesus() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var cbbTipoSolicitudCodis = $("#cbbTipoSolicitudCodisEdi").select().val();
    if (cbbTipoSolicitudCodis === "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Seleccione la Entrevista / Subentrevista',
            showConfirmButton: false
        });
        $("#cbbTipoSolicitudCodisEdi").addClass("is-invalid");
    } else {
        $("#btnEditarSolicitud").attr("disabled", true);
        var codi_solicitud = $("#cod_solicitud_edi").val();
        var solicitud_tipo = $("#cbbTipoSolicitud_edi").val();
        var docAlumno = $("#dataAlumno_edi").html().split(" - ");
        var matricula = $("#matric_edi").val();
        var sede = $("#txt_sede_edi").val();
        var categoria = $("#cbbCategoria_edi").select().val();
        var subcategoria = $("#cbbSubcategoria_edi").select().val();
        var sexo_alu = $("#cbbSexo_edi").select().val();
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
        var ruta_img1 = $("#ruta_img1").is(':visible');
        var ruta_img2 = $("#ruta_img2").is(':visible');
        var canvas_1 = document.getElementById("canvas1_edi");
        var canvas_2 = document.getElementById("canvas2_edi");
        var blank = isCanvasBlank(document.getElementById('canvas1_edi'));
        var blank2 = isCanvasBlank(document.getElementById('canvas2_edi'));
        SetTabletState(0, tmr, 0);
        SetTabletState(0, tmr2, 0);
        clearInterval(tmr);
        clearInterval(tmr2);
        var checkPrivacidad = $("#checkPrivacidad_edi").is(':checked');
        var privacidad_value = "";
        if (checkPrivacidad) {
            privacidad_value = "1";
        } else {
            privacidad_value = "0";
        }
        if (categoria === "") {
            mensaje += "*Seleccione la categoria<br>";
            $("#cbbCategoria_edi").addClass("is-invalid");
        }
        if (subcategoria === "") {
            mensaje += "*Seleccione la subcategoria<br>";
            $("#cbbSubcategoria_edi").addClass("is-invalid");
        }
        if (sexo_alu === "0") {
            mensaje += "*Seleccione el sexo<br>";
            $("#cbbSexo_edi").addClass("is-invalid");
        }
        if (solicitud_tipo === "1") {
            txtMotivo = $("#txtMotivo_edi").val();
            planEstudiante = $("#txtPlanEstudiante_edi").val();
            planEntrevistador = $("#txtPlanEntrevistador_edi").val();
            acuerdos = $("#txtAcuerdos_edi").val();
            informe = "";
            planPadre = "";
            planDocente = "";
            acuerdosPadres = "";
            acuerdosColegio = "";
            apoderado = "";
            if ($.trim(txtMotivo) == "") {
                mensaje += "*Ingrese el motivo de solicitud<br>";
                $("#txtMotivo_edi").addClass("is-invalid");
            }
            if ($.trim(planEstudiante) == '') {
                mensaje += "*Ingrese el Planteamiento del estudiante<br>";
                $("#txtPlanEstudiante_edi").addClass("is-invalid");
            }
            if ($.trim(planEntrevistador) == '') {
                mensaje += "*Ingrese el Planteamiento del entrevistador(a)<br>";
                $("#txtPlanEntrevistador_edi").addClass("is-invalid");
            }
            if ($.trim(acuerdos) == '') {
                mensaje += "*Ingrese los Acuerdos<br>";
                $("#txtAcuerdos_edi").addClass("is-invalid");
            }
            //if (ruta_img1 == false) {
            //    if (signaturePad_edi.isEmpty()) {
            //        mensaje += "*Ingrese la firma del estudiante<br>";
            //        canvas_1.style.border = '1px solid #dc3545';
            //    }
            //}
            /*if (ruta_img2 == false) {
             if (blank2 == true) {
             mensaje += "*Ingrese la firma del entrevistador<br>";
             canvas_2.style.border = '1px solid #dc3545';
             $("#ruta_img2").css("border", "1px solid #dc3545");
             }
             }*/ /*else {
              if (blank2 == true) {
              mensaje += "*Ingrese la firma del entrevistador<br>";
              canvas_2.style.border = '1px solid #dc3545';
              $("#ruta_img2").css("border", "1px solid #dc3545");
              }
              }*/
        } else {
            txtMotivo = $("#txtMotivo_edi").val();
            planEstudiante = "";
            planEntrevistador = "";
            acuerdos = "";
            informe = $("#txtInforme_edi").val();
            planPadre = $("#txtPlanPadre_edi").val();
            planDocente = $("#txtPlanDocente_edi").val();
            acuerdosPadres = $("#txtAcuerdosPadres_edi").val();
            acuerdosColegio = $("#txtAcuerdosColegio_edi").val();
            apoderado = $("#cbbTipoApoderado_edi").select().val();
            if ($.trim(apoderado) < 0 || $.trim(apoderado) === "") {
                mensaje += "*Seleccione el apoderado<br>";
                $("#cbbTipoApoderado_edi").addClass("is-invalid");
            }
            if ($.trim(txtMotivo) == "") {
                mensaje += "*Ingrese el motivo de solicitud<br>";
                $("#txtMotivo_edi").addClass("is-invalid");
            }
            if ($.trim(informe) == '') {
                mensaje += "*Ingrese el Informe<br>";
                $("#txtInforme_edi").addClass("is-invalid");
            }
            if ($.trim(planPadre) == '') {
                mensaje += "*Ingrese el Planteamiento del padre, madre <br>";
                $("#txtPlanPadre_edi").addClass("is-invalid");
            }
            if ($.trim(planDocente) == '') {
                mensaje += "*Ingrese el Planteamiento del docente<br>";
                $("#txtPlanDocente_edi").addClass("is-invalid");
            }
            if ($.trim(acuerdosPadres) == '') {
                mensaje += "*Ingrese las acciones a realizar por los padres<br>";
                $("#txtAcuerdosPadres_edi").addClass("is-invalid");
            }
            if ($.trim(acuerdosColegio) == '') {
                mensaje += "*Ingrese las acciones a realizar por el colegio<br>";
                $("#txtAcuerdosColegio_edi").addClass("is-invalid");
            }
            /*if (signaturePad.isEmpty()) {
             mensaje += "Ingrese la firma del padre, madre o apoderado<br>";
             canvas.style.border = '1px solid #dc3545';
             }*/
            /*if (ruta_img2 == false) {
             if (blank2 == true) {
             mensaje += "*Ingrese la firma del entrevistador<br>";
             canvas_2.style.border = '1px solid #dc3545';
             $("#ruta_img2").css("border", "1px solid #dc3545");
             }
             }*/ /*else {
              if (blank2 == true) {
              mensaje += "*Ingrese la firma del entrevistador<br>";
              canvas_2.style.border = '1px solid #dc3545';
              $("#ruta_img2").css("border", "1px solid #dc3545");
              }
              }*/
        }
        if (mensaje !== "") {
            Toast.fire({
                position: 'top-end',
                icon: 'error',
                title: mensaje,
                showConfirmButton: false
            });
            $("#btnEditarSolicitud").attr("disabled", false);
        } else {
            var codSMenu = $("#hdnCodiSR");
            var canvas1 = {};
            var dataURL1 = {};
            var canvas2 = {};
            var dataURL2 = {};
            var dataURL1_1 = "aaa";
            if (blank === true) {
                dataURL1_1 = "";
            }
            if (ruta_img1 == false) {
                canvas1 = document.getElementById('canvas1_edi');
                dataURL1 = canvas1.toDataURL();
            } else {
                dataURL1 = $("#ruta_img1").attr("src");
            }

            if (ruta_img2 == false) {
                canvas2 = document.getElementById('canvas2_edi');
                dataURL2 = canvas2.toDataURL();
            } else {
                dataURL2 = $("#ruta_img2").attr("src");
            }

            $.ajax({
                url: "php/aco_php/psi_editar_entrevista.php",
                dataType: "html",
                type: "POST",
                data: {
                    sm_codigo: $.trim(codSMenu.val()),
                    s_codi_solicitud: $.trim(codi_solicitud),
                    s_docAlumno: $.trim(docAlumno[0]),
                    s_solicitud_tipo: solicitud_tipo,
                    s_matricula: matricula,
                    s_sede: sede,
                    s_categoria: categoria,
                    s_subcategoria: subcategoria,
                    s_sexo_alu: sexo_alu,
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
                    s_privacidad: privacidad_value,
                    s_dataURL1: dataURL1,
                    s_dataURL2: dataURL2,
                    s_dataURL1_1: dataURL1_1
                },
                beforeSend: function (objeto) {
                    $("#modal-editar-solicitud-alumno").find('.modal-footer div label').html("");
                    $("#modal-editar-solicitud-alumno").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //$("#contentMenu").html(xhr.responseText);
                },
                success: function (datos) {
                    var resp = datos.split("***");
                    if (resp[1] === "1") {
                        var lista_sm = resp[3].split("--");
                        $("#modal-editar-solicitud-alumno").find('.modal-footer div label').html('');
                        Toast.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: resp[2],
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            $('#modal-editar-solicitud-alumno').modal('hide');
                            $("#btnEditarSolicitud").attr("disabled", false);
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
                            $("#btnEditarSolicitud").attr("disabled", false);
                        }, 4500);
                    }
                }
            });
        }
    }
}

function editar_solicitud() {//Guadalupe
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var cbbTipoSolicitudCodis = $("#cbbTipoSolicitudCodisEdi").select().val();
    if (cbbTipoSolicitudCodis === "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Seleccione la Entrevista / Subentrevista',
            showConfirmButton: false
        });
        $("#cbbTipoSolicitudCodisEdi").addClass("is-invalid");
    } else {
        $("#btnEditarSolicitud").attr("disabled", true);
        var codi_solicitud = $("#cod_solicitud_edi").val();
        var solicitud_tipo = $("#cbbTipoSolicitud_edi").val();
        var docAlumno = $("#dataAlumno_edi").html().split(" - ");
        var matricula = $("#matric_edi").val();
        var sede = $("#txt_sede_edi").val();
        var categoria = $("#cbbCategoria_edi").select().val();
        var subcategoria = $("#cbbSubcategoria_edi").select().val();
        var sexo_alu = $("#cbbSexo_edi").select().val();
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
        var txt_perfil = $("#txt_perfil").val();
        var ruta_img1 = $("#ruta_img1").is(':visible');
        var ruta_img2 = $("#ruta_img2").is(':visible');
        var canvas_1 = document.getElementById("canvas1_edi");
        var canvas_2 = document.getElementById("canvas2_edi");
        var blank = isCanvasBlank(document.getElementById('canvas1_edi'));
        var blank2 = "";
        if (txt_perfil === "3" && solicitud_tipo === "1") {
            blank2 = "";
        } else {
            blank2 = isCanvasBlank(document.getElementById('canvas2_edi'));
        }
        SetTabletState(0, tmr, 0);
        SetTabletState(0, tmr2, 0);
        clearInterval(tmr);
        clearInterval(tmr2);
        var checkPrivacidad = $("#checkPrivacidad_edi").is(':checked');
        var privacidad_value = "";
        if (checkPrivacidad) {
            privacidad_value = "1";
        } else {
            privacidad_value = "0";
        }
        if (categoria === "") {
            mensaje += "*Seleccione la categoria<br>";
            $("#cbbCategoria_edi").addClass("is-invalid");
        }
        if (subcategoria === "") {
            mensaje += "*Seleccione la subcategoria<br>";
            $("#cbbSubcategoria_edi").addClass("is-invalid");
        }
        if (sexo_alu === "0") {
            mensaje += "*Seleccione el sexo<br>";
            $("#cbbSexo_edi").addClass("is-invalid");
        }
        if (solicitud_tipo === "1") {
            txtMotivo = $("#txtMotivo_edi").val();
            planEstudiante = $("#txtPlanEstudiante_edi").val();
            planEntrevistador = $("#txtPlanEntrevistador_edi").val();
            acuerdos = $("#txtAcuerdos_edi").val();
            informe = "";
            planPadre = "";
            planDocente = "";
            acuerdosPadres = "";
            acuerdosColegio = "";
            apoderado = "";
            if ($.trim(txtMotivo) == "") {
                mensaje += "*Ingrese el motivo de solicitud<br>";
                $("#txtMotivo_edi").addClass("is-invalid");
            }
            if ($.trim(planEstudiante) == '') {
                mensaje += "*Ingrese el Planteamiento del estudiante<br>";
                $("#txtPlanEstudiante_edi").addClass("is-invalid");
            }
            if ($.trim(planEntrevistador) == '') {
                mensaje += "*Ingrese el Planteamiento del entrevistador(a)<br>";
                $("#txtPlanEntrevistador_edi").addClass("is-invalid");
            }
            if ($.trim(acuerdos) == '') {
                mensaje += "*Ingrese los Acuerdos<br>";
                $("#txtAcuerdos_edi").addClass("is-invalid");
            }
            //if (ruta_img1 == false) {
            //    if (signaturePad_edi.isEmpty()) {
            //        mensaje += "*Ingrese la firma del estudiante<br>";
            //        canvas_1.style.border = '1px solid #dc3545';
            //    }
            //}
            if (ruta_img2 == false) {
                if (txt_perfil !== "3" && solicitud_tipo !== "1") {
                    if (blank2 == true) {
                        mensaje += "*Ingrese la firma del entrevistador<br>";
                        canvas_2.style.border = '1px solid #dc3545';
                        $("#ruta_img2").css("border", "1px solid #dc3545");
                    }
                }
            } /*else {
             if (blank2 == true) {
             mensaje += "*Ingrese la firma del entrevistador<br>";
             canvas_2.style.border = '1px solid #dc3545';
             $("#ruta_img2").css("border", "1px solid #dc3545");
             }
             }*/
        } else {
            txtMotivo = $("#txtMotivo_edi").val();
            planEstudiante = "";
            planEntrevistador = "";
            acuerdos = "";
            informe = $("#txtInforme_edi").val();
            planPadre = $("#txtPlanPadre_edi").val();
            planDocente = $("#txtPlanDocente_edi").val();
            acuerdosPadres = $("#txtAcuerdosPadres_edi").val();
            acuerdosColegio = $("#txtAcuerdosColegio_edi").val();
            apoderado = $("#cbbTipoApoderado_edi").select().val();
            if ($.trim(apoderado) < 0 || $.trim(apoderado) === "") {
                mensaje += "*Seleccione el apoderado<br>";
                $("#cbbTipoApoderado_edi").addClass("is-invalid");
            }
            if ($.trim(txtMotivo) == "") {
                mensaje += "*Ingrese el motivo de solicitud<br>";
                $("#txtMotivo_edi").addClass("is-invalid");
            }
            if ($.trim(informe) == '') {
                mensaje += "*Ingrese el Informe<br>";
                $("#txtInforme_edi").addClass("is-invalid");
            }
            if ($.trim(planPadre) == '') {
                mensaje += "*Ingrese el Planteamiento del padre, madre <br>";
                $("#txtPlanPadre_edi").addClass("is-invalid");
            }
            if ($.trim(planDocente) == '') {
                mensaje += "*Ingrese el Planteamiento del docente<br>";
                $("#txtPlanDocente_edi").addClass("is-invalid");
            }
            if ($.trim(acuerdosPadres) == '') {
                mensaje += "*Ingrese las acciones a realizar por los padres<br>";
                $("#txtAcuerdosPadres_edi").addClass("is-invalid");
            }
            if ($.trim(acuerdosColegio) == '') {
                mensaje += "*Ingrese las acciones a realizar por el colegio<br>";
                $("#txtAcuerdosColegio_edi").addClass("is-invalid");
            }
            /*if (signaturePad.isEmpty()) {
             mensaje += "Ingrese la firma del padre, madre o apoderado<br>";
             canvas.style.border = '1px solid #dc3545';
             }*/
            if (ruta_img2 == false) {
                //if (txt_perfil !== "3" && solicitud_tipo !== "1") {
                if (blank2 == true) {
                    mensaje += "*Ingrese la firma del entrevistador<br>";
                    canvas_2.style.border = '1px solid #dc3545';
                    $("#ruta_img2").css("border", "1px solid #dc3545");
                }
                //}
            } /*else {
             if (blank2 == true) {
             mensaje += "*Ingrese la firma del entrevistador<br>";
             canvas_2.style.border = '1px solid #dc3545';
             $("#ruta_img2").css("border", "1px solid #dc3545");
             }
             }*/
        }
        if (mensaje !== "") {
            Toast.fire({
                position: 'top-end',
                icon: 'error',
                title: mensaje,
                showConfirmButton: false
            });
            $("#btnEditarSolicitud").attr("disabled", false);
        } else {
            var codSMenu = $("#hdnCodiSR");
            var canvas1 = {};
            var dataURL1 = {};
            var canvas2 = {};
            var dataURL2 = {};
            var dataURL1_1 = "aaa";
            if (blank === true) {
                dataURL1_1 = "";
            }
            if (ruta_img1 == false) {
                canvas1 = document.getElementById('canvas1_edi');
                dataURL1 = canvas1.toDataURL();
            } else {
                dataURL1 = $("#ruta_img1").attr("src");
            }

            if (ruta_img2 == false) {
                canvas2 = document.getElementById('canvas2_edi');
                dataURL2 = canvas2.toDataURL();
            } else {
                dataURL2 = $("#ruta_img2").attr("src");
            }

            $.ajax({
                url: "php/aco_php/psi_editar_entrevista.php",
                dataType: "html",
                type: "POST",
                data: {
                    sm_codigo: $.trim(codSMenu.val()),
                    s_codi_solicitud: $.trim(codi_solicitud),
                    s_docAlumno: $.trim(docAlumno[0]),
                    s_solicitud_tipo: solicitud_tipo,
                    s_matricula: matricula,
                    s_sede: sede,
                    s_categoria: categoria,
                    s_subcategoria: subcategoria,
                    s_sexo_alu: sexo_alu,
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
                    s_privacidad: privacidad_value,
                    s_dataURL1: dataURL1,
                    s_dataURL2: dataURL2,
                    s_dataURL1_1: dataURL1_1
                },
                beforeSend: function (objeto) {
                    $("#modal-editar-solicitud-alumno").find('.modal-footer div label').html("");
                    $("#modal-editar-solicitud-alumno").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //$("#contentMenu").html(xhr.responseText);
                },
                success: function (datos) {
                    var resp = datos.split("***");
                    if (resp[1] === "1") {
                        var lista_sm = resp[3].split("--");
                        $("#modal-editar-solicitud-alumno").find('.modal-footer div label').html('');
                        Toast.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: resp[2],
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            $('#modal-editar-solicitud-alumno').modal('hide');
                            $("#btnEditarSolicitud").attr("disabled", false);
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
                            $("#btnEditarSolicitud").attr("disabled", false);
                        }, 4500);
                    }
                }
            });
        }
    }
}

function limpiar_firma_edi() {
    //var ruta_img1 = $("#ruta_img1").is(':visible');
    var ruta_img1 = $("#ruta_img1").attr('src');
    var canvas1_edi = $("#canvas1_edi");
    var canvas1_edi_1 = document.getElementById("canvas1_edi");
    if (ruta_img1 !== "") {
        $("#ruta_img1").attr("src", "");
        $("#ruta_img1").hide();
        canvas1_edi_1.width = 500;
        canvas1_edi_1.height = 150;
        canvas1_edi.show();
        ClearTablet();
    } else {
        $("#ruta_img1").src = "";
        ClearTablet();
        //signaturePad_edi.clear();
    }
}

function limpiar_firma_entrevistador_edi() {
    //var ruta_img2 = $("#ruta_img2").is(':visible');
    var ruta_img2 = $("#ruta_img2").attr('src');
    var canvas2_edi = $("#canvas2_edi");
    var canvas1_edi_2 = document.getElementById("canvas2_edi");
    if (ruta_img2 !== "") {
        $("#ruta_img2").attr("src", "");
        $("#ruta_img2").hide();
        canvas1_edi_2.width = 500;
        canvas1_edi_2.height = 150;
        canvas2_edi.show();
        ClearTablet();
    } else {
        $("#ruta_img2").src = "";
        ClearTablet();
    }

}

function cargar_solicitudes_a_detallar(solicitud) {
    var solici = $("#cbbTipoSolicitudCodis").val();
    if (solici === "") {
        $('#divDetalleEntrevista').html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_carga_solicitudes_detalla",
                sol_cod: solici
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $('#divDetalleEntrevista').html(datos);
            }
        });
    }
}

function cargar_solicitudes_a_eliminar(solicitud) {
    var solici = $("#cbbTipoSolicitudCodisElis").select().val();
    if (solici === "") {
        $('#divDetalleEliminarEntrevista').html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_carga_solicitudes_eliminar",
                sol_cod: solici
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $('#divDetalleEliminarEntrevista').html(datos);
            }
        });
    }
}

function mostrar_registra_nueva_sede(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nueva_sede"
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbColorSed').selectpicker();
            $("#txtNombreSed").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDescripcionSed").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#cbbColorSed").change(function () {
                var value = $(this).val();
                if (value !== "0" && $(this).parent().is(".is-invalid")) {
                    $(this).parent().removeClass("is-invalid");
                }
            });
        }
    });
}

function registrar_sede() {
    $("#btnRegistrarSede").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSe");
    var nombre = $("#txtNombreSed");
    var descripcion = $("#txtDescripcionSed");
    var imagen = $("#cbbColorSed");
    var mensaje = "";
    if ($.trim(nombre.val()) == "") {
        mensaje += "Ingrese el nombre de la sede<br>";
        $("#txtNombreSed").addClass("is-invalid");
    }
    if ($.trim(descripcion.val()) == "") {
        mensaje += "Ingrese la descripción de la sede<br>";
        $("#txtDescripcionSed").addClass("is-invalid");
    }
    if ($.trim(imagen.select().val()) == 0) {
        mensaje += "Ingrese el color de la sede<br>";
        $("#cbbColorSed").parent().addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarSede").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_registro_nueva_sede",
                sm_codigo: $.trim(codSMenu.val()),
                m_nombre: $.trim(nombre.val()),
                m_descripcion: $.trim(descripcion.val()),
                m_imagen: $.trim(imagen.select().val())
            },
            beforeSend: function (objeto) {
                $("#modal-nueva-sede").find('.modal-footer div label').html("");
                $("#modal-nueva-sede").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nueva-sede").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nueva-sede').modal('hide');
                        $("#btnRegistrarSede").attr("disabled", false);
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
                        $("#btnRegistrarSede").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_sede(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_sede",
            u_sede_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $('#cbbImagenSedeEdi').selectpicker();
            $("#txtNombreSedEdi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#txtDescripcionSedEdi").keyup(function () {
                var value = $(this).val().length;
                if (value > 0 && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            }).keyup();
            $("#cbbImagenSedeEdi").change(function () {
                var value = $(this).val();
                if (value !== "0" && $(this).parent().is(".is-invalid")) {
                    $(this).parent().removeClass("is-invalid");
                }
            });
            $("#cbbEstadoSedeEdi").change(function () {
                var value = $(this).val();
                if (value !== "-1" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
        }
    });
}

function editar_sede() {
    $("#btnEditarSede").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSe");
    var codSede = $("#hdnCodiSede");
    var nombreEdi = $("#txtNombreSedEdi");
    var descripcionEdi = $("#txtDescripcionSedEdi");
    var imagenEdi = $("#cbbImagenSedeEdi");
    var estadoMeEdi = $("#cbbEstadoSedeEdi");
    var mensaje = "";
    if ($.trim(nombreEdi.val()) == "") {
        mensaje += "Ingrese el nombre de la sede<br>";
        $("#txtNombreSedEdi").addClass("is-invalid");
    }
    if ($.trim(descripcionEdi.val()) == "") {
        mensaje += "Ingrese la descripción de la sede<br>";
        $("#txtDescripcionSedEdi").addClass("is-invalid");
    }
    if ($.trim(imagenEdi.select().val()) == 0) {
        mensaje += "Ingrese el color de la sede<br>";
        $("#cbbImagenSedeEdi").parent().addClass("is-invalid");
    }
    if ($.trim(estadoMeEdi.select().val()) == -1) {
        mensaje += "Ingrese el estado de la sede<br>";
        $("#cbbEstadoSedeEdi").addClass("is-invalid");
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarSede").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_sede",
                sm_codigo: $.trim(codSMenu.val()),
                m_codigoEdi: $.trim(codSede.val()),
                m_nombreEdi: $.trim(nombreEdi.val()),
                m_descripcionEdi: $.trim(descripcionEdi.val()),
                m_imagenEdi: $.trim(imagenEdi.select().val()),
                m_estadoMeEdi: $.trim(estadoMeEdi.select().val())
            },
            beforeSend: function (objeto) {
                $("#modal-editar-sede").find('.modal-footer div label').html("");
                $("#modal-editar-sede").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-sede").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-sede').modal('hide');
                        $("#btnEditarSede").attr("disabled", false);
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
                        $("#btnEditarSede").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_eliminar_sede(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_sede",
            u_elsede_codigo: codigo
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

function eliminar_sede() {
    $("#btnEliminarSede").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSe");
    var hdnCodiSedeEli = $("#hdnCodiSedeEli");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_sede",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiSedeIdEli: $.trim(hdnCodiSedeEli.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-sede").find('.modal-footer div label').html("");
            $("#modal-eliminar-sede").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-sede").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-sede').modal('hide');
                    $("#btnEliminarSede").attr("disabled", false);
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
                    $("#btnEliminarSede").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_modificar_matriculas(modal) {
    var sede = $("#cbbSedes").select().val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_modificar_matriculas",
            s_sede: sede
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

function modificar_matriculas() {
    $("#btnEliminarSede").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiMM");
    var hdnCodiSedeEli = $("#hdnCodiSedeMatri");
    var anio = $("#cbbAnios");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_modificar_matriculas_por_sede",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiSedeId: $.trim(hdnCodiSedeEli.val()),
            sm_anio: $.trim(anio.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-sede").find('.modal-footer div label').html("");
            $("#modal-eliminar-sede").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-sede").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-sede').modal('hide');
                    $("#btnEliminarSede").attr("disabled", false);
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
                    $("#btnEliminarSede").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_descargar_solicitud(modal, solicitud) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_descargar_solicitud",
            s_solicitud: solicitud
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            /*$("#cbbTipoSolicitudCodisElis").change(function () {
             var value = $(this).val();
             if (value !== '' && $(this).is(".is-invalid")) {
             $(this).removeClass("is-invalid");
             }
             });*/
            var select = $("#cbbTipoSolicitudCodisDes option").length;
            if (select === 2) {
                $('select[id=cbbTipoSolicitudCodisDes] option:eq(1)').attr('selected', 'selected');
                cargar_solicitudes_a_descargar("");
            }
        }
    });
}

function cargar_solicitudes_a_descargar(solicitud) {
    var solici = $("#cbbTipoSolicitudCodisDes").select().val();
    if (solici === "") {
        $('#divDetalleDescargarEntrevista').html("");
    } else {
        window.location.href = "php/aco_php/psi_generar_pdf.php?sol_cod=" + solici;
    }
}

function mostrar_enviar_solicitud(modal, solicitud) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_enviar_solicitud",
            s_solicitud: solicitud
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $("#cbbTipoSolicitudCodisEnv").change(function () {
                var value = $(this).val();
                if (value !== '' && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            var select = $("#cbbTipoSolicitudCodisEnv option").length;
            if (select === 2) {
                $('select[id=cbbTipoSolicitudCodisEnv] option:eq(1)').attr('selected', 'selected');
                cargar_solicitudes_a_enviar("");
            }
        }
    });
}

function cargar_solicitudes_a_enviar(solicitud) {
    var solici = $("#cbbTipoSolicitudCodisEnv").select().val();
    if (solici === "") {
        $('#divDetalleEnviarEntrevista').html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "formulario_carga_solicitudes_enviar",
                sol_cod: solici
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var array = datos.split("***");
                if (array[0] === "0") {
                    $("#btnEnviarSolicitudAlumno").attr("disabled", true);
                } else {
                    $("#btnEnviarSolicitudAlumno").attr("disabled", false);
                }
                $('#divDetalleEnviarEntrevista').html(array[1]);
            }
        });
    }
}

function enviar_solicitud() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var cbbTipoSolicitudCodis = $("#cbbTipoSolicitudCodisEnv").select().val();
    if (cbbTipoSolicitudCodis === "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Seleccione la Entrevista / Subentrevista',
            showConfirmButton: false
        });
        $("#cbbTipoSolicitudCodisEnv").addClass("is-invalid");
        $("#btnEnviarSolicitudAlumno").attr("disabled", false);
    } else {
        $("#btnEnviarSolicitudAlumno").attr("disabled", true);
        var codSMenu = $("#hdnCodiSR");
        var hdnCodiSolicitud = $("#hdnCodiSolicitudEnv");
        var selected = [];
        $('#checkboxesEnv input:checked').each(function () {
            selected.push($(this).attr('value'));
        });
        if (selected.length <= 0) {
            Toast.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Debe seleccionar por lo menos 1 correo.',
                showConfirmButton: false
            });
            $("#btnEnviarSolicitudAlumno").attr("disabled", false);
        } else {
            $.ajax({
                url: "php/aco_php/psi_enviar_ficha_correo.php",
                dataType: "html",
                type: "POST",
                data: {
                    sm_codigo: $.trim(codSMenu.val()),
                    u_codiSolicitud: $.trim(hdnCodiSolicitud.val()),
                    u_lista_correos: selected
                },
                beforeSend: function (objeto) {
                    $("#modal-enviar-solicitud").find('.modal-footer div label').html("");
                    $("#modal-enviar-solicitud").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //$("#contentMenu").html(xhr.responseText);
                },
                success: function (datos) {
                    var resp = datos.split("***");
                    if (resp[1] === "1") {
                        var lista_sm = resp[3].split("--");
                        $("#modal-enviar-solicitud").find('.modal-footer div label').html('');
                        Toast.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: resp[2],
                            showConfirmButton: false
                        });
                        setTimeout(function () {
                            $('#modal-enviar-solicitud').modal('hide');
                            $("#btnEnviarSolicitudAlumno").attr("disabled", false);
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
                            $("#btnEnviarSolicitudAlumno").attr("disabled", false);
                        }, 4500);
                    }
                }
            });
        }
    }
}

function buscar_alumnos_no_entrevistados() {
    var sede = $("#cbbSedes").select().val();
    var fecha_inicio = $("#fecha1").val();
    var fecha_fin = $("#fecha2").val();
    var bimestre = $("#cbbBimestre").select().val();
    var nivel = $("#cbbNivel").select().val();
    var grado = $("#cbbGrado").select().val();
    var seccion = $("#cbbSeccion").select().val();
    var docente = $("#docen").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_buscar_no_entrevistados",
            s_sede: sede,
            s_fecha_inicio: fecha_inicio,
            s_fecha_fin: fecha_fin,
            s_bimestre: bimestre,
            s_nivel: nivel,
            s_grado: grado,
            s_seccion: seccion,
            s_docente: docente
        },
        beforeSend: function (objeto) {
            $("#modal-nueva-solicitud-detalle").find('.modal-footer div label').html("");
            $("#modal-nueva-solicitud-detalle").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableNoEntrevistados").DataTable().destroy();
            $("#tableNoEntrevistados tbody").html(datos);
            $("#tableNoEntrevistados").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "buttons": ["copy",
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Lista de Alumnos no entrevistados'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Lista de Alumnos no entrevistados'
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Lista de Alumnos no entrevistados'
                    }, "colvis"]
                        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        //"buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableNoEntrevistados_wrapper .col-md-6:eq(0)');
        }
    });
}


function buscar_entrevistas_alumnos() {
    var sede = $("#cbbSedes").select().val();
    var fecha_inicio = $("#fecha1").val();
    var fecha_fin = $("#fecha2").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_entrevistas_alumnos",
            s_sede: sede,
            s_fecha_inicio: fecha_inicio,
            s_fecha_fin: fecha_fin
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableEntrevistas").DataTable().destroy();
            $("#tableEntrevistas tbody").html(datos);
            $("#tableEntrevistas").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "buttons": ["copy",
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Lista de entrevitas a Alumnos'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Lista de entrevitas a Alumnos'
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Lista de entrevitas a Alumnos'
                    }, "colvis"]
                        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        //"buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableEntrevistas_wrapper .col-md-6:eq(0)');
        }
    });
}

function mostrar_grafico_solicitudes_registradas() {
    $.ajax({
        url: "php/aco_php/psi_grafico_entrevistas.php",
        dataType: "html",
        type: "POST",
        data: {
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#contenido_alertas").html(datos);
        }
    });
}

function mostrar_grafico_semaforo_docente() {
    $.ajax({
        url: "php/aco_php/psi_grafico_semaforo.php",
        dataType: "html",
        type: "POST",
        data: {

        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#contenido_alertas").html(datos);
        }
    });
}

function mostrar_grafico_alumnos_no_registrados() {
    $.ajax({
        url: "php/aco_php/psi_grafico_no_resgistrados.php",
        dataType: "html",
        type: "POST",
        data: {

        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#contenido_alertas").html(datos);
        }
    });
}

function semaforo_docentes_grafico_barras_sede(sede) {
    var str_sede = sede.value;
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "json",
        type: "POST",
        data: {
            opcion: "formulario_docentes_grafico_barras_sede",
            s_sede: str_sede
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#bar-chart").html("");
            if (datos.length !== 0) {
                window.barChart = Morris.Bar({
                    element: 'bar-chart',
                    data: datos,
                    xkey: 'y',
                    ykeys: ['a', 'b', 'c'],
                    labels: ['Total', 'Faltantes', 'Realizados'],
                    lineColors: ['#1e88e5', '#dc3545', '#28a745'],
                    barColors: ['#34495E', '#26B99A', '#3dbeee'],
                    lineWidth: '3px',
                    resize: true,
                    redraw: true
                }
                );
                $(window).resize(function () {
                    window.barChart.redraw();
                });
                $("#div_Niveles").html("");
                $("#div_Grados").html("");
                $("#div_Secciones").html("");
                //cargar_formulario_sede_nivel_semaforo("#div_Niveles");
            } else {
                $("#bar-chart").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                $("#div_Niveles").html("");
                $("#div_Grados").html("");
                $("#div_Secciones").html("");
            }
        }
    });
}

function cargar_formulario_sede_nivel_semaforo(div) {
    var str_sede = $("#cbbSedes").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_sede_nivel_semaforo",
            s_sede: str_sede
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function semaforo_docentes_grafico_barras_niveles(nivel) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    if ($.trim(str_nivel) == "") {
        $("#bar-chart2").html("");
        $("#div_Grados").html("");
        $("#div_Secciones").html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_docentes_grafico_barras_nivel",
                s_sede: str_sede,
                s_nivel: str_nivel
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#bar-chart2").html("");
                if (datos.length !== 0) {
                    window.barChart = Morris.Bar({
                        element: 'bar-chart2',
                        data: datos,
                        xkey: 'y',
                        ykeys: ['a', 'b', 'c'],
                        labels: ['Total', 'Faltantes', 'Realizados'],
                        lineColors: ['#1e88e5', '#dc3545', '#28a745'],
                        barColors: ['#34495E', '#26B99A', '#3dbeee'],
                        lineWidth: '3px',
                        resize: true,
                        redraw: true
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    //$("#div_Niveles").html("");
                    //$("#div_Grados").html("");
                    $("#div_Secciones").html("");
                    cargar_formulario_nivel_grado_semaforo("#div_Grados");
                } else {
                    $("#bar-chart2").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                    $("#div_Niveles").html("");
                    $("#div_Grados").html("");
                    $("#div_Secciones").html("");
                }
            }
        });
    }
}

function cargar_formulario_nivel_grado_semaforo(div) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nivel_grado_semaforo",
            s_sede: str_sede,
            s_nivel: str_nivel
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function semaforo_docentes_grafico_barras_grados(grado) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    var str_grado = $("#cbbGrado").select().val();
    if ($.trim(str_nivel) == "") {
        $("#bar-chart2").html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_docentes_grafico_barras_grado",
                s_sede: str_sede,
                s_nivel: str_nivel,
                s_grado: str_grado
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#bar-chart3").html("");
                if (datos.length !== 0) {
                    window.barChart = Morris.Bar({
                        element: 'bar-chart3',
                        data: datos,
                        xkey: 'y',
                        ykeys: ['a', 'b', 'c'],
                        labels: ['Total', 'Faltantes', 'Realizados'],
                        lineColors: ['#1e88e5', '#dc3545', '#28a745'],
                        barColors: ['#34495E', '#26B99A', '#3dbeee'],
                        lineWidth: '3px',
                        resize: true,
                        redraw: true
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    //$("#div_Niveles").html("");
                    //$("#div_Grados").html("");
                    $("#div_Secciones").html("");
                    cargar_formulario_grado_seccion_semaforo("#div_Secciones");
                } else {
                    $("#bar-chart2").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                    $("#div_Niveles").html("");
                    $("#div_Grados").html("");
                    $("#div_Secciones").html("");
                }
            }
        });
    }
}

function cargar_formulario_grado_seccion_semaforo(div) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    var str_grado = $("#cbbGrado").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_grado_seccion_semaforo",
            s_sede: str_sede,
            s_nivel: str_nivel,
            s_grado: str_grado
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function semaforo_docentes_grafico_barras_secciones(seccion) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    var str_grado = $("#cbbGrado").select().val();
    var str_seccion = $("#cbbSeccion").select().val();
    if ($.trim(str_seccion) == "") {
        $("#bar-chart4").html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_docentes_grafico_barras_secciones",
                s_sede: str_sede,
                s_nivel: str_nivel,
                s_grado: str_grado,
                s_seccion: str_seccion
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#bar-chart4").html("");
                if (datos.length !== 0) {
                    window.barChart = Morris.Bar({
                        element: 'bar-chart4',
                        data: datos,
                        xkey: 'y',
                        ykeys: ['a', 'b', 'c'],
                        labels: ['Total', 'Faltantes', 'Realizados'],
                        lineColors: ['#1e88e5', '#dc3545', '#28a745'],
                        barColors: ['#34495E', '#26B99A', '#3dbeee'],
                        lineWidth: '3px',
                        resize: true,
                        redraw: true
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    //$("#div_Niveles").html("");
                    //$("#div_Grados").html("");
                    //$("#div_Secciones").html("");
                } else {
                    $("#bar-chart4").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                }
            }
        });
    }
}

function alumnos_no_entrevistados_grafico_barras_sede(sede) {
    var str_sede = sede.value;
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "json",
        type: "POST",
        data: {
            opcion: "formulario_no_entrevistados_grafico_barras_sede",
            s_sede: str_sede
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#bar-chart").html("");
            $("#donut-chart").html("");
            $("#bar-chart2").html("");
            $("#div_Grados").html("");
            $("#div_Secciones").html("");
            if (datos.length !== 0) {
                var arreglo = [];
                for (const i in datos) {
                    arreglo.push({'label': datos[i].y, 'value': datos[i].c});
                }
                window.barChart = Morris.Bar({
                    element: 'bar-chart',
                    data: datos,
                    xkey: 'y',
                    ykeys: ['a', 'b', 'c'],
                    labels: ['Total', 'Faltantes', 'Realizados'],
                    lineColors: ['#1e88e5', '#dc3545', '#3dbeee'],
                    lineWidth: '3px',
                    resize: true,
                    redraw: true
                }
                );
                $(window).resize(function () {
                    window.barChart.redraw();
                    window.donutChart.redraw();
                });
                window.donutChart = Morris.Donut({
                    element: 'donut-chart',
                    data: arreglo,
                    resize: true,
                    redraw: true
                });
            } else {
                $("#bar-chart").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                $("#donut-chart").html("");
            }
        }
    });
}

function entrevistas_registradas_grafico_lineal_sede(sede) {
    var str_sede = sede.value;
    var str_privacidad = $("#txtPrivacidad").val()
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "json",
        type: "POST",
        data: {
            opcion: "formulario_grafico_solicitudes_registradas",
            s_sede: str_sede,
            s_privacidad: str_privacidad
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#line-chart").html("");
            if (datos.length !== 0) {
                const monthNames = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                    "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"
                ];
                window.lineChart = Morris.Line({
                    element: 'line-chart',
                    data: datos,
                    xkey: 'y',
                    parseTime: false,
                    ykeys: ['a', 'b', 'c'],
                    xLabelFormat: function (x) {
                        var index = parseInt(x.src.y);
                        return monthNames[index];
                    },
                    xLabels: "month",
                    labels: ['Total', 'Entrevista a Estudiantes', 'Entrevista a Padres de Familia'],
                    lineColors: ['#1e88e5', '#dc3545', '#3dbeee'],
                    hideHover: 'auto'
                }
                );
                $(window).resize(function () {
                    window.barChart.redraw();
                });
                $('#cbbNiveles option[value=0]').attr('selected', 'selected');
                $("#line-chart2").html("");
            } else {
                $("#line-chart").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
            }
            $("#divGrados").html("");
            $("#divSecciones").html("");
            $('#cbbNiveles option[value=0]').attr('selected', 'selected');
        }
    });
}

function entrevistas_registradas_grafico_lineal_niveles(nivel) {
    var str_sede = $("#cbbSedes").select().val();
    var str_privacidad = $("#txtPrivacidad").val();
    var str_nivel = $("#cbbNiveles").select().val();
    if (str_nivel !== "0") {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_grafico_solicitudes_registradas_niveles",
                s_sede: str_sede,
                s_privacidad: str_privacidad,
                s_nivel: str_nivel
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#line-chart2").html("");
                if (datos.length !== 0) {
                    const monthNames = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                        "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"
                    ];
                    window.lineChart = Morris.Line({
                        element: 'line-chart2',
                        data: datos,
                        xkey: 'y',
                        parseTime: false,
                        ykeys: ['a', 'b', 'c'],
                        xLabelFormat: function (x) {
                            var index = parseInt(x.src.y);
                            return monthNames[index];
                        },
                        xLabels: "month",
                        labels: ['Total', 'Entrevista a Estudiantes', 'Entrevista a Padres de Familia'],
                        lineColors: ['#1e88e5', '#dc3545', '#3dbeee'],
                        hideHover: 'auto'
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    $("#divGrados").html("");
                    $("#divSecciones").html("");
                    cargar_formulario_grado_nivel("#divGrados");
                } else {
                    $("#line-chart2").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                }
            }
        });
    } else {
        $("#line-chart2").html("");
        $("#divGrados").html("");
        $("#divSecciones").html("");
    }
}

function cargar_formulario_grado_nivel(div) {
    var str_nivel = $("#cbbNiveles").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_grado_nivel",
            s_nivel: str_nivel
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function entrevistas_registradas_grafico_lineal_grados(grado) {
    var str_sede = $("#cbbSedes").select().val();
    var str_privacidad = $("#txtPrivacidad").val();
    var str_nivel = $("#cbbNiveles").select().val();
    var str_grado = $("#cbbGrados").select().val();
    if (str_grado !== "0") {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_grafico_solicitudes_registradas_grados",
                s_sede: str_sede,
                s_privacidad: str_privacidad,
                s_nivel: str_nivel,
                s_grado: str_grado
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#line-chart3").html("");
                if (datos.length !== 0) {
                    const monthNames = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                        "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"
                    ];
                    window.lineChart = Morris.Line({
                        element: 'line-chart3',
                        data: datos,
                        xkey: 'y',
                        parseTime: false,
                        ykeys: ['a', 'b', 'c'],
                        xLabelFormat: function (x) {
                            var index = parseInt(x.src.y);
                            return monthNames[index];
                        },
                        xLabels: "month",
                        labels: ['Total', 'Entrevista a Estudiantes', 'Entrevista a Padres de Familia'],
                        lineColors: ['#1e88e5', '#dc3545', '#3dbeee'],
                        hideHover: 'auto'
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    cargar_formulario_seccion_grado("#divSecciones");
                } else {
                    $("#line-chart3").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                }
            }
        });
    } else {
        $("#line-chart3").html("");
        $("#divSecciones").html("");
    }
}

function cargar_formulario_seccion_grado(div) {
    var str_nivel = $("#cbbNiveles").select().val();
    var str_grado = $("#cbbGrados").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_seccion_grado",
            s_nivel: str_nivel,
            s_grado: str_grado
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function entrevistas_registradas_grafico_lineal_secciones(seccion) {
    var str_sede = $("#cbbSedes").select().val();
    var str_privacidad = $("#txtPrivacidad").val();
    var str_nivel = $("#cbbNiveles").select().val();
    var str_grado = $("#cbbGrados").select().val();
    var str_seccion = $("#cbbSecciones").select().val();
    if (str_seccion !== "0") {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_grafico_solicitudes_registradas_secciones",
                s_sede: str_sede,
                s_privacidad: str_privacidad,
                s_nivel: str_nivel,
                s_grado: str_grado,
                s_seccion: str_seccion
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#line-chart4").html("");
                if (datos.length !== 0) {
                    const monthNames = ["", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
                        "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre"
                    ];
                    window.lineChart = Morris.Line({
                        element: 'line-chart4',
                        data: datos,
                        xkey: 'y',
                        parseTime: false,
                        ykeys: ['a', 'b', 'c'],
                        xLabelFormat: function (x) {
                            var index = parseInt(x.src.y);
                            return monthNames[index];
                        },
                        xLabels: "month",
                        labels: ['Total', 'Entrevista a Estudiantes', 'Entrevista a Padres de Familia'],
                        lineColors: ['#1e88e5', '#dc3545', '#3dbeee'],
                        hideHover: 'auto'
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                } else {
                    //$("#line-chart4").html("");
                    $("#line-chart4").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                }
            }
        });
    } else {
        $("#line-chart4").html("");
    }
}

function mostrar_historial_alumno(dato) {
    var mensaje = "";
    var codSMenu = $("#hdnCodiSR");
    var matricu = dato;
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_historial_alumno",
            sol_matricula: matricu
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#divHistorial").html(datos);
            $('.checkAll').click(function () {
                if (this.checked) {
                    $(".checkboxes").prop("checked", true);
                } else {
                    $(".checkboxes").prop("checked", false);
                }
            });

            $(".checkboxes").click(function () {
                var numberOfCheckboxes = $(".checkboxes").length;
                var numberOfCheckboxesChecked = $('.checkboxes:checked').length;
                if (numberOfCheckboxes == numberOfCheckboxesChecked) {
                    $(".checkAll").prop("checked", true);
                } else {
                    $(".checkAll").prop("checked", false);
                }
            });

            $('.checkAllCampos').click(function () {
                if (this.checked) {
                    $(".checkboxesCampos").prop("checked", true);
                } else {
                    $(".checkboxesCampos").prop("checked", false);
                }
            });

            $(".checkboxesCampos").click(function () {
                var numberOfCheckboxes = $(".checkboxesCampos").length;
                var numberOfCheckboxesChecked = $('.checkboxesCampos:checked').length;
                if (numberOfCheckboxes == numberOfCheckboxesChecked) {
                    $(".checkAllCampos").prop("checked", true);
                } else {
                    $(".checkAllCampos").prop("checked", false);
                }
            });
        }
    });
}

function mostrar_entrevis_subentrevis(codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_historial_detalle",
            sol_cod: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#divHistorialDetalle").html(datos);
        }
    });
}


function mostrar_registra_nuevos_bimestres(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevos_bimestres"
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            var anio = $("#cbbAnios").select().val();
            validar_rango_fechas_x_anio(anio);
            $("#fecha11").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha12").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha21").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha22").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha31").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha32").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha41").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha42").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
        }
    });
}

function cambiar_anio_bimestre(dato) {
    validar_rango_fechas_x_anio(dato.value);
}

function validar_rango_fechas_x_anio(anio) {
    $("#fecha11").daterangepicker({
        autoApply: true,
        showButtonPanel: false,
        singleDatePicker: true,
        showDropdowns: true,
        linkedCalendar: false,
        autoUpdateInput: false,
        showCustomRangeLabel: false,
        locale: {
            format: "DD/MM/YYYY"
        },
        minDate: '01/01/' + anio,
        maxDate: '31/12/' + anio
    }).on('apply.daterangepicker', function (ev, start) {
        $("#fecha11").val(start.endDate.format('DD/MM/YYYY'));
        var fechaConcar1 = $("#fecha11").val();
        var array_fecha1 = fechaConcar1.split("/");
        var day_1 = parseInt(array_fecha1[0]);
        var month_1 = parseInt(array_fecha1[1]);
        var year_1 = parseInt(array_fecha1[2]);
        var str_msj = "";
        var str_can = "";
        if (month_1 == 12) {
            var lastDate = new Date(year_1, month_1 + 1, 0);
            var lastDay = lastDate.getDate();
            str_can = lastDay - day_1;
            str_msj = "day";
        } else {
            str_can = 12;
            str_msj = "month";
        }

        $("#fecha12").daterangepicker({
            autoApply: true,
            singleDatePicker: true,
            showDropdowns: true,
            linkedCalendar: false,
            autoUpdateInput: false,
            showCustomRangeLabel: false,
            starDate: start.endDate.format("DD/MM/YYYY"),
            minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
            /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
            maxDate: '31/12/' + anio,
            locale: {
                format: "DD/MM/YYYY"
            }
        }, function (start, end, label) {
        }).on('apply.daterangepicker', function (ev, start) {
            $("#fecha12").val(start.endDate.format("DD/MM/YYYY"));
            var fechaConcar1 = $("#fecha12").val();
            var array_fecha1 = fechaConcar1.split("/");
            var day_1 = parseInt(array_fecha1[0]);
            var month_1 = parseInt(array_fecha1[1]);
            var year_1 = parseInt(array_fecha1[2]);
            var str_msj = "";
            var str_can = "";
            if (month_1 == 12) {
                var lastDate = new Date(year_1, month_1 + 1, 0);
                var lastDay = lastDate.getDate();
                str_can = lastDay - day_1;
                str_msj = "day";
            } else {
                str_can = 12;
                str_msj = "month";
            }
            $("#fecha21").daterangepicker({
                autoApply: true,
                singleDatePicker: true,
                showDropdowns: true,
                linkedCalendar: false,
                autoUpdateInput: false,
                showCustomRangeLabel: false,
                starDate: start.endDate.format("DD/MM/YYYY"),
                minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                maxDate: '31/12/' + anio,
                locale: {
                    format: "DD/MM/YYYY"
                }
            }, function (start, end, label) {
            }).on('apply.daterangepicker', function (ev, start) {
                $("#fecha21").val(start.endDate.format("DD/MM/YYYY"));
                var fechaConcar1 = $("#fecha21").val();
                var array_fecha1 = fechaConcar1.split("/");
                var day_1 = parseInt(array_fecha1[0]);
                var month_1 = parseInt(array_fecha1[1]);
                var year_1 = parseInt(array_fecha1[2]);
                var str_msj = "";
                var str_can = "";
                if (month_1 == 12) {
                    var lastDate = new Date(year_1, month_1 + 1, 0);
                    var lastDay = lastDate.getDate();
                    str_can = lastDay - day_1;
                    str_msj = "day";
                } else {
                    str_can = 12;
                    str_msj = "month";
                }
                $("#fecha22").daterangepicker({
                    autoApply: true,
                    singleDatePicker: true,
                    showDropdowns: true,
                    linkedCalendar: false,
                    autoUpdateInput: false,
                    showCustomRangeLabel: false,
                    starDate: start.endDate.format("DD/MM/YYYY"),
                    minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                    /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                    maxDate: '31/12/' + anio,
                    locale: {
                        format: "DD/MM/YYYY"
                    }
                }, function (start, end, label) {
                }).on('apply.daterangepicker', function (ev, start) {
                    $("#fecha22").val(start.endDate.format("DD/MM/YYYY"));
                    var fechaConcar1 = $("#fecha22").val();
                    var array_fecha1 = fechaConcar1.split("/");
                    var day_1 = parseInt(array_fecha1[0]);
                    var month_1 = parseInt(array_fecha1[1]);
                    var year_1 = parseInt(array_fecha1[2]);
                    var str_msj = "";
                    var str_can = "";
                    if (month_1 == 12) {
                        var lastDate = new Date(year_1, month_1 + 1, 0);
                        var lastDay = lastDate.getDate();
                        str_can = lastDay - day_1;
                        str_msj = "day";
                    } else {
                        str_can = 12;
                        str_msj = "month";
                    }
                    $("#fecha31").daterangepicker({
                        autoApply: true,
                        singleDatePicker: true,
                        showDropdowns: true,
                        linkedCalendar: false,
                        autoUpdateInput: false,
                        showCustomRangeLabel: false,
                        starDate: start.endDate.format("DD/MM/YYYY"),
                        minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                        /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                        maxDate: '31/12/' + anio,
                        locale: {
                            format: "DD/MM/YYYY"
                        }
                    }, function (start, end, label) {
                    }).on('apply.daterangepicker', function (ev, start) {
                        $("#fecha31").val(start.endDate.format("DD/MM/YYYY"));
                        var fechaConcar1 = $("#fecha31").val();
                        var array_fecha1 = fechaConcar1.split("/");
                        var day_1 = parseInt(array_fecha1[0]);
                        var month_1 = parseInt(array_fecha1[1]);
                        var year_1 = parseInt(array_fecha1[2]);
                        var str_msj = "";
                        var str_can = "";
                        if (month_1 == 12) {
                            var lastDate = new Date(year_1, month_1 + 1, 0);
                            var lastDay = lastDate.getDate();
                            str_can = lastDay - day_1;
                            str_msj = "day";
                        } else {
                            str_can = 12;
                            str_msj = "month";
                        }
                        $("#fecha32").daterangepicker({
                            autoApply: true,
                            singleDatePicker: true,
                            showDropdowns: true,
                            linkedCalendar: false,
                            autoUpdateInput: false,
                            showCustomRangeLabel: false,
                            starDate: start.endDate.format("DD/MM/YYYY"),
                            minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                            /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                            maxDate: '31/12/' + anio,
                            locale: {
                                format: "DD/MM/YYYY"
                            }
                        }, function (start, end, label) {
                        }).on('apply.daterangepicker', function (ev, start) {
                            $("#fecha32").val(start.endDate.format("DD/MM/YYYY"));
                            var fechaConcar1 = $("#fecha32").val();
                            var array_fecha1 = fechaConcar1.split("/");
                            var day_1 = parseInt(array_fecha1[0]);
                            var month_1 = parseInt(array_fecha1[1]);
                            var year_1 = parseInt(array_fecha1[2]);
                            var str_msj = "";
                            var str_can = "";
                            if (month_1 == 12) {
                                var lastDate = new Date(year_1, month_1 + 1, 0);
                                var lastDay = lastDate.getDate();
                                str_can = lastDay - day_1;
                                str_msj = "day";
                            } else {
                                str_can = 12;
                                str_msj = "month";
                            }
                            $("#fecha41").daterangepicker({
                                autoApply: true,
                                singleDatePicker: true,
                                showDropdowns: true,
                                linkedCalendar: false,
                                autoUpdateInput: false,
                                showCustomRangeLabel: false,
                                starDate: start.endDate.format("DD/MM/YYYY"),
                                minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                                /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                                maxDate: '31/12/' + anio,
                                locale: {
                                    format: "DD/MM/YYYY"
                                }
                            }, function (start, end, label) {
                            }).on('apply.daterangepicker', function (ev, start) {
                                $("#fecha41").val(start.endDate.format("DD/MM/YYYY"));
                                var fechaConcar1 = $("#fecha41").val();
                                var array_fecha1 = fechaConcar1.split("/");
                                var day_1 = parseInt(array_fecha1[0]);
                                var month_1 = parseInt(array_fecha1[1]);
                                var year_1 = parseInt(array_fecha1[2]);
                                var str_msj = "";
                                var str_can = "";
                                if (month_1 == 12) {
                                    var lastDate = new Date(year_1, month_1 + 1, 0);
                                    var lastDay = lastDate.getDate();
                                    str_can = lastDay - day_1;
                                    str_msj = "day";
                                } else {
                                    str_can = 12;
                                    str_msj = "month";
                                }
                                $("#fecha42").daterangepicker({
                                    autoApply: true,
                                    singleDatePicker: true,
                                    showDropdowns: true,
                                    linkedCalendar: false,
                                    autoUpdateInput: false,
                                    showCustomRangeLabel: false,
                                    starDate: start.endDate.format("DD/MM/YYYY"),
                                    minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                                    /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                                    maxDate: '31/12/' + anio,
                                    locale: {
                                        format: "DD/MM/YYYY"
                                    }
                                }, function (start, end, label) {
                                }).on('apply.daterangepicker', function (ev, start) {
                                    $("#fecha42").val(start.endDate.format("DD/MM/YYYY"));
                                });
                            });
                        });
                    });
                });
            });
        });
    });

}

function registrar_bimestres() {
    $("#btnRegistrarBimestres").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var hdnCodiBi = $("#hdnCodiBi").val();
    var anio = $("#cbbAnios").select().val();
    var fecha11 = $("#fecha11").val();
    var fecha12 = $("#fecha12").val();
    var fecha21 = $("#fecha21").val();
    var fecha22 = $("#fecha22").val();
    var fecha31 = $("#fecha31").val();
    var fecha32 = $("#fecha32").val();
    var fecha41 = $("#fecha41").val();
    var fecha42 = $("#fecha42").val();
    var mensaje = "";
    if ($.trim(fecha11) === "") {
        mensaje += "*Ingrese la fecha de inicio del Primer Bimestre<br>";
        $("#fecha11").addClass("is-invalid");
    }
    if ($.trim(fecha12) === "") {
        mensaje += "*Ingrese la fecha fin del Primer Bimestre<br>";
        $("#fecha12").addClass("is-invalid");
    }
    if ($.trim(fecha21) === "") {
        mensaje += "*Ingrese la fecha de inicio del Segundo Bimestre<br>";
        $("#fecha21").addClass("is-invalid");
    }
    if ($.trim(fecha22) === "") {
        mensaje += "*Ingrese la fecha fin del Segundo Bimestre<br>";
        $("#fecha22").addClass("is-invalid");
    }
    if ($.trim(fecha31) === "") {
        mensaje += "*Ingrese la fecha de inicio del Tercer Bimestre<br>";
        $("#fecha31").addClass("is-invalid");
    }
    if ($.trim(fecha32) === "") {
        mensaje += "*Ingrese la fecha fin del Tercer Bimestre<br>";
        $("#fecha32").addClass("is-invalid");
    }
    if ($.trim(fecha41) === "") {
        mensaje += "*Ingrese la fecha de inicio del Cuarto Bimestre<br>";
        $("#fecha41").addClass("is-invalid");
    }
    if ($.trim(fecha42) === "") {
        mensaje += "*Ingrese la fecha fin del Cuarto Bimestre<br>";
        $("#fecha42").addClass("is-invalid");
    }

    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarBimestres").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_registrar_bimestres",
                sm_codigo: $.trim(hdnCodiBi),
                s_anio: anio,
                s_fecha11: $.trim(fecha11),
                s_fecha12: $.trim(fecha12),
                s_fecha21: $.trim(fecha21),
                s_fecha22: $.trim(fecha22),
                s_fecha31: $.trim(fecha31),
                s_fecha32: $.trim(fecha32),
                s_fecha41: $.trim(fecha41),
                s_fecha42: $.trim(fecha42)
            },
            beforeSend: function (objeto) {
                $("#modal-nuevos-bimestres").find('.modal-footer div label').html("");
                $("#modal-nuevos-bimestres").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevos-bimestres").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevos-bimestres').modal('hide');
                        $("#btnRegistrarBimestres").attr("disabled", false);
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
                        $("#btnRegistrarBimestres").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_bimestres(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_bimestres",
            u_anio_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            var anio = $("#cbbAnios_edi").select().val();
            validar_rango_fechas_x_anio_edi(anio);
            $("#fecha11_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha12_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha21_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha22_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha31_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha32_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha41_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
            $("#fecha42_edi").click(function () {
                var value = $(this).val().length;
                if (value !== "" && $(this).is(".is-invalid")) {
                    $(this).removeClass("is-invalid");
                }
            });
        }
    });
}

function validar_rango_fechas_x_anio_edi(anio) {
    $("#fecha11_edi").daterangepicker({
        autoApply: true,
        showButtonPanel: false,
        singleDatePicker: true,
        showDropdowns: true,
        linkedCalendar: false,
        autoUpdateInput: false,
        showCustomRangeLabel: false,
        locale: {
            format: "DD/MM/YYYY"
        },
        minDate: '01/01/' + anio,
        maxDate: '31/12/' + anio
    }).on('apply.daterangepicker', function (ev, start) {
        $("#fecha11_edi").val(start.endDate.format('DD/MM/YYYY'));
        var fechaConcar1 = $("#fecha11_edi").val();
        var array_fecha1 = fechaConcar1.split("/");
        var day_1 = parseInt(array_fecha1[0]);
        var month_1 = parseInt(array_fecha1[1]);
        var year_1 = parseInt(array_fecha1[2]);
        var str_msj = "";
        var str_can = "";
        if (month_1 == 12) {
            var lastDate = new Date(year_1, month_1 + 1, 0);
            var lastDay = lastDate.getDate();
            str_can = lastDay - day_1;
            str_msj = "day";
        } else {
            str_can = 12;
            str_msj = "month";
        }

        $("#fecha12_edi").daterangepicker({
            autoApply: true,
            singleDatePicker: true,
            showDropdowns: true,
            linkedCalendar: false,
            autoUpdateInput: false,
            showCustomRangeLabel: false,
            starDate: start.endDate.format("DD/MM/YYYY"),
            minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
            /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
            maxDate: '31/12/' + anio,
            locale: {
                format: "DD/MM/YYYY"
            }
        }, function (start, end, label) {
        }).on('apply.daterangepicker', function (ev, start) {
            $("#fecha12_edi").val(start.endDate.format("DD/MM/YYYY"));
            var fechaConcar1 = $("#fecha12_edi").val();
            var array_fecha1 = fechaConcar1.split("/");
            var day_1 = parseInt(array_fecha1[0]);
            var month_1 = parseInt(array_fecha1[1]);
            var year_1 = parseInt(array_fecha1[2]);
            var str_msj = "";
            var str_can = "";
            if (month_1 == 12) {
                var lastDate = new Date(year_1, month_1 + 1, 0);
                var lastDay = lastDate.getDate();
                str_can = lastDay - day_1;
                str_msj = "day";
            } else {
                str_can = 12;
                str_msj = "month";
            }
            $("#fecha21_edi").daterangepicker({
                autoApply: true,
                singleDatePicker: true,
                showDropdowns: true,
                linkedCalendar: false,
                autoUpdateInput: false,
                showCustomRangeLabel: false,
                starDate: start.endDate.format("DD/MM/YYYY"),
                minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                maxDate: '31/12/' + anio,
                locale: {
                    format: "DD/MM/YYYY"
                }
            }, function (start, end, label) {
            }).on('apply.daterangepicker', function (ev, start) {
                $("#fecha21_edi").val(start.endDate.format("DD/MM/YYYY"));
                var fechaConcar1 = $("#fecha21_edi").val();
                var array_fecha1 = fechaConcar1.split("/");
                var day_1 = parseInt(array_fecha1[0]);
                var month_1 = parseInt(array_fecha1[1]);
                var year_1 = parseInt(array_fecha1[2]);
                var str_msj = "";
                var str_can = "";
                if (month_1 == 12) {
                    var lastDate = new Date(year_1, month_1 + 1, 0);
                    var lastDay = lastDate.getDate();
                    str_can = lastDay - day_1;
                    str_msj = "day";
                } else {
                    str_can = 12;
                    str_msj = "month";
                }
                $("#fecha22_edi").daterangepicker({
                    autoApply: true,
                    singleDatePicker: true,
                    showDropdowns: true,
                    linkedCalendar: false,
                    autoUpdateInput: false,
                    showCustomRangeLabel: false,
                    starDate: start.endDate.format("DD/MM/YYYY"),
                    minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                    /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                    maxDate: '31/12/' + anio,
                    locale: {
                        format: "DD/MM/YYYY"
                    }
                }, function (start, end, label) {
                }).on('apply.daterangepicker', function (ev, start) {
                    $("#fecha22_edi").val(start.endDate.format("DD/MM/YYYY"));
                    var fechaConcar1 = $("#fecha22_edi").val();
                    var array_fecha1 = fechaConcar1.split("/");
                    var day_1 = parseInt(array_fecha1[0]);
                    var month_1 = parseInt(array_fecha1[1]);
                    var year_1 = parseInt(array_fecha1[2]);
                    var str_msj = "";
                    var str_can = "";
                    if (month_1 == 12) {
                        var lastDate = new Date(year_1, month_1 + 1, 0);
                        var lastDay = lastDate.getDate();
                        str_can = lastDay - day_1;
                        str_msj = "day";
                    } else {
                        str_can = 12;
                        str_msj = "month";
                    }
                    $("#fecha31_edi").daterangepicker({
                        autoApply: true,
                        singleDatePicker: true,
                        showDropdowns: true,
                        linkedCalendar: false,
                        autoUpdateInput: false,
                        showCustomRangeLabel: false,
                        starDate: start.endDate.format("DD/MM/YYYY"),
                        minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                        /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                        maxDate: '31/12/' + anio,
                        locale: {
                            format: "DD/MM/YYYY"
                        }
                    }, function (start, end, label) {
                    }).on('apply.daterangepicker', function (ev, start) {
                        $("#fecha31_edi").val(start.endDate.format("DD/MM/YYYY"));
                        var fechaConcar1 = $("#fecha31_edi").val();
                        var array_fecha1 = fechaConcar1.split("/");
                        var day_1 = parseInt(array_fecha1[0]);
                        var month_1 = parseInt(array_fecha1[1]);
                        var year_1 = parseInt(array_fecha1[2]);
                        var str_msj = "";
                        var str_can = "";
                        if (month_1 == 12) {
                            var lastDate = new Date(year_1, month_1 + 1, 0);
                            var lastDay = lastDate.getDate();
                            str_can = lastDay - day_1;
                            str_msj = "day";
                        } else {
                            str_can = 12;
                            str_msj = "month";
                        }
                        $("#fecha32_edi").daterangepicker({
                            autoApply: true,
                            singleDatePicker: true,
                            showDropdowns: true,
                            linkedCalendar: false,
                            autoUpdateInput: false,
                            showCustomRangeLabel: false,
                            starDate: start.endDate.format("DD/MM/YYYY"),
                            minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                            /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                            maxDate: '31/12/' + anio,
                            locale: {
                                format: "DD/MM/YYYY"
                            }
                        }, function (start, end, label) {
                        }).on('apply.daterangepicker', function (ev, start) {
                            $("#fecha32_edi").val(start.endDate.format("DD/MM/YYYY"));
                            var fechaConcar1 = $("#fecha32_edi").val();
                            var array_fecha1 = fechaConcar1.split("/");
                            var day_1 = parseInt(array_fecha1[0]);
                            var month_1 = parseInt(array_fecha1[1]);
                            var year_1 = parseInt(array_fecha1[2]);
                            var str_msj = "";
                            var str_can = "";
                            if (month_1 == 12) {
                                var lastDate = new Date(year_1, month_1 + 1, 0);
                                var lastDay = lastDate.getDate();
                                str_can = lastDay - day_1;
                                str_msj = "day";
                            } else {
                                str_can = 12;
                                str_msj = "month";
                            }
                            $("#fecha41_edi").daterangepicker({
                                autoApply: true,
                                singleDatePicker: true,
                                showDropdowns: true,
                                linkedCalendar: false,
                                autoUpdateInput: false,
                                showCustomRangeLabel: false,
                                starDate: start.endDate.format("DD/MM/YYYY"),
                                minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                                /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                                maxDate: '31/12/' + anio,
                                locale: {
                                    format: "DD/MM/YYYY"
                                }
                            }, function (start, end, label) {
                            }).on('apply.daterangepicker', function (ev, start) {
                                $("#fecha41_edi").val(start.endDate.format("DD/MM/YYYY"));
                                var fechaConcar1 = $("#fecha41_edi").val();
                                var array_fecha1 = fechaConcar1.split("/");
                                var day_1 = parseInt(array_fecha1[0]);
                                var month_1 = parseInt(array_fecha1[1]);
                                var year_1 = parseInt(array_fecha1[2]);
                                var str_msj = "";
                                var str_can = "";
                                if (month_1 == 12) {
                                    var lastDate = new Date(year_1, month_1 + 1, 0);
                                    var lastDay = lastDate.getDate();
                                    str_can = lastDay - day_1;
                                    str_msj = "day";
                                } else {
                                    str_can = 12;
                                    str_msj = "month";
                                }
                                $("#fecha42_edi").daterangepicker({
                                    autoApply: true,
                                    singleDatePicker: true,
                                    showDropdowns: true,
                                    linkedCalendar: false,
                                    autoUpdateInput: false,
                                    showCustomRangeLabel: false,
                                    starDate: start.endDate.format("DD/MM/YYYY"),
                                    minDate: moment(start.endDate.format("MM/DD/YYYY")).add(1, "day"),
                                    /*maxDate: moment(start.endDate.format("MM/DD/YYYY")).add(str_can, str_msj),*/
                                    maxDate: '31/12/' + anio,
                                    locale: {
                                        format: "DD/MM/YYYY"
                                    }
                                }, function (start, end, label) {
                                }).on('apply.daterangepicker', function (ev, start) {
                                    $("#fecha42_edi").val(start.endDate.format("DD/MM/YYYY"));
                                });
                            });
                        });
                    });
                });
            });
        });
    });
}

function editar_bimestres() {
    $("#btnEditarBimestres").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var hdnCodiBi = $("#hdnCodiBi").val();
    var codi_edi = $("#codi_edi").val();
    var anio = $("#cbbAnios_edi").select().val();
    var fecha11 = $("#fecha11_edi").val();
    var fecha12 = $("#fecha12_edi").val();
    var fecha21 = $("#fecha21_edi").val();
    var fecha22 = $("#fecha22_edi").val();
    var fecha31 = $("#fecha31_edi").val();
    var fecha32 = $("#fecha32_edi").val();
    var fecha41 = $("#fecha41_edi").val();
    var fecha42 = $("#fecha42_edi").val();
    var mensaje = "";
    if ($.trim(fecha11) === "") {
        mensaje += "*Ingrese la fecha de inicio del Primer Bimestre<br>";
        $("#fecha11").addClass("is-invalid");
    }
    if ($.trim(fecha12) === "") {
        mensaje += "*Ingrese la fecha fin del Primer Bimestre<br>";
        $("#fecha12").addClass("is-invalid");
    }
    if ($.trim(fecha21) === "") {
        mensaje += "*Ingrese la fecha de inicio del Segundo Bimestre<br>";
        $("#fecha21").addClass("is-invalid");
    }
    if ($.trim(fecha22) === "") {
        mensaje += "*Ingrese la fecha fin del Segundo Bimestre<br>";
        $("#fecha22").addClass("is-invalid");
    }
    if ($.trim(fecha31) === "") {
        mensaje += "*Ingrese la fecha de inicio del Tercer Bimestre<br>";
        $("#fecha31").addClass("is-invalid");
    }
    if ($.trim(fecha32) === "") {
        mensaje += "*Ingrese la fecha fin del Tercer Bimestre<br>";
        $("#fecha32").addClass("is-invalid");
    }
    if ($.trim(fecha41) === "") {
        mensaje += "*Ingrese la fecha de inicio del Cuarto Bimestre<br>";
        $("#fecha41").addClass("is-invalid");
    }
    if ($.trim(fecha42) === "") {
        mensaje += "*Ingrese la fecha fin del Cuarto Bimestre<br>";
        $("#fecha42").addClass("is-invalid");
    }

    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarBimestres").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_bimestres",
                sm_codigo: $.trim(hdnCodiBi),
                s_codi_edi: $.trim(codi_edi),
                s_anio: anio,
                s_fecha11: $.trim(fecha11),
                s_fecha12: $.trim(fecha12),
                s_fecha21: $.trim(fecha21),
                s_fecha22: $.trim(fecha22),
                s_fecha31: $.trim(fecha31),
                s_fecha32: $.trim(fecha32),
                s_fecha41: $.trim(fecha41),
                s_fecha42: $.trim(fecha42)
            },
            beforeSend: function (objeto) {
                $("#modal-editar-bimestres").find('.modal-footer div label').html("");
                $("#modal-editar-bimestres").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-bimestres").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-bimestres').modal('hide');
                        $("#btnEditarBimestres").attr("disabled", false);
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
                        $("#btnEditarBimestres").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_registra_nuevo_semaforo(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevo_semaforo"
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            var txt = document.getElementById('valor12');
            var txt2 = document.getElementById('valor22');
            txt.addEventListener('blur', limitDecimal);
            txt2.addEventListener('blur', limitDecimal);
            $('#valor12').keypress(function (event) {
                if (event.which == 8 || event.which == 0) {
                    return true;
                }
                if (event.which < 46 || event.which > 59) {
                    return false;
                    //event.preventDefault();
                } // prevent if not number/dot

                if (event.which == 46 && $(this).val().indexOf('.') != -1) {
                    return false;
                    //event.preventDefault();
                } // prevent if already dot
            });
            $('#valor22').keypress(function (event) {
                if (event.which == 8 || event.which == 0) {
                    return true;
                }
                if (event.which < 46 || event.which > 59) {
                    return false;
                    //event.preventDefault();
                } // prevent if not number/dot

                if (event.which == 46 && $(this).val().indexOf('.') != -1) {
                    return false;
                    //event.preventDefault();
                } // prevent if already dot
            });
        }
    });
}

function minmax(value, min, max) {
    if (parseFloat(value) < min || isNaN(value))
        return 0;
    else if (parseFloat(value) > max)
        return value.substring(0, 2);
    else
        return value;
}

function limitDecimal(e) {
    var val = this.value;
    var ide = this.id;
    var numberId = ide.replace('valor', '');
    var number = val.split(".");
    if (number.length > 1) {
        var entera = number[0];
        var decimal = number[1];
        if (decimal.length === 0) {
            this.value = entera + ".00";
        } else if (decimal.length === 1) {
            this.value = entera + "." + decimal + "0";
        } else {

        }
    } else {
        this.value = this.value + ".00";
    }
    var dato = parseInt(numberId) + parseInt(9);
    var newid = $("#valor" + dato);
    var newValue = parseFloat(this.value) + parseFloat('0.01');
    newid.val(newValue.toFixed(2));
}


function registrar_semaforo() {
    $("#btnRegistrarSemaforo").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var hdnCodiBi = $("#hdnCodiSema").val();
    var bimestres = $("#cbbBimestres").select().val();
    var valor11 = $("#valor11").val();
    var valor12 = $("#valor12").val();
    var valor21 = $("#valor21").val();
    var valor22 = $("#valor22").val();
    var valor31 = $("#valor31").val();
    var valor32 = $("#valor32").val();
    var mensaje = "";
    if ($.trim(valor12) === "") {
        mensaje += "*Ingrese el valor Hasta del Semaforo color Rojo<br>";
        $("#valor12").addClass("is-invalid");
    }
    if ($.trim(valor22) === "") {
        mensaje += "*Ingrese el valor Hasta del Semaforo color Ambar<br>";
        $("#valor22").addClass("is-invalid");
    }
    if (valor12 > valor22) {
        mensaje += "*El valor del semaforo Rojo no puede ser mayor al valor del color Ambar<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarSemaforo").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_registrar_semaforo",
                sm_codigo: $.trim(hdnCodiBi),
                s_bimestre: bimestres,
                s_valor11: $.trim(valor11),
                s_valor12: $.trim(valor12),
                s_valor21: $.trim(valor21),
                s_valor22: $.trim(valor22),
                s_valor31: $.trim(valor31),
                s_valor32: $.trim(valor32)
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-semaforo").find('.modal-footer div label').html("");
                $("#modal-nuevo-semaforo").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevo-semaforo").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-semaforo').modal('hide');
                        $("#btnRegistrarSemaforo").attr("disabled", false);
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
                        $("#btnRegistrarSemaforo").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_semaforo(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_semaforo",
            u_anio_codigo: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            var txt = document.getElementById('valor12_edi');
            var txt2 = document.getElementById('valor22_edi');
            txt.addEventListener('blur', limitDecimal_edi);
            txt2.addEventListener('blur', limitDecimal_edi);
            $('#valor12_edi').keypress(function (event) {
                if (event.which == 8 || event.which == 0) {
                    return true;
                }
                if (event.which < 46 || event.which > 59) {
                    return false;
                    //event.preventDefault();
                } // prevent if not number/dot

                if (event.which == 46 && $(this).val().indexOf('.') != -1) {
                    return false;
                    //event.preventDefault();
                } // prevent if already dot
            });
            $('#valor22_edi').keypress(function (event) {
                if (event.which == 8 || event.which == 0) {
                    return true;
                }
                if (event.which < 46 || event.which > 59) {
                    return false;
                    //event.preventDefault();
                } // prevent if not number/dot

                if (event.which == 46 && $(this).val().indexOf('.') != -1) {
                    return false;
                    //event.preventDefault();
                } // prevent if already dot
            });

        }
    });
}

function limitDecimal_edi(e) {
    var val = this.value;
    var ide = this.id;
    var numberId = ide.replace('valor', '');
    var number = val.split(".");
    if (number.length > 1) {
        var entera = number[0];
        var decimal = number[1];
        if (decimal.length === 0) {
            this.value = entera + ".00";
        } else if (decimal.length === 1) {
            this.value = entera + "." + decimal + "0";
        } else {

        }
    } else {
        this.value = this.value + ".00";
    }
    var dato = parseInt(numberId) + parseInt(9);
    var newid = $("#valor" + dato + "_edi");
    var newValue = parseFloat(this.value) + parseFloat('0.01');
    newid.val(newValue.toFixed(2));
}

function editar_semaforo() {
    $("#btnEditarSemaforo").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var hdnCodiSema = $("#hdnCodiSema").val();
    var codi_edi = $("#codi_edi").val();
    var bimestre = $("#cbbBimestre_edi").select().val();
    var valor11 = $("#valor11_edi").val();
    var valor12 = $("#valor12_edi").val();
    var valor21 = $("#valor21_edi").val();
    var valor22 = $("#valor22_edi").val();
    var valor31 = $("#valor31_edi").val();
    var valor32 = $("#valor32_edi").val();
    var mensaje = "";
    if ($.trim(valor12) === "") {
        mensaje += "*Ingrese el valor Hasta del Semaforo color Rojo<br>";
        $("#valor12_edi").addClass("is-invalid");
    }
    if ($.trim(valor22) === "") {
        mensaje += "*Ingrese el valor Hasta del Semaforo color Ambar<br>";
        $("#valor22_edi").addClass("is-invalid");
    }
    if (valor12 > valor22) {
        mensaje += "*El valor del semaforo Rojo no puede ser mayor al valor del color Ambar<br>";
    }

    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarSemaforo").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_semaforo",
                sm_codigo: $.trim(hdnCodiSema),
                s_codi_edi: $.trim(codi_edi),
                s_bimestre: bimestre,
                s_valor11: $.trim(valor11),
                s_valor12: $.trim(valor12),
                s_valor21: $.trim(valor21),
                s_valor22: $.trim(valor22),
                s_valor31: $.trim(valor31),
                s_valor32: $.trim(valor32)
            },
            beforeSend: function (objeto) {
                $("#modal-editar-semaforo").find('.modal-footer div label').html("");
                $("#modal-editar-semaforo").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-semaforo").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-semaforo').modal('hide');
                        $("#btnEditarSemaforo").attr("disabled", false);
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
                        $("#btnEditarSemaforo").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function descargar_historial_pdf() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var hdnCodi = $("#hdnCodiSR").val();
    var matricula = $("#matricH").val();
    var selectedHistorial = [];
    var selectedCampos = [];
    $('#tablaHistorial tr td input:checked').each(function () {
        selectedHistorial.push($(this).attr('value'));
    });
    $('#listFieldsetCampos label input:checked').each(function () {
        selectedCampos.push($(this).attr('value'));
    });
    if (selectedHistorial.length <= 0) {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Debe seleccionar por lo menos 1 registro del historial del alumno.',
            showConfirmButton: false
        });
        $("#btnEnviarSolicitudAlumno").attr("disabled", false);
    } else if (selectedCampos.length <= 0) {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Debe seleccionar por lo menos 1 registro de la lista de Campos.',
            showConfirmButton: false
        });
    } else {
        window.location.href = "php/aco_php/psi_generar_historial_pdf.php?codi=" + hdnCodi + "&matricula=" + matricula + "&historial=" + selectedHistorial + "&campos=" + selectedCampos;
    }
}

function descargar_historial_excel() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var hdnCodi = $("#hdnCodiSR").val();
    var matricula = $("#matricH").val();
    var selectedHistorial = [];
    var selectedCampos = [];
    $('#tablaHistorial tr td input:checked').each(function () {
        selectedHistorial.push($(this).attr('value'));
    });
    $('#listFieldsetCampos label input:checked').each(function () {
        selectedCampos.push($(this).attr('value'));
    });
    if (selectedHistorial.length <= 0) {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Debe seleccionar por lo menos 1 registro del historial del alumno.',
            showConfirmButton: false
        });
        $("#btnEnviarSolicitudAlumno").attr("disabled", false);
    } else if (selectedCampos.length <= 0) {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Debe seleccionar por lo menos 1 registro de la lista de Campos.',
            showConfirmButton: false
        });
    } else {
        window.location.href = "php/aco_php/psi_generar_historial_excel.php?codi=" + hdnCodi + "&matricula=" + matricula + "&historial=" + selectedHistorial + "&campos=" + selectedCampos;
    }
}

function cargar_selector_grado(nivel) {
    var str_nivel = nivel.value;
    $("#cbbGrado").html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_selector_grado",
            s_nivel: str_nivel
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#cbbGrado").html(datos);
            $("#cbbSeccion").html('<option value="0">-- Todos --</option>');//marita
        }
    });
}

function cargar_selector_seccion(grado) {
    var str_grado = grado.value;
    var str_nivel = $("#cbbNivel").select().val();
    $("#cbbSeccion").html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_selector_seccion",
            s_nivel: str_nivel,
            s_grado: str_grado
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#cbbSeccion").html(datos);
        }
    });
}

function limpiar_campos_semaforo() {
    $('#cbbSemaforo option[value=0]').removeAttr('selected');
    $('#cbbBimestre option[value=0]').removeAttr('selected');
    $('#cbbNivel option[value=0]').removeAttr('selected');
    $('#cbbGrado option[value=0]').removeAttr('selected');
    $('#cbbSeccion option[value=0]').removeAttr('selected');
    $("#dataDocente").html("Docente:");
    $("#docen").val("");
    $('#cbbSemaforo option[value=0]').attr('selected', 'selected');
    $('#cbbBimestre option[value=0]').attr('selected', 'selected');
    $('#cbbNivel option[value=0]').attr('selected', 'selected');
    $('#cbbGrado option[value=0]').attr('selected', 'selected');
    $('#cbbSeccion option[value=0]').attr('selected', 'selected');
    buscar_semaforo_docente();
}

function limpiar_campos_no_entrevistados() {
    $('#cbbBimestre option[value=0]').removeAttr('selected');
    $('#cbbNivel option[value=0]').removeAttr('selected');
    $('#cbbGrado option[value=0]').removeAttr('selected');
    $('#cbbSeccion option[value=0]').removeAttr('selected');
    $("#dataDocente").html("Docente:");
    $("#docen").val("");
    $("#searchDocente").val("");
    $('#cbbBimestre option[value=0]').attr('selected', 'selected');
    $('#cbbNivel option[value=0]').attr('selected', 'selected');
    $('#cbbGrado option[value=0]').attr('selected', 'selected');
    $('#cbbSeccion option[value=0]').attr('selected', 'selected');
    buscar_alumnos_no_entrevistados();
}

function alumnos_no_entrevistas_grafico_barras_niveles(nivel) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    if ($.trim(str_nivel) == "") {
        $("#bar-chart2").html("");
        $("#div_Grados").html("");
        $("#div_Secciones").html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_alumnos_no_entrevistas_grafico_barras_niveles",
                s_sede: str_sede,
                s_nivel: str_nivel
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#bar-chart2").html("");
                if (datos.length !== 0) {
                    window.barChart = Morris.Bar({
                        element: 'bar-chart2',
                        data: datos,
                        xkey: 'y',
                        ykeys: ['a', 'b', 'c'],
                        labels: ['Total', 'No entrevistados', 'Entrevistados'],
                        lineColors: ['#34495E', '#26B99A', '#3dbeee'],
                        lineWidth: '3px',
                        resize: true,
                        redraw: true
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    //$("#div_Niveles").html("");
                    $("#div_Grados").html("");
                    $("#div_Secciones").html("");
                    cargar_formulario_nivel_grado_no_entrevistados("#div_Grados");
                } else {
                    $("#bar-chart2").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                    $("#div_Niveles").html("");
                    $("#div_Grados").html("");
                    $("#div_Secciones").html("");
                }
            }
        });
    }
}

function cargar_formulario_nivel_grado_no_entrevistados(div) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_nivel_grado_no_entrevistados",
            s_sede: str_sede,
            s_nivel: str_nivel
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function alumnos_no_entrevistados_grafico_barras_grados(grado) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    var str_grado = $("#cbbGrado").select().val();
    if ($.trim(str_grado) == "") {
        $("#bar-chart3").html("");
        $("#div_Secciones").html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_alumnos_no_entrevistas_grafico_barras_grados",
                s_sede: str_sede,
                s_nivel: str_nivel,
                s_grado: str_grado
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#bar-chart3").html("");
                if (datos.length !== 0) {
                    window.barChart = Morris.Bar({
                        element: 'bar-chart3',
                        data: datos,
                        xkey: 'y',
                        ykeys: ['a', 'b', 'c'],
                        labels: ['Total', 'No entrevistados', 'Entrevistados'],
                        lineColors: ['#34495E', '#26B99A', '#3dbeee'],
                        lineWidth: '3px',
                        resize: true,
                        redraw: true
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    //$("#div_Niveles").html("");
                    //$("#div_Grados").html("");
                    $("#div_Secciones").html("");
                    cargar_formulario_grado_seccion_no_entrevistados("#div_Secciones");
                } else {
                    $("#bar-chart3").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
                    $("#div_Niveles").html("");
                    $("#div_Grados").html("");
                    $("#div_Secciones").html("");
                }
            }
        });
    }
}

function cargar_formulario_grado_seccion_no_entrevistados(div) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    var str_grado = $("#cbbGrado").select().val();
    $(div).html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_grado_seccion_no_entrevistados",
            s_sede: str_sede,
            s_nivel: str_nivel,
            s_grado: str_grado
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(div).html(datos);
        }
    });
}

function alumnos_no_entrevistados_grafico_barras_secciones(seccion) {
    var str_sede = $("#cbbSedes").select().val();
    var str_nivel = $("#cbbNivel").select().val();
    var str_grado = $("#cbbGrado").select().val();
    var str_seccion = $("#cbbSeccion").select().val();
    if ($.trim(str_seccion) == "") {
        $("#bar-chart4").html("");
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "json",
            type: "POST",
            data: {
                opcion: "formulario_alumnos_no_entrevistas_grafico_barras_secciones",
                s_sede: str_sede,
                s_nivel: str_nivel,
                s_grado: str_grado,
                s_seccion: str_seccion
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#bar-chart4").html("");
                if (datos.length !== 0) {
                    window.barChart = Morris.Bar({
                        element: 'bar-chart4',
                        data: datos,
                        xkey: 'y',
                        ykeys: ['a', 'b', 'c'],
                        labels: ['Total', 'No entrevistados', 'Entrevistados'],
                        lineColors: ['#34495E', '#26B99A', '#3dbeee'],
                        lineWidth: '3px',
                        resize: true,
                        redraw: true
                    }
                    );
                    $(window).resize(function () {
                        window.barChart.redraw();
                    });
                    //$("#div_Niveles").html("");
                    //$("#div_Grados").html("");
                    //$("#div_Secciones").html("");
                } else {
                    $("#bar-chart4").html('<div class="col-md-12"><span><i class="nav-icon fa fa-info-circle" style="color: red"></i> Respuesta: No se encontraron registros.</span>&nbsp;&nbsp;</div>');
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

function solo_letras_v(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8 || tecla == 0) {
        return true;
    }
    patron = /^[a-zA-Z_.\x09\xD1\xF1\s]+$/;
    te = String.fromCharCode(tecla);
    return patron.test(te);
}

function sumarFechas(hora1, hora, minuto, segundo) {
    var arreglo = hora1.split(":");
    var s_hora = parseFloat($.trim(arreglo[0]));
    var s_minuto = parseFloat($.trim(arreglo[1]));
    var s_segundo = parseFloat($.trim(arreglo[2]));
    var s_hora2 = parseFloat($.trim(hora));
    var s_minuto2 = parseFloat($.trim(minuto));
    var s_segundo2 = parseFloat($.trim(segundo));
    var result_hora1 = 0;
    var result_hora2 = 0;
    result_hora1 = s_hora * 3600 + s_minuto * 60 + s_segundo;
    result_hora2 = s_hora2 * 3600 + s_minuto2 * 60 + s_segundo2;
    var hours = Math.floor((result_hora1 + result_hora2) / 3600);
    var minutes = Math.floor(((result_hora1 + result_hora2) % 3600) / 60);
    var seconds = (result_hora1 + result_hora2) % 60;
    return hours.toString().padStart(2, '0') + ":" + minutes.toString().padStart(2, '0') + ":" + seconds.toString().padStart(2, '0');
}

function restarFechas(hora1, hora, minuto, segundo) {
    var arreglo = hora1.split(":");
    var s_hora = parseFloat($.trim(arreglo[0]));
    var s_minuto = parseFloat($.trim(arreglo[1]));
    var s_segundo = parseFloat($.trim(arreglo[2]));
    var s_hora2 = parseFloat($.trim(hora));
    var s_minuto2 = parseFloat($.trim(minuto));
    var s_segundo2 = parseFloat($.trim(segundo));
    var result_hora1 = 0;
    var result_hora2 = 0;
    result_hora1 = s_hora * 3600 + s_minuto * 60 + s_segundo;
    result_hora2 = s_hora2 * 3600 + s_minuto2 * 60 + s_segundo2;
    var hours = Math.floor((result_hora1 - result_hora2) / 3600);
    var minutes = Math.floor(((result_hora1 - result_hora2) % 3600) / 60);
    var seconds = (result_hora1 - result_hora2) % 60;
    return hours.toString().padStart(2, '0') + ":" + minutes.toString().padStart(2, '0') + ":" + seconds.toString().padStart(2, '0');
}

function minutos_a_hora(minutos) {
    var data = minutos * 60;
    var hora = Math.floor(data / 3600);
    var minuto = Math.floor((data % 3600) / 60);
    var segundo = data % 60;
    return  hora + ":" + minuto + ":" + segundo;
}

function reiniciar_cronometro() {
    timeLimit = 40; //tiempo en minutos
    document.getElementById('hora').innerHTML = '';
    document.getElementById('minuto').innerHTML = '';
    document.getElementById('segundo').innerHTML = '';
    clearInterval(intervaloRegresivo);
    $("#fecha").hide();
}

function reiniciar_cronometro_2() {
    timeLimitSub = 40; //tiempo en minutos
    document.getElementById('hora_s').innerHTML = '';
    document.getElementById('minuto_s').innerHTML = '';
    document.getElementById('segundo_s').innerHTML = '';
    clearInterval(intervaloRegresivo_s);
    $("#fecha_s").hide();
}

function isSigWeb_1_6_4_0_Installed(sigWebVer) {
    var minSigWebVersionResetSupport = "1.6.4.0";

    if (isOlderSigWebVersionInstalled(minSigWebVersionResetSupport, sigWebVer)) {
        console.log("SigWeb version 1.6.4.0 or higher not installed.");
        return false;
    }
    return true;
}

function isSigWeb_1_7_0_0_Installed(sigWebVer) {
    var minSigWebVersionGetDaysUntilCertificateExpiresSupport = "1.7.0.0";

    if (isOlderSigWebVersionInstalled(minSigWebVersionGetDaysUntilCertificateExpiresSupport, sigWebVer)) {
        console.log("SigWeb version 1.7.0.0 or higher not installed.");
        return false;
    }
    return true;
}

function isOlderSigWebVersionInstalled(cmprVer, sigWebVer) {
    return isOlderVersion(cmprVer, sigWebVer);
}

function isOlderVersion(oldVer, newVer) {
    const oldParts = oldVer.split('.')
    const newParts = newVer.split('.')
    for (var i = 0; i < newParts.length; i++) {
        const a = parseInt(newParts[i]) || 0
        const b = parseInt(oldParts[i]) || 0
        if (a < b)
            return true
        if (a > b)
            return false
    }
    return false;
}

function iniciar_firma_1() {
    if (IsSigWebInstalled()) {
        var ctx = document.getElementById('canvas1').getContext('2d');
        SetDisplayXSize(500);
        SetDisplayYSize(100);
        SetTabletState(0, tmr);
        SetJustifyMode(0);
        ClearTablet();
        if (tmr == null) {
            tmr = SetTabletState(1, ctx, 50);
        } else {
            SetTabletState(0, tmr);
            tmr = null;
            tmr = SetTabletState(1, ctx, 50);
        }
    } else {
        alert("No se puede comunicar con SigWeb. Confirme que SigWeb está instalado y ejecutándose en esta PC.");
    }
}

function iniciar_firma_2() {
    if (IsSigWebInstalled()) {
        var canva = document.getElementById('canvas2');
        var ctx = canva.getContext('2d');
        canva.style.border = '1px solid black';
        SetDisplayXSize(500);
        SetDisplayYSize(100);
        SetTabletState(0, tmr2);
        SetJustifyMode(0);
        ClearTablet();
        if (tmr2 == null)
        {
            tmr2 = SetTabletState(1, ctx, 50);
        } else
        {
            SetTabletState(0, tmr);
            tmr2 = null;
            tmr2 = SetTabletState(1, ctx, 50);
        }
    } else {
        alert("No se puede comunicar con SigWeb. Confirme que SigWeb está instalado y ejecutándose en esta PC.");
    }
}

function iniciar_firma_sub_1() {
    if (IsSigWebInstalled()) {
        var ctx = document.getElementById('canvas1_sub').getContext('2d');
        SetDisplayXSize(500);
        SetDisplayYSize(100);
        SetTabletState(0, tmr_sub);
        SetJustifyMode(0);
        ClearTablet();
        if (tmr_sub == null) {
            tmr_sub = SetTabletState(1, ctx, 50);
        } else {
            SetTabletState(0, tmr_sub);
            tmr_sub = null;
            tmr_sub = SetTabletState(1, ctx, 50);
        }
    } else {
        alert("No se puede comunicar con SigWeb. Confirme que SigWeb está instalado y ejecutándose en esta PC.");
    }
}

function iniciar_firma_sub_2() {
    if (IsSigWebInstalled()) {
        var canva = document.getElementById('canvas2_sub');
        var ctx = canva.getContext('2d');
        canva.style.border = '1px solid black';
        SetDisplayXSize(500);
        SetDisplayYSize(100);
        SetTabletState(0, tmr_sub2);
        SetJustifyMode(0);
        ClearTablet();
        if (tmr_sub2 == null)
        {
            tmr_sub2 = SetTabletState(1, ctx, 50);
        } else
        {
            SetTabletState(0, tmr);
            tmr_sub2 = null;
            tmr_sub2 = SetTabletState(1, ctx, 50);
        }
    } else {
        alert("No se puede comunicar con SigWeb. Confirme que SigWeb está instalado y ejecutándose en esta PC.");
    }
}

function iniciar_firma_edi() {
    if (IsSigWebInstalled()) {
        var divFirma1 = $("#divFirma1").find("div").length;
        var divFirma1_1 = $("#divFirma1").find("img").length;
        if (divFirma1 > 0 || divFirma1_1 > 0) {
            limpiar_firma_edi();
            $("#divFirma1").find("div").hide();
            $("#btnLimpiarFirma1").prop("disabled", false);
        }
        var canva = document.getElementById('canvas1_edi');
        var ctx = canva.getContext('2d');
        canva.style.border = '1px solid black';
        SetDisplayXSize(500);
        SetDisplayYSize(100);
        SetTabletState(0, tmr_edi);
        SetJustifyMode(0);
        ClearTablet();
        if (tmr_edi == null) {
            tmr_edi = SetTabletState(1, ctx, 50);
        } else {
            SetTabletState(0, tmr_edi);
            tmr_edi = null;
            tmr_edi = SetTabletState(1, ctx, 50);
        }
    } else {
        alert("No se puede comunicar con SigWeb. Confirme que SigWeb está instalado y ejecutándose en esta PC.");
    }
}

function iniciar_firma_edi2() {
    if (IsSigWebInstalled()) {
        var divFirma2 = $("#divFirma2").find("div").length;
        var divFirma2_1 = $("#divFirma2").find("img").length;
        if (divFirma2 > 0 || divFirma2_1 > 0) {
            limpiar_firma_entrevistador_edi();
            $("#divFirma2").find("div").hide();
            $("#btnLimpiarFirma2").prop("disabled", false);
        }
        var canva = document.getElementById('canvas2_edi');
        var ctx = canva.getContext('2d');
        canva.style.border = '1px solid black';
        SetDisplayXSize(500);
        SetDisplayYSize(100);
        SetTabletState(0, tmr_edi2);
        SetJustifyMode(0);
        ClearTablet();
        if (tmr_edi2 == null) {
            tmr_edi2 = SetTabletState(1, ctx, 50);
        } else {
            SetTabletState(0, tmr_edi2);
            tmr_edi2 = null;
            tmr_edi2 = SetTabletState(1, ctx, 50);
        }
    } else {
        alert("No se puede comunicar con SigWeb. Confirme que SigWeb está instalado y ejecutándose en esta PC.");
    }
}

function limpiar_firma() {
    ClearTablet();
}

function limpiar_firma_entrevistador() {
    ClearTablet();
}

function limpiar_firma_sub() {
    ClearTablet();
}

function limpiar_firma_entrevistador_sub() {
    ClearTablet();
}

function onDone() {
    if (NumberOfTabletPoints() == 0) {
        alert("Please sign before continuing");
    } else {
        SetTabletState(0, tmr);
        //RETURN TOPAZ-FORMAT SIGSTRING
        SetSigCompressionMode(1);
        document.FORM1.bioSigData.value = GetSigString();
        document.FORM1.sigStringData.value = GetSigString();
        //this returns the signature in Topaz's own format, with biometric information

        //RETURN BMP BYTE ARRAY CONVERTED TO BASE64 STRING
        SetImageXSize(500);
        SetImageYSize(100);
        SetImagePenWidth(5);
        GetSigImageB64(SigImageCallback);
    }
}

function buscar_historial_auditoria() {
    var sede = $("#cbbSedes").select().val();
    var fecha_inicio = $("#fecha1").val();
    var fecha_fin = $("#fecha2").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_historial_auditoria",
            s_sede: sede,
            s_fecha_inicio: fecha_inicio,
            s_fecha_fin: fecha_fin
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableAuditorias").DataTable().destroy();
            $("#tableAuditorias tbody").html(datos);
            $("#tableAuditorias").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "buttons": ["copy",
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Lista de auditoria del sistema'//marita
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Lista de auditoria del sistema'//marita
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Lista de auditoria del sistema'//marita
                    }, "colvis"]
                        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        //"buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableAuditorias_wrapper .col-md-6:eq(0)');
        }
    });
}


function mostrar_detalle_solicitud_alumno(modal, solicitud) {
    var arreglo = solicitud.split("*");
    var codigo = arreglo[1];
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_carga_solicitudes_detalla",
            sol_cod: codigo
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $(modal).find(".modal-body").html(datos);
        }
    });
}

function mostrar_tabla_mis_aulas(usuario) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "mostrar_tabla_mis_aulas",
            codigo: usuario
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#contenido_alertas").html(datos);
        }
    });
}

function cargar_opcion_cambiar_contrasena(usuario) {
    $.ajax({
        url: "./php/aco_view/cc_cambiar_contrasena_usuario.php",
        dataType: "html",
        type: "POST",
        data: {
            codigo_menu: 100,
            nombre_opcion: 'Cambiar contraseña',
            s_usuario: usuario
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


function enter_cambiar_contra(evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode == 13) {
        cambiar_contrasena_usuario();
    }
}


function cambiar_contrasena_usuario() {
    $("#btnCambiarContrasenaUsuario").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiCC");
    var usuario = $("#hdnUsuario");
    var contraNueva = $("#txtContraNueva");
    var contraConfirmar = $("#txtContraConfirmar");
    var mensaje = "";
    var cant = 0;
    var txt = "";

    $("#mensajeContrasena").html("");
    $("#mensajeContrasena").hide();

    if ($.trim(contraNueva.val()) == "") {
        mensaje += "Ingrese la nueva contraseña<br>";
        cant++;
        txt = contraNueva;
    }
    if ($.trim(contraConfirmar.val()) == "") {
        mensaje += "Ingrese confirmar la contraseña<br>";
        cant++;
        txt = contraConfirmar;
    }

    if ($.trim(contraNueva.val()) !== "" && $.trim(contraConfirmar.val()) !== "") {
        if ($.trim(contraNueva.val().length) < 7 && $.trim(contraConfirmar.val().length) < 7) {
            mensaje += "Las contraseñas deben tener un mínimo 7 caracteres<br>";
            cant++;
            txt = contraNueva;
        } else {
            if ($.trim(contraNueva.val()) !== $.trim(contraConfirmar.val())) {
                mensaje += "Las contraseñas no coinciden<br>";
                cant++;
                txt = contraNueva;
            }
        }
    }
    if (mensaje != "") {
        //mostrarMensajeLogin();
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        if (cant == 1) {
            txt.focus();
        }
        ocultar_mensaje("#mensaje_contrasena");
    } else {
        $.ajax({
            url: "php/aco_php/psi_cambiar_contrasena_usuario_pas.php",
            dataType: "html",
            type: "POST",
            data: {
                sm_codigo: $.trim(codSMenu.val()),
                s_codigo: $.trim(usuario.val()),
                s_contraNueva: $.trim(contraNueva.val()),
                s_contraConfirmar: $.trim(contraConfirmar.val())
            },
            error: function (xhr, ajaxOptions, thrownError) {

            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        location.href = "php/salir.php";
                        $("#btnCambiarContrasenaUsuario").attr("disabled", false);
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
}

/*marita*/
function buscar_cantidad_entrevistas_subentrevistas() {
    var sede = $("#cbbSedes").select().val();
    var bimestre = $("#cbbBimestre").val();
    var nivel = $("#cbbNivel").val();
    var grado = $("#cbbGrado").val();
    var seccion = $("#cbbSeccion").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_cantidad_entrevistas_subentrevitas",
            s_sede: sede,
            s_bimestre: bimestre,
            s_nivel: nivel,
            s_grado: grado,
            s_seccion: seccion
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableCantidadEntrevistas").DataTable().destroy();
            $("#tableCantidadEntrevistas tbody").html(datos);
            $("#tableCantidadEntrevistas").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "buttons": ["copy",
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Lista de cantidad de entrevistas y subentrevistas'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Lista de cantidad de entrevistas y subentrevistas'
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        title: 'Lista de cantidad de entrevistas y subentrevistas'
                    }, "colvis"]
                        //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                        //"buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableCantidadEntrevistas_wrapper .col-md-6:eq(0)');
        }
    });
}

function limpiar_campos_cantidad_entrevistas_subentrevistas() {
    //$('#cbbBimestre option[value=0]').removeAttr('selected');
    $('#cbbNivel option[value=0]').removeAttr('selected');
    $('#cbbGrado option[value=0]').removeAttr('selected');
    $('#cbbSeccion option[value=0]').removeAttr('selected');
    //$('#cbbBimestre option[value=0]').attr('selected', 'selected');
    $('#cbbNivel option[value=0]').attr('selected', 'selected');
    $('#cbbGrado option[value=0]').attr('selected', 'selected');
    $('#cbbSeccion option[value=0]').attr('selected', 'selected');
    buscar_cantidad_entrevistas_subentrevistas();
}

function cargar_rango_fechas(bimestre) {
    var str_bimestre = bimestre.value;
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_rango_fechas",
            s_bimestre: str_bimestre
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var data = $.trim(datos);
            if (data !== "*") {
                var arreglo = data.split("*");
                var fecha1 = arreglo[0];
                var fecha2 = arreglo[1];
                var array_fecha = fecha1.split("/");
                var day = parseInt(array_fecha[0]);
                var month = parseInt(array_fecha[1]) - 1;
                var year = parseInt(array_fecha[2]);
                var array_fecha2 = fecha2.split("/");
                var day2 = parseInt(array_fecha2[0]);
                var month2 = parseInt(array_fecha2[1]) - 1;
                var year2 = parseInt(array_fecha2[2]);
                $("#fecha1").val(fecha1);
                $("#fecha2").val(fecha2);
                $("#fecha1").daterangepicker({
                    autoApply: true,
                    showButtonPanel: false,
                    singleDatePicker: true,
                    showDropdowns: true,
                    linkedCalendar: false,
                    autoUpdateInput: false,
                    showCustomRangeLabel: false,
                    minDate: new Date(year, month, day),
                    maxDate: new Date(year2, month2, day2),
                    locale: {
                        format: "DD/MM/YYYY"
                    }
                }).on('apply.daterangepicker', function (ev, start) {
                    $("#fecha1").val(start.endDate.format('DD/MM/YYYY'));
                });
                $("#fecha2").daterangepicker({
                    autoApply: true,
                    singleDatePicker: true,
                    showDropdowns: true,
                    linkedCalendar: false,
                    autoUpdateInput: false,
                    showCustomRangeLabel: false,
                    maxDate: new Date(year2, month2, day2),
                    minDate: new Date(year, month, day),
                    starDate: new Date(year, month, day),
                    locale: {
                        format: "DD/MM/YYYY"
                    }
                }).on('apply.daterangepicker', function (ev, start) {
                    $("#fecha2").val(start.endDate.format('DD/MM/YYYY'));
                });
            }
        }
    });
}

function buscar_reporte_semanal() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var sede = $("#cbbSedes").select().val();
    var bimestre = $("#cbbBimestre").select().val();
    var fecha_ini = $("#fecha1").val();
    var fecha_fin = $("#fecha2").val();
    var nivel = $("#cbbNivel").val();
    var grado = $("#cbbGrado").val();
    var seccion = $("#cbbSeccion").val();
    if (bimestre === "0") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: '*Seleccione el Bimestre',
            showConfirmButton: false
        });
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_reporte_semanal",
                s_sede: sede,
                s_bimestre: bimestre,
                s_fecha_ini: fecha_ini,
                s_fecha_fin: fecha_fin,
                s_nivel: nivel,
                s_grado: grado,
                s_seccion: seccion
            },
            beforeSend: function (objeto) {
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                $("#tableCantidadEntrevistas").DataTable().destroy();
                $("#tableCantidadEntrevistas tbody").html(datos);
                $("#tableCantidadEntrevistas").DataTable({
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": true,
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "buttons": ["copy",
                        {
                            extend: 'csv',
                            text: 'CSV',
                            title: 'Lista de reporte semanal de ' + fecha_ini + ' a ' + fecha_fin
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            title: 'Lista de reporte semanal de ' + fecha_ini + ' a ' + fecha_fin
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'Lista de reporte semanal de ' + fecha_ini + ' a ' + fecha_fin
                        }, "colvis"]
                            //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                            //"buttons": ["new", "colvis"]
                }).buttons().container().appendTo('#tableCantidadEntrevistas_wrapper .col-md-6:eq(0)');
            }
        });
    }
}

function limpiar_reporte_semanal() {
    //$('#cbbBimestre option[value=0]').removeAttr('selected');
    $('#cbbNivel option[value=0]').removeAttr('selected');
    $('#cbbGrado option[value=0]').removeAttr('selected');
    $('#cbbSeccion option[value=0]').removeAttr('selected');
    //$('#cbbBimestre option[value=0]').attr('selected', 'selected');
    $('#cbbNivel option[value=0]').attr('selected', 'selected');
    $('#cbbGrado option[value=0]').attr('selected', 'selected');
    $('#cbbSeccion option[value=0]').attr('selected', 'selected');
    buscar_reporte_semanal();
}

function mostrar_cargar_archivos(modal, solicitud) {
    mostrar_cargar_archivos_2(modal, solicitud);
}

function mostrar_cargar_archivos_2(modal, solicitud) {
    var hdnCodiSR = $("#hdnCodiSR").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_cargar_archivos", //aqui
            s_solicitud: solicitud
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);formulario_editar_submenu
        },
        success: function (datos) {
            modal.find('.modal-body').append('<div class="overlay" id="divRegMatri"><i class="fa fa-refresh fa-spin"></i></div>');
            //$('.select2').select2();
            modal.find('.modal-body').html(datos);
            $("#upload_form").on('submit', function (event) {
                $("#upload").attr("disabled", true);
                event.preventDefault();
                var data_form = new FormData(this);
                data_form.append("solicitud", solicitud);
                data_form.append("sm_codigo", hdnCodiSR);
                $.ajax({
                    url: "php/aco_php/loadCargarArchivos.php",
                    method: "POST",
                    data: data_form,
                    dataType: "JSON",
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function (objeto) {
                        $("#messageFile").append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
                        $("#messageFile").css("display", "block");
                        $("#messageFile").removeClass("alert alert-danger-color");
                        $("#messageFile").addClass("alert alert-info-color");
                    },
                    success: function (data) {
                        $("#messageFile").css("display", "block");
                        $("#messageFile").removeClass("alert-info-color");
                        $("#messageFile").removeClass("alert-success-color");
                        $("#messageFile").removeClass("alert-danger-color");
                        $("#messageFile").addClass(data.class_name);
                        $("#messageFile").html(data.message);
                        $("#upload").attr("disabled", false);
                        setTimeout(function () {
                            if (data.resp === '1') {
                                mostrar_cargar_archivos_2($("#modal-cargar-archivos"), solicitud);
                                $("#messageFile").removeClass("alert-success-color");
                                $("#messageFile").html("");
                                document.getElementById("select_file").value = "";
                                $("#upload").attr("disabled", false);
                            }
                        }, 4500);
                    }
                });
            });
            $("#tableListArchivos").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                //"buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tableListArchivos_wrapper .col-md-6:eq(0)');
        }
    });
}



function cargar_tabla_archivos(solicitud) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_cargar_tabla_archivos",
            s_solicitud: solicitud
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#tableListArchivos tbody").html(datos);
            if ($.fn.dataTable.isDataTable('#tableListArchivos')) {
                table = $('#tableListArchivos').DataTable();
            } else {
                table = $('#tableListArchivos').DataTable({
                    paging: false
                });
            }
        }
    });

}

function mostrar_eliminar_archivo(modal, solicitud, archivo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_archivo",
            s_solicitud: solicitud,
            s_archivo: archivo
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

function eliminar_archivo() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    $("#btnEliminarArchivo").attr("disabled", true);
    var hdnCodiSR = $("#hdnCodiSR").val();
    var hdnCodiSolicitud = $("#hdnCodiSolicitud");
    var hdnCodiArchivo = $("#hdnCodiArchivo");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_archivo",
            sm_codigo: hdnCodiSR,
            u_codiArchivo: $.trim(hdnCodiArchivo.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-archivo").find('.modal-footer div label').html("");
            $("#modal-eliminar-archivo").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                $("#modal-eliminar-archivo").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-archivo').modal('hide');
                    $("#btnEliminarArchivo").attr("disabled", false);
                    //$('.modal-backdrop').remove();
                    mostrar_cargar_archivos_2($("#modal-cargar-archivos"), hdnCodiSolicitud.val());
                }, 4500);
            } else {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $("#btnEliminarArchivo").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_activar_archivo(modal, solicitud, archivo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_activar_archivo",
            s_solicitud: solicitud,
            s_archivo: archivo
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

function activar_archivo() {
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    $("#btnActivarArchivo").attr("disabled", true);
    var hdnCodiSR = $("#hdnCodiSR").val();
    var hdnCodiSolicitud = $("#hdnCodiSolicitud");
    var hdnCodiArchivo = $("#hdnCodiArchivo");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_activar_archivo",
            sm_codigo: hdnCodiSR,
            u_codiArchivo: $.trim(hdnCodiArchivo.val())
        },
        beforeSend: function (objeto) {
            $("#modal-activar-archivo").find('.modal-footer div label').html("");
            $("#modal-activar-archivo").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                $("#modal-activar-archivo").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-activar-archivo').modal('hide');
                    $("#btnActivarArchivo").attr("disabled", false);
                    //$('.modal-backdrop').remove();
                    mostrar_cargar_archivos_2($("#modal-cargar-archivos"), hdnCodiSolicitud.val());
                }, 4500);
            } else {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $("#btnActivarArchivo").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_secciones_docente(dato) {
    var mensaje = "";
    var codSMenu = $("#hdnCodiSR");
    var usuario = dato;
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_secciones_docente",
            sol_usuario: usuario
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#divSecciones").html(datos);
            $("#tablaHistorialDocente").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                "buttons": ["new", "colvis"]
            }).buttons().container().appendTo('#tablaHistorialDocente_wrapper .col-md-6:eq(0)');
        }
    });
}

function mostrar_registra_seccion_docente(modal, docente) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_seccion_docente",
            s_docente: docente
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

function registrar_seccion_docente() {
    $("#btnRegistrarSeccionDocente").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSR");
    var docente = $("#hdnDocente");
    var cbbNivel = $("#cbbNivel");
    var cbbGrado = $("#cbbGrado");
    var cbbSeccion = $("#cbbSeccion");
    var txtSede = $("#txtSede");
    var mensaje = "";
    if ($.trim(cbbNivel.select().val()) == 0) {
        mensaje += "*Seleccione el nivel<br>";
    }
    if ($.trim(cbbGrado.select().val()) == 0) {
        mensaje += "*Seleccione el grado<br>";
    }
    if ($.trim(cbbSeccion.select().val()) == 0) {
        mensaje += "*Seleccione la sección<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarSeccionDocente").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_registro_seccion_docente",
                sm_codigo: $.trim(codSMenu.val()),
                s_docente: $.trim(docente.val()),
                s_nivel: $.trim(cbbNivel.select().val()),
                s_grado: $.trim(cbbGrado.select().val()),
                s_seccion: $.trim(cbbSeccion.select().val()),
                s_sede: $.trim(txtSede.val())
            },
            beforeSend: function (objeto) {
                $("#modal-nueva-seccion-docente").find('.modal-footer div label').html("");
                $("#modal-nueva-seccion-docente").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nueva-seccion-docente").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nueva-seccion-docente').modal('hide');
                        $('.modal-backdrop').remove();
                        $("#btnRegistrarSeccionDocente").attr("disabled", false);
                        //cargar_opcion(lista_sm[0], lista_sm[1], lista_sm[2]);
                        mostrar_secciones_docente($.trim(docente.val()));
                    }, 4500);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    $("#modal-nueva-seccion-docente").find('.modal-footer div label').html('');
                    setTimeout(function () {
                        $("#btnRegistrarSeccionDocente").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_seccion_docente(modal, docente, dictado) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_seccion_docente",
            s_dictado: dictado,
            s_docente: docente
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

function editar_seccion_docente() {
    $("#btnEditarSeccionDocente").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSR");
    var dictado = $("#hdnDictadoEdi");
    var docente = $("#hdnDocenteEdi");
    var cbbNivel = $("#cbbNivel");
    var cbbGrado = $("#cbbGrado");
    var cbbSeccion = $("#cbbSeccion");
    var cbbEstadoEdi = $("#cbbEstadoEdi");
    var txtSede = $("#txtSedeEdi");
    var mensaje = "";
    if ($.trim(cbbNivel.select().val()) == 0) {
        mensaje += "*Seleccione el nivel<br>";
    }
    if ($.trim(cbbGrado.select().val()) == 0) {
        mensaje += "*Seleccione el grado<br>";
    }
    if ($.trim(cbbSeccion.select().val()) == 0) {
        mensaje += "*Seleccione la sección<br>";
    }
    if ($.trim(cbbEstadoEdi.select().val()) == -1) {
        mensaje += "*Seleccione el estado<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarSeccionDocente").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "operacion_editar_seccion_docente",
                sm_codigo: $.trim(codSMenu.val()),
                s_docente: $.trim(docente.val()),
                s_dictado: $.trim(dictado.val()),
                s_nivel: $.trim(cbbNivel.select().val()),
                s_grado: $.trim(cbbGrado.select().val()),
                s_seccion: $.trim(cbbSeccion.select().val()),
                s_sede: $.trim(txtSede.val()),
                s_estado: $.trim(cbbEstadoEdi.select().val()),
            },
            beforeSend: function (objeto) {
                $("#modal-editar-seccion-docente").find('.modal-footer div label').html("");
                $("#modal-editar-seccion-docente").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-seccion-docente").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-seccion-docente').modal('hide');
                        $('.modal-backdrop').remove();
                        $("#btnEditarSeccionDocente").attr("disabled", false);
                        cargar_opcion(lista_sm[0], lista_sm[1], lista_sm[2]);
                    }, 4500);
                } else {
                    Toast.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    $("#modal-editar-seccion-docente").find('.modal-footer div label').html('');
                    setTimeout(function () {
                        $("#btnEditarSeccionDocente").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_eliminar_seccion_docente(modal, docente, dictado) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_seccion_docente",
            u_docente: docente,
            u_dictado: dictado
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

function eliminar_seccion_docente() {
    $("#btnEliminarSeccionDocente").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSR");
    var hdnCodiDictado = $("#hdnCodiDictado");
    var hdnCodiDocente = $("#hdnCodiDocente").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_seccion_docente",
            sm_codigo: $.trim(codSMenu.val()),
            u_hdnCodiDictado: $.trim(hdnCodiDictado.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-seccion-docente").find('.modal-footer div label').html("");
            $("#modal-eliminar-seccion-docente").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                $("#modal-eliminar-seccion-docente").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-seccion-docente').modal('hide');
                    $("#btnEliminarSeccionDocente").attr("disabled", false);
                    $('.modal-backdrop').remove();
                    mostrar_secciones_docente(hdnCodiDocente);
                }, 4500);
            } else {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $("#btnEliminarSeccionDocente").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_activar_seccion_docente(modal, docente, dictado) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_activar_seccion_docente",
            u_docente: docente,
            u_dictado: dictado
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

function activar_seccion_docente() {
    $("#btnActivarSeccionDocente").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiSR");
    var hdnCodiDictado = $("#hdnCodiDictado");
    var hdnCodiDocente = $("#hdnCodiDocente").val();
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_activar_seccion_docente",
            sm_codigo: $.trim(codSMenu.val()),
            u_hdnCodiDictado: $.trim(hdnCodiDictado.val())
        },
        beforeSend: function (objeto) {
            $("#modal-activar-seccion-docente").find('.modal-footer div label').html("");
            $("#modal-activar-seccion-docente").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                $("#modal-activar-seccion-docente").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-activar-seccion-docente').modal('hide');
                    $("#btnActivarSeccionDocente").attr("disabled", false);
                    $('.modal-backdrop').remove();
                    mostrar_secciones_docente(hdnCodiDocente);
                }, 4500);
            } else {
                Toast.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $("#btnActivarSeccionDocente").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function mostrar_registra_nuevo_gradoseccion(modal) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_registro_nuevo_gradoseccion"
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

//Función  crear elemento
function crear_elemento() {
    var secciones = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'L', 'K'];
    var tamanio = $('#contenedor').find('li').length * 1;
    $('#contenedor').append('<li><label>Sección ' + secciones[tamanio] + '</label> <a onclick="eliminar_elemento(this);">&times;</a></li>');
}

function crear_elemento_edi() {
    var secciones = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'L', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'X'];
    var letra = "";
    var index = 0;
    $('#contenedor li').each(function (index) {
        var arreglo = $(this).find('label').text().split(' ');
        letra = arreglo[1];
    });
    if (letra === "") {
        index = $('#contenedor0').find('li').length * 1;
    } else {
        index = secciones.indexOf(letra) + 1;
    }
    $('#contenedor').append('<li><label>Sección ' + secciones[index] + '</label> <a onclick="eliminar_elemento(this);">&times;</a></li>');
}

//Función eliminar elemento
function eliminar_elemento(valor) {
    valor.parentNode.parentNode.removeChild(valor.parentNode);
    var secciones = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'L', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'X'];
    var tamanio = $('#contenedor0').find('li').length * 1;
    var tamanio2 = $('#contenedor').find('li').length * 1;
    var letra = "";
    $('#contenedor li').each(function (index) {
        $(this).find('label').text('Sección ' + secciones[index + tamanio]);
    });

}

function registrar_gradoseccion() {
    $("#btnRegistrarGradoSeccion").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiGS");
    var codigo = $("#txtCodigo");
    var nombre = $("#txtNombre");
    var cbbNivel = $("#cbbNivel");
    var contenedor = $("#contenedor");
    var seccionesList = "";
    var mensaje = "";

    if ($.trim(codigo.val()) == "") {
        mensaje += "*Ingrese el código del grado<br>";
    }
    if ($.trim(nombre.val()) == "") {
        mensaje += "*Ingrese el nombre del grado<br>";
    }
    if ($.trim(cbbNivel.select().val()) == 0) {
        mensaje += "*Seleccione el nivel<br>";
    }

    if (contenedor.find("li").length === 0) {
        mensaje += "*Agregue al menos una sección<br>";
    } else {
        contenedor.find('li').each(function () {
            var value = $(this).find('label').text();
            seccionesList += value + "*";
        });
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnRegistrarGradoSeccion").attr("disabled", false);
    } else {
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_nuevo_gradoseccion",
                sm_codigo: $.trim(codSMenu.val()),
                s_codigo: $.trim(codigo.val()),
                s_nombre: $.trim(nombre.val()),
                s_nivel: $.trim(cbbNivel.val()),
                sec_lista: $.trim(seccionesList)
            },
            beforeSend: function (objeto) {
                $("#modal-nuevo-gradoseccion").find('.modal-footer div label').html("");
                $("#modal-nuevo-gradoseccion").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-nuevo-gradoseccion").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-nuevo-gradoseccion').modal('hide');
                        $("#btnRegistrarGradoSeccion").attr("disabled", false);
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
                        $("#btnRegistrarGradoSeccion").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_editar_gradoseccion(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_editar_gradoseccion",
            u_gradoseccion: codigo
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

function editar_gradoseccion() {
    $("#btnEditarGradoSeccion").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiGS");
    var cod = $("#txtCod");
    var disable = $("#txtDisable");
    var codigo = $("#txtCodigoEdi");
    var nombre = $("#txtNombreEdi");
    var cbbNivel = $("#cbbNivelEdi");
    var cbbEstadoGraSec = $("#cbbEstadoGraSec");
    var contenedor = $("#contenedor");
    var seccionesList0 = "";
    var seccionesList = "";
    var mensaje = "";

    if ($.trim(codigo.val()) == "") {
        mensaje += "*Ingrese el código del grado<br>";
    }
    if ($.trim(nombre.val()) == "") {
        mensaje += "*Ingrese el nombre del grado<br>";
    }
    if ($.trim(cbbNivel.select().val()) == 0) {
        mensaje += "*Seleccione el nivel<br>";
    }
    if ($.trim(cbbEstadoGraSec.select().val()) == -1) {
        mensaje += "*Seleccione el estado<br>";
    }
    if (mensaje !== "") {
        Toast.fire({
            position: 'top-end',
            icon: 'error',
            title: mensaje,
            showConfirmButton: false
        });
        $("#btnEditarGradoSeccion").attr("disabled", false);
    } else {
        $("input[name='check_seccion[]']").each(function () {
            var estado = 0;
            if ($(this).is(":checked") === true) {
                estado = 1;
            } else {
                estado = 0;
            }
            seccionesList0 += $(this).val() + "-" + estado + "*";
        });

        contenedor.find('li').each(function () {
            var value = $(this).find('label').text();
            seccionesList += value + "*";
        });
        $.ajax({
            url: "php/aco_php/controller.php",
            dataType: "html",
            type: "POST",
            data: {
                opcion: "proceso_editar_gradoseccion",
                sm_codigo: $.trim(codSMenu.val()),
                s_cod: $.trim(cod.val()),
                s_disable: $.trim(disable.val()),
                s_codigo: $.trim(codigo.val()),
                s_nombre: $.trim(nombre.val()),
                s_nivel: $.trim(cbbNivel.select().val()),
                s_estado: $.trim(cbbEstadoGraSec.select().val()),
                sec_lista_edi: $.trim(seccionesList0),
                sec_lista_nue: $.trim(seccionesList)
            },
            beforeSend: function (objeto) {
                $("#modal-editar-gradoseccion").find('.modal-footer div label').html("");
                $("#modal-editar-gradoseccion").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //$("#contentMenu").html(xhr.responseText);
            },
            success: function (datos) {
                var resp = datos.split("***");
                if (resp[1] === "1") {
                    var lista_sm = resp[3].split("--");
                    $("#modal-editar-gradoseccion").find('.modal-footer div label').html('');
                    Toast.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: resp[2],
                        showConfirmButton: false
                    });
                    setTimeout(function () {
                        $('#modal-editar-gradoseccion').modal('hide');
                        $("#btnEditarGradoSeccion").attr("disabled", false);
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
                        $("#btnEditarGradoSeccion").attr("disabled", false);
                    }, 4500);
                }
            }
        });
    }
}

function mostrar_eliminar_gradoseccion(modal, codigo) {
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_eliminar_gradoseccion",
            u_gradoseccion: codigo
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

function eliminar_gradoseccion() {
    $("#btnEliminarGradoSeccion").attr("disabled", true);
    var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
    var codSMenu = $("#hdnCodiGS");
    var hdnCodiGrado = $("#hdnCodiGradoEli");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "operacion_eliminar_gradoseccion",
            sm_codigo: $.trim(codSMenu.val()),
            u_codiGrado: $.trim(hdnCodiGrado.val())
        },
        beforeSend: function (objeto) {
            $("#modal-eliminar-gradoseccion").find('.modal-footer div label').html("");
            $("#modal-eliminar-gradoseccion").find('.modal-footer div label').append('<i class="fas fa-spinner fa-pulse"></i> Cargando...&nbsp;&nbsp;&nbsp;');
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            var resp = datos.split("***");
            if (resp[1] === "1") {
                var lista_sm = resp[3].split("--");
                $("#modal-eliminar-gradoseccion").find('.modal-footer div label').html('');
                Toast.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: resp[2],
                    showConfirmButton: false
                });
                setTimeout(function () {
                    $('#modal-eliminar-gradoseccion').modal('hide');
                    $("#btnEliminarGradoSeccion").attr("disabled", false);
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
                    $("#btnEliminarGradoSeccion").attr("disabled", false);
                }, 4500);
            }
        }
    });
}

function cargar_selector_grado_docente(nivel) {
    var str_nivel = nivel.value;
    $("#cbbGrado").html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_selector_grado_docente",
            s_nivel: str_nivel
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#cbbGrado").html(datos);
            $("#cbbSeccion").html('<option value="0">-- Seleccione --</option>');
        }
    });
}

function cargar_selector_seccion_docente(grado) {
    var str_grado = grado.value;
    var str_nivel = $("#cbbNivel").select().val();
    $("#cbbSeccion").html("");
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_selector_seccion_docente",
            s_nivel: str_nivel,
            s_grado: str_grado
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#cbbSeccion").html(datos);
        }
    });
}

function cargar_semaforo_x_bimestre(bimestre){
    var str_bimestre = bimestre.value;
    $.ajax({
        url: "php/aco_php/controller.php",
        dataType: "html",
        type: "POST",
        data: {
            opcion: "formulario_cargar_semaforo_bimestre",
            s_bimestre: str_bimestre,
        },
        beforeSend: function (objeto) {
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //$("#contentMenu").html(xhr.responseText);
        },
        success: function (datos) {
            $("#cbbSemaforo").html(datos);
        }
    });
}

function ocultar_mensaje(id) {
    $(document).ready(function () {
        setTimeout(function () {
            $(id).fadeOut(3000);
        }, 5000);
    });
}

function SigImageCallback(str)
{
    document.FORM1.sigImageData.value = str;
}

function endDemo()
{
    ClearTablet();
    SetTabletState(0, tmr);
}

function close() {
    if (resetIsSupported) {
        Reset();
    } else {
        endDemo();
    }
}

function isCanvasBlank(canvas) {
    const context = canvas.getContext('2d');
    const pixelBuffer = new Uint32Array(
            context.getImageData(0, 0, canvas.width, canvas.height).data.buffer
            );

    return !pixelBuffer.some(color => color !== 0);
}