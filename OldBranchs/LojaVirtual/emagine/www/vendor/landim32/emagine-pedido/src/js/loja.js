$.loja = {
    id_loja: 0,
    lojaAtual: null,
    lojaPin: null,
    infowindow: null,
    pegar: function(id_loja, sucesso, falha) {
        var url = $.app.base_path + '/api/loja/pegar/' + id_loja;
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
    getInfoWindow: function(loja) {
        var str = '';
        str += '<div class="content">';
        str += '<h4 class="text-center">' + loja.nome + '</h4>';
        str += '<p>';
        str += '<span>' + loja.endereco_completo + '</span>';
        str += '</p>';
        str += '</div>';
        /*
        str += '<div class="content" style="min-width: 400px">';
        str += '<h4 class="text-center">';
        str += '<a href="#">' + prospeccao.nome + ' #' + prospeccao.id_prospeccao  +  ' <i class="fa fa-pencil"></i></a>';
        str += '</h4>';
        str += '<dl class="dl-horizontal" style="font-size: 80%; margin-bottom: 0px;">\n';
        if (prospeccao.id_prospeccao > 0) {
            str += '  <dt>Número:</dt>\n';
            str += '  <dd>' + prospeccao.id_prospeccao + '</dd>\n';
        }
        if (prospeccao.id_contato > 0) {
            str += '  <dt>Contato:</dt>\n';
            str += '  <dd>' + prospeccao.contato.imobiliaria + '</dd>\n';
            str += '  <dt>Responsável:</dt>\n';
            str += '  <dd>' + prospeccao.contato.nome + '</dd>\n';
            str += '  <dt>Email:</dt>\n';
            str += '  <dd>' + prospeccao.contato.email + '</dd>\n';
            str += '  <dt>Telefone:</dt>\n';
            str += '  <dd>' + prospeccao.contato.telefone + '</dd>\n';
        }
        if (prospeccao.area_nra > 0) {
            str += '  <dt>Área NRA (m<sup>2</sup>):</dt>\n';
            str += '  <dd>' + prospeccao.area_nra + ' m<sup>2</sup></dd>\n';
        }

        if (prospeccao.cod_situacao > 0) {
            str += '  <dt>Status:</dt>\n';
            str += '  <dd>' + prospeccao.situacao + '</dd>\n';
        }
        if (prospeccao.cod_tipo > 0) {
            str += '  <dt>Tipo:</dt>\n';
            str += '  <dd>' + prospeccao.tipo + '</dd>\n';
        }
        if (prospeccao.cod_segmento > 0) {
            str += '  <dt>Segmento:</dt>\n';
            str += '  <dd>' + prospeccao.segmento + '</dd>\n';
        }
        str += '  <dt>Data do registro:</dt>\n';
        str += '  <dd>' + prospeccao.data_inclusao_str + '</dd>\n';
        str += '  <dt>Última Alteração:</dt>\n';
        str += '  <dd>' + prospeccao.ultima_alteracao_str + '</dd>\n';

        if (!(!prospeccao.pasta || prospeccao.pasta.length === 0)) {
            str += '  <dt>Pasta:</dt>\n';
            str += '  <dd>' + prospeccao.pasta + '</dd>\n';
        }
        if (prospeccao.area_terreno > 0) {
            str += '  <dt>Área Terreno (m<sup>2</sup>):</dt>\n';
            str += '  <dd>' + prospeccao.area_terreno + '</dd>\n';
        }
        str += '</dl>';
        if (prospeccao.diarios && prospeccao.diarios.length > 0) {
            str += '<table class="diarios table table-striped table-hover table-responsive" style="font-size: 80%"><thead>';
            str += '<tr>';
            str += '<th>Data</th>';
            str += '<th>Texto</th>';
            str += '<th>Usuário</th>';
            str += '</tr>';
            str += '</thead><tbody>';
            prospeccao.diarios.forEach(function(diario) {
                str += '<tr>';
                str += '<td>' + diario.data_inclusao_str + '</td>';
                str += '<td>' + diario.mensagem + '</td>';
                str += '<td>' + diario.usuario.nome + '</td>';
                str += '</tr>';
            });
            str += '</tbody>';
            str += '</table>';
        }

        str += '<div class="text-right">';
        str += '<a href="#" onclick="return $.prospeccao.abrirDiario(' + prospeccao.id_prospeccao + ');" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Novo diário</a> ';
        str += '<a href="#" onclick="return $.prospeccao.removerMarker(' + prospeccao.id_prospeccao + ');" class="btn btn-xs btn-danger"><i class="fa fa-remove"></i> Remover</a>';
        str += '</div>';
        str += '</div>';
        */

        return new google.maps.InfoWindow({
            content: str
        });
    },
    adicionarPin: function(loja) {
        var pinColor = 'ff0000';
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
            /*
            position: {
                lat: parseFloat(loja.latitude),
                lng: parseFloat(loja.longitude)
            },
            */
            position: new google.maps.LatLng(
                loja.latitude,
                loja.longitude
            ),
            map: $.mapa.mapa,
            title: loja.nome,
            icon: pinImage,
            shadow: pinShadow
        });
        /*
        var marker = new RichMarker({
            map: $.mapa.mapa,
            shadow: 'none',
            position: new google.maps.LatLng(
                loja.latitude,
                loja.longitude
            ),
            loja: loja,
            content: '<div><div class="pin pin-primary">' + loja.nome + '</div></div>'
        });
        marker.addListener('click', function() {
            if ($.loja.infowindow != null) {
                $.loja.infowindow.close();
            }
            $.loja.infowindow = $.loja.getInfoWindow(loja);
            $.loja.infowindow.open($.mapa.mapa, marker);
        });
        */
        return marker;
    }
};