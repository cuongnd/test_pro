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
<li class="note-item" data-apps-notes-item data-id="<?php echo $note->id;?>">
	<div class="row-fluid">
		<div class="pull-left">
			<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'id' => $appId, 'cid' => $note->id , 'userid' => $user->getAlias() ) );?>" class="note-title"><?php echo $note->title; ?></a>

			<div class="muted small note-date">
				<i class="ies-calendar"></i> <time datetime="<?php echo $this->html( 'string.date' , $note->created ); ?>" class="note-date"><?php echo $this->html( 'string.date' , $note->created , JText::_( 'DATE_FORMAT_LC3' ) ); ?></time>
			</div>
		</div>

		<div class="pull-right btn-group">
			<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_ loginLink btn btn-dropdown">
				<i class="icon-es-dropdown"></i>
			</a>

			<ul class="dropdown-menu dropdown-menu-user messageDropDown">					
				<li>
					<a href="javascript:void(0);" data-apps-notes-edit>
						<?php echo JText::_( 'APP_NOTES_EDIT_BUTTON' );?>
					</a>
				</li>
				<li data-friends-unfriend="">
					<a href="javascript:void(0);" data-apps-notes-delete data-id="<?php echo $note->id;?>">
						<?php echo JText::_( 'APP_NOTES_DELETE_BUTTON' );?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="notes-meta mt-10" data-stream-item>
		<?php echo $note->actions;?>
	</div>

</li>