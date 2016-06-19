$(function() {
	function cb(start, end) {
        var start = start.format('YYYY/MM/DD');
        var end = end.format('YYYY/MM/DD');
        var target = $('#reportrange span');
        target.html(start + ' - ' + end);
        target.closest('div').find( '[data-bind="input"][name="start"]' ).val(start);
        target.closest('div').find( '[data-bind="input"][name="end"]' ).val(end);
    }
    cb(moment().subtract(29, 'days'), moment());

    $('#reportrange').daterangepicker({
        opens: 'center',
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);


    $( document.body ).on( 'click', '.dropdown-menu li', function( event ) {

       var $target = $( event.currentTarget );

       $target.closest('div')
          .find( '[data-bind="label"]' ).text( $target.text() )
             .end()
          .children( '.dropdown-toggle' ).dropdown( 'toggle' );

       $target.closest('div')
          .find( '[data-bind="input"]' ).val( $target.text() );

       return false;

    });
    
});
