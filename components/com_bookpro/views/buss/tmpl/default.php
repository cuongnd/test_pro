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
AImporter::helper('form','html');
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
AImporter::css('bus','bookpro');
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
$doc=JFactory::getDocument();
//$doc->addScript(JURI::root().'components/com_bookpro/assets/js/i18n/jquery.ui.datepicker-'.$local.'.js');

$doc->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if($local !='en'){
	$doc->addScript(JURI::root().'components/com_bookpro/assets/js/validatei18n/messages_'.$local.'.js');
}
$doc->addScript('components/com_bookpro/assets/js/view-bustrips.js');
$doc->addScript('components/com_bookpro/assets/js/jquery-create-seat.js');
$doc->addStyleSheet('components/com_bookpro/assets/css/jquery-create-seat.css');
$doc->addScript('components/com_bookpro/assets/js/jquery.session.js');
$doc->addStyleSheet('components/com_bookpro/assets/css/view-bustrips.css');
$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
$cart->load();

$doc->addScriptDeclaration('
		var adult='.$cart->adult.';
		var msg_select_again="'.JText::sprintf("COM_BOOKPRO_SELECT_AGAIN",$cart->adult).'";
		');

$today=JFactory::getDate()->getTimestamp();
$doc->addScriptDeclaration($tab);



$from_title=$this->from_to[0]->title;
$to_title=$this->from_to[1]->title;

$arr_param=array('option'=>'com_bookpro',
		'controller'=>'bus',
		'task'=>'ajaxsearch',
		'format'=>'raw',
		'start'=>JFactory::getDate($this->cart->start)->format('Y-m-d'),
		'tmpl'=>'component');

$oneway_action=JURI::base().'index.php?'.JURI::buildQuery($arr_param);

if($this->cart->end){
	$arr_param_r=array('option'=>'com_bookpro',
			'controller'=>'bus',
			'task'=>'ajaxsearch',
			'layout'=>'return',
			'format'=>'raw',
			'end'=>JFactory::getDate($this->cart->end)->format('Y-m-d'),
			'tmpl'=>'component');

	$roundtrip_action=JURI::base().'index.php?'.JURI::buildQuery($arr_param_r);
}


?>


<div class="bustrip_form">
	<form name="frontForm" id="bustrip_form" method="post"
		action='index.php' onsubmit="return submitForm()">
		<div class="bpblock">
			<h4>
				<span><?php echo JText::sprintf('COM_BOOKPRO_BUS_FROM_TO',$from_title,$to_title) ?>
				</span>(
				<?php echo JHtml::date($this->cart->start,'d-m-Y');?>
				)
			</h4>
		</div>

		<div id="tabs">
			<!-- Display oneway trip -->

		</div>

		<p></p>

		<?php if ($this->cart->roundtrip==1) {?>
		<div class="bpblock">
			<h4>
				<span><?php echo JText::sprintf('COM_BOOKPRO_BUS_FROM_TO',$to_title,$from_title) ?>
				</span> (
				<?php echo JHtml::date($this->cart->end,'d-m-Y');?>
				)
			</h4>
		</div>

		<div id="tabs_return">
			<!-- Display return trip -->
		</div>
		<?php } ?>
		<div class="passenger">
			<?php 

			$layout = new JLayoutFile('passenger_form', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
			$html = $layout->render($cart);
			echo $html;
			?>
		</div>
		<div class='center-button'>
			<input type="submit" name="btnSubmit"
				value="<?php echo JText::_('COM_BOOKPRO_CONTINUE') ?>"
				class="btn btn-primary" />
		</div>

		<?php echo FormHelper::bookproHiddenField(array('controller'=>'bus','task'=>'reserve','Itemid'=>JRequest::getVar('Itemid')))?>
	</form>
</div>
<script type="text/javascript">

jQuery(document).ready(function($) {
	ajaxbustripload();
	ajaxbustripload_return();
function ajaxbustripload(){
	$.ajax({
		type:"GET",
		url: "<?php echo $oneway_action ?>",
		data:"",
		beforeSend : function() {
			$("#tabs").html('<h1><?php echo JText::_("COM_BOOKPRO_LOADING")?></h1>');
		},
		success:function(result){
				$("#tabs").html(result);
			}
		});
}

function ajaxbustripload_return(){
	$.ajax({
		type:"GET",
		url: "<?php echo $roundtrip_action ?>",
		data:"",
		beforeSend : function() {
			$("#tabs_return").html('<h1><?php echo JText::_("COM_BOOKPRO_LOADING")?></h1>');
		},
		success:function(result){
				$("#tabs_return").html(result);
			}
		});

}
});
function getValidateSeat(pSeat,j){
	var check = true;
	
	for(var i = 0;i < pSeat.length;i++){
		
		if(i != j){
			
			
			
			if(parseInt(pSeat[j].value) == parseInt(pSeat[i].value)){
				
				check = false;
			}
		}
	}
	return check;
}		
function submitForm(){
	
	var pSeat = jQuery("select[name='pSeat[]']");
	var pReturnSeat = jQuery("select[name='pReturnSeat[]']");
	var check = true;
	for(var i = 0;i < pSeat.length;i ++){
		check = getValidateSeat(pSeat,i);
		
	}
	<?php if($this->cart->roundtrip==1){ ?>
	var checkreturn = true;
	for(var j = 0;j < pReturnSeat.length;j ++){
		
		checkreturn = getValidateSeat(pReturnSeat,j);
	}
	<?php } ?>
	
	
	
	var form= document.frontForm;
	if(jQuery("input:radio[name='bustrip_id']").is(":checked")==false)
	{
		alert("<?php echo JText::_('COM_BOOKPRO_SELECT_BUSTRIPS_WARN')?>");
	 		return false; 
	}
	if(jQuery("input:radio[name='return_bustrip_id']").is("*")){
		if(jQuery("input:radio[name='return_bustrip_id']").is(":checked")==false)
		{
			alert("<?php echo JText::_('COM_BOOKPRO_SELECT_BUSTRIPS_WARN')?>");
		 		return false; 
		}
	}
	var stop=0;
	jQuery("input:radio[name='bustrip_id'],input:radio[name='return_bustrip_id']").each(function () {
		
		if(jQuery(this).is(":checked"))
		{
			var tr_viewseat=jQuery(this).closest('.busitem').next('.tr_viewseat');
			if(tr_viewseat.find('.bodybuyt .choose').length<adult)
			{
				alert('<?php echo JText::_('COM_BOOKPRO_SELECT_SEAT_WARN') ?>');
				stop=1;
				tr_viewseat.find('.bodybuyt').focus();
				return false;
				
			}
		}
	});
	
	if(check === false){
		alert('<?php echo JText::_('COM_BOOKPRO_DUPLICATE_SEAT') ?>');
		return false;
	}
	<?php if($this->cart->roundtrip==1){ ?>
	if(checkreturn === false){
		alert('<?php echo JText::_('COM_BOOKPRO_DUPLICATE_RETURNSEAT') ?>');
		return false;
	}
	<?php } ?>
	if(stop==1)
	{
		return false;
	}	
	form.submit();
}


</script>
<script type="text/javascript">
var ALPHA_REGEX = "[a-zA-Z]*";

jQuery(document).ready(function($){

	jQuery.validator.addMethod("accept", function(value, element, param) {
		  return value.match(new RegExp("." + param + "$"));
		}); 
	
$("#bustrip_form").validate({
    lang: '<?php echo $local ?>',
   	rules: {
          firstname: { accept: "[a-zA-Z ]+", 
   					   minlength: 2
           },
    	  lastname: { accept: "[a-zA-Z ]+", 
		   minlength: 2
		},
           'pFirstname[]': { accept: "[a-zA-Z ]+", 
    		   minlength: 2
    		},
           'pLastname[]': { accept: "[a-zA-Z ]+", 
    		   minlength: 2
    		},
    		city: { accept: "[a-zA-Z ]+", 
     		   minlength: 2
     		}
    		
           
   	}
	 
});
});

</script>
<script>
	jQuery(document).ready(function($) {
	if ($("input.birthday").length > 0){
		
	     $( "input.birthday" ).datepicker({yearRange:"1930:2013",maxDate:-1,changeMonth: true,changeYear: true,showOn: "button",buttonImage: "<?php echo JUri::base()?>components/com_bookpro/assets/images/calendar.png",buttonImageOnly: true });
	     $( "input.birthday" ).datepicker( "option", $.datepicker.regional['vi']);
	     $( "input.birthday" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
	}
  });
</script>
