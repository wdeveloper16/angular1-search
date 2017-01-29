(function ($) {

	function setup_front_image() {
		wh = $(window).height();
		fih = $('#front_image').height();
		if (wh > fih) {
			mt = Math.round(( wh - fih ) / 2);
		}
		else {
			mt = 0;
		}
		$('#front_image').css('margin-top', mt + 'px');
	}

	function setup_sizes() {
		$('#q').width($('#front_query').innerWidth() - $('#search').outerWidth(true) - 8);
	}

	function vertically() {
		var t = Math.round(( $('#front_image').height() / 2 ) - $('#logo').height() - $('#front_query').outerHeight(true) + ( $('#q').height() / 2 ));
		if (t > 0) {
			$('#front_logo').css('margin-top', t + 'px');
		}
		else {
			$('#front_logo').css('margin-top', 0);
		}
	}

	function manage_sizes() {
		setup_front_image();
		setup_sizes();
		vertically();
	}

	function autofocus() {
		var q = $('#q');
		if (q.attr('data-autofocus') == 'true') {
			q.focus();
			var t = q.val();
			q.val('');
			q.val(t);
		}
	}

	function magnifier() {
		$('#front_query').on('submit', function () {
			$('#search').css('background-position', '1px 0');
			$('#search').css('display', 'inline-block');
			$('#search').offsetHeight;
			$('#search').css('display', 'block');
		});
	}

	function show_more(id) {
		$('.drop_place').hide();
		var di = $('#drop_content');
		var dl = $('#buttons').position();
		di.css('top', ( dl.top + $('#dropList').outerHeight() + 4 ) + 'px');
		$('#' + id + '_content').show();
		di.show();
		di.on('mouseleave', function () {
			di.hide();
		});
	}

	function menu() {
		$('.drop_button').on('mouseenter', function (e) {
			show_more($(this).attr('id'));
		});
		$('.drop_button').on('click', function (e) {
			e.preventDefault();
			show_more($(this).attr('id'));
		});
	}

	function autocomplete() {
		$('#q').autocomplete({
			minLength: 2,
			messages:  {
				noResults: '',
				results:   function () {
				}
			},
			open:      function () {

				var fq = $('#front_query');
				var fqp = fq.position();
				console.log(fqp);

				//$('.ui-autocomplete').css({
				//	'left':  fqp.left,
				//	'top':   Math.round(fqp.top + fq.outerHeight(true) - 2),
				//	'width': fq.width() + 'px'
				//});

			},
			source:    function (request, response) {
				var ajax_url = site_url + 'ac.php?term=' + request.term;
				$.ajax({
					url:     ajax_url,
					cache:   false,
					success: function (data) {
						r = $.parseJSON(( data  ));
						if (r.S == '1') {
							response(r.D);
						}
					}
				});
			}
		});
	}

	function anostat() {
		var ajax_url = site_url + 'stat.php?at=f';
		$.ajax({
			url:     ajax_url,
			cache:   false,
			success: function (data) {
			}
		});
	}

	$(document).ready(function () {
		//$('#page_content').show();
		manage_sizes();
		menu();
		autofocus();
		magnifier();
		autocomplete();
		anostat();
	});

	//$(window).resize( _.debounce( function() {
	//        manage_sizes();
	//}, 150 ) );

})(jQuery);