<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<!doctype html>
<html lang="en">
<head>
	<title><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION' ); ?> - <?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_STEP' );?> <?php echo $active; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="<?php echo JURI::base();?>components/com_easysocial/setup/assets/styles/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo JURI::base();?>components/com_easysocial/setup/assets/styles/style.css" type="text/css" />
	<script src="<?php echo JURI::base();?>components/com_easysocial/setup/assets/scripts/jquery.js" type="text/javascript"></script>
	<script src="<?php echo JURI::base();?>components/com_easysocial/setup/assets/scripts/bootstrap.min.js" type="text/javascript"></script>
	<script src="<?php echo JURI::base();?>components/com_easysocial/setup/assets/scripts/application.js" type="text/javascript"></script>
	<script type="text/javascript">
	<?php require( JPATH_ROOT . '/administrator/components/com_easysocial/setup/assets/scripts/script.js' ); ?>
	</script>
</head>
<body class="step<?php echo $active;?>">
<div id="es-header">
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">

				<span class="brand">
					<img src="<?php echo JURI::root();?>administrator/components/com_easysocial/setup/assets/images/logo.png" width="32" class="mr-5" /> 
					<span class="title">EasySocial</span>
					<span class="tagline">Social network extension for Joomla!</span>
				</span>

				<ul class="nav nav-pills pull-right social-links">
					<li>
						<a href="https://twitter.com/StackIdeas" class="twitter-follow-button" data-show-count="true" data-lang="en">Follow @stackideas</a><script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</li>
					<li>
						<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2FStackIdeas&amp;width=450&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=406369119482668" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100px; height:21px;margin-top: 6px;" allowTransparency="true"></iframe>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div id="es-wrap">

	<div class="es-installer-header">
		<?php if( $activeStep->template == 'complete' ){ ?>
		<h2 class="section-heading text-center">
			<span><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_COMPLETED' );?></span>
		</h2>
		<?php } else { ?>
		<h2 class="section-heading text-center">
			<span><?php echo JText::_( $activeStep->title );?></span>
		</h2>
		<?php } ?>
	</div>

	<div class="es-installer">

		<?php if( $activeStep->template != 'complete' ){ ?>
		<div class="navbar es-stepbar">
			<div class="navbar-inner">

				<div class="nav-collapse collapse">

					<div class="xmedia">
						<div class="media-object pull-left">
							<?php include( dirname( __FILE__ ) . '/default.steps.php' ); ?>
						</div>
						<div class="media-body">
							<div class="divider-vertical-last"></div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<?php } ?>

		<div class="es-installer-body">
			<?php include( dirname( __FILE__ ) . '/default.content.php' ); ?>
		</div>

		<?php include( dirname( __FILE__ ) . '/default.footer.php' ); ?>
	</div>

</div>

</body>
</html>
