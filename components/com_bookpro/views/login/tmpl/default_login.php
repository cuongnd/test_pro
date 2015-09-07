 <legend>
            <?php echo JText::_("COM_BOOKPRO_LOGIN_FORM") ?>
        </legend>
 <div class="form-inline">
           
            <label><?php echo JText::_('COM_BOOKPRO_LOGIN_SOCIAL')?></label>
            <label>
        	<?php 
        		$dispatcher	= JDispatcher::getInstance();
        		JPluginHelper::importPlugin('bookpro');
        		$results = $dispatcher->trigger('onBookproGetRegistrationForm', array ($group_id));
        		echo $results[0];
        	?>
        	</label>
            
    </div>
    	<hr/>
<form name="loginform" method="post" action="index.php">
    <fieldset>
	
        <div class="form-horizontal">            
            
            <div class="control-group">
                <label class="control-label"> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_USERNAME'); ?></label>
                <div class="controls">
                	<input type="text" class="required" id="username" name="username" autocomplete="off"  size="25" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_USERNAME'); ?>"  required/>
           		</div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSWORD'); ?> </label>
                <div class="controls">
                <input type="password" class="required" id="password" name="password" value="" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSWORD'); ?>"  required/>
            </div>   
            </div> 
			 <div class="control-group">
			   <div class="controls">
               <label class="checkbox"> <input type="checkbox"> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_REMEMBER_ME') ?> </label>
               </div>
            </div>
			<div class="control-group">
			   <div class="controls">
                <a href="<?php echo JRoute::_(JURI::root().'index.php?option=com_bookpro&view=reset&Itemid='.JRequest::getVar('Itemid')) ?>">
                    <?php echo JText::_('Forgot your password?'); ?>
                </a>
              </div>
              </div>
			<div class="control-group">
			<label class="control-label"></label>
			 <div class="controls">
             <button type="submit" class="btn btn-medium btn-primary"  type="submit"> <?php echo JText::_('COM_BOOKPRO_LOGIN'); ?> </button>
       		 &nbsp;&nbsp;
       		 <a href="<?php echo JRoute::_(JURI::root().'index.php?option=com_bookpro&view=register&return='.JRequest::getVar('return')) ?>" class="btn btn-primary">
                    <?php echo JText::_('COM_BOOKPRO_REGISTER'); ?>
                </a>
       		
       		</div>
       		</div>
       	
       
        </div>
        <input type="hidden" name="controller" value="customer" />
        <input type="hidden" name="option" value="com_bookpro" />
		<input type="hidden" name="task" value="bplogin" />
        <input type="hidden" name="return" value="<?php echo JRequest::getVar('return',0) ;?>" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid') ;?>" />

        <?php echo JHtml::_('form.token'); ?>
    </fieldset>    
</form>