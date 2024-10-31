$.social = {

};

$.social.buscarAmigo = function (palavraChave, sucesso, falha) {
    var url = $.app.base_path + '/api/social/buscar-amigo';
    $.ajax({
        method: 'POST',
        url: url,
        data: {
            'palavra_chave': palavraChave
        },
        success: function (dado) {
            sucesso(dado);
        },
        error: function (request, status, error) {
            falha(request.responseText);
        }
    });
};

$(document).ready(function() {
    $( "#mensagem-para" ).autocomplete({
        minLength: 2,
        source: function( request, response ) {
            //alert(request.term);
            $.social.buscarAmigo(request.term, function (data) {
                //alert(JSON.stringify(data));
                var retorno = [{label: 'Convidar Amigo', value: '0', foto: ''}];
                if (data) {
                    $.each(data, function (index, usuario) {
                        retorno.push({
                            label: usuario.nome,
                            value: usuario.id_usuario,
                            foto: usuario.foto
                        });
                    });
                }
                //alert(JSON.stringify(retorno));
                response(retorno);
            }, function (erro) {
                $.error(erro);
            });
        },
        select: function( event, ui ) {
            $('#mensagem-controle').hide();
            $('#mensagem-foto').attr('src', ui.item.foto);
            $('#mensagem-nome').html(ui.item.label);
            $('#mensagem-usuario').show();
            if (ui.item.value == '0') {
                $("#mensagem-para").val('');
            }
            else {
                $("#mensagem-para").val(ui.item.label);
            }
            $('#mensagem-para').attr('data-usuario', ui.item.value);
            return false;
        },
        focus: function( event, ui ) {
            if (ui.item.value == '0')
                $( "#mensagem-para" ).val( '' );
            else
                $( "#mensagem-para" ).val( ui.item.label );
            return false;
        },
        open: function() {
            $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
        },
        close: function() {
            $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
        }
    })/*.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        if (item.value == '0')
            return $( "<li class='novo'>" ).append( "<i class='fa fa-fw fa-plus'></i> " + item.label ).appendTo( ul );
        else
            return $( "<li>" ).append( "<i class='fa fa-fw fa-user'></i> " + item.label ).appendTo( ul );
    }*/;

    $('#mensagem-remover').click(function() {
        $('#mensagem-controle').show();
        $('#mensagem-usuario').hide();
        $('#mensagem-para').val('');
        return false;
    });
});

$( document ).on( "reload", function( e ) {

});


/*
$('#conviteModal form').submit(function() {
    $.ajax({
        type: "POST",
        url: "ajax-convite",
        data: $('#conviteModal form').serialize()
    }).done(function( msg ) {
        if (msg == 'ok') {
            noty({text: constants.invitationSent, type: 'success'});
        }
        else {
            noty({text: msg, type: 'error'});
            //return false;
        }

    }).fail(function(jqXHR, textStatus) {
        noty({text: "Erro: " + textStatus, type: 'error'});
    });
    $('#conviteModal').modal('hide');
    $('#conviteModal form').trigger("reset");
    return false;
});

$('a.mensagem').click(function() {
    MENSAGEM_USUARIO_ID = $(this).attr('data-usuario');
    $('#mensagem_foto').attr('src', $(this).attr('data-foto'));
    $('#mensagem_nome').html($(this).attr('data-nome'));
    $('#mensagem_controle').hide();
    $('#mensagem_usuario').show();
    $('#mensagemModal').modal('show');
    return false;
});
$('#mensagem_remover').click(function() {
    MENSAGEM_USUARIO_ID = 0;
    $('#mensagem_controle').show();
    $('#mensagem_usuario').hide();
    $('#mensagem_para').val('');
    return false;
});

$('#mensagem-nova').click(function() {
    var $btn = $(this).button('loading');
    if (!(MENSAGEM_USUARIO_ID > 0)) {
        noty({text: 'Selecione o destinatário da mensagem.', type: 'error'});
        $('#mensagem_para').focus();
        return false;
    }
    //alert(MENSAGEM_USUARIO_ID);
    var vMensagem = $('#mensagem_conteudo').val();
    if (vMensagem == '') {
        noty({text: constants.fillMessage, type: 'error'});
        $('#mensagem_conteudo').focus();
        return false;
    }
    $.ajax({
        type: "POST",
        url: "ajax-mensagem",
        data: {
            id_usuario: MENSAGEM_USUARIO_ID,
            mensagem: vMensagem
        }
    }).done(function( msg ) {
        $btn.button('reset');
        if (msg == 'ok') {
            noty({text: 'Mensagem enviada com sucesso!', type: 'success'});
            $('#mensagem_conteudo').val('');
            $('#mensagemModal').modal('hide');
        }
        else {
            noty({text: msg, type: 'error'});
            return false;
        }
    }).fail(function(jqXHR, textStatus) {
        $btn.button('reset');
        noty({text: "Erro: " + textStatus, type: 'error'});
    });
    return false;
});

$('button[data-loading-text]').on('click', function () {
    var btn = $(this);
    btn.button('loading');
    setTimeout(function () {
        btn.button('reset')
    }, 3000);
});
*/