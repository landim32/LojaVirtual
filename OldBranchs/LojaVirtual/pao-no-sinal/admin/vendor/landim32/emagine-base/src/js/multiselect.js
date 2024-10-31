$( document ).on( "reload", function( e ) {
    $('.multiselect').multiselect({
        templates: {
            //button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"></button>',
            ul: '<ul class="multiselect-container dropdown-menu"></ul>',
            filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
            filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="fa fa-remove"></i></button></span>',
            //li: '<li><a href="javascript:void(0);"><label></label></a></li>',
            //divider: '<li class="multiselect-item divider"></li>',
            //liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
        },
        numberDisplayed: 6,
        nonSelectedText: 'Nenhum selecionado',
        allSelectedText: 'Todos selecionados',
        enableFiltering: true,
        includeSelectAllOption: true,
        //buttonWidth: '400px',
        maxHeight: 300,
        dropUp: true
    });
});