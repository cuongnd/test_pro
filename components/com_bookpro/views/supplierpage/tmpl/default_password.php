<?php 
defined('_JEXEC') or die('Restricted access');
?>
<fieldset>
     <legend> <?php echo JText::_('COM_BOOKPRO_SUPPLIER_CHANGE_PASSWORD')?></legend>
<form action="index.php" method="post" name="password" >
    <div class="form-horizontal" id="editcell">
        <div class="control-group">
                <label class="control-label" for="name"> <?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_PASSWORD' ); ?>:* </label>
            <div class="controls">
                 <input class="inputbox" type="password" name="password" id="pass" size="30" maxlength="50" value="" required/>
                <!-- <font size="1"><?php // echo JText::_( 'COM_BOOKPRO_MINIMUM_LENGTH_IS_6' ); ?></font>  -->
            </div>
        </div>
            
        <div class="control-group">
                <label class="control-label" for="name"><?php echo JText::_( 'COM_BOOKPRO_CUSTOMER_CONFIRM_PASSWORD' ); ?>:* </label>
            <div class="controls">
                 <input class="inputbox" type="password" name="password2" id="pass2" size="30" maxlength="50" value="" required/>
            </div>
        </div>

    <div class="center-button span5">
    	<input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>"/>
    </div>
     
  </div>
  <input type="hidden" name="option" value="com_bookpro" />  
  <input type="hidden" name="controller" value="customer"/>
  <input type="hidden" name="task" value="changepassword" />
  <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>  
  <?php echo JHTML::_( 'form.token' ); ?>
</form>
</fieldset>

