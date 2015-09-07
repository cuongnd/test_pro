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

$last_type = '';
?>
<div class="search-result">
<?php if( $data ) { ?>
	<?php foreach( $data as $group => $items) {

		$groupIcon = 'ies-search-2';

		switch( $group )
		{
			case 'photos':
				$groupIcon = 'ies-picture';
				break;
			case 'albums':
				$groupIcon = 'ies-pictures';
				break;
			case 'users':
				$groupIcon = 'ies-user';
				break;
			case 'list':
				$groupIcon = 'ies-cube';
				break;
			default:
				$groupIcon = 'ies-file';
				break;
		}

		$groupLang = JText::_( 'COM_EASYSOCIAL_SEARCH_GROUP_' . $group );
		$groupLang = ( strpos( $groupLang, 'COM_EASYSOCIAL_SEARCH_GROUP_') !== false ) ? ucfirst( $group ) : $groupLang;

	?>

		<div class="search-blk">

			<div class="search-blk-hd">
				<i class="<?php echo $groupIcon; ?>"></i>
				<?php echo $groupLang; ?>
			</div>
			<div class="search-blk-bd">
				<ul class="search-result-list" data-nav-search-ul>
					<?php
						if( $items )
						{
							foreach( $items as $item )
							{
								echo $this->loadTemplate( 'site/search/default.item.mini' , array( 'item' => $item ) );
							}
						}
					?>

				</ul>
			</div>

		</div>

	<?php } //end foreach ?>

	<div class="search-footer">
		<div class="center small muted">
			<?php echo JText::sprintf( 'COM_EASYSOCIAL_SEARCH_NUMBER_ITEM_FOUND_TOOLBAR', $total ); ?>
		</div>
		<div class="center small mt-10">
			<a href="<?php echo FRoute::search( array( 'q' => urlencode( $keywords ) ) );?>">
				<?php echo JText::_('COM_EASYSOCIAL_SEARCH_VIEW_ALL_RESULTS'); ?>
			</a>
		</div>
	</div>

<?php } else { ?>
		<div class="search-empty">
			<?php echo JText::_('COM_EASYSOCIAL_SEARCH_NO_RECORDS_FOUND'); ?>
		</div>
<?php } ?>
</div>
