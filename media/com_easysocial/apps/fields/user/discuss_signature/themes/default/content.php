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
<div id="discuss-wrapper" data-field-discussSignature style="min-height: 0;padding: 0;margin-bottom: 10px;">
	<textarea name="<?php echo $inputName; ?>"
				id="<?php echo $inputName;?>"
				class="full-width"
				style="<?php echo $params->get( 'inline' );?>"
				data-field-discussSignature-item=""
	><?php echo $value; ?></textarea>

</div>
