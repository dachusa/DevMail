<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Dev Mail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="/assets/bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="/assets/css/flat-ui.css" rel="stylesheet">

    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="/assets/js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>
	<header class="palette palette-belize-hole">
		<h1 style="margin:0px;padding:0px;float:left;font-size:0px;">
			<a href="/" style="color:#ffffff;font-weight:700;font-size:18px"><span class="fui-mail"></span> Dev Mail</a>
		</h1>
		<nav style="float:right">
			<ul style="list-style-type:none">
				<li style="list-style-type:none"><a href="/help.php" style="color:#ffffff;font-weight:700;font-size:16px">Help</a></li>
			</ul>
		</nav>
		<div style="clear:both"></div>
	</header>
    <div class="container">
		<?php if(isset($siteAlert) && $siteAlert!=""){ ?>
		<div class="row">
			<?php echo $siteAlert; ?>
		</div>
		<?php } ?>