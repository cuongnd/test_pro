<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 84 2012-08-17 07:16:08Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
BookProHelper::setSubmenu(1);
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();

?>
<div style="float: left;width: 80%; ">
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="col width-60">
		<fieldset class="adminform">
			<legend>
			<?php echo JText::_('COM_BOOKPRO_AIRLINE_EDIT'); ?>
			</legend>
			<table class="admintable">
				<tr>
					<td class="key"><label for="alias"><?php echo JText::_('COM_BOOKPRO_AIRLINE_TITLE'); ?>:
					</label></td>

					<td><input class="text_area" type="text" name="title" id="title"
						size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</td>
					

				</tr>
				<tr>
					<td class="key"><label for="alias"><?php echo JText::_('COM_BOOKPRO_AIRLINE_IATA_CODE'); ?>:
					</label></td>

					<td><input class="text_area" type="text" name="code" id="code"
						size="60" maxlength="255" value="<?php echo $this->obj->code; ?>" />
					</td>

				</tr>
				

				<tr>
					<td class="key"><label for="alias"><?php echo JText::_('Children price(%)'); ?>:
					</label></td>

					<td><input class="text_area" type="text" name="child_percent"
						id="child_percent" size="60" maxlength="255"
						value="<?php echo $this->obj->child_percent; ?>" /></td>

				</tr>
				<tr>
					<td class="key"><label for="alias"><?php echo JText::_('COM_BOOKPRO_AIRLINE_INFANT_PRICE_TYPE'); ?>:
					</label></td>

					<td>
					<input type="radio" class="inputRadio" name="infant_price_type" value="1"
						id="state_active"
						<?php if ($this->obj->infant_price_type == 1) echo 'checked="checked"'; ?> />
						<label for="state_active"><?php echo JText::_('COM_BOOKPRO_AIRLINE_FIXED_PRICE'); ?> </label>
						<input type="radio" class="inputRadio" name="infant_price_type" value="0"
						id="state_inactive"
						<?php if ($this->obj->infant_price_type == 0) echo 'checked="checked"'; ?> />
						<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_AIRLINE_PERCENT_PRICE'); ?> </label>
						
				   </td>
				 </tr>
				 <tr>
					<td class="key"><label for="alias"><?php echo JText::_('COM_BOOKPRO_AIRLINE_INFANT_PRICE_TYPE'); ?>:
					</label></td>

					<td><input class="text_area" type="text" name="infant_price"
						id="infant_price" size="60" maxlength="255"
						value="<?php echo $this->obj->infant_price; ?>" /></td>
				 </tr>
				 
				<tr>
					<td class="key"><label for="alias"><?php echo JText::_('Infant price(%)'); ?>:
					</label></td>

					<td><input class="text_area" type="text" name="infant_percent"
						id="infant_percent" size="60" maxlength="255"
						value="<?php echo $this->obj->infant_percent; ?>" /></td>
				 </tr>
				
				 <tr>
					<td class="key"><?php echo AHtml::tooltip(JText::_('COM_BOOKPRO_AIRLINE_LOGO'), JText::_('COM_BOOKPRO_AIRLINE_LOGO')); ?>
							<label><?php echo JText::_('COM_BOOKPRO_AIRLINE_LOGO'); ?>:</label>
						</td>
						<td><?php 
						$this->image = $this->obj->image;
						AImporter::tpl('images', $this->_layout, 'image');
						?>
						</td>
					</tr>

				<tr>
					<td class="key"><?php echo JText::_('COM_BOOKPRO_AIRLINE_STATUS'); ?>:</td>
					<td><input type="radio" class="inputRadio" name="state" value="1"
						id="state_active"
						<?php if ($this->obj->state == 1) echo 'checked="checked"'; ?> />
						<label for="state_active"><?php echo JText::_('COM_BOOKPRO_AIRLINE_ACTIVE'); ?> </label>
						<input type="radio" class="inputRadio" name="state" value="0"
						id="state_inactive"
						<?php if ($this->obj->state == 0) echo 'checked="checked"'; ?> />
						<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_AIRLINE_INACTIVE'); ?> </label>
					</td>
				</tr>

			</table>
		</fieldset>

		<div class="clr"></div>
	</div>

	<div class="clr"></div>
	<div class="compulsory">
	<?php echo JText::_('Compulsory items'); ?>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_AIRLINE; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

		<?php echo JHTML::_('form.token'); ?>
</form>
</div>