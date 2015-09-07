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

$maxchar    = 20;
$objectImg 	= '';
$objectName = '';
$objectLink = '';

$title 		= ( JString::strlen( $item->title ) > $maxchar ) ? JString::substr( $item->title, 0, $maxchar ) . JText::_( 'COM_EASYSOCIAL_ELLIPSES' ) : $item->title;

switch( $item->utype )
{
	case SOCIAL_INDEXER_TYPE_USERS:

		$user 		= Foundry::user( $item->uid );
		$objectImg 	= $user->getAvatar();

		$name 		= $user->getName();
		$objectName = ( JString::strlen( $name ) > $maxchar ) ? JString::substr( $name, 0, $maxchar ) . JText::_( 'COM_EASYSOCIAL_ELLIPSES' ) : $name;
		$objectLink = $user->getPermalink();
		break;
	case SOCIAL_INDEXER_TYPE_PHOTOS:

		$objectImg 	= ( $item->image ) ? $item->image : '';
		$objectName = $title;
		$objectLink = $item->link;
		break;
	case SOCIAL_INDEXER_TYPE_LISTS:

		$objectImg 	= '';
		$objectName = $title;
		$objectLink = ( $item->link ) ? $item->link : FRoute::friends( array( 'listid' => $item->uid ) );
		break;
	default:

		$objectImg 	= ( $item->image ) ? $item->image : '';
		$objectName = $title;
		$objectLink = $item->link;

		break;
}

?>

<li class="navSearchItem"
	data-search-item
	data-search-item-id="<?php echo $item->id; ?>"
	data-search-item-type="<?php echo $item->utype; ?>"
	data-search-item-typeid="<?php echo $item->uid; ?>"
	data-search-custom-name="<?php echo $objectName; ?>"
	data-search-custom-avatar="<?php echo $objectImg; ?>"
	>

	<?php if( $item->utype == SOCIAL_INDEXER_TYPE_LISTS ) { ?>

		<a href="<?php echo $objectLink; ?>">
			<span class="es-avatar es-borderless pull-left">
				<i class="icon-es-friends"></i>
			</span>
			<span class="es-result-name"><?php echo $objectName; ?></span>
		</a>

	<?php } else { ?>

	<a href="<?php echo $objectLink; ?>">
		<span class="es-avatar es-borderless pull-left">
			<img class="app-icon-small mr-5" src="<?php echo $objectImg; ?>" />
		</span>
		<span class="search-result-name"><?php echo $objectName; ?></span>
	</a>
	<?php } ?>
</li>
