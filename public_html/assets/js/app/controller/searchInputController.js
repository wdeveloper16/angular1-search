app.controller('searchInputController', function ($scope, $state, Results, $timeout) {

		$scope.q = $state.params.q ? $state.params.q : '';

		$timeout(function(){
			$('#q').focus();
		}, 600);

		$scope.updateSuggestions = function () {
			if ($scope.q.length > 0) {
				Results.getAutocomplete($scope.q).then(function (result) {
					$scope.suggestions = result.data;
				})
			}
			else {
				$scope.suggestions = [];
			}
		};

		$scope.useSuggestion = function (q) {
			$state.go('results', {
				'q':   q,
				'tab': 'web'
			});
		};

		$scope.submit = function () {
			if (!_.isUndefined($scope.q) && $scope.q.length > 0) {
				$state.go('results', {
					'q':   $scope.q,
					'tab': 'web'
				});
			}
			$('#q').blur();
		};
	}
)
;
