$.mensagem = {
    enviar: function (idUsuario, mensagem, sucesso, falha) {
        var url = $.app.base_path + '/api/mensagem/enviar';
        $.ajax({
            method: 'POST',
            url: url,
            data: {
                'id_usuario': idUsuario,
                'mensagem': mensagem
            },
            success: function (dado) {
                sucesso(dado);
            },
            error: function (request, status, error) {
                falha(request.responseText);
            }
        });
    },
    excluir: function (idMensagem, sucesso, falha) {
        var url = $.app.base_path + '/api/mensagem/excluir/' + idMensagem;
        $.get(url, function (data) {
            sucesso(data);
        }).fail(function (request, status, error) {
            falha(request.responseText);
        });
    },
    exibirAviso: function (id_usuario) {
        var url = $.app.base_path + '/api/aviso/' + id_usuario;
        $.getJSON(url, function (data) {
            if (data) {
                $.each(data, function (index, mensagem) {
                    if (mensagem.url) {
                        var n = new Noty({
                            text: '<i class="fa fa-info-circle"></i> ' + mensagem.mensagem,
                            type: 'success',
                            force: true,
                            timeout: 25000,
                            mensagem: mensagem,
                            buttons: [
                                Noty.button('Acessar', 'btn btn-sm btn-success', function () {
                                    n.close();
                                    location.href = mensagem.url;
                                })
                            ]
                        }).show();
                    }
                    else {
                        //alert(mensagem.mensagem);
                        new Noty({
                            text: '<i class="fa fa-info-circle"></i> ' + mensagem.mensagem,
                            type: 'success',
                            force: true,
                            timeout: 25000
                        }).show();
                    }
                });
            }
        });
    }
};

$(document).ready(function() {
    $("#mensagem-submit").click(function (e) {
        e.preventDefault();

        //var $btn = $(this);
        //$btn.button('loading');

        var idUsuario = $('#mensagem-para').attr('data-usuario');
        $.mensagem.enviar(idUsuario, $('#mensagem-conteudo').val(), function () {
            $.success("Mensagem enviada com sucesso!");
            $('#mensagemModal').modal('hide');
            //$btn.button('reset');
        }, function (erro) {
            //$btn.button('reset');
            $.error(erro);
        });
        return false;
    });
});

$( document ).on( "reload", function( e ) {
    $('a.mensagem').click(function() {
        $('#mensagem-foto').attr('src', $(this).attr('data-foto'));
        $('#mensagem-para').attr('data-usuario', $(this).attr('data-usuario'));
        $('#mensagem-nome').html($(this).attr('data-nome'));
        $('#mensagem-controle').hide();
        $('#mensagem-usuario').show();
        $('#mensagemModal').modal('show');
        return false;
    });
    $('#mensagem_remover').click(function() {
        $('#mensagem-controle').show();
        $('#mensagem-usuario').hide();
        $('#mensagem-para').val('');
        $('#mensagem-para').attr('data-usuario', '');
        return false;
    });
    $('a.mensagem-excluir').click(function() {
        e.preventDefault();

        var $btn = $(this);
        var idMensagem = $(this).attr('data-mensagem');

        $.confirm("Tem certeza?", function () {
            $.mensagem.excluir(idMensagem, function () {
                $btn.closest('div.list-group-item').fadeOut('slow', function () {
                    $.success("Mensagem excluída com sucesso.");
                });
            }, function (erro) {
                $.error(erro);
            });
        });

        return false;
    });
});