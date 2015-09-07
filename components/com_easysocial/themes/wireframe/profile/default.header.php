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
<div class="es-header" data-profile-header data-id="<?php echo $user->id;?>" data-name="<?php echo $this->html( 'string.escape' , $user->getName() );?>" data-avatar="<?php echo $user->getAvatar();?>">
	
	<div class="es-header-cover <?php echo $this->template->get( 'profile_cover' ) ? ' with-cover' : ' without-cover';?>">

		<?php if( $this->template->get( 'profile_cover' ) ){ ?>
		<?php if( !isset( $showCover ) || $showCover ){ ?>
		<?php echo $this->includeTemplate("site/profile/cover"); ?>
		<?php } ?>
		<?php } ?>

		<?php echo $this->includeTemplate("site/profile/avatar"); ?>

		<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'afterAvatar' , array( $user ) ); ?>
	</div>

	<div class="es-header-content">
		

		<div class="es-action pull-right">

			<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'beforeActions' , array( $user ) ); ?>

			<?php echo $this->render( 'module' , 'es-profile-before-actions' ); ?>
			<ul class="es-nav es-nav-stacked pull-right user-actions">
				<?php if( $user->id != $this->my->id ){ ?>
					<?php
						$privacy = $this->my->getPrivacy();

						if( $privacy->validate( 'friends.request' , $user->id ) )
						{
					?>
					<li class="friendsAction"
						data-id="<?php echo $user->id; ?>"
						data-callback="<?php echo base64_encode( JRequest::getURI() ); ?>"
						data-profile-friends
						data-friend="<?php echo $user->getFriend( $this->my->id )->id;?>"
					>
						<?php echo $this->loadTemplate( 'site/profile/default.header.friends' , array( 'user' => $user ) ); ?>
					</li>

					<?php } ?>

					<?php if( $this->config->get( 'followers.enabled' ) ){ ?>
					<li class="followAction"
						data-id="<?php echo $user->id; ?>"
						data-profile-followers
						style="position:relative;"
					>
						<?php if( Foundry::get( 'Subscriptions' )->isFollowing( $user->id , SOCIAL_TYPE_USER ) ){ ?>
							<?php echo $this->loadTemplate( 'site/profile/button.followers.unfollow' ); ?>
						<?php } else { ?>
							<?php echo $this->loadTemplate( 'site/profile/button.followers.follow' ); ?>
						<?php } ?>
					</li>
					<?php } ?>

					<?php if( $privacy->validate( 'profiles.post.message' , $user->id ) && $this->config->get( 'conversations.enabled' ) && $this->access->allowed( 'conversations.create' ) ){ ?>
					<li>
						<?php echo $this->loadTemplate( 'site/profile/button.conversations.new' ); ?>
					</li>
					<?php } ?>

				<?php } else { ?>
					<li>
						<a href="<?php echo FRoute::profile( array( 'layout' => 'edit' ));?>" class="btn btn-clean">
							<i class="icon-es-aircon-document"></i>
							<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_UPDATE_PROFILE' );?>
						</a>
					</li>
				<?php } ?>

				<?php if( $this->template->get( 'profile_points' , true ) && $this->config->get( 'points.enabled' ) ){ ?>
					<li>
						<a href="<?php echo FRoute::points( array( 'userid' => $user->getAlias() , 'layout' => 'history' ) );?>" class="btn btn-clean">

							<div class="center">
								<strong><?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_POINTS' );?></strong>
							</div>

							<div class="mt-10 center">
								<span style="font-size: 26px;font-weight:700;"><?php echo $user->getPoints();?></span>
							</div>
						</a>
					</li>
				<?php } ?>
			</ul>
			<?php echo $this->render( 'module' , 'es-profile-after-actions' ); ?>

			<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'afterActions' , array( $user ) ); ?>
		</div>



		<div class="es-header-info">
			<ul class="unstyled">
				<li>
					<?php echo $this->render( 'module' , 'es-profile-before-name' ); ?>

					<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'beforeName' , array( $user ) ); ?>

					<h2 class="h3 es-cover-title">
						<a href="<?php echo $user->getPermalink();?>"><?php echo $user->getName();?></a>
					</h2>

					<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'afterName' , array( $user ) ); ?>

					<?php echo $this->render( 'module' , 'es-profile-after-name' ); ?>

					<div class="user-meta">
						<a href="<?php echo FRoute::friends( array( 'userid' => $user->getAlias() ) );?>">
							<?php echo $user->getTotalFriends();?> <?php echo JText::_( Foundry::string()->computeNoun( 'COM_EASYSOCIAL_FRIENDS' , $user->getTotalFriends() ) ); ?>
						</a> 
						&middot; 
						<a href="<?php echo FRoute::followers( array( 'userid' => $user->getAlias() ) );?>">
							<?php echo $user->getTotalFollowers();?> <?php echo JText::_( Foundry::string()->computeNoun( 'COM_EASYSOCIAL_FOLLOWERS' , $user->getTotalFollowers() ) ); ?>
						</a> 

						<?php if( $this->config->get('badges.enabled' ) ){ ?>
						&middot; 
						<a href="<?php echo FRoute::badges( array( 'layout' => 'achievements' , 'userid' => $user->getAlias() ) );?>">
							<?php echo $user->getTotalBadges();?> <?php echo JText::_( Foundry::string()->computeNoun( 'COM_EASYSOCIAL_ACHIEVEMENTS' , $user->getTotalBadges() ) ); ?>
						</a> 
						<?php } ?>
					</div>

				</li>

				<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'beforeBadges' , array( $user ) ); ?>

				<?php if( $this->config->get( 'badges.enabled' ) && $user->getBadges() && $this->template->get( 'profile_badges' ) ){ ?>
				<li>
					<ul class="unstyled es-badge-list">
						<?php foreach( $user->getBadges() as $badge ){ ?>
						<li class="es-badge-item">
							<a href="<?php echo $badge->getPermalink();?>" class="badge-link" data-es-provide="tooltip" data-placement="top" data-original-title="<?php echo $this->html( 'string.escape' , $badge->get( 'title' ) );?>">
							<img class="es-badge-icon" alt="<?php echo $this->html( 'string.escape' , $badge->get( 'title' ) );?>" src="<?php echo $badge->getAvatar();?>"></a>
						</li>
						<?php } ?>
					</ul>
				</li>
				<?php } ?>

				<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'afterBadges' , array( $user ) ); ?>

				<?php if( $this->template->get( 'profile_age' , true ) ){ ?>
				<li class="mt-5 teaser-about small">
					<div class="small">
						<?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderA' , array( 'BIRTHDAY' , $user ) ); ?>
					</div>
				</li>
				<?php } ?>

				<?php if( $this->template->get( 'profile_gender' , true ) ){ ?>
				<li class="mt-5 teaser-about small">
					<div class="small">
						<?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderA' , array( 'GENDER' , $user ) ); ?>
					</div>
				</li>
				<?php } ?>

				<?php if( $this->template->get( 'profile_address' , true ) ){ ?>
				<li class="mt-5 teaser-about">
					<div class="small"><?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderB' , array( 'ADDRESS' , $user ) ); ?></div>
				</li>
				<?php } ?>

				<?php if( $this->template->get( 'profile_website' , true ) ){ ?>
				<li class="mt-5 teaser-about">
					<div class="small">
						<?php echo $this->render( 'fields' , 'user' , 'profile' , 'profileHeaderD' , array( 'URL' , $user ) ); ?>
					</div>
				</li>
				<?php } ?>
			</ul>

			<?php echo $this->render( 'module' , 'es-profile-before-info' ); ?>

			<div class="mt-10">
				<a href="<?php echo FRoute::profile( array( 'id' => $user->getAlias() , 'layout' => 'about' ) );?>"><?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_MORE_INFO' ); ?></a>

				<?php if( $this->my->id != $user->id && $this->template->get( 'profile_report' , true ) && $this->access->allowed( 'reports.submit' ) && $this->config->get( 'reports.enabled' ) ){ ?>
				&middot;
				<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_USER , $user->id , $user->getName() , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER' ) , '' , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER_DESC' ) , $user->getPermalink( true , true ) ); ?>
				<?php } ?>
			</div>

			<?php echo $this->render( 'module' , 'es-profile-after-info' ); ?>

			<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'afterInfo' , array( $user ) ); ?>

		</div>

	</div>

</div>
