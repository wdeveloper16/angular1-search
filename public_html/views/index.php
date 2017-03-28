<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SourceMoz</title>

	<meta property="og:title" content="SourceMoz">
	<!--<meta property="og:url" content="http://107.170.83.155/">-->
	<meta property="og:description" content="We don't save, track or share your activity.">
	<meta property="og:image" content="/assets/img/smf.png">

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />

	<!-- Desktop Browsers -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/icons/icons/favicon.ico" />

	<!-- Android: Chrome M39 and up-->
	<link rel="manifest" href="assets/icons/manifest.json">
	<!-- Android: Chrome M31 and up, ignored if manifest is present-->
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="icon" sizes="192x192" href="assets/icons/icons/icon-192x192.png">
	<!-- iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">

	<link rel="apple-touch-icon" sizes="180x180" href="assets/icons/icons/apple-touch-icon-180x180-precomposed.png">
	<link href="assets/icons/icons/apple-touch-icon-152x152-precomposed.png" sizes="152x152" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-144x144-precomposed.png" sizes="144x144" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-120x120-precomposed.png" sizes="120x120" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-114x114-precomposed.png" sizes="114x114" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-76x76-precomposed.png" sizes="76x76" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-72x72-precomposed.png" sizes="72x72" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-60x60-precomposed.png" sizes="60x60" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-57x57-precomposed.png" sizes="57x57" rel="apple-touch-icon">
	<link href="assets/icons/icons/apple-touch-icon-precomposed.png" rel="apple-touch-icon">

	<!-- Windows 8 and IE 11 -->
	<meta name="msapplication-config" content="assets/icons/browserconfig.xml" />

	<!--[if lt IE 9]>
	<script src="/assets/js/>html5shiv.min.js"></script>
	<![endif]-->
	<script type="text/javascript">
		var site_url = "http://sourcemoz.com/";
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
		if (file_exists('assets/theme/' . date('Y.m.d') . '/style.css')) {
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

<div ui-view style="width: 100%;height: auto;min-height:100%;"></div>

</body>
</html>