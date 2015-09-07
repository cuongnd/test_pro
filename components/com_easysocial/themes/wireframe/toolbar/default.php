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
<?php if( $toolbar ){ ?>
<div class="navbar es-toolbar wide" data-notifications>
	<div class="navbar-inner">
		<div class="nav-collapse collapse">
			<?php if( $this->my->id ){ ?>
			<ul class="es-nav">
				<?php if( $dashboard ){ ?>
				<li class="toolbarItem toolbar-home" data-toolbar-item>
					<a data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_DASHBOARD' , true );?>"
						data-placement="top"
						data-es-provide="tooltip"
						href="<?php echo FRoute::dashboard();?>"
					>
						<i class="icon-es-tb-home"></i>
						<span class="visible-phone"><?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_DASHBOARD' , true );?></span>
					</a>
				</li>
				<li class="divider-vertical"></li>
				<?php } ?>

				<?php if( $friends ){ ?>
					<?php echo $this->loadTemplate( 'site/toolbar/default.friends' , array( 'requests' => $newRequests ) ); ?>
				<?php } ?>

				<?php if( $conversations ){ ?>
					<?php echo $this->loadTemplate( 'site/toolbar/default.conversations' , array( 'newConversations' => $newConversations ) ); ?>
				<?php } ?>

				<?php if( $notifications ){ ?>
					<?php echo $this->loadTemplate( 'site/toolbar/default.notifications' , array( 'newNotifications' => $newNotifications ) ); ?>
				<?php } ?>

			</ul>
			<?php } ?>

			<?php if( $search ){ ?>
			<div class="es-navbar-search pull-right" data-nav-search>
				<form action="<?php echo JRoute::_( 'index.php' );?>" method="post">
					<input type="text" name="q" class="search-query" autocomplete="off" data-nav-search-input placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_TOOLBAR_SEARCH' , true );?>" />

					<div class="dropdown-menu dropdown-menu-search" data-nav-search-dropdown></div>

					<?php echo $this->html( 'form.itemid' ); ?>
					<input type="hidden" name="view" value="search" />
					<input type="hidden" name="option" value="com_easysocial" />
				</form>
			</div>
			<?php } ?>

			<ul class="es-nav pull-right">

				<?php if( !$this->my->id && ( $login ) ){ ?>
				<li class="dropdown_">
					<?php echo $this->includeTemplate( 'site/toolbar/default.login' , array( 'facebook' => $facebook )); ?>
				</li>
				<?php } ?>

				<?php if( $this->my->id && ( $profile ) ){ ?>
					<?php echo $this->includeTemplate( 'site/toolbar/default.profile' ); ?>
				<?php } ?>

			</ul>

		</div>

	</div>
</div>
<?php } ?>
