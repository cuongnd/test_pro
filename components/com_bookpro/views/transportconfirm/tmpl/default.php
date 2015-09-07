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
AImporter::helper('bookpro','date','form','currency','request');
AImporter::css('transport','jquery-ui','jquery.ui.datepicker');
?>

<form name="frontForm"
	action='index.php'
	method="post" onsubmit="return validateForm()">
<div class="container-fluid">


	
	<div class="row-fluid">
	<?php 
		if(count($this->trips)>0):
			for ($i = 0; $i < count($this->trips); $i++): 
			 ?>
		<div class="span6">
			
			<div>
			<p class="lead"><span><?php echo JText::_('COM_BOOKPRO_ROUTE')?></span></p>
			<hr/>
			  <div class="location"><span aria-hidden="true" class="icon-location"></span><span><?php echo JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_LOCATION') ?></span></div>
			  
			   <?php if($this->trips[$i]['priority']==1) {?>
			   	<p class="address"><?php echo JText::sprintf('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER_TEXT',$this->trips[$i]['purpose'])?> 
				, <?php echo $this->trips[$i]['from_title']?></p>
				<?php 
					} 
					else 
					{ ?>
					<p class="address"><?php echo JText::sprintf('COM_BOOKPRO_TRANSPORT_PRIVATE_ADDRESS_TEXT',$this->trips[$i]['location'])?>, <?php echo $this->trips[$i]['from_title']?> </p>
					
				<?php 
					} 
					?>
			</div>
		
		
		<div>
			<div class="location"><span aria-hidden="true" class="icon-location"></span><?php echo JText::_('COM_BOOKPRO_TRANSPORT_DROP_LOCATION') ?></div>
			 <?php if($this->trips[$i]['priority']==1) {?>
				<p><?php echo JText::sprintf('COM_BOOKPRO_TRANSPORT_PRIVATE_ADDRESS_TEXT',$this->trips[$i]['location'])?>
					, <?php echo $this->trips[$i]['to_title']?> </p>
				<?php } else { ?>
				
				<span class="address"><?php echo JText::sprintf('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER_TEXT',$this->trips[$i]['purpose'])?> </span>
				,<span><?php echo $this->trips[$i]['to_title']?></span>
					
				<?php } ?>
		</div>
			
		<div>
			<div class="location"><span aria-hidden="true" class="icon-clock"></span><?php echo JText::_('COM_BOOKPRO_TRANSPORT_DATETIME') ?></div>
			<?php echo JHtml::_('date',$this->trips[$i]['start']) ?><br/>
				<?php echo JText::sprintf('COM_BOOKPRO_TRANSPORT_TIME_TEXT',JHtml::_('date',$this->trips[$i]['start'],'H:i')) ?>
		</div>
		
		<div class="location">
			 <span aria-hidden="true" class="icon-user"></span><span> <?php echo JText::sprintf('COM_BOOKPRO_TRANSPORT_PAX_TEXT',$this->trips[$i]['adult']) ?></span>
	    </div>
	    <div>
			 <?php 
			 
			 echo JText::sprintf('COM_BOOKPRO_TRANSPORT_TYPE_TEXT',$this->trips[$i]['package']?JText::_('COM_BOOKPRO_PRIVATE'):JText::_('COM_BOOKPRO_SHARED'),CurrencyHelper::formatprice($this->trips[$i]['price']))?>
	    </div>
		 
		</div>
		<?php endfor;
			   endif;
		    ?>
		
		</div>
		<hr/>
		<div class="lead">
			<?php echo JText::sprintf('COM_BOOKPRO_ORDER_TOTAL_TEXT',CurrencyHelper::formatprice($this->cart->total))?>
		</div>

<?php $this->addTemplatePath( JPATH_COMPONENT_FRONT_END_SITE.DS.'views' . DS . 'mypage' . DS . 'tmpl' );
echo $this->loadTemplate('customer'); ?>
<div class="form-actions ">
<input type="submit" value="<?php echo JText::_('COM_BOOKPRO_CONFIRM')?>" class="btn btn-primary pull-right" /></div>
</div>

<?php 
	$hidden=array('controller'=>'transport','task'=>'confirm',
			'customer_id'=>$this->customer->id,
			"Itemid"=>JRequest::getVar('Itemid'));
		 echo FormHelper::bookproHiddenField($hidden);
	?>
</form>
<script type="text/javascript">
function validateForm(){
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
	form.submit();
}

</script>

