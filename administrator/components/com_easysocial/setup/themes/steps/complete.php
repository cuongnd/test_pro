<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="text-center">
	<img class="mt-20 mb-20" src="<?php echo JURI::root();?>administrator/components/com_easysocial/setup/assets/images/completed.png" />

	<p class="section-desc">
		<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_COMPLETED_DESC' );?>
	</p>
	
	<div class="alert alert-error text-left" style="display: none;" data-delete-error>
		<?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_COMPLETED_ERROR' ); ?>
		<br /><br />
		<strong><?php echo JPATH_ROOT;?>/administrator/components/com_easysocial/setup</strong>
	</div>


	<a class="btn btn-es-primary btn-large btn-start mr-5" href="<?php echo rtrim( JURI::root() , '/' );?>/administrator/index.php?option=com_easysocial"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_LAUNCH_BACKEND' );?></a>
	<?php echo JText::_( 'COM_EASYSOCIAL_OR' ); ?>
	<a href="<?php echo rtrim( JURI::root() , '/' );?>/index.php?option=com_easysocial" target="_blank"><?php echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_LAUNCH_FRONTEND' );?></a>
</div>