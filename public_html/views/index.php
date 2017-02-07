<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SourceMoz &mdash; Safe search - we don't save, track or share your activity.</title>

	<meta property="og:title" content="SourceMoz">
	<!--<meta property="og:url" content="http://107.170.83.155/">-->
	<meta property="og:description" content="We don't save, track or share your activity.">
	<meta property="og:image" content="/assets/img/smf.png">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />

	<!--[if lt IE 9]>
	<script src="/assets/js/>html5shiv.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
		var site_url = "http://45.33.50.87/";
		// var site_url = "http://localhost";
	</script>

	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '103093080162711',
				xfbml      : true,
				version    : 'v2.8'
			});
			FB.AppEvents.logPageView();
		};

		(function(d, s, id){
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
	</script>

	<link rel="stylesheet" href="/build/app.css"/>

	<!--<link rel="stylesheet" href="/assets/css/front.css">-->
	<!--<link rel="stylesheet" href="/assets/css/bootstrap.min.css">-->
	<!--<link rel="stylesheet" href="/assets/css/bootstrap-theme.min.css">-->
	<link rel="stylesheet" href="/assets/css/font-awesome/css/font-awesome.min.css">
	<!--<link rel="stylesheet" href="/assets/css/style.css">-->

	<link rel="stylesheet" href="/assets/css/web.css">
	<!--<link rel="stylesheet" href="/assets/css/social.css">-->
<!--	<link rel="stylesheet" href="/assets/css/bootstrap.min.css">-->
<!--	<link rel="stylesheet" href="/assets/css/bootstrap-theme.min.css">-->
	<link rel="stylesheet" href="/assets/css/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/assets/css/swiper.min.css">

	<script src='https://s.yimg.com/uv/dm/scripts/syndication.js'></script>
	<script src="/build/libs.js"></script>
	<script src="/build/app.js"></script>

	<?php
		if (file_exists('/var/www/html/public_html/assets/theme/' . date('Y.m.d') . '/style.css')) {
			echo '<link rel="stylesheet" href="/assets/theme/' . date('Y.m.d') . '/style.css" />';
		}
		else{
			echo '<!-- missing /assets/theme/' . date('Y.m.d') . '/style.css -->';
		}

	?>

	<meta name="description" content="Safe search the Web with SourceMoz">
	<meta name="keywords" content="Search, Web, Safe, Tracking, User, SourceMoz">
</head>
<body ng-app="app">

<div ui-view style="width: 100%;height: 100%;"></div>

</body>
</html>