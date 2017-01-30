// BEGIN public_html/assets/js/app/app.js
var app = angular.module('app', ['autocomplete', 'ui.router','pageslide-directive', 'ngTouch']);

app.run([
	'$state', '$rootScope',
	function ($state, $rootScope) {
		$rootScope.$state = window.state = $state;
	}]);
// END public_html/assets/js/app/app.js

// BEGIN public_html/assets/js/app/config.js
app.config(function ($stateProvider, $urlRouterProvider) {
	//
	// For any unmatched url, redirect to /state1
	$urlRouterProvider.otherwise("/");
	//
	// Now set up the states
	$stateProvider
		.state('frontpage', {
			url:         "/",
			controller:  'searchInputController',
			templateUrl: "/views/frontpage.html"
		})
		.state('about', {
			url:         "/about",
			controller:  'aboutController',
			templateUrl: "/views/about.html"
		})
		.state('results', {
			url:         "/search/:q/:tab",
			templateUrl: "/views/results.html",
			controller:  'resultsController'
		});
});
// END public_html/assets/js/app/config.js

// BEGIN public_html/assets/js/app/controller/aboutController.js
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
// END public_html/assets/js/app/controller/aboutController.js

// BEGIN public_html/assets/js/app/controller/frontController.js
app.controller('frontController', function ($scope, $state, $http) {

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
// END public_html/assets/js/app/controller/frontController.js

// BEGIN public_html/assets/js/app/controller/resultsController.js
app.controller('resultsController', function ($scope, $state, Results, $http, $sce, $rootScope) {
	$scope.q = $state.params.q;
	$scope.$rootScope = $rootScope;
	//img croper
    $scope.cropper = {};
    $scope.cropper.sourceImage = null;
    $scope.cropper.croppedImage   = null;
    $scope.bounds = {};
    $scope.bounds.left = 0;
    $scope.bounds.right = 0;
    $scope.bounds.top = 0;
    $scope.bounds.bottom = 0;
	//end img croper
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
// END public_html/assets/js/app/controller/resultsController.js

// BEGIN public_html/assets/js/app/controller/searchInputController.js
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
					$scope.suggestions = result.D;
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
// END public_html/assets/js/app/controller/searchInputController.js

// BEGIN public_html/assets/js/app/controller/slideMenuController.js
app.controller('slideMenuController', function ($scope, $rootScope) {
	// $rootScope.popupMenuVisible = false;

	$scope.hidePopup = function(){
		$scope.sideMenuVisible = false;
	};

	$scope.fbShare = function () {
		FB.ui({method: 'share', href: site_url});
		// $scope.hidePopup();
	};

	$scope.twitterShare = function () {
		window.open('http://twitter.com/share?text=Here%2C%20look%20at%20new%20search%20application%2C%20they%20don%27t%20save%20or%20track%20your%20activity%21&url=SM_URL', 'targetTwitterShare', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=450,height=400');
		// $scope.hidePopup();
	};
});
// END public_html/assets/js/app/controller/slideMenuController.js

// BEGIN public_html/assets/js/app/directive/ddd.js
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
// END public_html/assets/js/app/directive/ddd.js

// BEGIN public_html/assets/js/app/directive/smoothSlider.js
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
// END public_html/assets/js/app/directive/smoothSlider.js

// BEGIN public_html/assets/js/app/directive/socialIconErrorHandler.js
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
// END public_html/assets/js/app/directive/socialIconErrorHandler.js

// BEGIN public_html/assets/js/app/service/resultsService.js
app.service('Results', function ($q, $http) {
	return {

		get: function (term, type) {
			var key = 'searchResults-' + type + '-' + term;
			var data = sessionStorage.getItem(key);

			if (_.isUndefined(data) || _.isNull(data)) {
				return $http.get('/search.php?type=' + type + '&term=' + term).then(function (httpResponse) {
					window.sessionStorage.setItem(key, JSON.stringify(httpResponse));

					return httpResponse.data;
				});
			}
			else {
				data = JSON.parse(data);
				return $q.when(data.data);
			}
		},

		getAutocomplete: function (term) {
			var key = 'autocomplete-' + term;
			var data = sessionStorage.getItem(key);

			if (_.isUndefined(data) || _.isNull(data)) {
				return $http.get('/ac.php?term=' + term).then(function (httpResponse) {
					window.sessionStorage.setItem(key, JSON.stringify(httpResponse));

					return httpResponse.data;
				});
			}
			else {
				data = JSON.parse(data);
				return $q.when(data.data);
			}
		},

		getSocial: function(term){
			return this.get(term,'social').then(function(data){
				//resort results to show fb/twitter on top
				var sites = {
					'social_facebook':  'facebook.com',
					'social_twitter':   'twitter.com',
					'social_lastfm':    'last.fm',
					'social_pinterest': 'pinterest.com/',
					'social_google':    '.google.com/',
					'social_instagram': 'instagram.com/',
					'social_tumblr':    'tumblr.com/',
					'social_quora':     'quora.com/',
					'social_delicious': 'delicious.com/',
					'social_digg':      'digg.com/',
					'social_flickr':    'flickr.com/',
					'social_stumble':   'stumbleupon.com/',
					'social_linkedin':  'linkedin.com/',
					'social_yelp':      'yelp.com/',
					'social_vine':      'vine.co/',
					'social_four':      'foursquare.com/',
					'social_reddit':    'reddit.com'
				};

				var values = [];
				var first  = [];
				var second = [];

				if (!_.isUndefined(data.webPages)) {
					_.each(sites, function (v2, k2) {
						_.each(data.webPages.value, function (v, i) {
							if (!_.isNull(v.displayUrl.match(v2))) {
								v.class = k2;
								values.push(v);
							}

						});
					});

					_.each(values, function (v) {
						if (!_.find(first, {'class': v.class})) {
							first.push(v);
						}
						else {
							second.push(v);
						}
					});
				}

				_.each(second, function (v) {
					first.push(v);
				});

				return first;
			});
		},

		post: function (term, type, postData) {
			var key = 'searchResults-' + type + '-' + term;
			var data = sessionStorage.getItem(key);

			if (_.isUndefined(data) || _.isNull(data)) {
				return $http.post('/search.php?type=' + type + '&term=' + term, postData).then(function (httpResponse) {
					window.sessionStorage.setItem(key, JSON.stringify(httpResponse));
					return httpResponse.data;
				});
			}
			else {
				data = JSON.parse(data);
				return $q.when(data.data);
			}
		}
	}
});
// END public_html/assets/js/app/service/resultsService.js
