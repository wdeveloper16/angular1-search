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

	document.title="About SourceMoz";

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

	document.title="SourceMoz";

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

	document.title=$state.params.q+" | SourceMoz";

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

	function arrangeImages(images) {
		images = _.isUndefined(images) ? [] : images.value;
		if (images.length) {

			var screen_width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
			screen_width = Math.min(screen_width, 860);

			var wiki_width = $('#wikipedia_level2').width();
			if (!wiki_width) wiki_width = screen_width;
			console.log(wiki_width);

			var collimit = 1;
			var rowLimit = 3;

			if (!$scope.wikipedia) {
				rowLimit = 2;
			}

			var widthPercent = $scope.wikipedia ? 0.55 : 1;
			var wLimit = wiki_width * widthPercent;
			var hMain = 180;
			var hThumb = hMain / rowLimit;
			var wMain = Math.round(images[0].thumbnail.width * hMain / images[0].thumbnail.height);
			var wMax = wMain;
			var xPos = wMain;
			var yPos = 0;
			var imgRow = 0;
			var imgCol = 0;

			var imgSectionWidth = 0;

			var rightEdges = [];
			var numImagesPerRow = [];

			for (var i = 1; i < images.length; i ++) {

				// calculate thumbnail width
				var wThumb = Math.round(images[i].thumbnail.width * hThumb / images[i].thumbnail.height);

				images[i].width = wThumb;
				images[i].height = hThumb;

				// if exceed limit
				if (xPos + wThumb > wLimit) {

					// thumbnail images should have at least 1 column
					if (imgCol >= collimit) {

						rightEdges[imgRow] = xPos;
						numImagesPerRow[imgRow] = imgCol;

						imgRow ++;
						imgCol = 1;

						xPos = wMain + wThumb;
						yPos = imgRow * hThumb;

						if (imgRow >= rowLimit) break;

						continue;
					}
					else {
						xPos += wThumb;
						wLimit = xPos;
					}
				}
				else
					xPos += wThumb;

				imgCol ++;
			}

			var offsetThres = 10;
			var avgRight = 0;
			for (var i = 0; i < rightEdges.length; i ++)
				avgRight += rightEdges[i];
			avgRight /= rightEdges.length;

			var maxRightEdge = 0;

			var startIx = 1;
			for (var i = 0; i < rightEdges.length; i ++) {

				var offset = Math.abs(rightEdges[i] - avgRight);
				if (offset > offsetThres) {

					offset = Math.random() * offsetThres;
					var newRightEdge = (rightEdges[i] > avgRight) ? avgRight + offset : avgRight - offset;
					var ratio = (newRightEdge - wMain) / (rightEdges[i] - wMain);
					rightEdges[i] = newRightEdge;

					for (var j = 0; j < numImagesPerRow[i]; j ++) {

						var index = startIx + j;
						images[index].width = Math.round(images[index].width * ratio);
					}
				}

				maxRightEdge = Math.max(maxRightEdge, rightEdges[i]);
				imgSectionWidth = Math.ceil(maxRightEdge);

				startIx += numImagesPerRow[i];
			}

			images[0].width = wMain;
			images[0].height = hMain;

			$scope.wikiSectionStyle = "min-width: " + imgSectionWidth + "px; max-width: " + imgSectionWidth + "px;";
			for (var i = 0; i < images.length; i ++) {
				images[i].style = "width: " + images[i].width + "px; height: " + images[i].height + "px;"
			}
		}

		$scope.hasBingImages = true;

		return images;
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
				// $scope.images  = _.isUndefined(data.images) ? [] : data.images.value;
				var imgData = data.images;
				$scope.images  = arrangeImages(imgData);
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
					console.log('wiki');
					$scope.wikiLoading = true;
					Results.post(term, 'wikipedia', $scope.wikiMatch).then(function (data) {
						var wikitextLimit = 100;
						if (data && data.extract && data.extract.toString().length > wikitextLimit) {
							$scope.wikiLoading = false;
							data.extract       = $sce.trustAsHtml(data.extract);
							$scope.wikipedia   = data;

							if ($scope.images.length == 0) {
								Results.post(term, 'wikiimages', {
									lang:   $scope.wikiMatch.lang,
									pageid: data.pageid
								}).then(function (data) {
									console.log('wikiimage', data);
									if (data.length > 0) {
										var images = [{thumbnailUrl: data[0], style: "width: auto;"}];
										$scope.images = images;
										$scope.hasBingImages = false;
										$scope.wikiSectionStyle = "min-width: 0";
									}
								});
							}
							else {
								// rearrange images
								$scope.images  = arrangeImages(imgData);
							}
						}
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

        var queryTimeoutVar;
		function doRequest() {
            clearTimeout(queryTimeoutVar);
            queryTimeoutVar = setTimeout(function(){
                if ($scope.q.length > 0) {
                    Results.getAutocomplete($scope.q).then(function (result) {
                        $scope.suggestions = [];
                        for(var i = 0; i < result.data.suggestionGroups[0].searchSuggestions.length; i++){
                            $scope.suggestions.push(result.data.suggestionGroups[0].searchSuggestions[i].displayText);
                        }
                    });
                }
            }, 200);
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

// BEGIN public_html/assets/js/app/directive/responsiveText.js
/**
 * Created by azizk on 2/8/2017.
 */
app.directive('responsiveText', ['$window', function ($window) {
    return {
        link: link,
        restrict: 'E',
        scope: {
            textData: '='
        }
    };
    function link(scope, element, attrs) {
        scope.width = $window.innerWidth;
        var that = element;
        element = element[0];
        var text = scope.textData;
        function refactorText (){
            if (typeof text != 'undefined') {
                var c = 0;
                that.text('');
                for (var i = 0; i < text.length; i++) {
                    var newNode = document.createElement('span');
                    newNode.appendChild(document.createTextNode(text.charAt(i)));
                    element.appendChild(newNode);
                }
                var startPoint = $(element).offset().top;
                var endPoint = $(element).innerHeight() + $(element).offset().top;
                var visibleText = '';
                for (var sp = 0; sp < $(element).children('span').length; sp++){
                    var newNode = $($(element).children('span')[sp]);
                    if ($(newNode).offset().top < endPoint) {
                        visibleText += $($(element).children('span')[sp]).html();
                        c++;
                    }
                }
                visibleText = visibleText.substring(0, visibleText.length - 2);
                var lastWord = visibleText.lastIndexOf(" ");
                visibleText = visibleText.substring(0, lastWord);
                visibleText += '...';
                console.log(visibleText);
                $(element).html(visibleText);
            }
        }
        if( $(element).hasClass('videoResponsive') &&  scope.width < 581){
            refactorText();
        }else if(scope.width < 1025){
            refactorText();
        }
        angular.element($window).bind('resize', function(){
            scope.width = $window.innerWidth;
            if( $(element).hasClass('videoResponsive') &&  scope.width < 581){
                refactorText();
            }else if(scope.width < 1025){
                refactorText();
            }
        });
    }
}]);
// END public_html/assets/js/app/directive/responsiveText.js

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
                return $http.get("https://api.cognitive.microsoft.com/bing/v5.0/suggestions/?q=" + term, {
                    data: "{body}",
                    headers: {"Ocp-Apim-Subscription-Key": "ee2f8d5e08dd41939bfda956fbf3c00f"}
                }).then(function (httpResponse) {
                    window.sessionStorage.setItem(key, JSON.stringify(httpResponse));
                    return httpResponse;
                });
            }
            else {
                data = JSON.parse(data);
                return $q.when(data);
            }
        },

        getSocial: function (term) {
            return this.get(term, 'social').then(function (data) {
                //resort results to show fb/twitter on top
                var sites = {
                    'social_facebook': 'facebook.com',
                    'social_twitter': 'twitter.com',
                    'social_lastfm': 'last.fm',
                    'social_pinterest': 'pinterest.com/',
                    'social_google': '.google.com/',
                    'social_instagram': 'instagram.com/',
                    'social_tumblr': 'tumblr.com/',
                    'social_quora': 'quora.com/',
                    'social_delicious': 'delicious.com/',
                    'social_digg': 'digg.com/',
                    'social_flickr': 'flickr.com/',
                    'social_stumble': 'stumbleupon.com/',
                    'social_linkedin': 'linkedin.com/',
                    'social_yelp': 'yelp.com/',
                    'social_vine': 'vine.co/',
                    'social_four': 'foursquare.com/',
                    'social_reddit': 'reddit.com'
                };

                var values = [];
                var first = [];
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
