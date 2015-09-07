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
<div class="row-fluid">
	<span class="actor">
	<?php if( $me ){ ?>
		<?php echo JText::_( 'COM_EASYSOCIAL_STREAM_YOU' ); ?>
	<?php } else { ?>

		<?php if( !$actor->isBlock() ) { ?>
		<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><?php echo $actor->getName(); ?></a>
		<?php } else { ?>
		<?php echo $actor->getName(); ?>
		<?php } ?>

	<?php } ?>
	</span>

	<?php if( !empty( $aggregateTitle ) ) { ?>
		<span class="action">
			<?php echo JText::_( 'added tasks' ); ?>
		</span>

		<span class="tasks">
			<?php echo $aggregateTitle; ?>
		</span>
	<?php } else { ?>
		<span class="action">
			<?php echo JText::_( 'added new task' ); ?>
		</span>

		<span class="tasks">
			<?php echo $task->title; ?>
		</span>
	<?php } ?>

</div>
