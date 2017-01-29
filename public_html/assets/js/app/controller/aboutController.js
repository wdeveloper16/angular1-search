app.controller('aboutController', function ($scope, $state, $http) {

	$scope.q = '';

	// gives another movie array on change
	$scope.updateSuggestions = function (typed) {
		return $http.get(site_url + 'ac.php?term=' + typed).then(function (results) {
			$scope.suggestions = results.data.D;
		});
	};

	$scope.useSuggestion = function (q) {
		$state.go('results_web', {
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
	};
});