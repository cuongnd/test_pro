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

$today=JFactory::getDate()->getTimestamp();

$date_arr = DateHelper::getArrayTabDate($this->cart->start);

if($this->cart->end){
	$date_arr_return=DateHelper::getArrayTabDate($this->cart->end);
	
}

$active_tabs = 0;
$active_return = 0;

for ($i = 0; $i < count($date_arr); $i++) {
	
	if ($date_arr[$i]->format('Y-m-d',true) == JFactory::getDate($this->cart->start)->format('Y-m-d',true)) {
		$active_tabs = $i;
	}
}
for ($i = 0; $i < count($date_arr_return); $i++) {
	
	if ($date_arr_return[$i]->format('Y-m-d',true) == JFactory::getDate($this->cart->end)->format('Y-m-d',true)) {
		$active_return = $i;
	}
}


$airport_from=$this->from_to[0]->title." (".$this->from_to[0]->code.")";
$airport_to=$this->from_to[1]->title." (".$this->from_to[1]->code.")";
$action='index.php?Itemid='.JRequest::getVar("Itemid");


?>


<form name="frontForm" id="flight_form" method="post" action='<?php echo $action ?>' onsubmit="return submitForm()">

	
	
	

	<div id="flight_depart">
		<ul id="tabs" class="nav nav-tabs tabs-up">
			<?php for ($i = 0; $i < count($date_arr); $i++) {
				?>
				<?php
				$action=JURI::base().'index.php?option=com_bookpro&controller=flight&task=ajaxsearch&tmpl=component&format=raw&start='.$date_arr[$i]->format('Y-m-d').'&roundtrip='.$this->cart->roundtrip;
				$attribs = array('data-url'=>$action);
				
				?>
				<li class="<?php echo $i==$active_tabs ? "active":""; ?>">
				<?php 
				echo JHtml::link('#tabs-'.$i, JHTML::_('date', $date_arr[$i], 'D, M j'),$attribs);?>
				</li>
			<?php 	
    		} ?>
		</ul>
		<div id="tab_content" class="tab-content">
		<?php for ($j = 0; $j < count($date_arr); $j++) {?>
		<div class="tab-pane <?php echo $j==$active_tabs ? "active":""; ?>" id="tabs-<?php echo $j?>"></div>
		<?php  } ?>
		</div>
		<div id="loading" style="display: none;"><?php echo JText::_('COM_BOOKPRO_LOADING') ?></div>
	</div>

	
	
	<?php if ($this->cart->roundtrip=='1') {?>
	

	<div id="flight_return">
		<ul id="tabs_return" class="nav nav-tabs tabs-up">
			<?php for ($i = 0; $i < count($date_arr_return); $i++) {
				$params=array('option'=>'com_bookpro',
						'controller'=>'flight',
						'task'=>'ajaxsearch',
						'layout'=>'return',
						'format'=>'raw',
						'end'=>$date_arr_return[$i]->format('Y-m-d'),
						'roundtrip'=>$this->cart->roundtrip,
						'tmpl'=>'component');
					
				$action_return=JURI::base().'index.php?'.JURI::buildQuery($params);
				$attribs_return = array('data-url'=>$action_return);
				
				?>
				<li class="<?php echo $i==$active_return ? "active":""; ?>">
				<?php 
				echo JHtml::link('#tabs_return-'.$i, JHTML::_('date', $date_arr_return[$i], 'D, M j'),$attribs_return);
				?>
				</li>
			<?php 	
    		} ?>
		</ul>
		<div id="return_tab_content" class="tab-content">
			<?php for ($j = 0; $j < count($date_arr_return); $j++) {?>
			<div class="tab-pane <?php echo $j==$active_return ? "active":""; ?>" id="tabs_return-<?php echo $j?>"></div>
			<?php  } ?>
			
		</div>
		<div id="return_loading" style="display: none;"><?php echo JText::_('COM_BOOKPRO_LOADING') ?></div>
	</div>
	<?php } ?>

	
	<div class="center-button">
		<button class="btn btn-primary" type="submit"><?php echo JText::_('COM_BOOKPRO_CONTINUE') ?></button>
		
	</div>
	<?php 

	$hidden=array('controller'=>'flight','task'=>'reserve','customer_id'=>$this->customer->id,
			'package'=>'','return_package'=>'');
	echo FormHelper::bookproHiddenField($hidden);	?>
	 <input type="hidden"
		name="<?php echo $this->token?>" value="1" />

</form>
<script>

