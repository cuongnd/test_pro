<?php


defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewCustomer */


JToolBarHelper::save();
JToolBarHelper::apply();

JToolBarHelper::cancel();

$config = &AFactory::getConfig();
JHtml::_('behavior.formvalidation')	
?>
	<script type="text/javascript">       
 Joomla.submitbutton = function(task) {
      var form = document.adminForm;
      if (task == 'cancel') {
         form.task.value = task;
         form.submit();
         return;
      }
      if (document.formvalidator.isValid(form)) {
         form.task.value = task;
         form.submit();
       }
       else {
         alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');
         return false;
       }
   }
	</script>
<form class="form-validate" action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="form-horizontal">
	
		<fieldset class="adminform">
    		<legend><?php echo JText::_('COM_BOOKPRO_AGENT_DETAILS'); ?></legend>
    		
    		<div class="control-group">
				<label class="control-label" for="firstname">
				 <?php echo JText::_('COM_BOOKPRO_AGENT_FIRSTNAME')?>
				</label>
				<div class="controls">
					<input class="text_area required" type="text" name="firstname" id="firstname" size="60" maxlength="255" value="<?php echo $this->agent->firstname; ?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="lastname">
				 <?php echo JText::_('COM_BOOKPRO_AGENT_LASTNAME')?>
				</label>
				<div class="controls">
					<input class="text_area required" type="text" name="lastname" id="lastname" size="60" maxlength="255" value="<?php echo $this->agent->lastname; ?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="company">
				 <?php echo JText::_('COM_BOOKPRO_AGENT_NAME')?>
				</label>
				<div class="controls">
					<input class="text_area required" type="text" name="company" id="company" size="60" maxlength="255" value="<?php echo $this->agent->company; ?>" />
				</div>
			</div>
		
			<div class="control-group">
				<label class="control-label" for="brandname">
				 <?php echo JText::_('COM_BOOKPRO_AGENT_BRANDNAME')?>
				</label>
				<div class="controls">
					<input class="text_area required" type="text" name="brandname" id="brandname" size="60" maxlength="255" value="<?php echo $this->agent->brandname; ?>" />
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="image">
				 <?php echo JText::_('Photo')?>
				</label>
				<div class="controls">
					<?php 
						$this->image = $this->agent->image;
						
						AImporter::tpl('images', $this->_layout, 'image');
						?>
				</div>
			</div>

    		<div class="control-group form-inline">
				<label class="control-label" for="state">
				 <?php echo JText::_('State')?>
				</label>
				<div class="controls">
					<input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->agent->state == 1) echo 'checked="checked"'; ?>/>
					<label for="state_active"><?php echo JText::_('Active'); ?></label>
					<input type="radio" class="inputRadio" name="state" value="0" id="state_deleted" <?php if ($this->agent->state == 0) echo 'checked="checked"'; ?>/>
					<label for="state_deleted"><?php echo JText::_('Trashed'); ?></label>
				</div>
			</div>	
    		
    		<div class="control-group">
				<label class="control-label" for="desc">
				 <?php echo JText::_('COM_BOOKPRO_AGENT_DESCRIPTION')?>
				</label>
				<div class="controls">
					<?php
						$editor =& JFactory::getEditor();
						echo $editor->display('desc', $this->agent->desc, '450', '400', '60', '20', false);
					?>
				</div>
			</div>
    			
    			
    			  			
    			    					
    			
        		
    	</fieldset>
    	<?php if ($this->agent->id) { ?>
        	<fieldset class="adminform">
        		<legend><?php echo JText::_('System data'); ?></legend>
        		
        			<?php if ($this->user->id) { ?>
        			<div class="control-group">
						<label class="control-label" for="username">
						 <?php echo JText::_('User')?>
						</label>
						<div class="controls">
							<a href="<?php echo ARoute::editUser($this->user->id); ?>" title=""><?php echo $this->user->username; ?></a>
						</div>
					</div>
					
        			<div class="control-group form-inline">
						<label class="control-label" for="block">
						 <?php echo JText::_('Block')?>
						</label>
						<div class="controls">
							<input type="radio" class="inputRadio" name="block" value="<?php echo CUSTOMER_USER_STATE_BLOCK; ?>" id="block_yes" <?php if ($this->user->block == CUSTOMER_USER_STATE_BLOCK) echo 'checked="checked"'; ?>/>
    						<label for="block_yes"><?php echo JText::_('JYES'); ?></label>
    						<input type="radio" class="inputRadio" name="block" value="<?php echo CUSTOMER_USER_STATE_ENABLED; ?>" id="block_no" <?php if ($this->user->block == CUSTOMER_USER_STATE_ENABLED) echo 'checked="checked"'; ?>/>
   							<label for="block_no"><?php echo jtext::_('JNO'); ?></label>
						</div>
					</div>
	    			
	    			<div class="control-group">
						<label class="control-label" for="usertype">
						 <?php echo JText::_('User type')?>
						</label>
						<div class="controls">
							<?php echo $this->agent->usertype ?>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="email">
						 <?php echo JText::_('Email')?>
						</label>
						<div class="controls">
							<input class="text_area required" type="text" name="email" id="email" size="60" maxlength="255" value="<?php echo $this->user->email; ?>" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="registerDate">
						 <?php echo JText::_('Register date')?>
						</label>
						<div class="controls">
							<?php echo AHtml::date($this->user->registerDate, ADATE_FORMAT_LONG); ?>
						</div>
					</div>		
	       						
       					
        					
        				
       				<?php } else { ?>
       					<div class="control-group">
							<label class="control-label" for="registerDate">
							 <?php echo JText::_('User')?>
							</label>
							<div class="controls">
								<?php echo JText::_('Not found'); ?>
							</div>
						</div>
        				
       				<?php } ?>
        		
        	</fieldset>
        <?php } else { ?>
        	<fieldset class="adminform">
        		<legend><?php echo JText::_('COM_BOOKPRO_AGENT_USER_ACOUNT'); ?></legend>
        		<div class="control-group">
					<label class="control-label" for="username">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_USERNAME')?>
					</label>
					<div class="controls">
						<input type="text" name="username" id="username" size="60" maxlength="255" value="<?php echo $this->user->username; ?>" class="inputbox" autocomplete="off"/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="email">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_EMAIL')?>
					</label>
					<div class="controls">
						<input type="text" name="email" id="email" size="60" maxlength="255" value="<?php echo $this->user->email; ?>" class="inputbox" autocomplete="off"/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="password">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_NEW_PASSWORD')?>
					</label>
					<div class="controls">
						<input type="password" name="password" id="password" size="40" value="<?php echo JRequest::getString('password'); ?>" class="inputbox" autocomplete="off"/>
					</div>
				</div>	
				
				<div class="control-group">
					<label class="control-label" for="password2">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_VERIFY_PASSWORD')?>
					</label>
					<div class="controls">
						<input type="password" name="password2" id="password2" size="40" value="<?php echo JRequest::getString('password2'); ?>" class="inputbox" autocomplete="off"/>
					</div>
				</div>	
				
    		</fieldset>
        <?php } ?>
        
    	
    	<fieldset class="adminform">
    		<legend><?php echo JText::_('COM_BOOKPRO_AGENT_CONTACT'); ?></legend>
    			<div class="control-group">
					<label class="control-label" for="address">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_ADDRESS')?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="address" id="address" size="60" maxlength="255" value="<?php echo $this->agent->address; ?>" />
					</div>
				</div>
    		
    			<div class="control-group">
					<label class="control-label" for="zip">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_ZIP')?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="zip" id="zip" size="60" maxlength="255" value="<?php echo $this->agent->zip; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="countries">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_COUNTRY')?>
					</label>
					<div class="controls">
						<?php echo $this->countries; ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="city">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_CITY')?>
					</label>
					<div class="controls">
						<input type="text" name="city" id="city" size="60" maxlength="255" value="<?php echo $this->agent->city; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="telephone">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_TELPHONE')?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="telephone" id="telephone" size="60" maxlength="255" value="<?php echo $this->agent->telephone; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="mobile">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_MOBILE')?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="mobile" id="mobile" size="60" maxlength="255" value="<?php echo $this->agent->mobile; ?>" />
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="fax">
					 <?php echo JText::_('COM_BOOKPRO_AGENT_FAX')?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="fax" id="fax" size="60" maxlength="255" value="<?php echo $this->agent->fax; ?>" />
					</div>
				</div>
 			
    	</fieldset>
    	
   	</div>
   	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_AGENT; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->agent->id; ?>"/>
	<!-- Use for display customers reservations -->
	<input type="hidden" name="filter_agent-id" value="<?php echo $this->agent->id; ?>"/>
	<?php echo JHTML::_('form.token'); ?>
	
</form>