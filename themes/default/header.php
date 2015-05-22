<?php
/*
header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
header('Last-Modified: '.gmdate( 'D, d M Y H:i:s' ).' GMT');
header('Cache-Control: no-cache, must-revalidate, max-age=0');
header('Cache: no-cache');
header('Pragma: no-cache');
*/
if ($gPageProps["authenticated"] && !array_key_exists("account_id", $_SESSION)) {
	header('Location: unauthorized.php');
	exit;
}
header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html>
<html lang="<?php echo($gLang); ?>">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="description" data-i18n="[content]app.description;" />
		<meta name="author" content="J&eacute;r&eacute;mie Langlade &lt;jlanglade@pixbuf.net&gt;" />
		<link rel="icon" href="./themes/<?php echo($gThemeName); ?>/style/favicon.ico" />
		<link href="./themes/<?php echo($gThemeName); ?>/style/favicon.png" type="image/x-icon" rel="icon" />
		<title data-i18n="app.title" data-i18n-options="{&quot;page&quot;:&quot;<?php echo($gPageProps["id"]); ?>&quot;}"></title>
		<!-- CSS -->
		<link href="./themes/<?php echo($gThemeName); ?>/style/style.css" rel="stylesheet" />
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body id="<?php echo($gPageProps["id"]); ?>" data-spy="scroll" data-target="#pageNavbar"><?php
include_once(WCT_INC_DIR . 'analyticstracking.php');
?>
		<div id="progressDialog">
			<div class="progress">
				<div id="progressBar" class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0 %</div>
			</div>
			<p id="progressInfoMessage">&nbsp;</p>
		</div>
		<div id="content"><?php
if ($gPageProps["blocks"]["nav"]) { ?>
			<!-- Static navbar -->
			<nav class="navbar navbar-default navbar-fixed-top navbar-material-grey-700 shadow-z-2" id="mainNavBar">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only" data-i18n="nav.toggle"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="./" data-i18n="[title]nav.home;app.name"></a>
					</div><?php
	if (!array_key_exists("account_id", $_SESSION)) { ?>
					<div id="navbar" class="navbar-collapse collapse">
						<nav class="social pull-right">
							<ul class="list-unstyled">
								<li class="facebook"><a href="http://www.facebook.com/share.php?u=[URL]&title=[TITLE]" data-toggle="tooltip" data-placement="bottom" data-i18n="[title]share.facebook;"><span>Facebook</span></a></li>
								<li class="twitter"><a href="https://twitter.com/share" data-toggle="tooltip" data-placement="bottom" data-i18n="[title]share.tweeter;"><span>Tweet</span></a></li>
							</ul>
						</nav>
					</div><?php
	} else { ?>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav navbar-right">
							<li class="dropdown<?php if ($gPageProps["id"] == 'my') { echo(' active'); } ?>">
								<a href="./my.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span id="playerNickName"><?php echo($_SESSION["nickname"]); ?></span> <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="./my.php#calendar"><span class="glyphicon glyphicon-calendar"></span> <span data-i18n="nav.my.calendar"></span></a></li>
									<li><a href="./my.php#garage"><span class="glyphicon glyphicon-oil"></span> <span data-i18n="nav.my.garage"></span></a></li>
									<li><a href="./my.php#stats"><span class="glyphicon glyphicon-signal"></span> <span data-i18n="nav.my.stats"></span></a></li>
									<li class="divider"></li>
									<li><a href="logout.php" id="linkLogout" data-i18n="[title]nav.logout;"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> <span data-i18n="nav.logout"></span></a></li>
								</ul>
							</li>
							<li<?php if ($gPageProps["id"] == 'garage') { echo(' class="active"'); } ?>><a href="garage.php" data-i18n="nav.garage"></a></li>
							<li<?php if ($gPageProps["id"] == 'events') { echo(' class="active"'); } ?>><a href="events.php" data-i18n="nav.events"></a></li>
							<!--<li<?php if ($gPageProps["id"] == 'stronghold') { echo(' class="active"'); } ?>><a href="stronghold.php" data-i18n="nav.stronghold"></a></li>-->
							<li class="dropdown<?php if ($gPageProps["id"] == 'strats') { echo(' active'); } ?>">
								<a href="./strats.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span data-i18n="nav.strats.title"></span> <span class="caret"></span></a>
								<ul class="dropdown-menu" role="menu">
									<li><a href="./strats.php?action=new"><span class="glyphicon glyphicon-plus"></span> <span data-i18n="nav.strats.new"></span></a></li>
									<li><a href="./strats.php?action=list&amp;view=my"><span class="glyphicon glyphicon-picture"></span> <span data-i18n="nav.my.strats"></span></a></li>
									<li class="divider"></li>
									<li class="dropdown-header" data-i18n="nav.strats.shared"></li>
									<li><a href="./strats.php?action=list&amp;view=valid"><span class="glyphicon glyphicon-star"></span> <span data-i18n="nav.strats.valid"></span></a></li>
									<li><a href="./strats.php?action=list&amp;view=review"><span class="glyphicon glyphicon-check"></span> <span data-i18n="nav.strats.review"></span></a></li>
								</ul>
							</li><?php
		// Show the clan settings only if the user is in the allowed users (commander and roles by clan settings)
		if (in_array($_SESSION['account_id'], $gAdmins)) { ?>
							<li<?php if ($gPageProps["id"] == 'clansettings') { echo(' class="active"'); } ?>><a href="clansettings.php" data-i18n="[title]page.clansettings.title;"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></a></li><?php
		} ?>
						</ul>
					</div><!--/.nav-collapse --><?php
	} ?>
				</div><!--/.container-fluid -->
			</nav><?php
}
?>
