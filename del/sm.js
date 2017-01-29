( function( $ ) {

    function setup_sizes() {
    }

    function manage_sizes() {
        setup_sizes();
    }

    function manage_menus() {
        $('#menu').on( 'click', function( e ) {
            e.preventDefault();
            show_upper_menu();
        } );
    }

    function manage_clicks() {
        $('#scrollTop').on( 'click', function( e ) { window.scrollTo(0,0); } );
    }

    function show_upper_menu() {
        var d = $('#menu_content').css('display');
        var m = $('#menu');
        if ( d == 'none' ) {
            m.css('background-position','0 -14px');
            var o = m.offset();
            var nt = o.top - 14;
            var nl = o.left - $('#menu_content').outerWidth() + m.outerWidth();
            $('#menu_content').css( {
                top : nt + 'px',
                left : nl + 'px',
                display : 'block',
            } );
            $('#menu_content').bind('mouseleave', function(){
                m.css('background-position','0 0');
                $('#menu_content').css( {
                    display : 'none',
                } );
            });
        } else {
            m.css('background-position','0 0');
            $('#menu_content').css( {
                display : 'none',
            } );
        }
    }

    function autofocus() {
        var q = $('#q');
        if ( q.attr('data-autofocus') == 'true' ) {
            q.focus();
            var t = q.val();
            q.val('');
            q.val(t);
        }
    }

    function magnifier() {
        $('#front_query').on('submit',function(){
            $('#search').css('background-position','1px 0');
            $('#search').css('display','inline-block');
            $('#search').offsetHeight;
            $('#search').css('display','block');
        } );
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

    function anostat() {
        var ajax_url = site_url + 'stat.php?at=q';
        $.ajax({
            url: ajax_url,
            cache: false,
            success: function( data ){}
        });
    }

    $.fn.sih = function( maxh ) {
        this.children().each( function() {
            maxh = typeof maxh !== 'undefined' ? maxh : 80;
            h = $(this).height();
            if ( h > maxh ) { h = maxh; }
            if ( h > 28 ) {
                mt = Math.round((h-28)/2);
                $('.socialIcon',$(this)).css('margin-top',mt);
            }
        } );
        return this;
    };

    $(document).ready( function(){
        $('#page_content').show();
        manage_sizes();
        manage_menus();
        manage_clicks();
        autofocus();
        magnifier();
        autocomplete();
        anostat();
    });

    $(window).resize( _.debounce( function() {
            manage_sizes();
    }, 150 ) );


} ) ( jQuery );