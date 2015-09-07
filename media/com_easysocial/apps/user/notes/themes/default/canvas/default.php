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
<div class="app-notes-wrapper canvas" data-canvas-app-notes data-id="<?php echo $note->id; ?>">
	<div class="row-fluid">
		<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=apps&layout=canvas&id=' . $app->id . '&appView=note&cid=' . $note->id . '&userid=' . $user->id );?>" class="note-title pull-left"><?php echo $note->title; ?></a>
	</div>
	
	<time datetime="<?php echo $this->html( 'string.date' , $note->created ); ?>" class="note-date">
		<span>
			<i class="ies-calendar-2 ies-small"></i>
			<?php echo $this->html( 'string.date' , $note->created , JText::_( 'DATE_FORMAT_LC3' ) ); ?>
		</span>
	</time>

	<div class="note-excerpt">
		<?php echo nl2br( $note->content );?>
	</div>

	<div class="es-action-wrap">
		<ul class="unstyled es-action-feedback">
			<li><a href="javascript:void(0);" class="small"><?php echo $likes->button();?></a></li>
		</ul>
	</div>

	<div data-stream-counter class="es-stream-counter<?php echo ( $likes->getCount() == 0 ) ? ' hide' : ''; ?>">
		<div class="es-stream-actions"><?php echo $likes->toHTML(); ?></div>
	</div>

	<div class="es-stream-actions">
		<?php echo $comments->getHTML( array( 'hideEmpty' => false ) );?>
	</div>

</div>