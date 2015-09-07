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

$core 		= array( SOCIAL_INDEXER_TYPE_USERS, SOCIAL_INDEXER_TYPE_PHOTOS, SOCIAL_INDEXER_TYPE_LISTS, SOCIAL_INDEXER_TYPE_ALBUMS );
$last_type 	= '';
?>
<?php if( $data ) { ?>

<div data-search-list>

	<?php foreach( $data as $group => $items) {

		$groupLang = JText::_( 'COM_EASYSOCIAL_SEARCH_GROUP_' . $group );
		$groupLang = ( strpos( $groupLang, 'COM_EASYSOCIAL_SEARCH_GROUP_') !== false ) ? ucfirst( $group ) : $groupLang;

		$groupTmpl = ( in_array( $group, $core ) ) ? $group : 'other';

	?>
		<div class="search-result-group">

			<h5 class="search-title pl-20"><i class="ies-<?php echo $group == 'photos' ? 'picture' : $group;?> mr-5"></i> <?php echo $groupLang; ?></h5>
			<hr class="mt-5 mb-10">

			<ul class="es-item-grid es-item-grid_1col" data-search-ul>
				<?php if( $items ){ ?>
					<?php foreach( $items as $item ){ ?>
						<?php echo $this->loadTemplate( 'site/search/default.item.' . $groupTmpl , array( 'item' => $item ) ); ?>

						<?php $last_type = $item->utype; ?>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
	<?php } ?>

	<div class="unstyled" data-search-pagination data-last-limit="<?php echo $next_limit; ?>" data-last-type="<?php echo $last_type; ?>">
		<?php if( $total > Foundry::themes()->getConfig()->get( 'search_limit' ) ) { ?>
		<a class="btn btn-es-primary btn-stream-updates" href="javascript:void(0);" data-search-loadmore-button><i class="ies-refresh"></i>	<?php echo JText::_( 'COM_EASYSOCIAL_SEARCH_LOAD_MORE_ITEMS' ); ?></a>
		<?php } ?>
	</div>

</div>

<?php } else { ?>
	<div class="mt-10">
		<div class="center">
			<i class="icon-es-empty-search"></i>
			<div class="mt-10"><?php echo JText::_('COM_EASYSOCIAL_SEARCH_NO_RECORDS_FOUND'); ?></div>
		</div>
	</div>
<?php } ?>
