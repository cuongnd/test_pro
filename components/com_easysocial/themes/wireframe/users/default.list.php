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
<?php if( !$isSort ){ ?>
<div class="row-fluid mb-10">
	<div data-apps-sorting="" class="btn-group btn-group-view-apps pull-right">
		<a href="<?php echo FRoute::users( array( 'filter' => $filter , 'sort' => 'latest' ) );?>"
			data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_USERS_SORT_LATEST' );?>"
			data-placement="bottom"
			data-es-provide="tooltip"
			data-users-sort
			data-type="latest"
			class="btn btn-small<?php echo $sort == 'latest' ? ' active' : '';?>">
			<i class="ies-bars ies-small"></i>
		</a>
		<a href="<?php echo FRoute::users( array( 'filter' => $filter , 'sort' => 'alphabetical' ) );?>"
			data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_USERS_SORT_ALPHABETICAL' );?>"
			data-placement="bottom"
			data-es-provide="tooltip"
			data-users-sort
			data-type="alphabetical"
			data-apps-sort=""
			class="btn btn-small trending<?php echo $sort == 'alphabetical' ? ' active' : '';?>">
			<i class="ies-fire ies-small"></i>
		</a>
	</div>
</div>
<?php } ?>

<ul class="es-item-grid es-item-grid_2col" data-users-listing>
	<?php foreach( $users as $user ){ ?>
		<?php echo $this->render( 'module' , 'es-users-between-user' ); ?>
		<li data-users-item
		data-id="<?php echo $user->id;?>"
		>
			<div class="es-item">
				<div class="es-avatar-wrap pull-left">
					<a href="<?php echo $user->getPermalink();?>" class="es-avatar pull-left">
						<img src="<?php echo $user->getAvatar( SOCIAL_AVATAR_MEDIUM );?>" alt="<?php echo $this->html( 'string.escape' , $user->getName() );?>" />
					</a>

					<?php echo $this->loadTemplate( 'site/utilities/user.online.state' , array( 'online' => $user->isOnline() , 'size' => 'small' ) ); ?>
				</div>

				<div class="es-item-body">

					<?php if( ($this->access->allowed( 'reports.submit' ) && $this->config->get( 'reports.enabled' ) )  || (Foundry::privacy( $this->my->id )->validate( 'profiles.post.message' , $user->id ) && $this->config->get( 'conversations.enabled' ) ) ){ ?>
					<div class="pull-right btn-group">
						<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_ loginLink btn btn-dropdown">
							<i class="icon-es-dropdown"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-user messageDropDown">

							<?php if( $this->access->allowed( 'reports.submit' ) && $this->config->get( 'reports.enabled' ) ){ ?>
							<li>
								<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_USER , $user->id , $user->getName() , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER' ) , '' , JText::_( 'COM_EASYSOCIAL_PROFILE_REPORT_USER_DESC' ) , $user->getPermalink( true , true ) ); ?>
							</li>
							<?php } ?>

							<?php if( Foundry::privacy( $this->my->id )->validate( 'profiles.post.message' , $user->id ) && $this->access->allowed( 'conversations.create' ) ){ ?>
							<li>
								<a href="javascript:void(0);"
									data-es-conversations-compose
									data-es-conversations-id="<?php echo $user->id;?>"><?php echo JText::_( 'COM_EASYSOCIAL_USERS_START_CONVERSATION' );?></a>
							</li>
							<?php } ?>
						</ul>
					</div>
					<?php } ?>

					<div class="es-item-detail">
						<ul class="unstyled">
							<li>
								<div class="es-item-title">
									<a href="<?php echo $user->getPermalink();?>"><?php echo $user->getName();?></a>
								</div>
							</li>
							<li class="mt-10">
								<a href="<?php echo FRoute::friends( array( 'userid' => $user->getAlias() ) );?>" class="small muted">
									<i class="ies-users-2 ies-small"></i>

									<?php if( $user->getTotalFriends() ){ ?>
										<?php echo $user->getTotalFriends();?> <?php echo JText::_( Foundry::string()->computeNoun( 'COM_EASYSOCIAL_FRIENDS' , $user->getTotalFriends() ) ); ?>
									<?php } else { ?>
										<?php echo JText::_( 'COM_EASYSOCIAL_NO_FRIENDS_YET' ); ?>
									<?php } ?>
								</a>
							</li>
							<li class="mt-5">
								<a href="<?php echo FRoute::followers( array( 'userid' => $user->getAlias() ) );?>" class="small muted">
									<i class="ies-tree-view ies-small"></i>
									<?php if( $user->getTotalFollowers() ){ ?>
										<?php echo $user->getTotalFollowers();?> <?php echo JText::_( Foundry::string()->computeNoun( 'COM_EASYSOCIAL_FOLLOWERS' , $user->getTotalFollowers() ) ); ?>
									<?php } else { ?>
										<?php echo JText::_( 'COM_EASYSOCIAL_NO_FOLLOWERS_YET' ); ?>
									<?php } ?>
								</a>
							</li>

							<?php if( $this->config->get('badges.enabled' ) ){ ?>
							<li class="mt-5">
								<a href="<?php echo FRoute::badges( array( 'userid' => $user->getAlias() , 'layout' => 'achievements') );?>" class="small muted">
									<i class="ies-crown ies-small"></i>
									<?php if( $user->getTotalbadges() ){ ?>
										<?php echo $user->getTotalbadges();?> <?php echo JText::_( Foundry::string()->computeNoun( 'COM_EASYSOCIAL_BADGES' , $user->getTotalbadges() ) ); ?>
									<?php } else { ?>
										<?php echo JText::_( 'COM_EASYSOCIAL_NO_BADGES_YET' ); ?>
									<?php } ?>
								</a>
							</li>
							<?php } ?>

							<li class="mt-20">
								<?php if( $user->isFriends( $this->my->id ) ){ ?>
									<?php echo $this->loadTemplate( 'site/users/button.friends' ); ?>
								<?php } else { ?>
									<?php if( $user->getFriend( $this->my->id )->state == SOCIAL_FRIENDS_STATE_PENDING ){ ?>
										<?php echo $this->loadTemplate( 'site/users/button.pending' ); ?>
									<?php } else { ?>
										<?php echo $this->loadTemplate( 'site/users/button.add' , array( 'user' => $user ) ); ?>
									<?php } ?>
								<?php } ?>

							</li>
						</ul>
					</div>
				</div>
			</div>
		</li>
	<?php } ?>
</ul>
