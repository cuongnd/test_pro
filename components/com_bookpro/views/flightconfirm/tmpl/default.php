<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('date','bookpro','currency','request','form');
AImporter::css('bookpro','customer','flight','jquery-ui');
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
$config=AFactory::getConfig();
$document = JFactory::getDocument();
$document->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
//$document->addScript(JURI::root().'components/com_bookpro/assets/js/i18n/jquery.ui.datepicker-'.$local.'.js');
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
//$document->addScript(JURI::root().'components/com_bookpro/assets/js/validatei18n/messages_'.$local.'.js');
$user = JFactory::getUser();

?>


<script>

jQuery("#frontForm").validate({
    lang: '<?php echo $local ?>'
});

	
</script>

	
	<div class="row-fluid">
		<div class="span8">
			<?php 
			$user = JFactory::getUser();
			?>
			<?php if ($user->get('guest')) {
					 ?>
			<div class="asian-login">
				<?php 
				
				$layout = new JLayoutFile('login', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
				$html = $layout->render(null);
				echo $html;
				 ?>
			</div>
			<?php } ?>
			<form name="frontForm" action='index.php' method="post" id="frontForm">
			<?php echo $this->loadTemplate('flight'); ?>
			<?php 
				$hidden=array('controller'=>'flight','task'=>'confirm',
						'customer_id'=>$this->customer->id,
						"Itemid"=>JRequest::getVar('Itemid'));
					 echo FormHelper::bookproHiddenField($hidden);
					 ?>
				<input type="hidden" name="<?php echo $this->token?>" value="1" />
			</form>
		</div>
		<div class="span4">
		<?php echo $this->loadTemplate('right'); ?>
		</div>
	</div>
	
<script>
		 
</script>
<script type="text/javascript">
function submitForm(){
	
	var form= document.frontForm;
	// validate customer
	if(form.firstname.value==""){
		alert('<?php echo JText::_('COM_BOOKPRO_CUSTOMER_INPUT_WARN')?>');
		form.firstname.focus();
		return false;
	}

	if(form.lastname.value==""){
		alert('<?php echo JText::_('COM_BOOKPRO_CUSTOMER_INPUT_WARN')?>');
		form.lastname.focus();
		return false;
	}

    var phone=form.telephone.value;
	if(form.telephone.value==""){
		alert('<?php echo JText::_('COM_BOOKPRO_CUSTOMER_INPUT_WARN')?>');
		form.telephone.focus();
		return false;
	}
	

	if(form.email.value==""){
		alert('<?php echo JText::_('COM_BOOKPRO_CUSTOMER_INPUT_WARN')?>');
		form.email.focus();
		return false;
	}

	if(form.country_id.options[form.country_id.selectedIndex].value==0){
		alert('<?php echo JText::_('COM_BOOKPRO_CUSTOMER_INPUT_WARN')?>');
		form.country_id.focus();
		return false;
	}
	//validate passenger
	
	var firstname = document.getElementsByName('pFirstname[]');
	
	for(i=0; i < firstname.length; i++){
		
		if(firstname[i].value =="" ){
			alert('<?php echo JText::_('COM_BOOKPRO_PASSEGER_INPUT_WARN')?>');
			firstname[i].focus();
			return false;
		}
	}
	var lastname = document.getElementsByName('pLastname[]');
	
	for(i=0; i < lastname.length; i++){
		
		if(lastname[i].value =="" ){
			alert('<?php echo JText::_('COM_BOOKPRO_PASSEGER_INPUT_WARN')?>');
			lastname[i].focus();
			return false;
		}
	}
	var passport = document.getElementsByName('pPassport[]');
	
	for(i=0; i < passport.length; i++){
		
		if(passport[i].value =="" ){
			alert('<?php echo JText::_('COM_BOOKPRO_PASSEGER_INPUT_WARN')?>');
			passport[i].focus();
			return false;
		}
	}
	
	form.submit();
}

</script>
