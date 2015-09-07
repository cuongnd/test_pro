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
<div class="tab-box tab-box-alt<?php echo $sidebarTabs ? ' tab-box-sidenav' : '';?>">
	<div class="tabbable">
		<ul class="nav nav-tabs nav-tabs-icons">
			<?php $i = 0; ?>
			<?php foreach( $forms as $form ){ ?>
				<li class="tab-item<?php echo $i == 0 && !$active || $active == strtolower( str_ireplace( array( ' ' , ',' ) , '' , $form->title ) ) ? ' active' : '';?>" data-form-tabs data-item="<?php echo strtolower( str_ireplace( array( ' ' , ',' ) , '' , $form->title ) );?>">
					<a href="#<?php echo strtolower( str_ireplace( array( ' ' , ',' ) , '' , $form->title ) );?>-tabs" data-foundry-toggle="tab"><?php echo JText::_( $form->title ); ?></a>
				</li>
				<?php $i++;?>
			<?php } ?>
		</ul>

		<div class="tab-content">
			<?php $i = 0; ?>
			<?php foreach( $forms as $form ){ ?>
				<div class="tab-pane<?php echo $i == 0 && !$active || $active == strtolower( str_ireplace( array( ' ' , ',' ) , '' , $form->title ) ) ? ' active in' : '';?>" id="<?php echo strtolower( str_ireplace( array( ' ' , ',' ) , '' , $form->title ) );?>-tabs">
					<div class="row-fluid">
						<?php if( isset( $form->fields ) && $form->fields ){ ?>
						<table class="table table-striped table-noborder">
							<tbody>
							<?php foreach( $form->fields as $field ){ ?>
							<tr>
								<td width="25%" valign="top">
									<?php if( isset( $field->label ) ){ ?>
									<label for="<?php echo $field->name;?>"><?php echo JText::_( $field->label ); ?></label>
									<?php } ?>
								</td>
								<td width="1%" valign="top">
									<?php if( isset( $field->tooltip) ){ ?>
									<i data-placement="bottom" data-title="<?php echo JText::_( $field->label );?>"
										data-content="<?php echo JText::_( $field->tooltip );?>"
										data-es-provide="popover" class="icon-es-help pull-left"></i>
									<?php } ?>
								</td>
								<td valign="top">
									<?php echo $this->loadTemplate( 'admin/forms/types/' . $field->type , array( 'params' => $params , 'field' => $field ) ); ?>
								</td>
							</tr>
							<?php } ?>
							</tbody>
						</table>
						<?php } ?>
					</div>
				</div>
				<?php $i++;?>
			<?php } ?>
		</div>

	</div>
</div>
<?php } ?>
