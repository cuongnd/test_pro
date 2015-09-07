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
<li class="toolbarItem toolbar-profile" data-toolbar-profile>
	<a href="javascript:void(0);" class="dropdown-toggle_ login-link loginLink">
		<span class="es-avatar es-borderless">
			<img src="<?php echo $this->my->getAvatar();?>" alt="<?php echo $this->html( 'string.escape' , $this->my->getName() );?>" />
		</span>
		<span class="toolbar-user-name"><?php echo $this->my->getName();?></span>
		<b class="caret"></b>
	</a>
	
	<div style="display:none;" data-toolbar-profile-dropdown>
		<ul class="popbox-dropdown-menu dropdown-menu-user" style="display: block;">
			<li>
				<h5 class="ml-10">
					<i class="icon-es-account"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_HEADING_ACCOUNT' );?>
				</h5>
			</li>

			<li class="divider"></li>
			<li>
				<a href="<?php echo FRoute::profile();?>">
					<i class="ies-vcard ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_VIEW_YOUR_PROFILE' );?>
				</a>
			</li>
			<li>
				<a href="<?php echo FRoute::friends();?>">
					<i class="ies-users-2 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_FRIENDS' );?>
				</a>
			</li>
			<?php if( $this->config->get( 'followers.enabled' ) ){ ?>
			<li>
				<a href="<?php echo FRoute::followers();?>">
					<i class="ies-tree-view ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_FOLLOWERS' );?>
				</a>
			</li>
			<?php } ?>
			
			<?php if( $this->config->get( 'photos.enabled' ) ){ ?>
			<li>
				<a href="<?php echo FRoute::albums();?>">
					<i class="ies-picture ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_PHOTOS' );?>
				</a>
			</li>
			<?php } ?>

			<?php if( $this->config->get( 'badges.enabled' ) ){ ?>
			<li>
				<a href="<?php echo FRoute::badges( array( 'layout' => 'achievements' ) );?>">
					<i class="ies-crown ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_ACHIEVEMENTS' );?>
				</a>
			</li>
			<?php } ?>

			<?php if( $this->config->get( 'points.enabled' ) ){ ?>
			<li>
				<a href="<?php echo FRoute::points( array( 'layout' => 'history' , 'userid' => $this->my->getAlias() ) );?>">
					<i class="ies-health ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_POINTS_HISTORY' );?>
				</a>
			</li>
			<?php } ?>
			
			<?php if( $this->config->get( 'conversations.enabled' ) ){ ?>
			<li>
				<a href="<?php echo FRoute::conversations();?>">
					<i class="ies-comments-2 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_CONVERSATIONS' );?>
				</a>
			</li>
			<?php } ?>
			<li>
				<a href="<?php echo FRoute::apps();?>">
					<i class="ies-cube ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_APPS' );?>
				</a>
			</li>
			<li>
				<a href="<?php echo FRoute::activities();?>">
					<i class="ies-cabinet ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_ACTIVITIES' );?>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<h5 class="ml-10">
					<i class="icon-es-settings"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_HEADING_PREFERENCES' ); ?>
				</h5>
			</li>
			<li class="divider"></li>
			<li>
				<a href="<?php echo FRoute::profile( array( 'layout' => 'edit' ) );?>">
					<i class="ies-cog-2 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_ACCOUNT_SETTINGS' );?>
				</a>
			</li>
			<li>
				<a href="<?php echo FRoute::profile( array( 'layout' => 'editPrivacy' ) );?>">
					<i class="ies-key ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PRIVACY_SETTINGS' );?>
				</a>
			</li>
			<li>
				<a href="<?php echo FRoute::profile( array( 'layout' => 'editNotifications' ) );?>">
					<i class="ies-mail-3 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_NOTIFICATION_SETTINGS' );?>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="javascript:void(0);" class="logout-link" data-toolbar-logout-button>
					<i class="ies-switch ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_PROFILE_LOGOUT' );?>
				</a>
				<form class="logout-form" data-toolbar-logout-form>
					<input type="hidden" name="return" value="<?php echo $logoutReturn;?>" />
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.logout" />
					<?php echo $this->html( 'form.token' ); ?>
				</form>
			</li>
		</ul>
	</div>
</li>
