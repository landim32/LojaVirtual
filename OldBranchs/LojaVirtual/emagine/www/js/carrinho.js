$.carrinho = {
    idLoja: 0,
    valorFrete: 0,
    valorMinimo: 0,
    valorMinimoStr: '',
    totalCarrinho: function () {
        var produtos = $.carrinho.listar();
        var total = 0;
        if ($.isArray(produtos)) {
            $.each(produtos, function (index, produto) {
                var quantidade = produto.quantidade;
                var preco = produto.valor;
                if (produto.valor_promocao > 0) {
                    preco = produto.valor_promocao;
                }
                total += preco * quantidade;
            });
        }
        return total;
    }
};

$(document).ready(function() {
    if ($("#carrinho").length > 0) {
        if ($("table#pedido").length > 0) {
            $.carrinho.exibir();
        }
        $("#cod_pagamento").change(function () {
            if (parseInt($(this).val()) == 1) {
                $("#troco_para").removeAttr('disabled');
            }
            else {
                $("#troco_para").val("0,00");
                $("#troco_para").attr('disabled','disabled');
            }
        });
        $(".btn-adicionar").each(function (index) {
            var produto = $.carrinho.carregarTag(this);
            $.carrinho.carregarBotao(this, produto);
        });
        $(".btn-finalizar").click(function (e) {
            e.preventDefault();
            var $btn = $(this).button('loading');

            var pedido = $.carrinho.carregarPedido();
            //alert(JSON.stringify(pedido));
            $.carrinho.efetuarPedido(pedido, function (retorno) {
                $btn.button('reset');
                //alert(JSON.stringify(retorno));
                localStorage.clear();
                location.href = retorno.url;
            }, function (erro) {
                $btn.button('reset');
                $.error(erro);
            });

            return false;
        });
        $('.btn-entrega').click(function (e) {
            var valorTotal = $.carrinho.totalCarrinho();
            //alert(valorTotal + "<" + $.carrinho.valorMinimo);
            if (valorTotal < $.carrinho.valorMinimo) {
                var mensagem = "Sua compra precisa ter um valor mínimo de " + $.carrinho.valorMinimoStr + ".";
                $.error(mensagem);
                //alert("Sua compra precisa ter o valor mínimo de " + $.carrinho.valorMinimoStr + ".");
                return false;
            }
            return true;
        });
        $.carrinho.atualizar();
    }
});

$.carrinho.limpar = function() {
    localStorage.clear();
};

$.carrinho.listar = function () {
    return JSON.parse(localStorage.getItem("carrinho"));
};

$.carrinho.gravar = function (produtos) {
    localStorage.setItem("carrinho", JSON.stringify(produtos));
};

$.carrinho.carregarTag = function(btn) {
    return {
        id: $(btn).attr('data-id'),
        id_loja: $(btn).attr('data-loja'),
        nome: $(btn).attr('data-nome'),
        foto: $(btn).attr('data-foto'),
        valor: $(btn).attr('data-valor'),
        valor_promocao: $(btn).attr('data-promocao'),
        quantidade: 0
    };
};

$.carrinho.carregarBotao = function(btn, produto) {
    var str = "";
    var produtoNoCarrinho = $.carrinho.pegar(produto.id);
    if (produtoNoCarrinho && produtoNoCarrinho.quantidade && produtoNoCarrinho.quantidade > 0) {
        str += "<div class=\"btn-group btn-group-justified\" role=\"group\">\n";
        str += "<div class=\"btn-group\" role=\"group\">\n";
        str += "\t<button type=\"button\" class=\"btn btn-primary remover\"><i class='fa fa-minus'></i></button>\n";
        str += "</div>\n";
        str += "<div class=\"btn-group\" role=\"group\">\n";
        str += "<button type=\"button\" class=\"btn btn-default text-center\">" + produtoNoCarrinho.quantidade + "</button>\n";
        str += "</div>\n";
        str += "<div class=\"btn-group\" role=\"group\">\n";
        str += "<button type=\"button\" class=\"btn btn-primary adicionar\"><i class='fa fa-plus'></i></button>\n";
        str += "</div>\n";
        str += "</div>\n";
    }
    else {
        str += "<a href='#' class='btn btn-block btn-primary adicionar'>ADICIONAR</a>";
    }
    $(btn).html(str);
    $(btn).find('.adicionar').click(function (e) {
        e.preventDefault();
        var produto = $.carrinho.carregarTag( $(this).closest('.btn-adicionar') );
        var produtos = $.carrinho.listar();
        if (produtos.length > 0) {
            var temProdutoDeOutraLoja = false;
            $.each(produtos, function (index, item) {
                //$.info($.carrinho.idLoja + ' != ' + item.id_loja);
                if ($.carrinho.idLoja != item.id_loja) {
                    temProdutoDeOutraLoja = true;
                }
            });
            if (temProdutoDeOutraLoja === true) {
                //$.info($.carrinho.idLoja + ' != ' + produto.id_loja);
                $.error("Você precisa concluir a compra na outra loja antes de iniciar nessa.");
                return false;
            }
        }
        var encontrou = false;
        if ($.isArray(produtos)) {
            $.each(produtos, function (index, item) {
                if (item.id === produto.id) {
                    encontrou = true;
                    produtos[index].quantidade++;
                }
            });
        }
        else {
            produtos = [];
        }
        if (encontrou === false) {
            produto.quantidade = 1;
            produtos.push(produto);
        }
        //alert(JSON.stringify(produtos));
        //localStorage.setItem("carrinho", JSON.stringify(produtos));
        $.carrinho.gravar(produtos);
        $.carrinho.atualizar();
        if ($(this).closest('.btn-adicionar').length > 0) {
            var btn = $(this).closest('.btn-adicionar')[0];
            //alert(JSON.stringify(btn));
            $.carrinho.carregarBotao(btn, produto);
        }
        return false;
    });
    $(btn).find('.remover').click(function (e) {
        e.preventDefault();
        var produto = $.carrinho.carregarTag( $(this).closest('.btn-adicionar') );
        var produtos = $.carrinho.listar();
        var novoProdutos = [];
        if ($.isArray(produtos)) {
            $.each(produtos, function (index, item) {
                if (item.id === produto.id) {
                    item.quantidade--;
                    if (item.quantidade > 0) {
                        novoProdutos.push(item);
                    }
                }
                else {
                    novoProdutos.push(item);
                }
            });
        }
        //localStorage.setItem("carrinho", JSON.stringify(novoProdutos));
        $.carrinho.gravar(novoProdutos);
        $.carrinho.atualizar();
        if ($(this).closest('.btn-adicionar').length > 0) {
            var btn = $(this).closest('.btn-adicionar')[0];
            $.carrinho.carregarBotao(btn, produto);
        }
        return false;
    });

};

