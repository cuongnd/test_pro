<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<h3><?php echo JText::_( 'COM_EASYSOCIAL_USERS_USER_GROUPS' ); ?></h3>
<hr />
<p>
	<?php echo JText::_( 'COM_EASYSOCIAL_USERS_USER_GROUPS_DESC' ); ?>
</p>
<div class="row-fluid">
	<div class="es-controls-row">
		<div class="span4">
			<label for="theme"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILES_FORM_GROUPS_DEFAULT_USER_GROUP' );?></label>
		</div>
		<div class="span8">
			<?php echo $this->html( 'tree.groups' , 'gid' , $userGroups , $guestGroup ); ?>
		</div>
	</div>
</div>
