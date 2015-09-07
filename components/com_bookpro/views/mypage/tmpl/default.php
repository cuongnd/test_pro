<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.html.html' );
JHtmlBehavior::modal('a.modal_hotel');
AImporter::helper('string','date','bookpro','currency');
AImporter::css('bookpro','customer');   

?>
<div class="container-fluid">
	
	<div class="span3">
	<h4>
		<?php echo JText::sprintf('COM_BOOKPRO_CUSTOMER_WELCOME',JHTML::link('index.php?option=com_bookpro&view=mypage&form=profile',$this->user->name)) ?>
	</h4>
	    <?php
                $layout = new JLayoutFile('cmenu', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                $html = $layout->render($this->customer);
                echo $html;
                ?>
	
	</div>
	
	<div class="span9">
		<?php echo $this->loadTemplate(JRequest::getVar('form','order'))?>
	</div>
</div>
