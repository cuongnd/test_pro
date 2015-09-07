<?php


defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
jimport('joomla.html.html.bootstrap');

JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">

		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab',array('active'=>'tab1'));?> 
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab1', JText::_('Basic configuration')); ?> 
    		 <div class="form-horizontal">
    		
    			<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_APP_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="code"><?php echo JText::_('COM_BOOKPRO_APP_CODE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="code" id="code" size="20" maxlength="255" value="<?php echo $this->obj->code; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="views"><?php echo JText::_('COM_BOOKPRO_APP_VIEW'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="views" id="views" size="120" maxlength="255" value="<?php echo $this->obj->views; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_APP_DESCRIPTION'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="desc" id="desc" size="60" maxlength="255" value="<?php echo $this->obj->desc; ?>" />
					</div>
				</div>
				
				
				
    			<div class="control-group">
					<label class="control-label" for="service_fee"><?php echo JText::_('COM_BOOKPRO_APP_SERVICE_FEE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="service_fee" id="service_fee" size="5" maxlength="255" value="<?php echo $this->obj->service_fee; ?>" />
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="vat"><?php echo JText::_('VAT'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="vat" id="vat" size="5" maxlength="255" value="<?php echo $this->obj->vat; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="deposit"><?php echo JText::_('COM_BOOKPRO_APP_DEPOSITE_PERCENTAGE'); ?>
					</label>
					<div class="controls">
							<input class="text_area" type="text" name="deposit" id="deposit" size="30"  value="<?php echo $this->obj->deposit; ?>" />
					</div>
				</div>
				</div>
				<?php echo JHtml::_('bootstrap.endTab');?> 
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab2', JText::_('Email configuration')); ?> 
				 <div class="form-horizontal">
				
				<div class="control-group">
					<label class="control-label" for="email_send_from"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SEND_FROM'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email_send_from" id="email_send_from" size="60" maxlength="255" value="<?php echo $this->obj->email_send_from; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="email_send_from_name"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SEND_FROM_NAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email_send_from_name" id="email_send_from_name" size="60" maxlength="255" value="<?php echo $this->obj->email_send_from_name; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="email_customer_subject"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_CUSTOMER_SUBJECT'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email_customer_subject" id="email_customer_subject" size="60" maxlength="255" value="<?php echo $this->obj->email_customer_subject; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="email_customer_body"><?php echo JText::_('COM_BOOKPRO_APP_BODY_EMAIL_CUSTOMER'); ?>
					</label>
					<div class="controls">
						<?php
							 $editor =& JFactory::getEditor();
						echo $editor->display('email_customer_body', $this->obj->email_customer_body, '60%', '50', '60', '20', false);?>
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="email_admin"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_ADMIN'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email_admin" id="email_admin" size="60" maxlength="255" value="<?php echo $this->obj->email_admin; ?>" />
				</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="email_admin_subject"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_ADMIN_SUBJECT'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email_admin_subject" id="email_admin_subject" size="60"  value="<?php echo $this->obj->email_admin_subject; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="email_admin_body"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_ADMIN_BODY'); ?>
					</label>
					<div class="controls">
							<?php
						$editor =& JFactory::getEditor();
						$editor->set('filter','safehtml');
						echo $editor->display('email_admin_body', $this->obj->email_admin_body, '60%', '100', '60', '20', false);?>
					</div>
				</div>
				
				
				<div class="control-group">
					<label class="control-label" for="email_supplier_subject"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SUPPLIER_SUBJECT'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="email_supplier_subject" id="email_supplier_subject" size="60"  value="<?php echo $this->obj->email_supplier_subject; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="email_supplier_body"><?php echo JText::_('COM_BOOKPRO_APP_EMAIL_SUPPLIER_BODY'); ?>
					</label>
					<div class="controls">
							<?php
						$editor =& JFactory::getEditor();
						$editor->set('filter','safehtml');
						echo $editor->display('email_supplier_body', $this->obj->email_supplier_body, '60%', '50', '60', '20', false);?>
					</div>
				</div>
				<!-- 
				<div class="control-group">
					<label class="control-label" for="success"><?php echo JText::_('COM_BOOKPRO_APP_SUCCESS_MESSAGE'); ?>
					</label>
					<div class="controls">
							<?php
							 $editor =& JFactory::getEditor();
						echo $editor->display('success', $this->obj->success, '60%', '50', '50', '20', false);?>
					</div>
				</div>
				
				
				<div class="control-group">
					<label class="control-label" for="failed"><?php echo JText::_('COM_BOOKPRO_APP_FAILED_MESSAGE'); ?>
					</label>
					<div class="controls">
							<?php
							 $editor =& JFactory::getEditor();
						echo $editor->display('failed', $this->obj->failed, '60%', '50', '60', '20', false);?>
					</div>
				</div>
				-->
				
				
    		</div>	
    		
    	<?php echo JHtml::_('bootstrap.endTab');?> 
    	
    	<?php echo JHtml::_('bootstrap.endTabSet');?>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_APPLICATION; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
