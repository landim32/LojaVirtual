$.banner = {

};

$(document).ready(function() {
    $("#destino_loja").click(function (e) {
        $("#div_loja").show();
        $("#div_produto").hide();
        $("#div_url").hide();
        $("#destino_loja").removeClass("btn-default").addClass("btn-primary");
        $("#destino_produto").removeClass("btn-primary").addClass("btn-default");
        $("#destino_url").removeClass("btn-primary").addClass("btn-default");
        $("#cod_destino").val($(this).attr("data-destino"));
        return false;
    });
    $("#destino_produto").click(function (e) {
        $("#div_loja").hide();
        $("#div_produto").show();
        $("#div_url").hide();
        $("#destino_loja").removeClass("btn-primary").addClass("btn-default");
        $("#destino_produto").removeClass("btn-default").addClass("btn-primary");
        $("#destino_url").removeClass("btn-primary").addClass("btn-default");
        $("#cod_destino").val($(this).attr("data-destino"));
        return false;
    });
    $("#destino_url").click(function (e) {
        $("#div_loja").hide();
        $("#div_produto").hide();
        $("#div_url").show();
        $("#destino_loja").removeClass("btn-primary").addClass("btn-default");
        $("#destino_produto").removeClass("btn-primary").addClass("btn-default");
        $("#destino_url").removeClass("btn-default").addClass("btn-primary");
        $("#cod_destino").val($(this).attr("data-destino"));
        return false;
    });
});