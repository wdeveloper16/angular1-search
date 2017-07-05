<!doctype html>
<html lang="en">
<head>
	<title>SourceMoz</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="HandheldFriendly" content="True" />

	<meta property="og:title" content="SourceMoz" />
	<meta property="og:type" content="website" />
	<meta property="og:url" content="https://www.sourcemoz.com" />
	<meta property="og:image" content="https://www.sourcemoz.com/assets/img/smz3.png" />
	<meta property="fb:app_id" content="1901150616789535"/>
	<meta property="og:description" content="We don't save, track or share your activity." />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@SourceMoz" />
	<meta name="twitter:title" content="SourceMoz" />
	<meta name="twitter:description" content="We don't save, track or share your activity." />
	<meta name="twitter:image" content="https://sourcemoz.com/assets/img/smz-144.png" />

	<meta name="description" content="We don't save, track or share your activity.">
	<meta name="keywords" content="SourceMoz, Search, Search Engine, Web">

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
				// appId      : '103093080162711',
				appId      : '1901150616789535',
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

	<!--<link rel="stylesheet" href="/assets/css/web.css">-->
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

    <base href="/">
</head>
<body ng-app="app">

<div ui-view style="width: 100%;height: auto;min-height:100%;"></div>

</body>
</html>