<?php 
defined('_JEXEC') or die('Restricted access');
JHtmlBehavior::formvalidation();

?>

<form action="<?php echo  JRoute::_('index.php'); ?>" method="post" name="registerform" class="form-validate">

  	<div class="form-horizontal">
  	<div class="control-group">
		<label class="control-label" for="username"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PASSWORD' ); ?>
		</label>
		<div class="controls">
			
			<input class="inputbox required" type="password" name="password" id="pass" size="30" maxlength="50" value="" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_USERNAME' ); ?>" />
      
		</div>
	</div>
    
    <div class="control-group">
		<label class="control-label" for="password"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PASSWORD' ); ?>
		</label>
    	
		<div class="controls">
			<input class="inputbox required" type="password" name="password" id="pass" size="30" maxlength="50" value="" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PASSWORD' ); ?>" />
		</div>
    </div>
    
	<div class="control-group">
		<label class="control-label" for="password2"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_CONFIRM_PASSWORD' ); ?>
		</label>
    	<div class="controls">
			<input class="inputbox" type="password" name="password2" id="pass2" size="30" maxlength="50" value="" placeholder="<?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_CONFIRM_PASSWORD' ); ?>" />
		</div>
    </div>
    <div class="control-group">
    <div class="controls">
    	<input type="submit" class="btn btn-primary" name="submit" value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>" />
    </div>
    </div>
    </div>


  <input type="hidden" name="option" value="com_bookpro" />
   
  <input type="hidden" name="controller" value="customer"/>

  <input type="hidden" name="task" value="changepassword" />

  <?php echo JHTML::_( 'form.token' ); ?>

</form>

