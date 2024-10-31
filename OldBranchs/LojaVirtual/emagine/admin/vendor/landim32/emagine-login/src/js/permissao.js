jQuery.fn.exists = function(){ return this.length > 0; }

$.permissao = {};

$(document).ready(function() {
    //permissao-slug
    //permissao-nome
    //permissao-submit
    $('#permissao-submit').click(function (e) {
        e.preventDefault();

        var $btn = $(this);
        $btn.button('loading');

        var permissao = {
            slug: $('#permissao-slug').val(),
            nome: $('#permissao-nome').val()
        };
        if ($('#permissaoModal').attr('data-operacao') == 'alterar') {
            $.permissao.alterar(permissao, function() {
                $btn.button('reset');
                $('#permissaoModal').modal('hide');
                $.success("Permissão alterada com sucesso.");
                $.permissao.reload();
            }, function (erro) {
                $.error(erro);
            });
        }
        else {
            $.permissao.inserir(permissao, function() {
                $btn.button('reset');
                $('#permissaoModal').modal('hide');
                $.success("Permissão incluída com sucesso.");
                $.permissao.reload();
            }, function (erro) {
                $.error(erro);
            });
        }
        return false;
    });
});

$( document ).on( "reload", function( e ) {
    if ($('#permissaoTable').exists()) {
        $.permissao.reload();
    }
});

$.permissao.reload = function () {
    $.permissao.listar(function (permissoes) {
        $('#permissaoTable tbody').empty();
        $.each(permissoes, function (index, p) {
            var html = '';
            html += '<tr>';
            html += '<td class="permissao-slug">' + p.slug + '</td>';
            html += '<td class="permissao-nome">' + p.nome + '</td>';
            html += '<td class="text-right">';
            html += '<a class="permissao-alterar" data-slug="' + p.slug + '" href="#"><i class="fa fa-pencil"></i></a> ';
            html += '<a class="permissao-excluir" data-slug="' + p.slug + '" href="#"><i class="fa fa-remove"></i></a>';
            html += '</td>';
            html += '</tr>';
            $('#permissaoTable tbody').append(html);

            $('a.permissao-alterar').off('click');
            $('a.permissao-alterar').click(function (e) {
                e.preventDefault();
                $('#permissaoModal').attr('data-operacao', 'alterar');
                $('#permissaoModal').attr('data-slug', $(this).closest('tr').find('.permissao-slug').html());
                $('#permissao-slug-form').hide();
                $('#permissao-slug').val($(this).closest('tr').find('.permissao-slug').html());
                $('#permissao-nome').val($(this).closest('tr').find('.permissao-nome').html());
                $('#permissaoModal').modal('show');
                return false;
            });
            $('a.permissao-inserir').off('click');
            $('a.permissao-inserir').click(function (e) {
                e.preventDefault();
                $('#permissaoModal').attr('data-operacao', 'inserir');
                $('#permissaoModal').attr('data-slug', '');
                $('#permissao-slug-form').show();
                $('#permissao-slug').val('');
                $('#permissao-nome').val('');
                $('#permissaoModal').modal('show');
                return false;
            });
            $('a.permissao-excluir').off('click');
            $('a.permissao-excluir').click(function (e) {
                e.preventDefault();

                var $btn = $(this);
                $btn.button('loading');

                var slug = $(this).attr('data-slug');

                $.confirm("Tem certeza?", function () {
                    $.permissao.excluir(slug, function() {
                        $btn.button('reset');
                        $btn.closest("tr").fadeOut('slow', function () {
                            $.success("Permissão excluída com sucesso.");
                        });
                    }, function(erro) {
                        $btn.button('reset');
                        $.error(erro);
                    });
                });
                return false;
            });
        });
    }, function (erro) {
        $.error(erro);
    });
};

$.permissao.listar = function (sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/listar';
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.permissao.excluir = function (slug, sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/excluir/' + slug;
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.permissao.inserir = function (permissao, sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/inserir';
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

$.permissao.alterar = function (permissao, sucesso, falha) {
    var url = $.app.base_path + '/api/permissao/alterar';
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