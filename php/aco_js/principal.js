/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function cargar_opcion(ruta, nombre) {
    $.ajax({
        url: ruta,
        dataType: "html",
        type: "POST",
        data: {
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