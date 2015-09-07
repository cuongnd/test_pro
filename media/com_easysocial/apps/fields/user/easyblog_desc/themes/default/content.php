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
<div data-field-easyblog-desc>
	<ul class="input-vertical unstyled">
		<li>
			<textarea type="text" style="height:110px; width:80%;" id="<?php echo $inputName;?>" name="<?php echo $inputName;?>" data-field-easyblog-desc-input <?php echo $field->isRequired() ? ' data-check-required' : '';?>><?php echo $value; ?></textarea>
		</li>
	</ul>
</div>
