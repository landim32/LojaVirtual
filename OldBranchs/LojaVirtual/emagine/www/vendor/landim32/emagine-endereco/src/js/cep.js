$.cep = {
    urlCep: 'http://emagine.com.br/endereco',
    campos: {
        'cep': '.cep-busca',
        'logradouro': '.cep-logradouro',
        'complemento': '.cep-complemento',
        'numero': '.cep-numero',
        'bairro': '.cep-bairro',
        'cidade': '.cep-cidade',
        'uf': '.cep-uf',
        'latitude': '.cep-latitude',
        'longitude': '.cep-longitude'
    },
    pegarUf: function () {
        var uf = '';
        if ($($.cep.campos.uf).length > 0) {
            var ufControl = $($.cep.campos.uf)[0];
            uf = $(ufControl).val();
        }
        return uf;
    },
    pegarCidade: function () {
        var nomeCidade = '';
        if ($($.cep.campos.cidade).length > 0) {
            nomeCidade = $($.cep.campos.cidade).val();
        }
        return nomeCidade;
    },
    pegarBairro: function () {
        var nomeBairro = '';
        if ($($.cep.campos.bairro).length > 0) {
            nomeBairro = $($.cep.campos.bairro).val();
        }
        return nomeBairro;
    },
    inicializarCidade: function (cidadeBusca) {
        $(cidadeBusca).autocomplete({
            source: function (request, response) {
                var uf = $.cep.pegarUf();
                var url = $.cep.urlCep + '/api/cep/buscar-por-cidade';
                url += '?p=' + escape(request.term) + '&uf=' + escape(uf);
                //alert(url);
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
                return false;
            },
            minLength: 2,
            delay: 100
        });
    },
    inicializarBairro: function (bairroBusca) {
        $(bairroBusca).autocomplete({
            source: function (request, response) {
                var uf = $.cep.pegarUf();
                var cidade = $.cep.pegarCidade();
                var url = $.cep.urlCep + '/api/cep/buscar-por-bairro?p=' +
                    escape(request.term) + '&uf=' + encodeURI(uf) + '&cidade=' + encodeURI(cidade);
                //alert(url);
                $.getJSON(url, function (data) {
                    response($.map(data, function (bairro, key) {
                        return {
                            label: bairro.nome,
                            value: bairro.id_bairro
                        };
                    }));
                });
            },
            focus: function( event, ui ) {
                $(bairroBusca).val(ui.item.label);
                return false;
            },
            select: function(e, ui) {
                $(bairroBusca).val(ui.item.label);
                return false;
            },
            minLength: 2,
            delay: 100
        });
    },
    inicializarLogradouro: function (logradouroBusca) {
        $(logradouroBusca).autocomplete({
            source: function (request, response) {
                var uf = $.cep.pegarUf();
                var cidade = $.cep.pegarCidade();
                var bairro = $.cep.pegarBairro();
                var url = $.cep.urlCep + '/api/cep/buscar-por-logradouro?p=' +
                    escape(request.term) + '&uf=' + encodeURI(uf) + '&cidade=' +
                    encodeURI(cidade) + '&bairro=' + encodeURI(bairro);
                //alert(url);
                $.getJSON(url, function (data) {
                    response($.map(data, function (logradouro, key) {
                        return {
                            label: logradouro.logradouro,
                            value: logradouro.cep
                        };
                    }));
                });
            },
            focus: function( event, ui ) {
                $(logradouroBusca).val(ui.item.label);
                return false;
            },
            select: function(e, ui) {
                $(logradouroBusca).val(ui.item.label);
                if ($($.cep.campos.cep).length > 0) {
                    $($.cep.campos.cep).val(ui.item.value);
                }
                return false;
            },
            minLength: 2,
            delay: 100
        });
    },
    buscarCep: function (cep) {
        $.ajax({
            method: 'GET',
            dataType: "json",
            url: $.cep.urlCep + '/api/cep/pegar/' + escape(cep),
            success: function (endereco) {
                //alert(JSON.stringify(endereco));
                if (endereco) {
                    if (endereco.logradouro) {
                        if ($($.cep.campos.logradouro).length > 0) {
                            $($.cep.campos.logradouro).val(endereco.logradouro);
                        }
                    }
                    if (endereco.complemento) {
                        if ($($.cep.campos.complemento).length > 0) {
                            $($.cep.campos.complemento).val(endereco.complemento);
                        }
                    }
                    if (endereco.numero) {
                        if ($($.cep.campos.numero).length > 0) {
                            $($.cep.campos.numero).val(endereco.numero);
                        }
                    }
                    if (endereco.bairro) {
                        if ($($.cep.campos.bairro).length > 0) {
                            $($.cep.campos.bairro).val(endereco.bairro);
                        }
                    }
                    if (endereco.cidade) {
                        if ($($.cep.campos.cidade).length > 0) {
                            $($.cep.campos.cidade).val(endereco.cidade);
                        }
                    }
                    if (endereco.uf) {
                        if ($($.cep.campos.uf).length > 0) {
                            $($.cep.campos.uf).val(endereco.uf);
                        }
                    }
                    if (endereco.latitude) {
                        if ($($.cep.campos.latitude).length > 0) {
                            $($.cep.campos.latitude).val(endereco.latitude);
                        }
                    }
                    if (endereco.longitude) {
                        if ($($.cep.campos.longitude).length > 0) {
                            $($.cep.campos.longitude).val(endereco.longitude);
                        }
                    }
                }
            },
            error: function (request, status, error) {
                $.error(request.responseText);
            }
        });
    },
    inicilizar: function() {
        /*
        if ($($.cep.campos.uf).length > 0) {
            $($.cep.campos.uf).change(function (e) {

            });
        }
        */
        if ($($.cep.campos.cep).length > 0) {
            $($.cep.campos.cep).keyup(function (e) {
                if ($(this).val().length == 8) {
                    $.cep.buscarCep($(this).val());
                }
            });
        }
        if ($($.cep.campos.cidade).length > 0) {
            $($.cep.campos.cidade).each(function (index) {
                $.cep.inicializarCidade(this);
            });
        }

        if ($($.cep.campos.bairro).length > 0) {
            $($.cep.campos.bairro).each(function (index) {
                $.cep.inicializarBairro(this);
            });
        }

        if ($($.cep.campos.logradouro).length > 0) {
            $($.cep.campos.logradouro).each(function (index) {
                $.cep.inicializarLogradouro(this);
            });
        }
    }
};

$(document).ready(function() {
    $.cep.inicilizar();
});