jQuery(document).ready(function($) {
	
	$('#tabs a').click(function (e) {
		e.preventDefault();
	  
		var aurl = $(this).attr("data-url");
	  	var href = this.hash;
	  	var pane = $(this);

	  	$.ajax({
		  	url:aurl,
		  	beforeSend: function() {
		  	    $("#loading").show();
		  	  	$("#tab_content").hide();
		  	  },
		  	success:function(data){
		  		pane.tab('show');
		  		setTimeout(function(){

		  			
			  		$("#loading").hide();
			  		$("#tab_content").show();
			  		$(href).html(data);
			  		}, 1000);
		  		 
			 } 
		 });
		// ajax load from data-url
		
		
		
		
	});
	
		
		$.ajax({
		  	url:$('#flight_depart .active a').attr("data-url"),
		  	beforeSend: function() {
		  	    $("#loading").show();
		  	  	$("#tab_content").hide();
		  	  },
		  	success:function(data){
		  		
		  		setTimeout(function(){

		  			
			  		$("#loading").hide();
			  		$("#tab_content").show();
			  		$('.active a').tab('show');
			  		$('#tabs-<?php echo $active_tabs; ?>').html(data);
		  		}, 1000);
		  		 
			 } 
		 });
	
	
	
	// load first tab content
	
	
	
	$('#flight_return #tabs_return a').click(function (e) {
		e.preventDefault();
	  
		var aurl = $(this).attr("data-url");
		
	  	var href = this.hash;
	  	var pane = $(this);
	  	
		// ajax load from data-url
		$.ajax({
		  	url:aurl,
		  	beforeSend: function() {
		  	    $("#return_loading").show();
		  	  	$("#return_tab_content").hide();
		  	  },
		  	success:function(data){
		  		pane.tab('show');
		  		setTimeout(function(){

		  			
			  		$("#return_loading").hide();
			  		$("#return_tab_content").show();
			  		$(href).html(data);
			  		}, 1000);
		  		 
			 } 
		 });
		 /*
		$(href).load(url,function(result){      
		    pane.tab('show');
		});*/
		
	});
	
	// load first tab content
	$.ajax({
		  	url:$('#flight_return .active a').attr("data-url"),
		  	beforeSend: function() {
		  	    $("#return_loading").show();
		  	  	$("#return_tab_content").hide();
		  	  },
		  	success:function(data){
		  		$('.active a').tab('show');
		  		setTimeout(function(){

		  			
			  		$("#return_loading").hide();
			  		$("#return_tab_content").show();
			  		$('#tabs_return-<?php echo $active_return; ?>').html(data);
			  		}, 1000);
		  		 
			 } 
		 });
	 /*
	$('#tabs_return-<?php echo $active_return; ?>').load($('#flight_return .active a').attr("data-url"),function(result){
		
	  $('#tabs_return-<?php echo $active_return; ?> .active a').tab('show');
	});*/
   });
   
</script>
<script type="text/javascript">

function checkPackage(package){
	var form= document.frontForm;
	form.package.value=package;
}
function checkReturnPackage(package){
	var form= document.frontForm;
	form.return_package.value=package;
}


function changeDesfrom(select){
	var form= document.frontForm;
	
	for(i=0; i < form.desto.options.length; i++ ){
		option = form.desto.options[i];
		if(select.value != option.value){
			//option.removeAttribute('disabled');
			option.disabled = false;
		}else{
			option.disabled = true;
			if(form.desto.options[form.desto.selectedIndex].value == select.value){
				form.desto.selectedIndex=0;
			}
			
		}
	}
}



function submitForm(){

	var form= document.frontForm;
	if(jQuery("input:radio[name='flight']").is(":checked")==false)
	{
		alert("<?php echo JText::_('COM_BOOKPRO_FLIGHT_SELECT_WARN')?>");
	 		return false; 
	}
	if(jQuery("input:radio[name='return_flight']").is("*")){
		if(jQuery("input:radio[name='return_flight']").is(":checked")==false)
		{
			alert("<?php echo JText::_('COM_BOOKPRO_FLIGHT_SELECT_WARN')?>");
		 		return false; 
		}
	}
	
	//var regex = \d+ ?\w{0,9} ?\d+;	
	/*
	

	var firstname = document.getElementsByName('pFirstname[]');
	
	for(i=0 ; i < firstname.length; i++){
		
		if(firstname[i].value =="" ){
			alert('<?php echo JText::_('Must enter passenger information')?>');
			firstname[i].focus();
			return;
		}
	}

	if(form.firstname.value==""){
		alert('<?php echo JText::_('INPUT_FIRSTNAME_WARN')?>');
		form.firstname.focus();
		return;
	}

	if(form.lastname.value==""){
		alert('<?php echo JText::_('INPUT_LASTNAME_WARN')?>');
		form.lastname.focus();
		return;
	}

    var phone=form.telephone.value;
	if(form.telephone.value==""){
		alert('<?php echo JText::_('You must input telephone number')?>');
		form.telephone.focus();
		return;
	}
	  
	if (!/(^[0-9\s\-\+]{10,})$/gi.test(phone)){   
	   	//alert('<?php echo JText::_('You must input valid telephone number')?>');
		//form.telephone.focus();
		//return;
	  }

	if(form.email.value==""){
		alert('<?php echo JText::_('INPUT_EMAIL_WARN')?>');
		form.email.focus();
		return;
	}
	*/
//
//	if(form.country_id.options[form.country_id.selectedIndex].value==0){
//		alert('<?php echo JText::_('You must select Country')?>');
//		form.country_id.focus();
//		return;
//	}

	form.task.value ='reserve';
	form.submit();
}
</script>

