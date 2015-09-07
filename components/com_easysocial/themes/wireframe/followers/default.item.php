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
<li class="followerItem"
	data-id="<?php echo $user->id;?>"
	data-followers-item
>
	<div class="es-item">
		<div class="es-avatar-wrap pull-left">
			<a href="<?php echo $user->getPermalink();?>" class="es-avatar pull-left">
				<img src="<?php echo $user->getAvatar( SOCIAL_AVATAR_MEDIUM );?>" alt="<?php echo $this->html( 'string.escape' , $user->getName() );?>" />
			</a>
			<?php echo $this->loadTemplate( 'site/utilities/user.online.state' , array( 'online' => $user->isOnline() , 'size' => 'small' ) ); ?>
		</div>

		<div class="es-item-body">

			<div class="pull-right btn-group">
				<a class="dropdown-toggle_ loginLink btn btn-dropdown" data-foundry-toggle="dropdown" href="javascript:void(0);">
					<i class="icon-es-dropdown"></i>
				</a>

				<?php if( $this->access->allowed( 'reports.submit' ) || ($this->my->id == $currentUser->id && $active != 'followers' ) ){ ?>
				<ul class="dropdown-menu dropdown-menu-user messageDropDown">

					<?php if( $active != 'followers' ){ ?>
					<li data-followers-item-unfollow>
						<a href="javascript:void(0);">
							<?php echo JText::_( 'COM_EASYSOCIAL_FOLLOWERS_UNFOLLOW' );?>
						</a>
					</li>
					<li class="divider">
						<hr />
					</li>
					<?php } ?>

					<?php if( $this->access->allowed( 'reports.submit' ) ){ ?>
					<li>
						<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_USER , $user->id , $user->getName() , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER' ) , '' , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER_DESC' ) , $user->getPermalink() ); ?>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>

			</div>

			<div class="es-item-detail">
				<div class="es-item-title">
					<a href="<?php echo $user->getPermalink();?>"><?php echo $user->getName();?></a>
				</div>
				<ul class="unstyled es-friends-links">
					<li class="mt-10">
						<a href="<?php echo FRoute::friends( array( 'userid' => $user->getAlias() ) );?>" class="small muted">
							<i class="ies-users-2 ies-small"></i>

							<?php if( $user->getTotalFriends() ){ ?>
								<?php echo $user->getTotalFriends();?> <?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS' ); ?>
							<?php } else { ?>
								<?php echo JText::_( 'COM_EASYSOCIAL_NO_FRIENDS_YET' ); ?>
							<?php } ?>
						</a>
					</li>
					<li class="mt-5">
						<a href="<?php echo FRoute::followers( array( 'userid' => $user->getAlias() ) );?>" class="small muted">
							<i class="ies-heart ies-small"></i>
							<?php if( $user->getTotalFollowers() ){ ?>
								<?php echo $user->getTotalFollowers();?> <?php echo JText::_( 'COM_EASYSOCIAL_FOLLOWERS' ); ?>
							<?php } else { ?>
								<?php echo JText::_( 'COM_EASYSOCIAL_NO_FOLLOWERS_YET' ); ?>
							<?php } ?>
						</a>
					</li>

					<?php if ( $this->config->get( 'badges.enabled' ) ) { ?>
					<li class="mt-5">
						<a href="<?php echo FRoute::badges( array( 'layout' => 'achievements' , 'userid' => $user->getAlias() ) );?>" class="small muted">
							<i class="ies-crown ies-small"></i>
							<?php if( $user->getTotalbadges() ){ ?>
								<?php echo $user->getTotalbadges();?> <?php echo JText::_( 'COM_EASYSOCIAL_BADGES' ); ?>
							<?php } else { ?>
								<?php echo JText::_( 'COM_EASYSOCIAL_NO_BADGES_YET' ); ?>
							<?php } ?>
						</a>
					</li>
					<?php } ?>

					<?php if( $this->my->id != $user->id && $this->access->allowed( 'conversations.create' ) ){ ?>
						<?php if( Foundry::privacy( $this->my->id )->validate( 'profiles.post.message' , $user->id ) ){ ?>
						<li class="mt-20" data-followers-item-compose>
							<a href="javascript:void(0);">
								<i class="icon-es-pm"></i> <?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_SEND_MESSAGE' ); ?>
							</a>
						</li>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</li>
