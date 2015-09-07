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
<a href="javascript:void(0);" class="btn btn-clean btn-action-friends is-friends"
	data-profileFriends-button
	data-profileFriends-manage
	data-foundry-toggle="dropdown"
>
	<i class="ies-checkmark mr-10"></i>
	<span><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_FRIENDS' );?></span>
	<i class="ies-arrow-down ies-small"></i>
</a>

<ul class="dropdown-menu dropdown-arrow-topleft dropdown-friends" data-profileFriends-dropdown>
	<li data-friends-unfriend>
		<a href="javascript:void(0);" data-friends-unfriend><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_UNFRIEND' );?></a>
	</li>
</ul>