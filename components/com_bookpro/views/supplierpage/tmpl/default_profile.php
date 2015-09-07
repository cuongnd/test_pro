
<?php
defined('_JEXEC') or die('Restricted access');
$doc=JFactory::getDocument();
$doc->setTitle($this->customer->lastname);
?>
<fieldset>
     <legend> <?php echo JText::_('COM_BOOKPRO_SUPPLIER_CHANGE_PROFILE')?></legend>
<form action="index.php" method="post" name="profile">
 
	<?php echo $this->loadTemplate('customer') ?>
		
	<div class="center-button span5">
	    <input type="submit" class="btn btn-primary" name="submit" id="submit"	value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>" onclick="return validation()" />
	</div>
	 
	<input type="hidden" name="option" value="com_bookpro" /> 
    <input type="hidden" name="user_id" value="<?php echo $user_id->id;?>" /> 
    <input type="hidden" name="return" value="<?php echo $this->return?>" /> 
    <input type="hidden" name="controller" value="customer" /> 
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>    
	<?php echo JHTML::_( 'form.token'); ?>       
</form>
</fieldset>
