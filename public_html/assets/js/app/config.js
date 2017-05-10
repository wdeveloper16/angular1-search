app.config(function ($stateProvider, $urlRouterProvider, $locationProvider) {
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
		.state('privacy', {
			url:         "/privacy",
			controller:  'privacyController',
			templateUrl: "/views/privacy.html"
		})
		.state('results', {
			url:         "/:tab?q",
			templateUrl: "/views/results.html",
			controller:  'resultsController'
		});

        // use the HTML5 History API
        $locationProvider.html5Mode(true);
});