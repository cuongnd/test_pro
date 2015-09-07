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
<div class="app-notes-wrapper dashboard" data-dashboard-app-notes data-app-id="<?php echo $app->id; ?>">

	<div class="row-fluid small filter-tasks mt-10">
		<div class="pull-right">
			<a href="javascript:void(0);" class="btn btn-es-inverse btn-medium" data-app-notes-create>
				<?php echo JText::_( 'APP_NOTES_NEW_NOTE_BUTTON' ); ?>
			</a>
		</div>
	</div>
	<hr />

	
	<ul class="unstyled note-items" data-apps-notes>
		<?php if( $notes ){ ?>
			<?php foreach( $notes as $note ){ ?>
				<?php echo $this->loadTemplate( 'apps/user/notes/dashboard/item' , array( 'note' => $note , 'appId' => $app->id , 'user' => $user ) ); ?>
			<?php } ?>
		<?php } ?>
	</ul>

	<div class="empty center" data-apps-notes-empty style="<?php echo !$notes ? '' : 'display:none;';?>">
		<?php echo JText::_( 'APP_NOTES_EMPTY_NOTES' ); ?>
	</div>
	
</div>
