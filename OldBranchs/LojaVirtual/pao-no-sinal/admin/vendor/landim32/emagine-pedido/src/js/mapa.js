$.mapa = {
    api_key: "AIzaSyCYqp1ZAiX0M8Lqth1kFYRh6wju0Y4vAm8",
    id_loja: 0,
    mapa: null,
    inicializarMapa: function (loja, pedidos) {
        $.loja.lojaAtual = loja;
        $.mapa.mapa = new google.maps.Map(document.getElementById('mapa'), {
            zoom: 17,
            center: {
                lat: parseFloat(loja.latitude),
                lng: parseFloat(loja.longitude)
            },
            mapTypeId: 'roadmap'
        });

        /*
        var bounds = new google.maps.LatLngBounds();
        bounds.extend({
            lat: parseFloat(loja.latitude),
            lng: parseFloat(loja.longitude)
        });
        */
        $.loja.lojaPin = $.loja.adicionarPin(loja);
        /*
        $.pedido.pins = [];
        pedidos.forEach(function(pedido) {
            if (pedido.latitude !== 0 && pedido.longitude !== 0) {
                bounds.extend({
                    lat: parseFloat(pedido.latitude),
                    lng: parseFloat(pedido.longitude)
                });
                var pin = {
                    id_pedido: pedido.id_pedido,
                    marker: $.pedido.adicionarPin(pedido)
                };
                $.pedido.pins.push(pin);
            }
        });
        */
        //$.mapa.mapa.fitBounds(bounds);

        setInterval(function(){
            var loja = $.loja.lojaAtual;
            $.pedido.listarRetiradaMapeada(loja.id_loja, function (pedidos) {
                if (loja.latitude !== 0 && loja.longitude !== 0) {
                    $.mapa.atualizarMapa(pedidos);
                }
                else {
                    $.error("A Loja não possui latitude e longitude.");
                }
            }, function (erro) {
                $.error(erro);
            });
        }, 10000);
    },
    atualizarMapa: function (pedidos) {
        var loja = $.loja.lojaAtual;
        /*
        var bounds = new google.maps.LatLngBounds();
        bounds.extend({
            lat: parseFloat(loja.latitude),
            lng: parseFloat(loja.longitude)
        });
        */
        var pedidoIds = [];
        console.log('Atualizando Mapa!');
        pedidos.forEach(function(pedido) {
            var posicao = new google.maps.LatLng(
                parseFloat(pedido.latitude),
                parseFloat(pedido.longitude)
            )
            if (posicao.lat && posicao.lng && posicao.lat != 0 && pedido.lng != 0) {
                var encontrou = false;
                $.pedido.pins.forEach(function(pin) {
                    if (pin.id_pedido == pedido.id_pedido) {
                        pin.marker.pedido = pedido;
                        pin.marker.setPosition(posicao);
                        encontrou = true;
                        console.log('Encontrou com ID ' + pedido.id_pedido + '. Alterando!');
                    }
                });
                if (encontrou === false) {
                    console.log('Não encontrou com ID ' + pedido.id_pedido + '. Cadastrando!');
                    var pin = {
                        id_pedido: pedido.id_pedido,
                        marker: $.pedido.adicionarPin(pedido)
                    };
                    $.success('Novo pedido entrou no mapa #' + pedido.id_pedido);
                    $.pedido.pins.push(pin);
                }
                pedidoIds.push(pedido.id_pedido);
                //bounds.extend(posicao);
            }
        });
        var excluir = false;
        var pins = [];
        $.pedido.pins.forEach(function(pin) {
            if (!(pedidoIds.indexOf(pin.id_pedido) >= 0)) {
                $.success('Pedido #' + pin.id_pedido + ' retirado do mapa.');
                pin.marker.setMap(null);
                excluir = true;
            }
            else {
                pins.push(pin);
            }
        });
        if (excluir === true) {
            $.pedido.pins = pins;
        }
        var centro = new google.maps.LatLng(
            parseFloat(loja.latitude),
            parseFloat(loja.longitude)
        );
        $.mapa.mapa.setCenter(centro);
        $.mapa.mapa.setZoom(17);
        //$.mapa.mapa.fitBounds(bounds);
    }
};

function carregarLoja() {
    $.getScript($.pedido.js_path + '/api/richmarker-compiled.js', function () {});
    if ($.loja.id_loja === 0) {
        $.error("ID da loja não foi informado.");
    }
    $.loja.pegar($.loja.id_loja, function(loja) {
        if (loja) {
            $.loja.lojaAtual = loja;
            $.pedido.listarRetiradaMapeada(loja.id_loja, function (pedidos) {
                if (loja.latitude !== 0 && loja.longitude !== 0) {
                    $.mapa.inicializarMapa(loja, pedidos);
                }
                else {
                    $.error("A Loja não possui latitude e longitude.");
                }
            }, function (erro) {
                $.error(erro);
            });
        }
    }, function (erro) {
        $.error(erro);
    });
}

$(document).ready(function() {
    if ($('#mapa').length > 0) {
        $.getScript("https://maps.googleapis.com/maps/api/js?key=" + $.mapa.api_key +
            "&libraries=places,visualization&callback=carregarLoja", function () {
        });
    }
});