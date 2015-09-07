<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.html' );
JHtmlBehavior::modal('a.modal_hotel');
AImporter::helper('string','date','bookpro','currency');
AImporter::css('bookpro','customer');   

?>
<div class="row-fluid">

	
	
	<div class="span3">
	<h4>
		<?php echo JText::sprintf('COM_BOOKPRO_CUSTOMER_WELCOME',JHTML::link('index.php?option=com_bookpro&view=mypage&form=profile',$this->user->name)) ?>
	</h4>
	<?php echo $this->loadTemplate('menu')?>
	</div>
	
	<div class="span8">
		<?php echo $this->loadTemplate(JRequest::getVar('form','order'))?>
	</div>
</div>
