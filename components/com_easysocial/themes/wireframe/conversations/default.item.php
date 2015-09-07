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
<?php if( $conversations ){ ?>
	<?php foreach( $conversations as $conversation ){ ?>

		<?php echo $this->render( 'module' , 'es-conversations-between-conversation' ); ?>

		<li class="conversation-item <?php echo $conversation->isNew( $this->my->id ) ? 'unread' : 'read';?>" data-id="<?php echo $conversation->id;?>" data-conversations-item>
			<div class="row-fluid">

				<div class="checkbox-column pl-5">
					<input type="checkbox" name="conversationCheckbox" value="<?php echo $conversation->id;?>" class="item-check" data-conversationItem-checkbox />
				</div>

				<div class="content-column">
					<div class="row-fluid">
						<div class="span12">
							<div class="avatar-wrap">
								<?php foreach( $conversation->getParticipants( $this->my->id ) as $participant ){ ?>
									<span class="es-avatar es-avatar-small es-borderless es-borderthin"
										data-original-title="<?php echo $this->html( 'string.escape' , $participant->getName() );?>"
										data-es-provide="tooltip"
										data-placement="bottom"
									>
										<a href="<?php echo FRoute::conversations( array( 'layout' => 'read' , 'id' => $conversation->id ) );?>">
											<img class="es-avatar es-avatar-small" src="<?php echo $participant->getAvatar();?>" alt="<?php echo $this->html( 'string.escape' ,  $participant->getName() );?>" />
										</a>
									</span>
								<?php } ?>
							</div>

							<?php if( $conversation->hasAttachments() ){ ?>
							<i class="ies-attachment ies-small pull-left mr-5 with-attachments"
								data-es-provide="tooltip"
								data-placement="bottom"
								data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_WITH_ATTACHMENTS' , true );?>"
								></i>
							<?php } ?>

							<a href="<?php echo FRoute::conversations( array( 'layout' => 'read' , 'id' => $conversation->id ) );?>" class="conversation-title">
								<?php echo Foundry::get( 'String' )->namesToStream( $conversation->getParticipants( $this->my->id ) , false , 5 ); ?>
							</a>

							<?php if( $conversation->getLastMessage( $this->my->id ) ){ ?>
							<div class="conversation-meta small mt-5">
								<?php if( $conversation->getLastMessage($this->my->id)->created_by == $this->my->id ){ ?>
								<i class="ies-share-2 ies-small"
									data-es-provide="tooltip"
									data-placement="bottom"
									data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATION_YOU_HAVE_REPLIED_HERE' );?>"
								></i>
								<?php } ?>
								<?php echo $conversation->getLastMessage( $this->my->id )->getContents(); ?>
							</div>
							<?php } ?>
							<div class="conversation-lapsed">
								<time class="message-time small mr-5" title="<?php echo Foundry::get( 'Date' , $conversation->lastreplied )->toLapsed();?>">
									<i class="ies-clock-2 ies-small"></i>
									<?php echo Foundry::get( 'Date' , $conversation->lastreplied )->toLapsed();?>
								</time>
							</div>
						</div>
					</div>
				</div>
			</div>
		</li>
	<?php } ?>

	<?php if( $pagination && ( $pagination->total > $pagination->limit ) ) { ?>
	<li>
		<!-- Pagination -->
		<div class="pagination-wrapper mt-10">
			<?php echo $pagination->getListFooter( 'site' ); ?>
		</div>
	</li>
	<?php } ?>
<?php } ?>
