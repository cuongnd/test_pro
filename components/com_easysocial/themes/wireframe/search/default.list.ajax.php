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

$existingType = $last_type;

?>
<?php if( $data ) { ?>
	<?php foreach( $data as $group => $items) { ?>

		<?php if( $existingType != $group ) {
			$groupLang = JText::_( 'COM_EASYSOCIAL_SEARCH_GROUP_' . $group );
			$groupLang = ( strpos( $groupLang, 'COM_EASYSOCIAL_SEARCH_GROUP_') !== false ) ? ucfirst( $group ) : $groupLang;
		?>
			<h5 class="search-title pl-20"><i class="ies-<?php echo $group == 'photos' ? 'picture' : $group;?> mr-5"></i> <?php echo $groupLang; ?></h5>
			<hr class="mt-5 mb-10">
		<?php } ?>

		<ul class="es-item-grid es-item-grid_1col" data-search-ul>
			<?php
				if( $items )
				{



					foreach( $items as $item )
					{
						echo $this->loadTemplate( 'site/search/default.item.' . $group , array( 'item' => $item ) );
					}
				}
			?>
		</ul>

	<?php } //end foreach ?>
<?php } // if (data ) ?>
