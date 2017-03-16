app.controller('resultsController', function ($scope, $state, $location, Results, $http, $sce, $rootScope) {
	$scope.$watch(function(){ return $location.search() }, function(){
		searchQuery = $location.search()['q'];

		document.title = searchQuery + " | SourceMoz";

		clearScope();

		$scope.q = $state.params.q = searchQuery;
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

					// related searches
					$scope.relatedSearches = _.isUndefined(data.relatedSearches) ? [] : data.relatedSearches.value;
					_.each($scope.relatedSearches, function (v, i) {
						v.url = 'http://localhost:8181/#/search/' + encodeURIComponent(v.text) + '/web';
					});

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

		function clearScope() {
			$scope.results = undefined;
			$scope.wikipedia = undefined;
			$scope.relatedSearches = undefined;
			$scope.socialResults = undefined;
			$scope.images = undefined;
			$scope.videos = undefined;
			$scope.news = undefined;
			$scope.wikiSectionStyle = '';
			$scope.hasBingImages = false;
			$scope.loading = false;
			$scope.wikiLoading = false;
		}
	});
});