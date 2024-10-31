$( document ).on( "reload", function( e ) {
    $('.fullcalendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
        defaultDate: '2017-05-12',
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventResize: function (event, delta, revertFunc) {
            $.success("Escala alterada com sucesso.");
        },
        eventDrop: function (event, delta, revertFunc) {
            $.success("Escala alterada com sucesso.");
            /*
             if (!confirm("Are you sure about this change?")) {
             revertFunc();
             }
             */
        },
        eventLimit: true, // allow "more" link when too many events
        //url: '/myfeed.php',
        events: [
            {
                title: 'ALVES (Guarda, Cb Guarda)',
                start: '2017-05-01'
            },
            {
                title: 'ARNALDO (Guarda, Sentinela)',
                start: '2017-05-07',
                end: '2017-05-10'
            },
            {
                //id: 999,
                title: 'ARTHUR (Guarda, Sgt Dia)',
                start: '2017-05-09T16:00:00'
            },
            {
                title: 'FAGUNDES (Guarda, Sentinela)',
                start: '2017-05-11',
                end: '2017-05-13'
            }
        ]
    });
});