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
<?php if( !$user->isViewer() ){ ?>
<div class="es-header es-header-mini">

	<div class="es-header-mini-cover" style="background-image: url('<?php echo $user->getCover()->getSource();?>');background-position: <?php echo $user->getCover()->getPosition();?>;">
		<b></b>
		<b></b>
	</div>

	<div class="es-header-avatar">
		<a class="es-avatar es-avatar-medium" href="<?php echo $user->getPermalink();?>">
			<img alt="<?php echo $this->html( 'string.escape' , $user->getName() );?>" src="<?php echo $user->getAvatar( SOCIAL_AVATAR_SQUARE );?>" />
		</a>
		
		<?php echo $this->loadTemplate( 'site/utilities/user.online.state' , array( 'online' => $user->isOnline() , 'size' => 'small' ) ); ?>
	</div>

	<div class="es-header-content" data-appscroll>

		<div class="es-header-info">
			<ul class="unstyled">
				<li>
					<h2 class="h4 es-cover-title">
						<a href="<?php echo $user->getPermalink();?>" title="<?php echo $this->html( 'string.escape' , $user->getName() );?>"><?php echo $user->getName();?></a>
					</h2>
				</li>

				<?php if( $this->template->get( 'profile_last_online' ) ){ ?>
				<li class="mt-5 teaser-about small">
					<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_LAST_SEEN' );?>, <strong><?php echo $user->getLastVisitDate( 'lapsed' ); ?></strong>
				</li>
				<?php } ?>
			</ul>

			<div class="small">
				<a href="<?php echo FRoute::profile( array( 'id' => $user->getAlias() , 'layout' => 'about' ) );?>"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_MORE_INFO' ); ?></a>

				<?php if( $this->access->allowed( 'reports.submit' ) ){ ?>
				&bull;
				<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_USER , $user->id , $user->getName() , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER' ) , '' , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER_DESC' ) , $user->getPermalink( true , true )); ?>
				<?php } ?>
			</div>

		</div>

		<?php if( $user->getApps( 'profile' ) ){ ?>
		<div class="btn- btn-scroll" data-appscroll-buttons>
			<a href="javascript:void(0);" class="btn btn-left" data-appscroll-prev-button>
				<i class="ies-arrow-left"></i>
			</a>
			<a href="javascript:void(0);" class="btn btn-right" data-appscroll-next-button>
				<i class="ies-arrow-right"></i>
			</a>
		</div>

		<div class="es-action" data-appscroll-viewport>
			<ul class="es-nav es-nav- es-nav-apps" data-appscroll-content>
				<?php foreach( $user->getApps( 'profile' ) as $app ){ ?>
				<li>
					<a class="btn btn-clean" href="<?php echo $app->getUserPermalink( $user->getAlias() );?>">
						<span><?php echo $app->get( 'title' ); ?></span>
						<img src="<?php echo $app->getIcon();?>" class="es-nav-apps-icons" />
					</a>
				</li>				
				<?php } ?>
			</ul>
		</div>
		<?php } ?>		

	</div>

	<div class="es-header-footer">
		<div class="row-fluid">

			<div class="pull-left">
				<?php
					$privacy = $this->my->getPrivacy();

					if( $privacy->validate( 'friends.request' , $user->id ) )
					{
				?>
				<span class="action"
					data-id="<?php echo $user->id; ?>"
					data-callback="<?php echo base64_encode( JRequest::getURI() ); ?>"
					data-profile-friends
					data-friend="<?php echo $user->getFriend( $this->my->id )->id;?>"
				>
					<?php echo $this->loadTemplate( 'site/profile/default.header.friends' , array( 'user' => $user ) ); ?>
				</span>
				<?php } ?>

				<?php if( $this->config->get( 'followers.enabled' ) ){ ?>
				<span class="action" 
					data-profile-followers
					data-id="<?php echo $user->id; ?>"
				>
					<?php if( Foundry::get( 'Subscriptions' )->isFollowing( $user->id , SOCIAL_TYPE_USER ) ){ ?>
						<?php echo $this->loadTemplate( 'site/profile/button.followers.unfollow' ); ?>
					<?php } else { ?>
						<?php echo $this->loadTemplate( 'site/profile/button.followers.follow' ); ?>
					<?php } ?>
				</span>
				<?php } ?>
			</div>

			<?php if( $this->my->getPrivacy()->validate( 'profiles.post.message' , $user->id ) && $this->config->get( 'conversations.enabled' ) && $this->access->allowed( 'conversations.create' ) ){ ?>
			<div class="pull-right">
				<span class="action">
					<?php echo $this->loadTemplate( 'site/profile/button.conversations.new' ); ?>
				</span>
			</div>
			<?php } ?>

		</div>
	</div>
</div>
<?php } ?>