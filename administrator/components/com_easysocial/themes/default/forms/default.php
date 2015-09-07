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
<?php if( $forms ){ ?>
	<?php foreach( $forms as $form ){ ?>
		<div class="row-fluid widget-box">

			<?php if( isset( $form->title ) ){ ?>
			<h3><?php echo JText::_( $form->title );?></h3>
			<?php } ?>

			<?php if( isset( $form->desc ) ){ ?>
			<p class="small"><?php echo JText::_( $form->desc );?></p>
			<?php } ?>

			<div class="wbody wbody-padding">
				<?php if( isset( $form->fields ) && $form->fields ){ ?>
					<?php foreach( $form->fields as $field ){ ?>
					<div class="es-controls-row">

						<div class="span5">
							<?php if( isset( $field->label ) ){ ?>
							<label for="<?php echo $field->name;?>"><?php echo JText::_( $field->label ); ?></label>
							<?php } ?>

							<?php if( isset( $field->tooltip) ){ ?>
							<i data-placement="bottom" data-title="<?php echo JText::_( $field->label , true );?>"
								data-content="<?php echo JText::_( $field->tooltip , true );?>"
								data-es-provide="popover" class="icon-es-help pull-right"></i>
							<?php } ?>

						</div>

						<div class="span7">
							<?php if( stristr( $field->type , ':/' ) !== false ){ ?>
								<?php echo $this->loadTemplate( $field->type , array( 'params' => $params , 'field' => $field ) ); ?>
							<?php } else { ?>
								<?php echo $this->loadTemplate( 'admin/forms/types/' . $field->type , array( 'params' => $params , 'field' => $field ) ); ?>
							<?php } ?>
						</div>
					</div>
					<?php } ?>
				<?php } ?>

			</div>
		</div>
	<?php } ?>
<?php } ?>
