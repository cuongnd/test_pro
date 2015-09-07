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
<div data-field-easyblog-permalink>
	<ul class="input-vertical unstyled">
		<li>
			<input type="text" name="<?php echo $inputName;?>" class="input-xlarge" value="<?php echo $this->html( 'string.escape', $value ); ?>"
				id="<?php echo $inputName;?>" data-field-easyblog-permalink-input <?php echo $field->isRequired() ? ' data-check-required' : '';?> />
		</li>
	</ul>
</div>
