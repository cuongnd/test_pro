<?php


defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();

?>


<form action="index.php" method="post" name="adminForm" id="adminForm">
	
    			<div class="form-horizontal">
    		
    			<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_PAYMENT_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="inputbox required " type="text"	name="title" autocomplete="off" id="title" size="20"
							maxlength="50" value="<?php echo $this->obj->title ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="hostedtype"><?php echo JText::_('COM_BOOKPRO_PAYMENT_HOST_TYPE'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtml::_('select.booleanlist','hostedtype','class="btn-group"',$this->obj->hostedtype,'Merchant-Hosted','Server-Hosted')?>
					</div>
				</div>
				
				
				<div class="control-group">
					<label class="control-label" for="istest"><?php echo JText::_('COM_BOOKPRO_PAYMENT_TEST_MODE'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('istest','class="btn"',$this->obj->istest,'Yes','No')?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="paymenttype"><?php echo JText::_('COM_BOOKPRO_PAYMENT_TYPE'); ?>
					</label>
					<div class="controls">
						<?php echo $this->paymenttype ?></td>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="username"><?php echo JText::_('COM_BOOKPRO_PAYMENT_USERNAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="username" id="username" size="60" maxlength="255" value="<?php echo $this->obj->username; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="password"><?php echo JText::_('COM_BOOKPRO_PAYMENT_PASSWORD'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="password" id="password" size="60" maxlength="255" value="<?php echo $this->obj->password; ?>" />
					</div>
				</div>
    				
    			<div class="control-group">
					<label class="control-label" for="merchant_id"><?php echo JText::_('COM_BOOKPRO_PAYMENT_MERCHANT_ID'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="merchant_id" id="merchant_id" size="60" maxlength="255" value="<?php echo $this->obj->merchant_id; ?>" />
					</div>
				</div>	
				
				<div class="control-group">
					<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_PAYMENT_EMAIL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email" id="email" size="60" maxlength="255" value="<?php echo $this->obj->email; ?>" />
					</div>
				</div>		
				
				<div class="control-group">
					<label class="control-label" for="secondemail"><?php echo JText::_('COM_BOOKPRO_PAYMENT_SECOND_EMAIL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="secondemail" id="secondemail" size="60" maxlength="255" value="<?php echo $this->obj->secondemail; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="ipn_url"><?php echo JText::_('COM_BOOKPRO_PAYMENT_IPN_CALL_BACK_URL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="ipn_url" id="ipn_url" size="60" maxlength="255" value="<?php echo $this->obj->ipn_url; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="gateway_url"><?php echo JText::_('COM_BOOKPRO_PAYMENT_GATEWAY_URL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="gateway_url" id="gateway_url" size="60" maxlength="255" value="<?php echo $this->obj->gateway_url; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="success_url"><?php echo JText::_('COM_BOOKPRO_PAYMENT_SUCCESS_URL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="success_url" id="success_url" size="60" maxlength="255" value="<?php echo $this->obj->success_url; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="cancel_url"><?php echo JText::_('COM_BOOKPRO_PAYMENT_CANCELD_URL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="cancel_url" id="cancel_url" size="60" maxlength="255" value="<?php echo $this->obj->cancel_url; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="signature"><?php echo JText::_('COM_BOOKPRO_PAYMENT_SIGNATURE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="signature" id="signature" size="60" maxlength="255" value="<?php echo $this->obj->signature; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="secure_code"><?php echo JText::_('COM_BOOKPRO_PAYMENT_SECURE_CODE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="secure_code" id="secure_code" size="60" maxlength="255" value="<?php echo $this->obj->secure_code; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="description"><?php echo JText::_('COM_BOOKPRO_PAYMENT_DESCRIPTION'); ?>
					</label>
					<div class="controls">
						<?php
					$editor =& JFactory::getEditor();
					echo $editor->display('description', $this->obj->description, '550', '400', '60', '20', false);?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="description"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->obj->state == 1) echo 'checked="checked"'; ?>/>
						<label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?></label>
						<input type="radio" class="inputRadio" name="state" value="0" id="state_inactive" <?php if ($this->obj->state == 0) echo 'checked="checked"'; ?>/>
						<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?></label>
					</div>
				</div>
			</div>
				
   	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_PAYMENT; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
