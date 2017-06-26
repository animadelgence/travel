<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>ERROR PAGE</title>
	<link rel="shortcut icon" type="image/x-icon" href="/img/front/favicon.ico">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link type="text/css" rel="stylesheet" href="/css/bootstrap/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="/css/error.css">
	<link type="text/css" rel="stylesheet" href="/css/error/normalize.css">
	<link type="text/css" rel="stylesheet" href="/css/error/reset.css">
	<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,700" rel="stylesheet" type="text/css">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:800' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="/js/vendor/modernizr-2.6.2.min.js"></script>
	<!--[if lt IE 9]>
        	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <![endif]-->
	<!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>
	<!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
	<div class="main_wrap">

		<div class="top_section">
			<a href="javascript:void(0)" class="logo_error"><img src="/img/front/smartfanpagelogo-1.jpg" alt="" /></a>
		</div>
		<p>Dont worry you will be back on track in no time!!!</p>
		<div>
			<div class="svg-wrapper">
			</div>
		</div>
<p>Page doesn't exist or some other error occured.Please visit <a href="<?php
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
        echo $protocol.$_SERVER["SERVER_NAME"]?>">home page</a></p>
	</div>
	<!-- end of main_wrap -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script>
		(function ($) {
			var browser = window.navigator.userAgent;
			var index = browser.indexOf('MSIE');

			if (index > 0 || !!navigator.userAgent.match(/Trident\/7\.|Edge|Android|iOS|iPhone|mobile/i)) {
				$(document).ready(function () {
					var img404 = $('<img>', {
						'src': '/img/error.jpg',
						'alt': 'Page not found'
					});
					$('.svg-wrapper').append(img404);
				});
			} else {
                var innerContent = '<svg width="660px" height="300px" viewBox="0 0 660 300" class="svg-defs"> <symbol id="s-text"> <text text-anchor="middle" x="50%" y="98%" class="text" font-family="Open Sans" font-size="404px" textLength="677">404 </text> </symbol> <mask id="m-text" maskUnits="userSpaceOnUse" maskContentUnits="userSpaceOnUse"> <rect width="100%" height="100%" class="mask__shape"> </rect> <use xlink:href="#s-text" class="mask__text"></use> </mask> </svg> <div class="box-with-text"> <div class="text-fill"> </div> <svg width="660px" height="300px" viewBox="0 0 660 300 " class="svg-inverted-mask"> <rect width="100%" height="100%" mask="url(#m-text)" class="shape--fill"></rect> <use xlink:href="#s-text" class="text--transparent"></use> </svg> </div>';
                $(document).ready(function(){
                    $('.svg-wrapper').html(innerContent);
                });
            }
        })(jQuery);
    </script>
</body>

</html>
