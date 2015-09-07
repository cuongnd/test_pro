 <div id="form_login" class="flight-login">

	<div class="row-fluid">
		<div class="span4">
			<label class="login-label"><span><?php echo JText::_('COM_BOOKPRO_FLIGHT_LOGIN') ?></span> <?php echo JText::_('COM_BOOKPRO_FLIGHT_LOGIN_EMAIL'); ?></label>
			<input type="text" class="input-login required" id="email" name="email" autocomplete="off"  size="25"  required />
		</div>
		<div class="span4">
			 <label class="pasword-label"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSWORD'); ?> </label>
			
			 
			 <div class="login-password">
			 	<input class="input-login" type="password" class="required" id="password" name="password" value="" required />
			 	<div align="right" class="forgot-password">
			 	 <a href="<?php echo JRoute::_(JURI::root().'index.php?option=com_bookpro&view=reset&Itemid='.JRequest::getVar('Itemid')) ?>">
                    <?php echo JText::_('Forgot your password?'); ?>
                </a>
                </div>
			 </div>
		</div>
		<div class="span4">
			<div class="flight-button">
			<div class="flight-signin">
				<a href="<?php echo JRoute::_(JURI::root().'index.php?option=com_bookpro&view=register&return='.JRequest::getVar('return')) ?>">
                    <?php echo JText::_('COM_BOOKPRO_REGISTER'); ?>
                </a>
			</div>
			<button id="btn-login" type="submit" class="btn btn-primary btn-login"  type="submit"> 
			
			<?php echo JText::_('Go'); ?> 
			<i class="icon-flight-go"></i>
			</button>
       		</div>
       		 
		</div>
	</div>
   <!-- 
    <input type="hidden" name="controller" value="customer" />
        <input type="hidden" name="option" value="com_bookpro" />
		<input type="hidden" name="task" value="bplogin" />
        <input type="hidden" name="return" value="<?php echo JRequest::getVar('return',0) ;?>" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid') ;?>" />

        <?php //echo JHtml::_('form.token'); ?>
         -->    
	
</div>

