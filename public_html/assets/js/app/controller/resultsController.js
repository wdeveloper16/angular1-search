app.controller('resultsController', function ($scope, $state, Results, $http, $sce, $rootScope) {
	$scope.q = $state.params.q;
	$scope.$rootScope = $rootScope;

	$scope.showGoToTop = false;
	$scope.goToTop = function () {
		$("html, body").animate({scrollTop: 0}, "2000", 'linear');
	};

	$('#q').blur();

	function isWikiLink(url) {
		var regExpRule = /^(http[s]{0,1}:\/\/)([a-z]+)(\.wikipedia\.org\/wiki\/)(.+)$/;
		var matches    = url.match(regExpRule);

		if (_.isNull(matches)) {
			regExpRule = /^(http[s]{0,1}:\/\/)([a-z]+)(\.m.wikipedia\.org\/wiki\/)(.+)$/;
			matches    = url.match(regExpRule);
		}

		if (!_.isNull(matches)) {
			//#echo 'URL: ' . $this->to_utf8( $url ) . '<br />';
			var wiki_info = {
				'url':        url,
				'lang':       matches[2],
				'title':      matches[4],
				'wiki_title': matches[4].replace(/_/g, ' ')
			};

			//#echo '<pre>'.print_r($this->wiki_info,true).'</pre>';
			return wiki_info;
		}

		return false;
	}

	$scope.loading     = true;
	$scope.wikiLoading = false;
	$scope.openImage = function(url){
		window.location = url;
	};

	switch ($state.params.tab) {
		case 'web':
			var term = angular.copy($scope.q);

			//get web results
			Results.get(term, 'web').then(function (data) {
				$scope.results = _.isUndefined(data.webPages) ? [] : data.webPages.value;
				$scope.images  = _.isUndefined(data.images) ? [] : data.images.value;
				$scope.loading = false;

				$scope.showGoToTop = $scope.results.length > 0;

				//find wiki and get it
				$scope.wikiMatch = false;
				_.each($scope.results, function (v, i) {
					$scope.wikiMatch = isWikiLink(v.displayUrl);
					if ($scope.wikiMatch !== false) {
						return false;
					}
				});

				if ($scope.wikiMatch) {
					$scope.wikiLoading = true;
					Results.post(term, 'wikipedia', $scope.wikiMatch).then(function (data) {
						$scope.wikiLoading = false;
						data.extract       = $sce.trustAsHtml(data.extract);
						$scope.wikipedia   = data;

						Results.post(term, 'wikiimages', {
							lang:   $scope.wikiMatch.lang,
							pageid: data.pageid
						}).then(function (data) {
							_.each(data, function (v) {
								$scope.images.push({
									'thumbnailUrl': v
								})
							});
						});
					});
				}
			});

			Results.getSocial(term).then(function (data) {
				$scope.socialResults = data;
				window.setTimeout(function () {
					new Swiper('#social_swiper', {
						// Optional parameters
						direction: 'horizontal',
						loop:      true
					});
				}, 150);
			});

			break;

		case 'images':
			Results.get($scope.q, 'images').then(function (data) {
				$scope.loading = false;
				//$scope.images = _.isUndefined(data.images) ? [] : data.images.value;
				$scope.images  = _.isUndefined(data.value) ? [] : data.value;
				$scope.showGoToTop = $scope.images.length > 0;
				console.log($scope.showGoToTop);
			});
			break;

		case 'videos':
			Results.get($scope.q, 'videos').then(function (data) {
				$scope.loading = false;
				$scope.videos  = _.isUndefined(data.value) ? [] : data.value;

				$scope.showGoToTop = $scope.videos.length > 0;
			});
			break;

		case 'news':
			Results.get($scope.q, 'news').then(function (data) {
				$scope.loading = false;
				$scope.news    = _.isUndefined(data.value) ? [] : data.value;
				$scope.showGoToTop = $scope.news.length > 0;
			});
			break;
	}
});