$.carrinho.pegar = function(idProduto) {
    var produtos = $.carrinho.listar();
    var retorno = null;
    if ($.isArray(produtos)) {
        $.each(produtos, function (index, produto) {
            if (produto.id == idProduto) {
                retorno = produto;
            }
        });
    }
    return retorno;
};

$.carrinho.atualizar = function () {
    var produtos = $.carrinho.listar();
    //alert(JSON.stringify(produtos));
    var total = 0;
    var quantidadeTotal = 0;
    if ($.isArray(produtos)) {
        $.each(produtos, function (index, produto) {
            var quantidade = produto.quantidade;
            var preco = produto.valor;
            if (produto.valor_promocao > 0) {
                preco = produto.valor_promocao;
            }
            total += preco * quantidade;
            quantidadeTotal += quantidade;
        });
    }
    var totalComFrete = total + $.carrinho.valorFrete;
    $("#valorTotal").html(totalComFrete.toFixed(2).replace(".",","));
    $("#carrinho .total").html(total.toFixed(2).replace(".",","));
    $("#carrinho .quantidade").html(quantidadeTotal.toFixed(0).replace(".",","));
    $("#resumo .total").html(total.toFixed(2).replace(".",","));
    $("#resumo .quantidade").html(quantidadeTotal.toFixed(0).replace(".",","));
    $("input#pedido[type=hidden]").val(JSON.stringify(produtos));
};

$.carrinho.exibir = function() {
    var str = "";
    var produtos = $.carrinho.listar();
    if ($.isArray(produtos) && produtos.length > 0) {
        $.each(produtos, function (index, produto) {
            var urlFoto = $.app.base_path + "/produto/40x40/" + produto.foto;
            //var total = produto.valor * produto.quantidade;
            var preco = parseFloat( produto.valor );
            str += "<tr>\n";
            str += "<td width='10%'><img src='" + urlFoto + "' class='img-responsive'></td>\n";
            str += "<td class=\"hidden-xs\">" + produto.nome + "</td>\n";
            str += "<td class='text-right'>" + preco.toFixed(2).replace(".",",") + "</td>\n";
            str += "<td class='text-right' style='width: 150px;'>";
            str += "<div class=\"btn-adicionar\"";
            str += " data-id=\"" + produto.id + "\"";
            str += " data-loja=\"" + produto.id_loja + "\"";
            str += " data-foto=\"" + produto.foto + "\"";
            str += " data-nome=\"" + produto.nome + "\"";
            str += " data-valor=\"" + produto.valor + "\"";
            str += " data-promocao=\"" + produto.valor_promocao + "\"";
            str += "></div>";
            str += "</td>\n";
            str += "</tr>\n";
        });
    }
    else {
        str += "<tr><td colspan='5'><i class='fa fa-warning'></i> Nenhum produto no carrinho.</td></tr>";
    }
    $("table#pedido tbody").html(str);
};

$.carrinho.carregarPedido = function () {
    var pedido = {
        id_loja: $.carrinho.idLoja,
        cod_pagamento: $("#cod_pagamento").val(),
        troco_para: $("#troco_para").val(),
        valor_frete: $.carrinho.valorFrete,
        observacao: $("#observacao").val(),
        itens: []
    };
    var produtos = $.carrinho.listar();
    if ($.isArray(produtos) && produtos.length > 0) {
        $.each(produtos, function (index, produto) {
            pedido.itens.push({
                id_produto: produto.id,
                quantidade: produto.quantidade
            });
        });
    }
    return pedido;
};

$.carrinho.efetuarPedido = function (pedido, sucesso, falha) {
    var url = $.app.base_path + '/api/pedido/novo';
    //alert(url);
    $.ajax({
        method: 'PUT',
        url: url,
        data: JSON.stringify(pedido),
        success: function (dado) {
            sucesso(dado);
        },
        error: function (request, status, error) {
            falha(request.responseText);
        }
    });
};