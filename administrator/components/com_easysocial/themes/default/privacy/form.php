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
<form name="adminForm" id="adminForm" class="pointsForm" method="post" enctype="multipart/form-data">
<div class="row-fluid">

	<div class="span6">
		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_PRIVACY_FORM_GENERAL' );?></h3>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_PRIVACY_FORM_DEFAULT' );?></label>
						<i data-placement="bottom" data-title="<?php echo JText::_( 'COM_EASYSOCIAL_PRIVACY_FORM_DEFAULT' , true );?>" data-content="<?php echo JText::_( 'COM_EASYSOCIAL_PRIVACY_FORM_DEFAULT_DESC' , true ); ?>" data-es-provide="popover" class="icon-es-help pull-right" data-original-title=""></i>
					</div>
					<div class="span7">

						<select class="input-full" value="<?php echo $privacy->value;?>" name="value">
						<?php
							$options = Foundry::json()->decode( $privacy->options );

							foreach( $options->options as $option )
							{
								//$value 		= Foundry::call( 'Privacy' , 'toValue' , $option );
								$value 		= Foundry::privacy()->toValue( $option );
								$isChecked 	= ( $privacy->value == $value ) ? ' selected="selected"' : '';
								$label     	= JText::_( 'COM_EASYSOCIAL_PRIVACY_OPTION_' . strtoupper( $option ) );
							?>
							<option value="<?php echo $value; ?>"<?php echo $isChecked; ?>><?php echo $label; ?></option>
						<?php
							}
						?>
						</select>
					</div>
				</div>


			</div>
		</div>
	</div>

	<div class="span6">
		<div class="row-fluid">
		</div>
	</div>

</div>

<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="privacy" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $privacy->id; ?>" />
<?php echo JHTML::_( 'form.token' );?>

</form>
