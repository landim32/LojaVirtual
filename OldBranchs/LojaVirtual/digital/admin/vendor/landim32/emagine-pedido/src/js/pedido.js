$.pedido = {
    js_path: null,
    pins: [],
    infowindow: null,
    listarEntregando: function (id_loja, sucesso, falha) {
        var url = $.app.base_path + '/api/pedido/listar-entregando/' + id_loja;
        $.ajax({
            method: 'GET',
            url: url,
            success: function (dado) {
                sucesso(dado);
            },
            error: function (request, status, error) {
                falha(request.responseText);
            }
        });
    },
    listarRetiradaMapeada: function (id_loja, sucesso, falha) {
        var url = $.app.base_path + '/api/pedido/listar-retirada-mapeada/' + id_loja;
        $.ajax({
            method: 'GET',
            url: url,
            success: function (dado) {
                sucesso(dado);
            },
            error: function (request, status, error) {
                falha(request.responseText);
            }
        });
    },
    getInfoWindow: function(pedido) {
        var urlPedido = $.app.base_path + '/pedido/' + pedido.loja.slug + '/id/' + pedido.id_pedido;
        var str = '';
        str += '<div class="content">';
        str += '<h4 class="text-center"><a href="' + urlPedido + '">Pedido #' + pedido.id_pedido + '</a></h4>';
        str += '<i class="fa fa-fw fa-user-circle"></i> <span>' + pedido.usuario.nome + '</span><br />';
        str += '<i class="fa fa-fw fa-envelope"></i> <span>' + pedido.usuario.email + '</span><br />';
        str += '<i class="fa fa-fw fa-phone"></i> <span>' + pedido.usuario.telefone_str + '</span><br />';
        str += '<strong>Loja:</strong> <span>' + pedido.loja.nome + '</span><br />';
        str += '<strong>Endereço:</strong> <span>' + pedido.loja.endereco_completo + '</span><br />';
        if (pedido.distancia_str) {
            str += '<strong>Distância:</strong> <span>' + pedido.distancia_str + '</span><br />';
        }
        if (pedido.tempo_str) {
            str += '<strong>Tempo:</strong> <span>' + pedido.tempo_str + '</span><br />';
        }
        str += '<strong>Total:</strong> <span>R$ ' + pedido.total_str + '</span><br />';
        str += '</div>';

        return new google.maps.InfoWindow({
            content: str
        });
    },
    adicionarPin: function(pedido) {
        var pinColor = 'ff0000';
        /*
        var pinImage = new google.maps.MarkerImage(
            "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
            new google.maps.Size(21, 34),
            new google.maps.Point(0,0),
            new google.maps.Point(10, 34)
        );
        var pinShadow = new google.maps.MarkerImage(
            "http://chart.apis.google.com/chart?chst=d_map_pin_shadow",
            new google.maps.Size(40, 37),
            new google.maps.Point(0, 0),
            new google.maps.Point(12, 35)
        );
        var marker = new google.maps.Marker({
            position: {
                lat: parseFloat(pedido.latitude),
                lng: parseFloat(pedido.longitude)
            },
            map: $.mapa.mapa,
            title: '#' + pedido.id_pedido,
            icon: pinImage,
            shadow: pinShadow,
            pedido: pedido
        });
        */
        var marker = new RichMarker({
            map: $.mapa.mapa,
            shadow: 'none',
            position: new google.maps.LatLng(
                pedido.latitude,
                pedido.longitude
            ),
            pedido: pedido,
            content: '<div><div class="pin pin-danger">#' + pedido.id_pedido + '</div></div>'
        });
        marker.addListener('click', function() {
            if ($.pedido.infowindow != null) {
                $.pedido.infowindow.close();
            }
            $.pedido.infowindow = $.pedido.getInfoWindow(this.pedido);
            $.pedido.infowindow.open($.mapa.mapa, marker);
        });
        return marker;
    }
};