<?php


defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewCustomer */
//JToolBarHelper::title($this->customer->id?JText::_('Edit Staff') : JText::_('Add Staff'), 'customer');
//JToolBarHelper::save();
//JToolBarHelper::apply();
//JToolBarHelper::cancel();
JHtml::_('jquery.framework');
$config = &AFactory::getConfig();
jimport('joomla.html.html.bootstrap');
JHtmlBehavior::formvalidation();
JHtml::_('behavior.formvalidation');
?>
<script type="text/javascript">       
 Joomla.submitbutton = function(task) {
      var form = document.adminForm;
      if (task == 'cancel' || task == 'save' || task == 'apply') {
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
    
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
                <div class="row-fluid">
                    <div class="span12">
                        <div id="toolbar" class="btn-toolbar">
                            <div id="toolbar-save" class="btn-wrapper pull-left">
                                <button class="btn btn-small" onclick="Joomla.submitbutton('save')">
                                <span class="icon-save"></span>
                                Save &amp; Close</button>
                            </div>
                            <div id="toolbar-apply" class="btn-wrapper pull-left">
                                <button class="btn btn-small btn-success" onclick="Joomla.submitbutton('apply')">
                                <span class="icon-apply icon-white"></span>
                                Save</button>
                            </div>
                            <div id="toolbar-cancel" class="btn-wrapper pull-left">
                                <button class="btn btn-small" onclick="Joomla.submitbutton('cancel')">
                                <span class="icon-cancel"></span>
                                Cancel</button>
                            </div>
                            </div>      
                    </div>
                </div>

           <div class="form-horizontal">
                <div class="control-group">
                    <label class="control-label" for="cgroup_id"><?php echo JText::_('UserGroups'); ?>
                    </label>
                    <div class="controls">
                        <?php echo $this->usergroups; ?>
                    </div>
                </div>  
                   
                <div class="control-group">
                    <label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="firstname" id="firstname" size="60"  value="<?php echo $this->customer->firstname; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="lastname" id="lastname" size="60" maxlength="255" value="<?php echo $this->customer->lastname; ?>" />
                    </div>
                </div>
                

                
        <?php if ($this->customer->id) { ?>
                
                    <?php if ($this->user->id) { ?>
                    <div class="form-horizontal">
                        
                        <div class="control-group">
                            <label class="control-label" for="block"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_BLOCK'); ?>
                            </label>
                            <div class="controls form-inline">
                                <input type="radio" class="inputRadio" name="block" value="<?php echo CUSTOMER_USER_STATE_BLOCK; ?>" id="block_yes" <?php if ($this->user->block == CUSTOMER_USER_STATE_BLOCK) echo 'checked="checked"'; ?>/>
                                <label for="block_yes"><?php echo JText::_('JYES'); ?></label>
                                <input type="radio" class="inputRadio" name="block" value="<?php echo CUSTOMER_USER_STATE_ENABLED; ?>" id="block_no" <?php if ($this->user->block == CUSTOMER_USER_STATE_ENABLED) echo 'checked="checked"'; ?>/>
                                   <label for="block_no"><?php echo jtext::_('JNO'); ?></label>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>
                            </label>
                            <div class="controls">
                                <input class="text_area required" type="text" name="email" id="email" size="60" maxlength="255" value="<?php echo $this->user->email; ?>" />
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label class="control-label" for="registerDate"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_REGISTER_DATE'); ?>
                            </label>
                            <div class="controls">
                                <?php echo AHtmlFrontEnd::date($this->user->registerDate); ?>
                            </div>
                        </div>
                    
                        
                       <?php } else { ?>
                        <div class="control-group">
                            <label class="control-label" for="User"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_USER'); ?>
                            </label>
                            <div class="controls">
                                <?php echo JText::_('COM_BOOKPRO_CUSTOMER_NOT_FOUND'); ?>
                            </div>
                        </div>
                        
                       <?php } ?>
                       
        <?php } else { ?>
            
            
                    <div class="control-group">
                        <label class="control-label" for="username"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_USER_NAME'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="username" id="username" size="60" maxlength="255" value="<?php echo $this->user->username; ?>" class="inputbox" autocomplete="off"/>
                        </div>
                    </div>
                    
                    
                    
                    <div class="control-group">
                        <label class="control-label" for="password"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NEW_PASSWORD'); ?>
                        </label>
                        <div class="controls">
                            <input type="password" name="password" id="password" size="40" value="<?php echo JRequest::getString('password'); ?>" class="inputbox" autocomplete="off"/>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="password2"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_VERIFY_PASSWORD'); ?>
                        </label>
                        <div class="controls">
                            <input type="password" name="password2" id="password2" size="40" value="<?php echo JRequest::getString('password2'); ?>" class="inputbox" autocomplete="off"/>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="email" id="email" size="60" maxlength="255" value="<?php echo $this->user->email; ?>" class="inputbox" autocomplete="off"/>
                        </div>
                    </div>
            
        <?php } ?>
      
                
                <div class="control-group">
                    <label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="telephone" id="telephone" size="60" maxlength="255" value="<?php echo $this->customer->telephone; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="fax"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FAX'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="fax" id="fax" size="60" maxlength="255" value="<?php echo $this->customer->fax; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="address" id="address" size="60" maxlength="255" value="<?php echo $this->customer->address; ?>" />
                    </div>
                </div>
            
                <div class="control-group">
                    <label class="control-label" for="city"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CITY'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="city" id="city" size="60" maxlength="255" value="<?php echo $this->customer->city; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="zip"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ZIP'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="zip" id="zip" size="60" maxlength="255" value="<?php echo $this->customer->zip; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="countries"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY'); ?>
                    </label>
                    <div class="controls">
                        <?php echo $this->countries; ?>
                    </div>
                </div>
        </div>

    	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CUSTOMER; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->customer->id; ?>"/>
	<!-- Use for display customers reservations -->
	<input type="hidden" name="filter_customer-id" value="<?php echo $this->customer->id; ?>"/>
	<?php echo JHTML::_('form.token'); ?>
</form>
