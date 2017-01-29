app.directive('smoothSlider', [
	function () {
		return {
			restrict: 'EAC',
			link:     function ($scope, $element, $attrs) {

				window.setTimeout(function () {
					$($element).imagesLoaded(function () {
						$($element).smoothTouchScroll({
							continuousScrolling: true,

							hotSpotScrolling:          false,
							touchScrolling:            true,
							manualContinuousScrolling: true,
							mousewheelScrolling:       false
						});
					});
				}, 100);

				// $element.bind('$destroy', function () {
				// 	if ($attrs.fill === 'watch') {
				// 		$(window).off('resize', heightFixer);
				// 	}
				// 	heightFixer = void 0;
				// });
			}
		}
	}
]);