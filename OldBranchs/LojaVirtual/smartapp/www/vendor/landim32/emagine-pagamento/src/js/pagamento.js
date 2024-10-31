function bandeiraCartao(number)
{
    // visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "visa";

    // Mastercard
    // Updated for Mastercard 2017 BINs expansion
    if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number))
        return "mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "amex";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "diners";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "jcb";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "visa";

    return "";
}

$(document).ready(function() {
    $('#forma_pagamento_credito').bind('change', function (e) {
        $('#boleto').hide();
        $('#dinheiro').hide();
        $('#cartao').hide();
        $('#credito').show();
        $('#deposito').hide();
    });
    $('#forma_pagamento_debito').bind('change', function (e) {
        $('#boleto').hide();
        $('#dinheiro').hide();
        $('#cartao').hide();
        $('#credito').show();
        $('#deposito').hide();
    });
    $('#forma_pagamento_boleto').bind('change', function (e) {
        $('#dinheiro').hide();
        $('#credito').hide();
        $('#cartao').hide();
        $('#boleto').show();
        $('#deposito').hide();
    });
    $('#forma_pagamento_dinheiro').bind('change', function (e) {
        $('#credito').hide();
        $('#boleto').hide();
        $('#cartao').hide();
        $('#dinheiro').show();
        $('#deposito').hide();
    });
    $('#forma_pagamento_cartao').bind('change', function (e) {
        $('#credito').hide();
        $('#boleto').hide();
        $('#dinheiro').hide();
        $('#cartao').show();
        $('#deposito').hide();
    });
    $('#forma_pagamento_deposito').bind('change', function (e) {
        $('#credito').hide();
        $('#boleto').hide();
        $('#dinheiro').hide();
        $('#cartao').hide();
        $('#deposito').show();
    });
    $("#numero_cartao").keypress(function (e) {
        $(this).val(function(i, valor) {
            var valor = valor.replace(/[^\d]/g, '').match(/.{1,4}/g);
            var valor = valor ? valor.join(' ') : '';
            var bandeira = bandeiraCartao(valor.replace(/\D/g, ''));
            $('#bandeira').val(bandeira);
            switch (bandeira) {
                case 'visa':
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-cc-visa');
                    break;
                case 'mastercard':
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-cc-mastercard');
                    break;
                case 'amex':
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-cc-amex');
                    break;
                case 'discover':
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-cc-discover');
                    break;
                case 'diners':
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-cc-diners-club');
                    break;
                case 'jcb':
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-cc-jcb');
                    break;
                default:
                    $('#bandeira_icone i').attr('class', 'fa fa-fw fa-credit-card');
                    break;
            }
            return valor;
        });
    });
});