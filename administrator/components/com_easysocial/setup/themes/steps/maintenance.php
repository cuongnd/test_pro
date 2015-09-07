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
		es.ajaxUrl	= "<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&ajax=1&reinstall=1";
	<?php } ?>

	<?php if( $update ){ ?>
		es.ajaxUrl	= "<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&ajax=1&update=1";
	<?php } ?>
	// Immediately proceed with synchronization
	es.maintenance.init();

});
</script>
<form name="installation" data-installation-form>
<div class="row-fluid">
	<div class="span12">
		<p class="section-desc">
			<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_DESC' ); ?>
		</p>

		<div data-sync-progress>
			<div class="es-logs">
				<ol class="split" data-progress-logs>
					<li class="active" data-progress-syncdb>
						<b class="split__title"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_SYNC_DATABASE' );?></b>
						<span class="progress-state text-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_EXECUTING' );?></span>
						<div class="notes">
							<ul style="unstyled" data-progress-syncdb-items>
							</ul>
						</div>
					</li>
					<li class="pending" data-progress-syncuser>
						<b class="split__title"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_SYNC_USERS' );?></b>
						<span class="progress-state text-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_EXECUTING' );?></span>
						<div class="notes">
							<ul style="unstyled" data-progress-syncuser-items>
							</ul>
						</div>
					</li>
					<li class="pending" data-progress-syncprofiles>
						<b class="split__title"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_SYNC_PROFILES' );?></b>
						<span class="progress-state text-info"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_MAINTENANCE_EXECUTING' );?></span>
						<div class="notes">
							<ul style="unstyled" data-progress-syncprofile-items>
							</ul>
						</div>
					</li>
				</ol>
			</div>
		</div>

	</div>
</div>
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="active" value="<?php echo $active; ?>" />

<?php if( $reinstall ){ ?>
<input type="hidden" name="reinstall" value="1" />
<?php } ?>

<?php if( $update ){ ?>
<input type="hidden" name="update" value="1" />
<?php } ?>
</form>