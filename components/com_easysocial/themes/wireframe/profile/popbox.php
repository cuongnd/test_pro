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
<div class="profile-details">
	<div class="profile-title">
		<a href="<?php echo $user->getPermalink();?>"><?php echo $user->getName();?></a>
	</div>
	<div class="profile-desp">
		<?php if( $user->getLastVisitDate() == '0000-00-00 00:00:00' ){ ?>
			<?php echo JText::_( 'COM_EASYSOCIAL_USER_NEVER_LOGGED_IN' ); ?>
		<?php } else { ?>
			<?php echo JText::_( 'COM_EASYSOCIAL_LAST_LOGGED_IN' ); ?> <?php echo $user->getLastVisitDate( 'lapsed' );?>
		<?php } ?>
	</div>
	<input type="hidden" data-user-id="<?php echo $user->id; ?>" />
</div>
<div class="popbox-cover">
	<div style="background-image: url('<?php echo $user->getCover()->getSource();?>'); background-position: 50% 50%; background-size: cover" class="es-photo-scaled es-photo-wrap"></div>
</div>

<a class="es-avatar es-avatar-medium popbox-avatar" href="<?php echo $user->getPermalink();?>">
	<img alt="<?php echo $this->html( 'string.escape' , $user->getName() );?>" src="<?php echo $user->getAvatar( SOCIAL_AVATAR_MEDIUM ); ?>" />
</a>
<?php echo $this->loadTemplate( 'site/utilities/user.online.state' , array( 'online' => $user->isOnline() , 'size' => 'mini' ) ); ?>

<div class="popbox-info">
	<ul class="unstyled popbox-items">
		<li>
			<div class="popbox-item-info">
				<a href="<?php echo FRoute::friends( array( 'userid' => $user->getAlias() ) );?>">
					<div class="popbox-item-text">
						<?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS' );?>
					</div>
					<div class="popbox-item-total"><?php echo $user->getTotalFriends();?></div>
				</a>
			</div>
		</li>
		<?php if( $this->config->get( 'photos.enabled' ) ){ ?>
		<li>
			<div class="popbox-item-info">
				<a href="<?php echo FRoute::albums( array( 'userid' => $user->getAlias() ) );?>">
					<div class="popbox-item-text">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_ALBUMS' );?>
					</div>
					<div class="popbox-item-total">
						<?php echo $user->getTotalAlbums();?>
					</div>
				</a>
			</div>
		</li>
		<?php } ?>
		<li>
			<div class="popbox-item-info">
				<a href="<?php echo FRoute::followers( array( 'userid' => $user->getAlias() ) );?>">
					<div class="popbox-item-text">
						<?php echo JText::_( 'COM_EASYSOCIAL_FOLLOWERS' );?>
					</div>
					<div class="popbox-item-total"><?php echo $user->getTotalFollowers();?></div>
				</a>
			</div>
		</li>
	</ul>
</div>

<div class="popbox-footer">
	<?php if( !$user->isViewer() ){ ?>
	<div class="pull-right">
		<?php if( $user->getFriend( $this->my->id )->state == SOCIAL_FRIENDS_STATE_FRIENDS ){ ?>
			<div class="btn-group btn-group-friends">
				<?php echo $this->loadTemplate( 'site/profile/popbox.button.friends' , array( 'user' => $user ) ); ?>
			</div>
		<?php } else { ?>
			<?php if( $user->getFriend( $this->my->id )->state == SOCIAL_FRIENDS_STATE_PENDING ){ ?>
				<div class="btn-group btn-group-pending">
					<?php if( $user->getFriend( $this->my->id )->actor_id == $this->my->id ){ ?>
						<?php echo $this->loadTemplate( 'site/profile/popbox.button.requested' , array( 'user' => $user ) ); ?>
					<?php } else { ?>
						<?php echo $this->loadTemplate( 'site/profile/popbox.button.respond' , array( 'user' => $user ) ); ?>
					<?php } ?>
				</div>
			<?php } else { ?>
				<div class="btn-group btn-group-pending">
					<?php echo $this->loadTemplate( 'site/profile/popbox.button.add' , array( 'user' => $user ) ); ?>
				</div>
			<?php } ?>
		<?php } ?>

		<?php if( $this->access->allowed( 'conversations.create' ) ){ ?>
		<div class="btn-group btn-group-message">
			<a class="btn-es btn-message" href="javascript:void(0);" data-popbox-message><i class="ies-mail-3 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_MESSAGE' ); ?></a>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>
