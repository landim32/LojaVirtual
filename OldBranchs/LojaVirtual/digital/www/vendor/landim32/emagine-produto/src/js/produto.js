$.produto = {
    id_loja: 0,
    ultima_atualizacao: null,
    campos: {
        'loja': '#id_loja',
        'id_produto': '#id_produto',
        'produto_loja': '#nome_produto',
        'produto_original': '.produto-original'
    },
    inicializarPorLoja: function (produtoBusca) {
        $(produtoBusca).autocomplete({
            source: function (request, response) {
                var url = $.app.base_path + '/ajax/produto/buscar?p=' + escape(request.term);
                $.getJSON(url, function (data) {
                    response($.map(data, function (produto, key) {
                        var labelProduto = produto.nome;
                        return {
                            label: labelProduto,
                            value: produto.id_produto
                        };
                    }));
                });
            },
            focus: function( event, ui ) {
                $(produtoBusca).val(ui.item.label);
                return false;
            },
            select: function(e, ui) {
                $(produtoBusca).val(ui.item.label);
                if ($($.produto.campos.id_produto).length > 0) {
                    $($.produto.campos.id_produto).val(ui.item.value);
                }
                return false;
            },
            minLength: 2,
            delay: 100
        });
    },
    inicializarOriginal: function (produtoBusca) {
        $(produtoBusca).autocomplete({
            source: function (request, response) {
                //alert(request.term);
                var url = $.app.base_path + '/ajax/produto/buscar-original?p=' + escape(request.term);
                //alert(url);
                $.getJSON(url, function (data) {
                    //alert(JSON.stringify(data));
                    response($.map(data, function (produto, key) {
                        return {
                            label: produto.nome,
                            value: produto.id_produto
                        };
                    }));
                });
            },
            focus: function( event, ui ) {
                $(produtoBusca).val(ui.item.label);
                return false;
            },
            select: function(e, ui) {
                $(produtoBusca).val(ui.item.label);
                if ($($.produto.campos.id_produto).length > 0) {
                    $($.produto.campos.id_produto).val(ui.item.value);
                }
                return false;
            },
            minLength: 2,
            delay: 100
        });
    }
};

$(document).ready(function() {
    //alert("teste");
    $.produto.inicializarPorLoja($.produto.campos.produto_loja);
    $.produto.inicializarOriginal($.produto.campos.produto_original);
});