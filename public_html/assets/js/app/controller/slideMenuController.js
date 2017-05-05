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
		window.open('http://twitter.com/share?text=Check+out+SourceMoz.com&url=SM_URL', 'targetTwitterShare', 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=450,height=400');
		// $scope.hidePopup();
	};
});