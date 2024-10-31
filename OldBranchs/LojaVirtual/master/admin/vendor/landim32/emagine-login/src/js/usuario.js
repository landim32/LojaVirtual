$.usuario = {

};

$(document).ready(function() {
    $("a#foto-usuario").click(function (e) {
        e.preventDefault();
        $('#fotoModal').modal('show');
        return false;
    });
    $("#foto-submit").click(function (e) {
        e.preventDefault();
        var fotoData = $("#fotoUsuario").cropper("getCroppedCanvas", {width: 100, height: 100});
        var dataUrl = fotoData.toDataURL();

        var formData = new FormData();
        formData.append('foto', dataUrl);

        $.ajax($.app.base_path +  '/api/usuario/alterar/foto', {
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                $("a#foto-usuario img").attr('src', dataUrl);
                $('#fotoModal').modal('hide');
                $.success('Foto atualizada com sucesso.');
            },
            error: function (request, status, error) {
                $.error(request.responseText);
            }
        });
        return false;
    });
});

$( document ).on( "reload", function( e ) {

    var $image = $("#fotoUsuario");
    //var $input = $("[name='croppResult']");

    $("input:file").change(function() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(this.files[0]);
        oFReader.onload = function (oFREvent) {
            // Destroy the old cropper instance
            $image.cropper('destroy');

            $image.show();

            // Replace url
            $image.attr('src', this.result);

            // Start cropper
            $image.cropper({
                viewMode: 2,
                aspectRatio: 1,
                responsive: true,
                restore: true,
                movable: true,
                zoomable: true,
                rotatable: true,
                scalable: true
            });
        };
    });
});

$.usuario.listar = function (sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/listar';
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.usuario.listar = function (sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/excluir';
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.usuario.gravar = function (permissao, sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/gravar';
    $.ajax({
        method: 'PUT',
        url: url,
        data: JSON.stringify(permissao),
        success: function (dado) {
            sucesso(dado);
        },
        error: function (request, status, error) {
            falha(request.responseText);
        }
    });
};