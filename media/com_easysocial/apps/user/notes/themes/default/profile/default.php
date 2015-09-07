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
<div class="app-notes-wrapper profile" data-profile-user-apps-notes data-app-id="<?php echo $app->id; ?>">

	<?php if( $notes ) { ?>
	<ul class="unstyled note-items" data-profile-user-apps-notes-list>
		<?php foreach( $notes as $note ){ ?>
			<?php echo $this->loadTemplate( 'apps/user/notes/profile/item' , array( 'note' => $note , 'user' => $user , 'appId' => $app->id ) ); ?>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<div class="empty center">
		<?php echo JText::sprintf( 'APP_NOTES_EMPTY_NOTES_PROFILE' , $user->getName() ); ?>
	</div>
	<?php } ?>
</div>
