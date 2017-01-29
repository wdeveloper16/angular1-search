( function( $ ) {

    function anostat() {
        var ajax_url = site_url + 'stat.php?at=s';
        $.ajax({
            url: ajax_url,
            cache: false,
            success: function( data ){}
        });
    }

    function autocomplete() {
        $('#q').autocomplete( {
            minLength: 2,
            messages: {
                noResults: '',
                results: function(){}
            },
            open: function() {
                var fq = $('#front_query');
                var fqp = fq.position();
                $('.ui-autocomplete').css({
                    'left':fqp.left,
                    'top':Math.round( fqp.top + fq.outerHeight( true ) - 2 ),
                    'width':fq.width() + 'px',
                });
            },
            source: function(request, response){
                var ajax_url = site_url + 'ac.php?term=' + request.term;
                $.ajax({
                    url: ajax_url,
                    cache: false,
                    success: function( data ){
                        r = $.parseJSON( ( data  ) );
                        if ( r.S == '1' ) {
                            response(r.D);
                        }
                    }
                });
            }
        } );
    }

    function magnifier() {
        $('#front_query').on('submit',function(){
            $('#search').css('background-position','1px 0');
            $('#search').css('display','inline-block');
            $('#search').offsetHeight;
            $('#search').css('display','block');
        } );
    }

    $(document).ready( function(){
        $('#page_content').show();
        magnifier();
		  autocomplete();
        anostat();
    });

} ) ( jQuery );