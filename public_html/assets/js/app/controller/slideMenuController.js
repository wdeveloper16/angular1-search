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