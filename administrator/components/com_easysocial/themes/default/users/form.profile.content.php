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
<div class="wrapper accordion">
	<div class="tab-box tab-box-alt tab-box-sidenav">
		<div class="tabbable">
			<ul id="userForm" class="nav nav-tabs nav-tabs-icons">
				<li class="tabItem active" data-tabnav data-for="profile">
					<a href="#profile" data-foundry-toggle="tab">
						<i class="ies-user ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_USERS_PROFILE' );?>
					</a>
				</li>
				<?php if( isset( $user ) ){ ?>
				<li class="tabItem" data-tabnav data-for="badges">
					<a href="#badges" data-foundry-toggle="tab">
						<i class="ies-medal ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_USERS_ACHIEVEMENTS' );?>
					</a>
				</li>
				<li class="tabItem" data-tabnav data-for="points">
					<a href="#points" data-foundry-toggle="tab">
						<i class="ies-podium ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_USERS_POINTS' );?>
					</a>
				</li>
				<li class="tabItem" data-tabnav data-for="notifications">
					<a href="#notifications" data-foundry-toggle="tab">
						<i class="ies-feed-2 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_USERS_NOTIFICATIONS' );?>
					</a>
				</li>
				<li class="tabItem" data-tabnav data-for="privacy">
					<a href="#privacy" data-foundry-toggle="tab">
						<i class="ies-locked-2 ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_USERS_PRIVACY' );?>
					</a>
				</li>
				<li class="tabItem" data-tabnav data-for="usergroup">
					<a href="#usergroup" data-foundry-toggle="tab">
						<i class="ies-users ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_USERS_USERGROUP' );?>
					</a>
				</li>
				<?php } ?>
			</ul>

			<div class="tab-content">

				<div id="profile" class="tab-pane active" data-tabcontent data-for="profile">
					<?php echo $this->includeTemplate( 'admin/users/form.profile' ); ?>
				</div>

				<?php if( isset( $user ) ){ ?>
				<div id="badges" class="tab-pane" data-tabcontent data-for="badges">
					<?php echo $this->includeTemplate( 'admin/users/form.badges' ); ?>
				</div>

				<div id="points" class="tab-pane" data-tabcontent data-for="points">
					<?php echo $this->includeTemplate( 'admin/users/form.points' ); ?>
				</div>

				<div id="notifications" class="tab-pane" data-tabcontent data-for="notifications">
					<?php echo $this->includeTemplate( 'admin/users/form.notifications' ); ?>
				</div>

				<div id="privacy" class="tab-pane" data-tabcontent data-for="privacy">
					<?php echo $this->includeTemplate( 'admin/users/form.privacy' ); ?>
				</div>

				<div id="usergroup" class="tab-pane" data-tabcontent data-for="usergroup">
					<?php echo $this->includeTemplate( 'admin/users/form.usergroups' ); ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>

</div>
