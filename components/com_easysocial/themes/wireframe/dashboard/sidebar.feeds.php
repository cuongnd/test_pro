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
<div class="es-widget es-widget-borderless">
	<div class="es-widget-head">
		<?php echo JText::_( 'COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NEWSFEEDS' );?>
	</div>

	<div class="es-widget-body">
		<ul class="es-nav es-nav-stacked feed-items" data-dashboard-feeds>


			<li class="<?php echo !$isAppView && ( empty( $filter ) || $filter == 'me' ) ? 'active' : '';?>"
				data-dashboardSidebar-menu
				data-dashboardFeeds-item
				data-type="me"
				data-id=""
				data-url="<?php echo FRoute::dashboard();?>"
				data-title="<?php echo $this->html( 'string.escape' , $this->my->getName() ) . ' - ' . JText::_( 'COM_EASYSOCIAL_DASHBOARD_FEED_ME_AND_FRIENDS' , true ); ?>"
			>
				<a href="javascript:void(0);">
					<i class="icon-es-aircon-user mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_DASHBOARD_SIDEBAR_ME_AND_FRIENDS' );?>
					<div class="label label-notification pull-right mr-20" data-stream-counter-me>0</div>
				</a>
			</li>

			<li class="<?php echo $filter == 'everyone' ? ' active' : '';?>"
				data-dashboardSidebar-menu
				data-dashboardFeeds-item
				data-type="everyone"
				data-id=""
				data-url="<?php echo FRoute::dashboard( array( 'type' => 'everyone' ) );?>"
				data-title="<?php echo $this->html( 'string.escape' , $this->my->getName() ) . ' - ' . JText::_( 'COM_EASYSOCIAL_DASHBOARD_FEED_DASHBOARD_EVERYONE' , true ); ?>"
			>
				<a href="javascript:void(0);">
					<i class="icon-es-genius mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_DASHBOARD_SIDEBAR_NEWSFEEDS_EVERYONE' );?>
					<div class="label label-notification pull-right mr-20" data-stream-counter-everyone>0</div>
				</a>
			</li>


			<?php if( $this->config->get( 'followers.enabled' ) ){ ?>
			<li class="dashboard-filter<?php echo $filter == 'following' ? ' active' : '';?>"
				data-dashboardSidebar-menu
				data-dashboardFeeds-item
				data-type="following"
				data-id=""
				data-url="<?php echo FRoute::dashboard( array( 'type' => 'following' ) );?>"
				data-title="<?php echo $this->html( 'string.escape' , $this->my->getName() ) . ' - ' . JText::_( 'COM_EASYSOCIAL_DASHBOARD_FEED_FOLLLOW' ); ?>"
			>
				<a href="javascript:void(0);">
					<i class="icon-es-aircon-following mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_DASHBOARD_FEEDS_FOLLOWING' );?>
					<div class="label label-notification pull-right mr-20" data-stream-counter-following>0</div>
				</a>
			</li>
			<?php } ?>

			<?php if( $this->config->get( 'friends.list.enabled' ) ){ ?>
				<?php if( $lists && count( $lists ) > 0 ) { ?>
					<?php foreach( $lists as $list ){ ?>
					<li class="dashboard-filter<?php echo $listId == $list->id ? ' active' : '';?>"
						data-dashboardSidebar-menu
						data-dashboardFeeds-item
						data-type="list"
						data-id="<?php echo $list->id;?>"
						data-url="<?php echo FRoute::dashboard( array( 'type' => 'list' , 'listId' => $list->id ) );?>"
						data-title="<?php echo $this->html( 'string.escape' , $this->my->getName() ) . ' - ' . $this->html( 'string.escape' , $list->get( 'title' ) ); ?>"
					>
						<a href="javascript:void(0);">
							<i class="icon-es-aircon-document mr-5"></i> <?php echo $list->title; ?>
							<div class="label label-notification pull-right mr-20" data-stream-counter-list-<?php echo $list->id; ?>>0</div>
						</a>
					</li>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		</ul>
	</div>

</div>
