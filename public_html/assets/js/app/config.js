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