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
<div data-field-gender>
	<select id="<?php echo $inputName;?>" name="<?php echo $inputName;?>" data-field-gender-input>
		<option value=""<?php echo $value == 0 ? ' selected="selected"' : '';?>><?php echo JText::_( 'PLG_FIELDS_GENDER_SELECT_A_GENDER' , true ); ?></option>
		<option value="1"<?php echo $value == 1 ? ' selected="selected"' : '';?>><?php echo JText::_( 'PLG_FIELDS_GENDER_SELECT_MALE' , true );?></option>
		<option value="2"<?php echo $value == 2 ? ' selected="selected"' : '';?>><?php echo JText::_( 'PLG_FIELDS_GENDER_SELECT_FEMALE' , true ); ?></option>
	</select>
</div>
