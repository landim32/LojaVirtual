
$(document).ready(function() {
    $("input[type=text].autocomplete").each(function( index ) {
        var nome = $(this).attr('data-nome');
        if (!nome) {
            nome = 'name';
        }
        var sourceUrl = $(this).attr('data-url');
        if (!sourceUrl) {
            $.error("URL de autocomplete não informada.");
        }
        var input = this;
        $.get(sourceUrl, function(data) {
            //alert(nome + '|' + JSON.stringify(data));
            $(input).typeahead({
                source: data,
                displayText: function (item) {
                    return item[nome];
                },
                afterSelect: function (item) {
                    alert(JSON.stringify(item));
                },
                autoSelect: true
            });
        },'json');
    });

});