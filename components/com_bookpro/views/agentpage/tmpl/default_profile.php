
<?php
defined('_JEXEC') or die('Restricted access');
$doc=JFactory::getDocument();
$doc->setTitle($this->customer->lastname);
?>

<form action="<?php echo  JRoute::_('index.php'); ?>" method="post"
	name="registerform">


	<?php echo $this->loadTemplate('customer') ?>
	
	
	<div class="center-button span5">
	<input
		type="submit" class="btn btn-medium btn-success" name="submit" id="submit"	value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>" onclick="return validation()"
		/>
	</div>
	 
	<input type="hidden" name="option" value="com_bookpro" /> <input
		type="hidden" name="user_id" value="<?php echo $user_id->id;?>" /> <input
		type="hidden" name="return" value="<?php echo $this->return?>" /> <input
		type="hidden" name="controller" value="customer" /> <input
		type="hidden" name="task" value="save" />

	<?php echo JHTML::_( 'form.token'); ?>

</form>
