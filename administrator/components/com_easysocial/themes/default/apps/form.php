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
<form name="ez-fields" id="adminForm" class="ezsForm" method="post" action="index.php">
<div class="row-fluid">
	<div class="span7">

		<div class="row-fluid widget-box">
			<h3><?php echo JText::_( 'COM_EASYSOCIAL_APP_CONFIGURATION' );?></h3>

			<div class="accordion-body in" id="stat">
				<div class="wbody wbody-padding">

					<div class="es-controls-row">
						<div class="span3">
							<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_APP_TITLE' );?>:</label>
							<i data-placement="bottom" data-title="<?php echo JText::_( 'COM_EASYSOCIAL_APP_TITLE' );?>"
								data-content="<?php echo JText::_( 'COM_EASYSOCIAL_APP_TITLE_DESC' );?>"
								data-es-provide="popover" class="icon-es-help pull-right"></i>
						</div>
						<div class="span9">
							<input type="text" name="title" value="<?php echo $app->get( 'title' );?>"
								class="input-large" />
						</div>
					</div>

					<div class="es-controls-row">
						<div class="span3">
							<label for="alias"><?php echo JText::_( 'COM_EASYSOCIAL_APP_PERMALINK' );?>:</label>
							<i data-placement="bottom" data-title="<?php echo JText::_( 'COM_EASYSOCIAL_APP_PERMALINK' );?>"
								data-content="<?php echo JText::_( 'COM_EASYSOCIAL_APP_PERMALINK_DESC' , true );?>"
								data-es-provide="popover" class="icon-es-help pull-right"></i>
						</div>
						<div class="span9">
							<input type="text" id="alias" name="alias" value="<?php echo $app->get( 'alias' );?>"
								class="input-large" />
						</div>
					</div>

					<div class="es-controls-row">
						<div class="span3">
							<label for="alias"><?php echo JText::_( 'COM_EASYSOCIAL_APP_STATE' );?>:</label>
							<i data-placement="bottom" data-title="<?php echo JText::_( 'COM_EASYSOCIAL_APP_STATE' );?>"
								data-content="<?php echo JText::_( 'COM_EASYSOCIAL_APP_STATE_DESC' );?>"
								data-es-provide="popover" class="icon-es-help pull-right"></i>
						</div>
						<div class="span9">
							<?php echo $this->html( 'grid.boolean' , 'state' , $app->state ); ?>
						</div>
					</div>
					<?php if( $app->type == SOCIAL_TYPE_APPS && !$app->system ){ ?>
					<div class="es-controls-row">
						<div class="span3">
							<label><?php echo JText::_( 'COM_EASYSOCIAL_APP_DEFAULT' );?>:</label>
							<i data-placement="bottom" data-title="<?php echo JText::_( 'COM_EASYSOCIAL_APP_DEFAULT' );?>"
								data-content="<?php echo JText::_( 'COM_EASYSOCIAL_APP_DEFAULT_DESC' );?>"
								data-es-provide="popover" class="icon-es-help pull-right"></i>
						</div>
						<div class="span9">
							<?php echo $this->html( 'grid.boolean' , 'default' , $app->default ); ?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>


	</div>

	<div class="span5">

		<?php echo $app->renderForm( 'admin' , $app->getParams() , 'params' );?>

		<div class="widget-box">
			<h3><?php echo JText::_( 'COM_EASYSOCIAL_APP_ABOUT' );?></h3>

			<table class="table table-striped">
				<tbody>
					<tr>
						<td width="20%">
							<?php echo JText::_( 'COM_EASYSOCIAL_APP_AUTHOR' );?>:
						</td>
						<td>
							<?php echo $app->getMeta()->author;?>
						</td>
					</tr>
					<tr>
						<td width="20%">
							<?php echo JText::_( 'COM_EASYSOCIAL_APP_VERSION' ); ?>:
						</td>
						<td>
							<?php echo $app->getMeta()->version; ?>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'COM_EASYSOCIAL_APP_WEBSITE' ); ?>:
						</td>
						<td>
							<a href="<?php echo $app->getMeta()->url;?>" target="_blank"><?php echo $app->getMeta()->url;?></a>
						</td>
					</tr>
					<tr>
						<td>
							<?php echo JText::_( 'COM_EASYSOCIAL_APP_DESC' ); ?>:
						</td>
						<td>
							<?php echo $app->getMeta()->desc; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>



	</div>
</div>
<input type="hidden" name="id" value="<?php echo $app->id;?>" />
<input type="hidden" name="<?php echo Foundry::token();?>" value="1" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="view" value="apps" />
<input type="hidden" name="controller" value="apps" />
<input type="hidden" name="task" value="" />
</form>
