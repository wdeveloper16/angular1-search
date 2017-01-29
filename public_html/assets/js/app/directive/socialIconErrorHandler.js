app.directive('onError', function() {
	return {
		restrict:'A',
		require: 'ngModel',
		scope: {
			model: '=ngModel',
		},
		link: function(scope, element, attr) {
			element.on('error', function() {
				scope.model.avatarUrl = false;
			})
		}
	}
});