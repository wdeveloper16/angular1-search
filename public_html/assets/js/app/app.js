var app = angular.module('app', ['autocomplete', 'ui.router','pageslide-directive', 'ngTouch']);

app.run([
	'$state', '$rootScope',
	function ($state, $rootScope) {
		$rootScope.$state = window.state = $state;
	}]);