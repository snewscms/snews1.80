<?php

// Include all system function files here
include('snews.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php title(); ?>
	<meta name="robots" content="index,follow" />
	<meta name="author" content="Solucija.com" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div id="top">
			<ul id="topmenu">
				<?php pages(); ?>
			</ul>
			<div id="search">
				<?php searchform(); ?>
			</div>
		</div>
		<div id="logo">
			<h1><?php echo s('website_title'); ?></h1>
			<p><?php echo s('website_description'); ?></p>
		</div>
	</div>
	<div id="crumbs"><?php breadcrumbs(); ?></div>
	<div id="content">
		<div id="main">
			<?php center(); ?>
		</div>

		<div id="side">
			<div class="single">
				<h3>Categories</h3>
					<ul>
						<?php categories(); ?>
					</ul>
				<h3>RSS Feeds</h3>
					<ul>
						<?php rss_links(); ?>
					</ul>
				<?php extra(); ?>
			</div>

			<div class="single">
				<h3>New Posts</h3>
					<ul>
						<?php menu_articles(0, 3, 1); ?>
					</ul>
				<h3>New Comments</h3>
					<ul>
						<?php new_comments(5, 30); ?>
					</ul>
			</div>
		</div>
	</div>
	<div id="footer">
		<p>This site is powered by <a href="http://snewscms.com/" title="sNews CMS" onclick="target='_blank';">sNews</a> | <?php login_link(); ?></p>
	</div>
</div>
</body>
</html>