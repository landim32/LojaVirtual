$.localidade = {

};

function pegarIdPais() {
    var idPais = 0;
    if ($(".pais-select").length > 0) {
        var paisControl = $(".pais-select")[0];
        idPais = $(paisControl).val();
    }
    return idPais;
}

function pegarUf() {
    var uf = '';
    if ($(".uf-select").length > 0) {
        var ufControl = $(".uf-select")[0];
        uf = $(ufControl).val();
    }
    return uf;
}

function pegarIdCidade() {
    var idCidade = 0;
    if ($(".cidade-select").length > 0) {
        var cidadeControl = $(".cidade-select")[0];
        idCidade = $(cidadeControl).val();
    }
    return idCidade;
}

function pegarIdBairro() {
    var idBairro = 0;
    if ($(".bairro-select").length > 0) {
        var bairroControl = $(".bairro-select")[0];
        idBairro = $(bairroControl).val();
    }
    return idBairro;
}

function atualizarPais(paisControl) {
    $(paisControl).empty();
    $(paisControl).append('<option value="">Carregando...</option>');
    $.ajax({
        method: 'POST',
        url: $.app.base_path + "/api/localidade/paises",
        success: function (paises) {
            if (paises) {
                $(paisControl).empty();
                $(paisControl).append('<option value="">--selecione--</option>');
                $.each(paises, function (index, pais) {
                    var str = '<option value="' + pais.id_pais + '"';
                    if (pais.id_pais === pegarIdPais()) {
                        str += ' selected="selected"';
                    }
                    str += '>' + pais.nome + '</option>';
                    $(paisControl).append(str);
                });
                if ($(".uf-select").length > 0) {
                    atualizarUf($(".uf-select")[0]);
                }
            }
        },
        error: function (request, status, error) {
            $.error(request.responseText);
        }
    });
}

function atualizarUf(ufControl) {
    if ($(".pais-select").length > 0) {
        var idPais = pegarIdPais();
        if (!(idPais > 0)) {
            $(ufControl).empty();
            $(ufControl).append('<option value="">--selecione--</option>');
            return;
        }
    }
    $(ufControl).empty();
    $(ufControl).append('<option value="">Carregando...</option>');
    $.ajax({
        method: 'POST',
        url: $.app.base_path + "/api/localidade/estados",
        data: {
            id_pais: idPais
        },
        success: function (estados) {
            //alert(idPais + ', ' + JSON.stringify(estados));
            if (estados) {
                $(ufControl).empty();
                $(ufControl).append('<option value="">--selecione--</option>');
                $.each(estados, function (index, estado) {
                    var str = '<option value="' + estado.uf + '"';
                    if (estado.uf === pegarUf()) {
                        str += ' selected="selected"';
                    }
                    str += '>' + estado.uf + '</option>';
                    $(ufControl).append(str);
                });
                if ($(".cidade-select").length > 0) {
                    atualizarCidade($(".cidade-select")[0]);
                }
            }
        },
        error: function (request, status, error) {
            $.error(request.responseText);
        }
    });
}

function atualizarCidade(cidadeControl) {
    var idPais = 0;
    if ($(".pais-select").length > 0) {
        idPais = pegarIdPais();
        if (!(idPais > 0)) {
            $(cidadeControl).empty();
            $(cidadeControl).append('<option value="">--selecione--</option>');
            return;
        }
    }
    var uf = pegarUf();
    if (!(uf != "")) {
        $(cidadeControl).empty();
        $(cidadeControl).append('<option value="">--selecione--</option>');
        return;
    }
    $(cidadeControl).empty();
    $(cidadeControl).append('<option value="">Carregando...</option>');
    $.ajax({
        method: 'POST',
        url: $.app.base_path + "/api/cidade/listar",
        data: {
            id_pais: idPais,
            uf: uf
        },
        success: function (cidades) {
            if (cidades) {
                $(cidadeControl).empty();
                $(cidadeControl).append('<option value="">--selecione--</option>');
                $.each(cidades, function (index, cidade) {
                    var str = '<option value="' + cidade.id_cidade + '"';
                    if (cidade.id_cidade === pegarIdCidade()) {
                        str += ' selected="selected"';
                    }
                    str += '>' + cidade.nome + '</option>';
                    $(cidadeControl).append(str);
                });
                if ($(".bairro-select").length > 0) {
                    atualizarBairro($(".bairro-select")[0]);
                }
            }
        },
        error: function (request, status, error) {
            $.error(request.responseText);
        }
    });
}

function atualizarBairro(bairroControl) {
    var idCidade = pegarIdCidade();
    if (!(idCidade > 0)) {
        $(bairroControl).empty();
        $(bairroControl).append('<option value="">--selecione--</option>');
        return;
    }
    $(bairroControl).empty();
    $(bairroControl).append('<option value="">Carregando...</option>');
    $.ajax({
        method: 'POST',
        url: $.app.base_path + "/api/localidade/bairros",
        data: {
            id_cidade: idCidade
        },
        success: function (bairros) {
            if (bairros) {
                $(bairroControl).empty();
                $(bairroControl).append('<option value="">--selecione--</option>');
                $.each(bairros, function (index, bairro) {
                    var str = '<option value="' + bairro.id_bairro + '"';
                    if (bairro.id_bairro === pegarIdBairro()) {
                        str += ' selected="selected"';
                    }
                    str += '>' + bairro.nome + '</option>';
                    $(bairroControl).append(str);
                });
            }
        },
        error: function (request, status, error) {
            $.error(request.responseText);
        }
    });
}

function buscaCidade(cidadeBusca) {
    var destinoStr = $(cidadeBusca).attr("data-destino");
    if (!(typeof(destinoStr) !== "string" && destinoStr.length > 0)) {
        destinoStr = "#id_cidade";
    }
    $(cidadeBusca).autocomplete({
        source: function (request, response) {
            var url = $.app.base_path + '/api/cidade/buscar?p=' + escape(request.term);
            $.getJSON(url, function (data) {
                response($.map(data, function (cidade, key) {
                    return {
                        label: cidade.nome,
                        value: cidade.id_cidade
                    };
                }));
            });
        },
        focus: function( event, ui ) {
            $(cidadeBusca).val(ui.item.label);
            return false;
        },
        select: function(e, ui) {
            $(cidadeBusca).val(ui.item.label);
            $(destinoStr).val(ui.item.value);
            return false;
        },
        minLength: 2,
        delay: 100
    });
}

$(document).ready(function() {

    if ($("input[type=text].busca-cidade").length > 0) {
        $("input[type=text].busca-cidade").each(function( index ) {
            buscaCidade(this);
        });
    }

    if ($(".pais-select").length > 0) {
        $(".pais-select").each(function( index ) {
            atualizarPais(this);
        });
    }
    else {
        if ($(".uf-select").length > 0) {
            atualizarUf($(".uf-select")[0]);
        }
    }
    $(".pais-select").bind("change", function () {
        if ($(".uf-select").length > 0) {
            atualizarUf($(".uf-select")[0]);
        }
    });
    $(".uf-select").bind("change", function () {
        if ($(".cidade-select").length > 0) {
            atualizarCidade($(".cidade-select")[0]);
        }
    });
    $(".cidade-select").bind("change", function () {
        if ($(".bairro-select").length > 0) {
            atualizarBairro($(".bairro-select")[0]);
        }
    });
});