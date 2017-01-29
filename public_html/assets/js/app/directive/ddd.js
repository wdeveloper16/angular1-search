//requires https://github.com/FrDH/jQuery.dotdotdot
app.directive('ddd', function(){
		return {
			restrict: 'A',
			link: function(scope, element, attributes) {
				scope.$watch(function() {
					element.dotdotdot({
						watch: 'window'
					});
				});
			}
		}
	});
