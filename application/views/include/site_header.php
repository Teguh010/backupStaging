<?php

/**

 * *

 *  * Created by PhpStorm.

 *  * User: boonkhailim

 *  * Year: 2017

 *

 */



defined('BASEPATH') OR exit('No direct script access allowed');

?><!DOCTYPE html>

<html lang="en">

<head>
 
   <style>
		.mq-textcolor .mq-text-mode {
			display: block;
		}
   </style>

	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<title><?php echo BRANCH_TITLE;?></title>

	<link href="<?php echo base_url(); ?>css/bootstrap.min.css?<?= date('YmdHis') ?>" rel="stylesheet" type="text/css">

	<link href="<?php echo base_url(); ?>css/style.css?<?= date('YmdHis') ?>" rel="stylesheet" type="text/css">

	<link href="<?php echo base_url(); ?>css/bootstrap-tagsinput.css?<?= date('YmdHis') ?>" rel="stylesheet" type="text/css">

	<link href="<?php echo base_url(); ?>css/daterangepicker.css" rel="stylesheet" type="text/css">

	<link href="<?php echo base_url(); ?>css/fastselect.min.css" rel="stylesheet" type="text/css">

	<link href="<?php echo base_url(); ?>css/selectize.bootstrap3.css" rel="stylesheet" type="text/css">

	<link href="<?php echo base_url(); ?>css/icon_fonts_assets/picons-thin/style.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>css/icon_fonts_assets/batch-icons/style.css" rel="stylesheet">

	<link href="<?php echo base_url(); ?>js/plugins/magnific-popup/dist/magnific-popup.css" rel="stylesheet" type="text/css">

	<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

	<!-- <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/datatables.css"/>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/datatables.min.css"/> -->

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/html5lightbox/html5lightbox.js"></script> -->		
	
	<?php ######## CONFIG CSS ######### ?>
	<style type="text/css">
		<?php echo BRANCH_CSS; ?>
	</style>

	<script type="text/javascript">

	  var base_url = "<?php print base_url(); ?>";

	</script>

	<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

	<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->

	<!-- <script type="text/javascript" src="<?php echo base_url() ?>js/jquery-3.4.1.min.js"></script> -->

	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

	<!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script> -->	

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.1/Chart.min.js"></script>

	<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/mathquill.js"></script> -->

	<script type="text/javascript" src="<?php echo base_url(); ?>js/field-selection.js"></script>
	
	<!-- <link href="<?php echo base_url(); ?>css/social-button.css" rel="stylesheet" type="text/css"> -->

	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url(); ?>img/favicon/apple-icon-57x57.png">

	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url(); ?>img/favicon/apple-icon-60x60.png">

	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url(); ?>img/favicon/apple-icon-72x72.png">

	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url(); ?>img/favicon/apple-icon-76x76.png">

	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url(); ?>img/favicon/apple-icon-114x114.png">

	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url(); ?>img/favicon/apple-icon-120x120.png">

	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url(); ?>img/favicon/apple-icon-144x144.png">

	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url(); ?>img/favicon/apple-icon-152x152.png">

	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url(); ?>img/favicon/apple-icon-180x180.png">

	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url(); ?>img/favicon/android-icon-192x192.png">

	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url(); ?>img/favicon/favicon-32x32.png">

	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url(); ?>img/favicon/favicon-96x96.png">

	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>img/favicon/favicon-16x16.png">

	<link rel="manifest" href="<?php echo base_url(); ?>img/favicon/manifest.json">

	<meta name="msapplication-TileColor" content="#ffffff">

	<meta name="msapplication-TileImage" content="<?php echo base_url(); ?>img/favicon/ms-icon-144x144.png">

	<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.css" rel="stylesheet">

	<meta name="theme-color" content="#ffffff">

	<!-- Start of Async Drift Code

	<script>

		!function() {

			var t;

			if (t = window.driftt = window.drift = window.driftt || [], !t.init) return t.invoked ? void (window.console && console.error && console.error("Drift snippet included twice.")) : (t.invoked = !0,

				t.methods = [ "identify", "config", "track", "reset", "debug", "show", "ping", "page", "hide", "off", "on" ],

				t.factory = function(e) {

					return function() {

						var n;

						return n = Array.prototype.slice.call(arguments), n.unshift(e), t.push(n), t;

					};

				}, t.methods.forEach(function(e) {

				t[e] = t.factory(e);

			}), t.load = function(t) {

				var e, n, o, i;

				e = 3e5, i = Math.ceil(new Date() / e) * e, o = document.createElement("script"),

					o.type = "text/javascript", o.async = !0, o.crossorigin = "anonymous", o.src = "https://js.driftt.com/include/" + i + "/" + t + ".js",

					n = document.getElementsByTagName("script")[0], n.parentNode.insertBefore(o, n);

			});

		}();

		drift.SNIPPET_VERSION = '0.3.1';

		drift.load('xmxs5v25pvgm');

	</script> -->

	<!-- End of Async Drift Code -->


</head>

<body>

<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-99681502-1', 'auto');

  ga('send', 'pageview');

</script>
