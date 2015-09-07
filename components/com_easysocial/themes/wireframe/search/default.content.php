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
<form name="frmSearch" method="post" action="<?php echo JRoute::_( 'index.php' );?>">
	<div class="search-input-wrap mt-10 pl-10">
		<div class="search-input-left">
			<input type="text" class="input-search" id="appendedInputButton" value="<?php echo $this->html( 'string.escape' , $query ); ?>" name="q" data-search-query>
		</div>
		<div class="search-input-right">
			<button class="btn btn-search" type="button" onclick="document.forms['frmSearch'].submit();"><?php echo JText::_('COM_EASYSOCIAL_SEARCH_BUTTON'); ?></button>
		</div>
	</div>
	<?php echo $this->html( 'form.itemid' ); ?>
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="view" value="search" />
</form>

<?php if( $query && $total ) { ?>
<div class="mt-10 mr-10 ml-10 center small">
	<?php echo JText::sprintf( 'COM_EASYSOCIAL_SEARCH_NUMBER_ITEM_FOUND', $total ); ?>
</div>
<?php } ?>

<hr />

<div data-search-content>
	<?php if( !$query ){ ?>
		<div class="pl-20"><?php echo JText::_( 'COM_EASYSOCIAL_SEARCH_PLEASE_ENTER_TO_SEARCH' ); ?></div>
	<?php } else { ?>
		<?php echo $this->loadTemplate( 'site/search/default.list' , array( 'data' => $data, 'next_limit' => $next_limit, 'total' => $total )  ); ?>
	<?php } ?>
</div>
