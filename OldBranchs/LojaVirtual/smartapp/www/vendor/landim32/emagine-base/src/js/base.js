var _telefone = null;

$.app = {
    base_path: "/"
};

$.app.mudarPagina = function (url) {

    if (url == null || url == '') {
        url = '#dashboard';
    }

    $('a.list-group-item').removeClass('active');
    $('a.list-group-item[href$="' + url + '"]').addClass('active');

    $('#navbar a').removeClass('active');
    $('#navbar a[href$="' + url + '"]').addClass('active');

    var ajaxUrl = $.app.base_path + "/ajax/" + url.substr(1);
    $.get( ajaxUrl, function( data ) {
        $( "#main-content" ).html( data );
        $( document ).trigger( "reload" );
    }).fail(function (request, status, error) {
        $( "#main-content" ).html( request.responseText );
    });
}

$.confirm = function (text, callback) {
    var n = new Noty({
        text: '<i class="fa fa-warning"></i> ' + text,
        type: 'info',
        theme: 'relax',
        dismissQueue: false,
        modal: true,
        buttons: [
            Noty.button('<i class="fa fa-check"></i> Sim', 'btn btn-success', function () {
                n.close();
                callback();
            }),
            Noty.button('<i class="fa fa-ban"></i> Não', 'btn btn-danger', function () {
                n.close();
            })
        ]
    }).show();
};

$('a.confirm').click(function() {
    var vhref = $(this).attr('href');
    var dataMsg = $(this).attr('data-msg');
    if (!dataMsg) {
        dataMsg = 'Tem certeza?';
    }
    $.confirm(dataMsg, function () {
        location.href = vhref;
    });
    return false;
});

$.success = function (text) {
    new Noty({
        text: text,
        type: 'success',
        timeout: 4000
    }).show();
};

$.info = function (text) {
    new Noty({
        text: text,
        type: 'info',
        timeout: 25000
    }).show();
};

$.error = function (text) {
    new Noty({
        text: text,
        type: 'error',
        timeout: 8000
    }).show();
};

function strToNumber(valor){
    valor = '' + valor;
    if(valor === ''){
        valor =  0;
    }
    else{
        valor = valor.replace('.','');
        valor = valor.replace('.','');
        valor = valor.replace(',','.');
        valor = parseFloat(valor);
    }
    return valor;
}

function numberToStr(valor){
    var inteiro = null, decimal = null, c = null, j = null;
    var aux = new Array();
    valor = ""+valor;
    c = valor.indexOf(".",0);
    //encontrou o ponto na string
    if(c > 0){
        //separa as partes em inteiro e decimal
        inteiro = valor.substring(0,c);
        decimal = valor.substring(c+1,valor.length);
    }
    else {
        inteiro = valor;
    }

    //pega a parte inteiro de 3 em 3 partes
    for (j = inteiro.length, c = 0; j > 0; j-=3, c++){
        aux[c]=inteiro.substring(j-3,j);
    }

    //percorre a string acrescentando os pontos
    inteiro = "";
    for(c = aux.length-1; c >= 0; c--){
        inteiro += aux[c]+'.';
    }
    //retirando o ultimo ponto e finalizando a parte inteiro

    inteiro = inteiro.substring(0,inteiro.length-1);

    decimal = parseInt(decimal);
    if(isNaN(decimal)){
        decimal = "00";
    }
    else {
        decimal = ""+decimal;
        if(decimal.length === 1){
            decimal = decimal+"0";
        }
        if(decimal.length > 2)
            decimal = decimal.substr(0, 2);
    }
    valor = "R$ "+inteiro+","+decimal;
    return valor;
}


$( document ).on( "reload", function( e ) {

    $('[data-toggle="tooltip"]').tooltip();

    if ($(".datepicker").length > 0) {
        $("#main-content").find(".datepicker").datepicker({
            defaultDate: +7,
            showOtherMonths: true,
            autoSize: true,
            dateFormat: 'dd/mm/yy',
            monthNames: ['janeiro', 'fevereiro', 'março', 'abril', 'mai', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'],
            monthNamesShort: ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"],
            dayNames: ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"],
            dayNamesMin: ['do', 'se', 'te', 'qa', 'qi', 'sx', 'sb'],
            today: 'hoje',
            clear: 'excluir',
            format: 'dddd, d !de mmmm !de yyyy',
            altFormat: 'yyyy-mm-dd'
        });
    }

    $('input[type=text].money').maskMoney({allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $('.telefone').blur(function () {
        _telefone = $(this);
        setTimeout(function() {
            var v = _telefone.val();
            v = v.toString();
            v = v.replace(/[^0-9]/g,"");
            v = v.replace(/^(\d{2})(\d)/g,"($1) $2");
            v = v.replace(/(\d)(\d{4})$/,"$1-$2");
            _telefone.val(v);
        });
    });

    $('input[type=email],input[type=text].email').blur(function () {
        if (!validateEmail($(this).val())) {
            $.error("Email inválido.");
            $(this).focus();
        }
    });

    $(".tagsinput").tagsinput({
        allowDuplicates: false,
        tagClass: 'label label-default'
    });

    $('form.form-ajax').submit(function(e) {
        e.preventDefault();

        var $btn = $(this).find(".loading-btn");
        $btn.button('loading');

        var url = $(this).attr('action');
        var sucesso = $(this).attr('data-success');
        if (!sucesso) {
            sucesso = "Cadastro efetuado com sucesso.";
        }
        var dataForm = $(this).serialize();
        $.ajax({
            type: "POST",
            url: url,
            data: dataForm,
            success: function(data) {
                $btn.button('reset');
                $.success(sucesso);
            },
            error: function (request, status, error) {
                $btn.button('reset');
                $.error(request.responseText);
            }
        });
        return false;
    });
});

function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

$(window).on('hashchange', function() {
    $.app.mudarPagina(location.hash);
    return true;
});

$(document).ready(function() {

    NProgress.configure({ trickleSpeed: 200 });
    $(document).ajaxStart(function () {
        NProgress.start();
    });
    $(document).ajaxStop(function () {
        NProgress.done();
    });

    /*
    $('.loading-btn').on('click', function () {
        var $btn = $(this).button('loading');
        $btn.button('reset');
    });
    */

    if ($(".datepicker").length > 0) {
        $(".datepicker").datepicker({
            defaultDate: +7,
            showOtherMonths: true,
            autoSize: true,
            dateFormat: 'dd/mm/yy',
            monthNames: ['janeiro', 'fevereiro', 'março', 'abril', 'mai', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'],
            monthNamesShort: ["jan", "fev", "mar", "abr", "mai", "jun", "jul", "ago", "set", "out", "nov", "dez"],
            dayNames: ["domingo", "segunda", "terça", "quarta", "quinta", "sexta", "sábado"],
            dayNamesMin: ['do', 'se', 'te', 'qa', 'qi', 'sx', 'sb'],
            today: 'hoje',
            clear: 'excluir',
            format: 'dddd, d !de mmmm !de yyyy',
            altFormat: 'yyyy-mm-dd'
        });
    }

    if (location.hash && location.hash != '') {
        $.app.mudarPagina(location.hash);
    }
    else {
        $(document).trigger("reload");
    }
});