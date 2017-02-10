app.controller('searchInputController', function ($scope, $state, Results, $timeout) {

		$scope.q = $state.params.q ? $state.params.q : '';

		$timeout(function(){
			$('#q').focus();
		},600);

		// $scope.theme = Math.floor(Math.random() * 3) + 1;

		// var now = new Date();
		// var start = new Date(now.getFullYear(), 0, 0);
		// var diff = now - start;
		// var oneDay = 1000 * 60 * 60 * 24;
		// var day = Math.floor(diff / oneDay);

		// $scope.theme = Math.floor(day % 3);


		function doRequest() {
			if ($scope.q.length > 2) {
				Results.getAutocomplete($scope.q).then(function (result) {
                    $scope.suggestions = [];
                    for(var i = 0; i < result.data.suggestionGroups[0].searchSuggestions.length; i++){
                        $scope.suggestions.push(result.data.suggestionGroups[0].searchSuggestions[i].displayText);
                    }
				});
			}
		}

		$scope.updateSuggestions = _.throttle(doRequest, 500, {
			'leading':  false,
			'trailing': true
		});

		$scope.useSuggestion = function (q) {
			//window.location = '?q=' + q;
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
