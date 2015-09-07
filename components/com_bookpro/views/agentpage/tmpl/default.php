<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.html' );
JHtmlBehavior::modal('a.modal_hotel');
AImporter::helper('string','date','bookpro','currency');
AImporter::css('bookpro','customer');   
AImporter::jquery();

?>
<div class="mypage">

	<h4>
		<?php echo JText::sprintf('COM_BOOKPRO_CUSTOMER_WELCOME',JHTML::link('index.php?option=com_bookpro&view=mypage&form=profile',$this->user->name)) ?>
	</h4>
	
	<div class="menu">
	<?php echo $this->loadTemplate('menu')?>
	</div>
	
	<div class="order">
		<?php echo $this->loadTemplate(JRequest::getVar('form','order'))?>
	</div>
	<div class="clear"></div>
</div>
