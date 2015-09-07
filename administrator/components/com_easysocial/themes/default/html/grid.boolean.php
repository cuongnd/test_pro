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
<div class="btn-group-yesno"
	data-foundry-toggle="buttons-radio"
	data-es-provide="popover"
	data-content="<?php echo isset( $tips[ 'content' ] ) ? JText::_( $tips[ 'content' ] ) : '';?>"
	data-original-title="<?php echo isset( $tips[ 'title' ] ) ? JText::_( $tips[ 'title' ] ) : '';?>"
	data-placement="<?php echo isset( $tips[ 'placement' ] ) ? $tips[ 'placement' ] : 'right';?>">
	<button type="button" class="btn btn-yes<?php echo $checked ? ' active' : '';?>" data-fd-toggle-value="1"><?php echo JText::_( 'COM_EASYSOCIAL_GRID_YES' );?></button>
	<button type="button" class="btn btn-no<?php echo !$checked ? ' active' : '';?>" data-fd-toggle-value="0"><?php echo JText::_( 'COM_EASYSOCIAL_GRID_NO' );?></button>
	<input type="hidden" id="<?php echo empty( $id ) ? $name : $id; ?>" name="<?php echo $name ;?>" value="<?php echo $checked ? '1' : '0'; ?>" <?php echo $attributes; ?> />
</div>
