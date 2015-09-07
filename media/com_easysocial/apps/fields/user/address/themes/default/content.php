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
<div data-field-address data-address-required="<?php echo $required; ?>">

	<ul class="input-vertical unstyled">
		<li>
			<input type="text" class="input full-width validation keyup length-4"
			placeholder="<?php echo JText::_( 'PLG_FIELDS_ADDRESS_ADDRESS1_PLACEHOLDER' , true );?>"
			name="<?php echo $inputName;?>[address1]"
			value="<?php echo $value->address1;?>"
			data-field-address-address1
			/>
		</li>
		<li>
			<input type="text" class="input full-width validation keyup length-4"
			placeholder="<?php echo JText::_( 'PLG_FIELDS_ADDRESS_ADDRESS2_PLACEHOLDER' , true );?>"
			name="<?php echo $inputName;?>[address2]"
			value="<?php echo $value->address2;?>"
			data-field-address-address2
			/>
		</li>
		<li class="mb-0">
			<input type="text" class="input validation keyup length-4"
			placeholder="<?php echo JText::_( 'PLG_FIELDS_ADDRESS_CITY_PLACEHOLDER' , true );?>"
			style="width:49%;margin-bottom:4px;"
			name="<?php echo $inputName;?>[city]"
			value="<?php echo $value->city;?>"
			data-field-address-city
			/>
			<input type="text" class="input validation keyup length-4"
			placeholder="<?php echo JText::_( 'PLG_FIELDS_ADDRESS_STATE_PLACEHOLDER' , true );?>"
			style="width:49%;margin-bottom:4px;float:right;"
			name="<?php echo $inputName;?>[state]"
			value="<?php echo $value->state;?>"
			data-field-address-state
			/>
		</li>
		<li class="mb-0">
			<input type="text" class="input validation keyup length-4"
			placeholder="<?php echo JText::_( 'PLG_FIELDS_ADDRESS_ZIP_PLACEHOLDER' , true );?>"
			style="width:38%;margin-bottom:4px;"
			name="<?php echo $inputName;?>[zip]"
			value="<?php echo $value->zip;?>"
			data-field-address-zip
			/>

			<select class="input" name="<?php echo $inputName;?>[country]"
			style="width:60%;margin-bottom:4px;float:right;"
			data-field-address-country>
				<option value=""><?php echo JText::_( 'PLG_FIELDS_ADDRESS_SELECT_A_COUNTRY' ); ?></option>
				<?php foreach( $countries as $code => $title ){ ?>
				<option value="<?php echo $code;?>"<?php echo $code == $value->country ? ' selected="selected"' : '';?>><?php echo $title;?></option>
				<?php } ?>
			</select>

		</li>
	</ul>
</div>
