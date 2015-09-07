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
<li class="feed-item" data-feeds-item data-id="<?php echo $feed->id;?>">
	<div class="row-fluid">
		<div class="pull-left">
			<span class="btn-group mr-10">
				<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_ btn btn-dropdown">
					<i class="icon-es-dropdown"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-user messageDropDown small">
					<li>
						<a href="javascript:void(0);" class="small" data-feeds-item-remove>
							<?php echo JText::_( 'APP_FEEDS_REMOVE_ITEM' );?>
						</a>
					</li>
				</ul>
			</span>

			<i class="ies-feed-2 mr-5"></i> 
			<a href="<?php echo $this->html( 'string.escape' , $feed->url );?>" target="_blank">
				<?php echo $feed->title; ?>
				<i class="ies-new-tab ies-small"></i>
			</a>
		</div>
	
		<div class="pull-right small">
			<i class="ies-clock ies-small"></i> <?php echo $feed->created;?>
		</div>
	</div>
</li>