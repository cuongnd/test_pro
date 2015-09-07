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
<div data-field-joomla_fullname data-name-format="<?php echo $params->get( 'format', 1 ); ?>">

	<ul class="input-vertical unstyled">
		<?php if( $params->get( 'format' , 1 ) == 1 ){ ?>
		<li>
			<input type="text" size="30" class="input input-xlarge" id="first_name" name="first_name" value="<?php echo $firstName; ?>"
				data-field-jname-first
				placeholder="<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_FIRST_NAME' , true );?>"<?php echo $field->isRequired() ? ' data-check-required' : '';?> />
		</li>
		<?php } ?>

		<?php if( $params->get( 'format' , 1 ) == 2 ){ ?>
		<li>
			<input type="text" size="30" class="input input-xlarge" id="last_name" name="last_name" value="<?php echo $lastName; ?>"
			data-field-jname-last
			placeholder="<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_LAST_NAME' , true );?>" />
		</li>
		<?php } ?>

		<?php if( $params->get( 'format' , 1 ) == 3 ){ ?>
		<li>
			<input type="text" size="30" class="input input-xlarge" id="first_name" name="first_name" value="<?php echo $name; ?>"
				data-field-jname-name
				placeholder="<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_YOUR_NAME' , true );?>" />
		</li>
		<?php } ?>

		<?php if( $params->get( 'format' , 1 ) != 3 ){ ?>
		<li>
			<input type="text" size="30" class="input input-xlarge" id="middle_name" name="middle_name" value="<?php echo $middleName; ?>"
			data-field-jname-middle
			placeholder="<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_MIDDLE_NAME' , true );?>" />
		</li>
		<?php } ?>

		<?php if( $params->get( 'format' , 1 ) != 2 && $params->get( 'format' , 1 ) != 3 ){ ?>
		<li>
			<input type="text" size="30" class="input input-xlarge" id="last_name" name="last_name" value="<?php echo $lastName; ?>"
			placeholder="<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_LAST_NAME' , true );?>" />
		</li>
		<?php } ?>

		<?php if( $params->get( 'format' , 1 ) == 2 ){ ?>
		<li>
			<input type="text" size="30" class="input input-xlarge" id="first_name" name="first_name" value="<?php echo $firstName; ?>"
			data-field-jname-first
			placeholder="<?php echo JText::_( 'PLG_FIELDS_JOOMLA_FULLNAME_PLACEHOLDER_FIRST_NAME' , true );?>"<?php echo $field->isRequired() ? ' data-check-required' : '';?> />
		</li>
		<?php } ?>

	</ul>
</div>
