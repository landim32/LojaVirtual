jQuery.fn.exists = function(){ return this.length > 0; }

$.grupo = {};

$(document).ready(function() {

    $('#grupo-submit').click(function (e) {
        e.preventDefault();

        var $btn = $(this);
        $btn.button('loading');

        var grupo = {
            id_grupo: $('#grupoModal').attr('data-grupo'),
            nome: $('#grupo-nome').val(),
            permissoes: []
        };
        $('#grupo-permissoes :selected').each(function(i, sel){
            var perm = {
                slug: $(sel).val(),
                nome: $(sel).html()
            };
            grupo.permissoes.push(perm);
        });
        if ($('#grupoModal').attr('data-operacao') == 'alterar') {
            $.grupo.alterar(grupo, function() {
                $btn.button('reset');
                $('#grupoModal').modal('hide');
                $.success("Grupo alterado com sucesso.");
                $.grupo.reload();
            }, function (erro) {
                $btn.button('reset');
                $.error(erro);
            });
        }
        else {
            $.grupo.inserir(grupo, function() {
                $btn.button('reset');
                $('#grupoModal').modal('hide');
                $.success("Grupo incluído com sucesso.");
                $.grupo.reload();
            }, function (erro) {
                $btn.button('reset');
                $.error(erro);
            });
        }
        return false;
    });
});

$( document ).on( "reload", function( e ) {
    if ($('#grupoTable').exists()) {
        $.grupo.reload();
    }
});

$.grupo.reload = function () {
    $.grupo.listar(function (grupos) {
        $('#grupoTable tbody').empty();
        $.each(grupos, function (index, grupo) {
            var html = '';
            html += '<tr data-grupo="' + grupo.id_grupo + '">';
            html += '<td>' + grupo.nome + '</td>';
            html += '<td>' + grupo.permissao_str + '</td>';
            html += '<td class="text-right">';
            html += '<a class="grupo-alterar" data-grupo="' + grupo.id_grupo + '" href="#"><i class="fa fa-pencil"></i></a> ';
            html += '<a class="grupo-excluir" data-grupo="' + grupo.id_grupo + '" href="#"><i class="fa fa-remove"></i></a>';
            html += '</td>';
            html += '</tr>';
            $('#grupoTable tbody').append(html);

            $('a.grupo-alterar').off('click');
            $('a.grupo-alterar').click(function (e) {
                e.preventDefault();

                var $btn = $(this);
                $btn.button('loading');

                var id_pegar = $(this).attr('data-grupo');

                $.grupo.pegar(id_pegar, function (grupo) {
                    $.permissao.listar(function (permissoes) {
                        $('#grupo-permissoes').empty();
                        $.each(permissoes, function (index, perm) {
                            $('#grupo-permissoes').append('<option value="' + perm.slug + '">' + perm.nome + '</option>');
                        });
                        $('#grupo-permissoes').multiselect('rebuild');

                        var lista = [];
                        $.each(grupo.permissoes, function (index, permissao) {
                            lista.push(permissao.slug);
                        });
                        $('#grupoModal').attr('data-grupo', grupo.id_grupo);
                        $('#grupoModal').attr('data-operacao', 'alterar');
                        $('#grupo-nome').val(grupo.nome);
                        $('#grupo-permissoes').multiselect('select', lista);
                        $btn.button('reset');
                        $('#grupoModal').modal('show');
                    }, function (erro) {
                        $btn.button('reset');
                        $.error(erro);
                    });
                }, function (erro) {
                    $btn.button('reset');
                    $.error(erro);
                });

                return false;
            });
            $('a.grupo-inserir').off('click');
            $('a.grupo-inserir').click(function (e) {
                e.preventDefault();

                var $btn = $(this);
                $btn.button('loading');

                $.permissao.listar(function (permissoes) {
                    $('#grupo-permissoes').empty();
                    $.each(permissoes, function (index, perm) {
                        $('#grupo-permissoes').append('<option value="' + perm.slug + '">' + perm.nome + '</option>');
                    });
                    $('#grupo-permissoes').multiselect('rebuild');

                    $('#grupoModal').attr('data-grupo', '0');
                    $('#grupoModal').attr('data-operacao', 'inserir');
                    $('#grupo-nome').val('');
                    $('#grupo-permissoes').multiselect('select', []);
                    $btn.button('reset');
                    $('#grupoModal').modal('show');
                }, function (erro) {
                    $btn.button('reset');
                    $.error(erro);
                });
                return false;
            });
            $('a.grupo-excluir').off('click');
            $('a.grupo-excluir').click(function (e) {
                e.preventDefault();

                var $btn = $(this);
                $btn.button('loading');

                var id_grupo = $(this).attr('data-grupo');

                $.confirm("Tem certeza?", function () {
                    $.grupo.excluir(id_grupo, function() {
                        $btn.button('reset');
                        $btn.closest("tr").fadeOut('slow', function () {
                            $.success("Grupo excluído com sucesso.");
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

$.grupo.listar = function (sucesso, falha) {
    var url = $.app.base_path + '/api/grupo/listar';
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.grupo.pegar = function (id_grupo, sucesso, falha) {
    var url = $.app.base_path + '/api/grupo/pegar/' + id_grupo;
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.grupo.excluir = function (id_grupo, sucesso, falha) {
    var url = $.app.base_path + '/api/grupo/excluir/' + id_grupo;
    $.get(url, function (data) {
        sucesso(data);
    }).fail(function (request, status, error) {
        falha(request.responseText);
    });
};

$.grupo.inserir = function (grupo, sucesso, falha) {
    var url = $.app.base_path + '/api/grupo/inserir';
    $.ajax({
        method: 'PUT',
        url: url,
        data: JSON.stringify(grupo),
        success: function (dado) {
            sucesso(dado);
        },
        error: function (request, status, error) {
            falha(request.responseText);
        }
    });
};

$.grupo.alterar = function (grupo, sucesso, falha) {
    var url = $.app.base_path + '/api/grupo/alterar';
    $.ajax({
        method: 'PUT',
        url: url,
        data: JSON.stringify(grupo),
        success: function (dado) {
            sucesso(dado);
        },
        error: function (request, status, error) {
            falha(request.responseText);
        }
    });
};
