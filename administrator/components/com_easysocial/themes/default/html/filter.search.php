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
<span class="input-append">
	<input type="text" class="input-large" name="search" value="<?php echo $this->html( 'string.escape' , $value );?>" data-table-grid-search-input />
	<button class="btn btn-es" data-table-grid-search><i class="ies-search ies-small"></i></button>
	<button class="btn btn-es" data-table-grid-search-reset><i class="ies-cancel ies-small"></i></button>
</span>
