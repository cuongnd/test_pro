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
<a href="javascript:void(0);" class="btn btn-clean dropdown-toggle"
	data-profileFriends-button
	data-profileFriends-respond
	data-foundry-toggle="dropdown"
>
	<i class="icon-es-aircon-adduser mr-10"></i>
	<span><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_RESPOND_TO_REQUEST' );?></span>
	<i class="ies-arrow-down ies-small"></i>
</a>

<ul class="dropdown-menu dropdown-arrow-topleft dropdown-friends" data-profileFriends-dropdown>
	<li data-friends-response-approve>
		<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_APPROVE_FRIEND_REQUEST' );?></a>
		<form name="respondFriend" id="respondFriend" style="display: none;">
			<input type="hidden" name="option" value="com_easysocial" />
			<input type="hidden" name="controller" value="friends" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="id" value="" />
			<input type="hidden" name="return" value="" />
			<input type="hidden" name="<?php echo Foundry::token();?>" value="1" />
		</form>
	</li>
	<li data-friends-response-reject>
		<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_REJECT_FRIEND_REQUEST' );?></a>
	</li>
</ul>