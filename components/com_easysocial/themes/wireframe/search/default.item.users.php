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

$user = Foundry::user( $item->uid );

?>
<li data-search-item
	data-search-item-id="<?php echo $item->id; ?>"
	data-search-item-type="<?php echo $item->utype; ?>"
	data-search-item-typeid="<?php echo $item->uid; ?>"
	data-search-custom-name="<?php echo $user->getName();?>"
	data-search-custom-avatar="<?php echo $user->getAvatar();?>"
	data-friend-uid="<?php echo $user->id; ?>"
	>
	<div class="es-item">
		<a class="es-avatar pull-left mr-10" href="<?php echo $user->getPermalink();?>">
			<img src="<?php echo $user->getAvatar();?>" title="<?php echo Foundry::get( 'String' )->escape( $user->getName() ); ?>" />
		</a>
		<div class="es-item-body">
			<div class="pull-right">
				<?php if( $user->getFriend( $this->my->id )->state == SOCIAL_FRIENDS_STATE_PENDING ){ ?>
				<a href="javascript:void(0);" class="btn btn-clean small" data-search-friend-pending-button>
					<i class="icon-es-aircon-checkmark mr-10"></i>
					<span class="small"><?php echo JText::_( 'Friend request sent' );?></span>
				</a>
				<?php } else if( $user->getFriend( $this->my->id )->state != SOCIAL_FRIENDS_STATE_FRIENDS ) { ?>

					<?php if( Foundry::privacy( $this->my->id )->validate( 'friends.request' , $user->id ) && !$user->isViewer() ){ ?>

					<a href="javascript:void(0);" class="btn btn-clean small"
						data-search-friend-button
					>
						<i class="icon-es-aircon-user mr-10"></i>
						<span><?php echo JText::_( 'COM_EASYSOCIAL_FRIENDS_ADD_AS_FRIEND' );?></span>
					</a>

					<?php } ?>

				<?php } ?>

				<ul class="dropdown-menu dropdown-arrow-topleft dropdown-friends" data-profileFriends-dropdown>

				</ul>
			</div>

			<div class="es-item-detail">
				<ul class="unstyled">
					<li class="item-name">
						<span class="es-item-title">
							<a href="<?php echo $user->getPermalink();?>"><?php echo $user->getName(); ?></a>
						</span>
					</li>
					<?php if( isset( $item->description ) && $item->description ) { ?>
					<li class="item-friend item-meta small">
						<?php echo $item->description; ?>
					</li>
					<?php } ?>
					<li class="item-friend item-meta">
						<a href="javascript:void(0);"> <?php echo Foundry::get( 'Language', 'COM_EASYSOCIAL_FRIENDS' )->pluralize( $user->getTotalFriends() , true ); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</li>
