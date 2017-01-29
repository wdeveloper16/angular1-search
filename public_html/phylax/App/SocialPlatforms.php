<?php

/**
 *
 *
 * @author Lukasz Nowicki <jagoo@post.pl>
 *

 */

namespace Phylax\SourceMoz;


trait SocialPlatforms {


	public function get_reveal_open() {

		$s = '';

		$s .= '<div id="social_reveal">' . PHP_EOL;

		$s .= '    <div id="social_reveal_content">' . PHP_EOL;

		return $s;

	}


	public function get_reveal_close() {

		$s = '';

		$s .= '    </div><!-- #social_reveal_content -->' . PHP_EOL;

		$s .= '    <div id="reveal_open"><span id="ro_button" class="ro_open"></span></div>' . PHP_EOL;

		$s .= '</div><!-- #social_reveal -->' . PHP_EOL;

		return $s;

	}

	public function get_access_token_twitter($url, $params, $header) {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$data    = curl_exec($ch);
		$reqInfo = curl_getinfo($ch);
		curl_close($ch);

		return $data;
	}

	public function get_thumbnail_twitter($url, $header) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$data    = curl_exec($ch);
		$reqInfo = curl_getinfo($ch);
		curl_close($ch);

		return $data;
	}


	public function get_social_item($item, $add_first = false, $add_last = false) {
		//error_log(print_R($item,TRUE), 3, "/var/www/sourcemoz/public_html/msg.log");

		$s = $icon_str = $icon_data = '';

		$only_item = false;

		if ($add_first && $add_last) {

			$only_item = true;

		}

		$s .= '<div id="' . $item->id . '" class="searchItem socialItem' . (($only_item == true) ? ' onlyItem' : '') . (($add_last == true) ? ' lastItem' : '') . (($add_first == true) ? ' firstItem' : '') . '">' . PHP_EOL;

		$s .= '    <div class="socialIcon"><span class="socialIconView ' . $item->class . '"></span></div>' . PHP_EOL;

		$s .= '    <div class="socialDesc">' . PHP_EOL;
		$s .= '        <div class="socialHeader">' . PHP_EOL;

		if ($item->type == S_FACEBOOK) {
			//error_log(print_R($item,TRUE), 3, "/var/www/sourcemoz/public_html/msg.log");
			$icon = new IconFacebook($item);
			if (!is_null($icon->icon_img)) {

				$icon_str = $icon->icon_img;

			}

			$s .= '            <div class="socialItemIcon">' . $icon_str . '</div>' . PHP_EOL;

		}
		else {
			switch ($item->type) {

				case S_TWITTER:

					$key      = base64_encode('tCsVuau56nl5RnDAQAl0Dos6q:LXWU4oF2QWMXaubXoaVghlz6ZyMlujBH80trUSwsF4k9U8OPwn');
					$params   = ['grant_type' => 'client_credentials'];
					$header   = ["Authorization: Basic $key"];
					$url      = 'https://api.twitter.com/oauth2/token';
					$response = json_decode($this->get_access_token_twitter($url, $params, $header), true);
					$token    = $response['access_token'];

					//get json profile
					$header      = ["Authorization: Bearer $token"];
					$str         = $item->title;
					$from        = "(";
					$to          = ")";
					$sub         = substr($str, strpos($str, $from) + strlen($from), strlen($str));
					$output      = substr($sub, 0, strpos($sub, $to));
					$screen_name = substr($output, 1);
					$url         = "https://api.twitter.com/1.1/users/show.json";
					$params      = ['screen_name' => $screen_name];
					$url .= '?' . http_build_query($params);
					$data = json_decode($this->get_thumbnail_twitter($url, $header), true);
					$img  = '<img class="sIconView" src="' . $data['profile_image_url'] . '">';

					if($img)
					$s .= '            <div class="socialItemIcon">' . $img . '</div>' . PHP_EOL;
					//error_log(print_R($data['profile_image_url'],TRUE), 3, "/var/www/sourcemoz/public_html/msg.log");
					break;
				case S_GOOGLE:

				case S_LASTFM:

				case S_PINTEREST:

				case S_REDDIT:

				case S_TUMBLR:
					$icon = new IconTumblr($item);
					break;

				case S_YELP:
					#echo '<pre>'.print_r($item,true).'</pre>';
					$icon_data = base64_encode(json_encode([
						'U' => $item->clickurl,
						'T' => $item->type,
						'I' => $item->id,
					]));

					break;

			}

//			if ($icon_data != '') {
//				$icon_data = ' data-ico="' . $icon_data . '"';
//			}

//			if($icon_data)
//			$s .= '            <div class="socialItemIcon"' . $icon_data . '></div>' . PHP_EOL;
		}

		$s .= '            <div class="socialItemHead">' . PHP_EOL;
		$s .= '                <h1 class="itemTitle"><a href="' . $item->url . '" class="itemClick" target="_blank">' . $item->title . '</a></h1>' . PHP_EOL;
		$s .= '                <a href="' . $item->url . '" class="itemDispUrl" target="_blank">' . $item->url . '</a>' . PHP_EOL;
		$s .= '            </div>' . PHP_EOL;
		$s .= '            <div class="c"></div>' . PHP_EOL;
		$s .= '        </div>' . PHP_EOL;
		$s .= '        <div class="itemBody">' . PHP_EOL;
		$s .= '            ' . $item->abstract . PHP_EOL;
		$s .= '        </div>' . PHP_EOL;
		$s .= '    </div>' . PHP_EOL;
		$s .= '</div>' . PHP_EOL;

		return $s;

	}


	public function update_item(&$item) {

		$item->class = $_SESSION['AppSocialInfo'][$item->type]['View']['Class'];

	}


	public function define_social_info() {

		if ((true) || !isset($_SESSION['AppSocialInfo'])) {

			$_SESSION['AppSocialInfo'] = [

				S_FACEBOOK  => [

					'View'      => [

						'Class' => 'social_facebook',

					],

					'CmpUrl'    => [

						'https://www.facebook.com' => strlen('https://www.facebook.com'),

						'http://www.facebook.com'  => strlen('http://www.facebook.com'),

					],

					'AddCmpUrl' => ['.facebook.com/'],

					'Key'       => S_FACEBOOK,

				], # S_FACEBOOK

				S_TWITTER   => [

					'View'      => [

						'Class' => 'social_twitter',

					],

					'CmpUrl'    => [

						'https://twitter.com' => strlen('https://twitter.com'),

						'http://twitter.com'  => strlen('http://twitter.com'),

					],

					'AddCmpUrl' => ['.twitter.com/'],

					'Key'       => S_TWITTER,

				], # S_TWITTER

				S_REDDIT    => [

					'View'      => [

						'Class' => 'social_reddit',

					],

					'CmpUrl'    => [

						'http://www.reddit.com'  => strlen('http://www.reddit.com'),

						'https://www.reddit.com' => strlen('https://www.reddit.com'),

						'http://reddit.com'      => strlen('http://reddit.com'),

						'https://reddit.com'     => strlen('https://reddit.com'),

					],

					'AddCmpUrl' => ['.reddit.com/'],

					'Key'       => S_REDDIT,

				], # S_REDDIT

				S_LASTFM    => [

					'View'      => [

						'Class' => 'social_lastfm',

					],

					'CmpUrl'    => [

						'http://www.last.fm'  => strlen('http://www.last.fm'),

						'https://www.last.fm' => strlen('https://www.last.fm'),

					],

					'AddCmpUrl' => ['.last.fm/'],

					'Key'       => S_LASTFM,

				], # S_LASTFM

				S_PINTEREST => [

					'View'      => [

						'Class' => 'social_pinterest',

					],

					'CmpUrl'    => [

						'https://www.pinterest.com' => strlen('https://www.pinterest.com'),

						'http://www.pinterest.com'  => strlen('http://www.pinterest.com'),

					],

					'AddCmpUrl' => ['.pinterest.com/'],

					'Key'       => S_PINTEREST,

				], # S_PINTEREST

				S_GOOGLE    => [

					'View'      => [

						'Class' => 'social_google',

					],

					'CmpUrl'    => [

						'https://plus.google.com' => strlen('https://plus.google.com'),

						'http://plus.google.com'  => strlen('http://plus.google.com'),

					],

					'AddCmpUrl' => ['.google.com/'],

					'Key'       => S_GOOGLE,

				], # S_GOOGLE

				S_INSTAGRAM => [

					'View'      => [

						'Class' => 'social_instagram',

					],

					'CmpUrl'    => [

						'https://instagram.com' => strlen('https://instagram.com'),

						'http://instagram.com'  => strlen('http://instagram.com'),

					],

					'AddCmpUrl' => ['.instagram.com/'],

					'Key'       => S_INSTAGRAM,

				], # S_INSTAGRAM

				S_TUMBLR    => [

					'View'      => [

						'Class' => 'social_tumblr',

					],

					'CmpUrl'    => [

						'http://www.tumblr.com'  => strlen('http://www.tumblr.com'),

						'https://www.tumblr.com' => strlen('https://www.tumblr.com'),

					],

					'AddCmpUrl' => ['.tumblr.com/'],

					'Key'       => S_TUMBLR,

				], # S_TUMBLR

				S_QUORA     => [

					'View'      => [

						'Class' => 'social_quora',

					],

					'CmpUrl'    => [

						'https://www.quora.com' => strlen('https://www.quora.com'),

						'http://www.quora.com'  => strlen('http://www.quora.com'),

					],

					'AddCmpUrl' => ['.quora.com/'],

					'Key'       => S_QUORA,

				], # S_QUORA

				S_DELICIOUS => [

					'View'      => [

						'Class' => 'social_delicious',

					],

					'CmpUrl'    => [

						'https://delicious.com' => strlen('https://delicious.com'),

						'http://delicious.com'  => strlen('http://delicious.com'),

					],

					'AddCmpUrl' => ['.delicious.com/'],

					'Key'       => S_DELICIOUS,

				], # S_DELICIOUS

				S_DIGG      => [

					'View'      => [

						'Class' => 'social_digg',

					],

					'CmpUrl'    => [

						'http://digg.com'  => strlen('http://digg.com'),

						'https://digg.com' => strlen('https://digg.com'),

					],

					'AddCmpUrl' => ['.digg.com/'],

					'Key'       => S_DIGG,

				], # S_DIGG

				S_FLICKR    => [

					'View'      => [

						'Class' => 'social_flickr',

					],

					'CmpUrl'    => [

						'https://www.flickr.com' => strlen('https://www.flickr.com'),

						'http://www.flickr.com'  => strlen('http://www.flickr.com'),

						'https://flickr.com'     => strlen('https://flickr.com'),

						'http://flickr.com'      => strlen('http://flickr.com'),

					],

					'AddCmpUrl' => ['.flickr.com/'],

					'Key'       => S_FLICKR,

				], # S_FLICKR

				S_STUMBLE   => [

					'View'      => [

						'Class' => 'social_stumble',

					],

					'CmpUrl'    => [

						'http://www.stumbleupon.com'  => strlen('http://www.stumbleupon.com'),

						'https://www.stumbleupon.com' => strlen('https://www.stumbleupon.com'),

					],

					'AddCmpUrl' => ['.stumbleupon.com/'],

					'Key'       => S_STUMBLE,

				], # S_STUMBLE

				S_LINKEDIN  => [

					'View'      => [

						'Class' => 'social_linkedin',

					],

					'CmpUrl'    => [

						'https://www.linkedin.com' => strlen('https://www.linkedin.com'),

						'http://www.linkedin.com'  => strlen('http://www.linkedin.com'),

					],

					'AddCmpUrl' => ['.linkedin.com/'],

					'Key'       => S_LINKEDIN,

				], # S_LINKEDIN

				S_YELP      => [

					'View'      => [

						'Class' => 'social_yelp',

					],

					'CmpUrl'    => [

						'http://www.yelp.com'  => strlen('http://www.yelp.com'),

						'https://www.yelp.com' => strlen('https://www.yelp.com'),

					],

					'AddCmpUrl' => ['.yelp.com/'],

					'Key'       => S_YELP,

				], # S_YELP

				S_VINE      => [

					'View'      => [

						'Class' => 'social_vine',

					],

					'CmpUrl'    => [

						'https://vine.co' => strlen('https://vine.co'),

						'http://vine.co'  => strlen('http://vine.co'),

					],

					'AddCmpUrl' => ['.vine.co/'],

					'Key'       => S_VINE,

				], # S_VINE

				S_FOUR      => [

					'View'      => [

						'Class' => 'social_four',

					],

					'CmpUrl'    => [

						'https://foursquare.com' => strlen('https://foursquare.com'),

						'http://foursquare.com'  => strlen('http://foursquare.com'),

					],

					'AddCmpUrl' => ['.foursquare.com/'],

					'Key'       => S_FOUR,

				], # S_FOUR

			];

		}

	}


	public function define_social_constants() {
		define(__NAMESPACE__ . '\S_FACEBOOK', 100100);
		define(__NAMESPACE__ . '\S_TWITTER', 100200);
		define(__NAMESPACE__ . '\S_REDDIT', 100300);
		define(__NAMESPACE__ . '\S_LASTFM', 100400);
		define(__NAMESPACE__ . '\S_PINTEREST', 100500);
		define(__NAMESPACE__ . '\S_GOOGLE', 100600);
		define(__NAMESPACE__ . '\S_INSTAGRAM', 100700);
		define(__NAMESPACE__ . '\S_TUMBLR', 100800);
		define(__NAMESPACE__ . '\S_QUORA', 100900);
		define(__NAMESPACE__ . '\S_DELICIOUS', 101000);
		define(__NAMESPACE__ . '\S_DIGG', 101100);
		define(__NAMESPACE__ . '\S_FLICKR', 101200);
		define(__NAMESPACE__ . '\S_STUMBLE', 101300);
		define(__NAMESPACE__ . '\S_LINKEDIN', 101400);
		define(__NAMESPACE__ . '\S_YELP', 101500);
		define(__NAMESPACE__ . '\S_VINE', 101600);
		define(__NAMESPACE__ . '\S_FOUR', 101700);
	}

	public function define_socials() {
		if (!defined(__NAMESPACE__ . '\S_FACEBOOK')) {
			$this->define_social_constants();
		}
		$this->define_social_info();
	}
}