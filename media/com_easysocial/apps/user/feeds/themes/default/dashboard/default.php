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
<div class="app-user-feeds-wrapper dashboard" data-feeds>
	<div class="row-fluid small filter-tasks mt-10">
		<div class="pull-right">
			<a href="javascript:void(0);" class="btn btn-es-inverse btn-medium small" data-feeds-create>
				<?php echo JText::_( 'APP_FEEDS_NEW_FEED' ); ?>
			</a>
		</div>
	</div>
	<hr />

	<p class="small mb-20">
		<?php echo JText::_( 'APP_FEEDS_DASHBOARD_INFO' ); ?>
	</p>

	<ul class="unstyled feeds-list" data-feeds-lists>
		<?php if( $feeds ){ ?>
			<?php foreach( $feeds as $feed ){ ?>
				<?php echo $this->loadTemplate( 'themes:/apps/user/feeds/dashboard/default.item' , array( 'feed' => $feed ) ); ?>
			<?php } ?>
		<?php } ?>
	</ul>
	
	<div class="empty center<?php echo $feeds ? ' hide' : '';?>" data-feeds-empty>
		<?php echo JText::_( 'APP_FEEDS_NO_FEEDS_YET' ); ?>
	</div>

</div>
