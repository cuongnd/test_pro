<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<script type="text/javascript">
jQuery(document).ready( function(){

	<?php if( $reinstall ){ ?>
		es.ajaxUrl	= "<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&ajax=1&reinstall=1&license=<?php echo JRequest::getVar( 'license' );?>";
	<?php } ?>

	<?php if( $update ){ ?>
		es.ajaxUrl	= "<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&ajax=1&update=1&license=<?php echo JRequest::getVar( 'license' );?>";
	<?php } ?>

	// Immediately proceed with installation
	es.installation.download();
});

</script>
<form name="installation" method="post" data-installation-form>
<div class="row-fluid">
	<div class="span12">
		<hr class="section-separator" />

		<p class="section-desc">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_INSTALLING_DESC' );?>
		</p>

		<div class="alert alert-success" data-installation-completed style="display: none;">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_INSTALLING_COMPLETED' ); ?>
		</div>

		<div data-install-progress>

			<div class="es-progress-wrap">
				<div class="progress progress-info progress-striped">
					<div class="bar" style="width: 1%" data-progress-bar></div>
				</div>
				<div class="progress-result" data-progress-bar-result>0%</div>
			</div>
			<div class="install-note">
				<p data-progress-active-message><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_INSTALLING_DOWNLOADING_FILES' );?></p>
			</div>
			<div class="es-logs">
				<ol class="split" data-progress-logs>
					<li class="active" data-progress-download>
						<b class="split__title"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_INSTALLING_DOWNLOADING_FILES' );?></b>
						<span class="progress-state text-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_DOWNLOADING' );?></span>
						<div class="notes"></div>
					</li>
					<?php include( dirname( __FILE__ ) . '/installing.steps.php' ); ?>
				</ol>
			</div>

		</div>

	</div>
</div>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="install" value="1" />
<input type="hidden" name="active" value="<?php echo $active; ?>" />

<?php if( $reinstall ){ ?>
<input type="hidden" name="reinstall" value="1" />
<?php } ?>

<?php if( $update ){ ?>
<input type="hidden" name="update" value="1" />
<?php } ?>
</form>