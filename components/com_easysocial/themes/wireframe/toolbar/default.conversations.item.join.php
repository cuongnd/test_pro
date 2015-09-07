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
<div class="object-title">
	<?php echo Foundry::get( 'String' )->namesToStream( $conversation->getParticipants( $this->my->id ) , false , 5 ); ?>
</div>

<div class="object-content small">
	<?php echo $message->getCreator()->getStreamName( false ); ?> <?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_INVITED' ); ?> <?php echo $message->getTarget()->getStreamName( false );?> <?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_INTO_THIS_CONVERSATION' );?>
</div>

<div class="object-timestamp mt-5">
	<small><?php echo Foundry::date( $conversation->lastreplied )->toLapsed(); ?></small>
</div>