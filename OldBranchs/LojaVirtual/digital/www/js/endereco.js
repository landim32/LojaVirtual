$.endereco = {
    pegar: function () {
        return JSON.parse(localStorage.getItem("endereco"));
    },
    gravar: function (endereco) {
        //alert(JSON.stringify(endereco));
        localStorage.setItem("endereco", JSON.stringify(endereco));
    },
    carregar: function(link) {
        return {
            id_endereco: $(link).attr('data-id'),
            logradouro: $(link).attr('data-logradouro'),
            complemento: $(link).attr('data-complemento'),
            numero: $(link).attr('data-numero'),
            bairro: $(link).attr('data-bairro'),
            cidade: $(link).attr('data-cidade'),
            uf: $(link).attr('data-uf'),
            cep: $(link).attr('data-cep'),
            latitude: $(link).attr('data-latitude'),
            longitude: $(link).attr('data-longitude')
        };
    },
    formatarTexto: function (endereco) {
        var str = endereco.logradouro;
        if (endereco.complemento && endereco.complemento !== '') {
            str += ', ' + endereco.complemento;
        }
        if (endereco.numero && endereco.numero !== '') {
            str += ', ' + endereco.numero;
        }
        if (endereco.bairro && endereco.bairro !== '') {
            str += ', ' + endereco.bairro;
        }
        if (endereco.cidade && endereco.cidade !== '') {
            str += ', ' + endereco.cidade;
        }
        if (endereco.uf && endereco.uf !== '') {
            str += ', ' + endereco.uf;
        }
        return str;
    },
    atualizar: function () {
        var endereco = $.endereco.pegar();
        if (endereco && endereco != null) {
            if ($(".endereco-atual").length > 0) {
                //alert(JSON.stringify(endereco));
                var str = $.endereco.formatarTexto(endereco);
                $(".endereco-atual").find('span.completo').html(str);
                $(".endereco-atual").show();
            }
            $("div.panel-endereco").removeClass("panel-primary");
            $("div.panel-endereco").addClass("panel-default");
            $("div#endereco-" + endereco.id_endereco + ".panel-endereco").removeClass("panel-default");
            $("div#endereco-" + endereco.id_endereco + ".panel-endereco").addClass("panel-primary");
            $("input#id_endereco[type=hidden]").val(endereco.id_endereco);
        }
        else {
            $(".endereco-atual").hide();
        }
    }
};

$(document).ready(function() {
    $(".endereco").click(function (e) {
        var endereco = $.endereco.carregar(this);
        $.endereco.gravar(endereco);
        return true;
    });
    $(".endereco-mudar").click(function (e) {
        e.preventDefault();
        var endereco = $.endereco.carregar(this);
        $.endereco.gravar(endereco);
        $.endereco.atualizar();
        return false;
    });
    $.endereco.atualizar();
});