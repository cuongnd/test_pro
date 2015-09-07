<?php
JHtml::_('dropdown.init');
//JHtml::_('formbehavior.chosen', 'select');


JHtml::_('behavior.multiselect');
defined('_JEXEC') or die('Restricted access');
$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(2);
JToolBarHelper::title(JText::_('COM_BOOKPRO_CREATE_TICKET'));
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
JHtml::_('bootstrap.framework');
AImporter::helper('currency','form');
AImporter::css('bus','customer','passenger','view-bustrips','jquery-create-seat');
AImporter::js('view-bustrips','jquery-create-seat');
$doc=JFactory::getDocument();
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.session.js');
$from_title = $from_title=$this->from_to[0]->title;
$to_title=$this->from_to[1]->title;
$adult=ARequest::getUserStateFromRequest('adult', 1, 'int');
$doc->addScriptDeclaration('
		var adult='.$adult.';
		var msg_select_again="'.JText::sprintf("COM_BOOKPRO_SELECT_AGAIN",$adult).'";
		');

?>
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function() {
	
	
	if(jQuery('input:radio[name=roundtrip]:checked').val()==1){
		jQuery("#returnDate").show();
	}else {
		jQuery("#returnDate").hide();
		}
	jQuery("input:radio[name=roundtrip]").change(function(){
		jQuery("#returnDate").toggle();
	});	
	/*
	jQuery('#desfrom').change(function(){
		jQuery.ajax({
			url:'index.php?option=com_bookpro&controller=bussearch&task=findDestination&from='+jQuery(this).val()+'&tmpl=component',
			success:function(data){
				
				jQuery('#desto').html(data);
				
			}
		});
	});
	*/
});

</script>
<script type="text/javascript">
	function getListSeat(obj){
		
		document.getElementById('listseat').value = obj.value;
	}
	
	function getReturnListSeat(obj){
		document.getElementById('returnlistseat').value = obj.value;
	}
</script>
<div class="span10">
		<form action="index.php" method="post" name="adminForm" id="adminForm"
			class="form-validate">
			
			<div class="container-fluid" style="background-color: #EEF0E4;border: 1px solid #ccc;">
			<div class="form-inline" style="padding: 0px 0px 10px 10px;">
			
				<?php echo JHtmlSelect::booleanlist('roundtrip','class="inputbox"',$this->lists['roundtrip'],JText::_('Return'),JText::_('MOD_BOOKPRO_TRAVEL_SEARCH_ONEWAY'),'roundtrip') ?>
				
				<?php echo $this->select_from; ?>
				<?php echo $this->select_to; ?>

				<div class="input-prepend input-append">
					<div class="add-on">
						<?php echo JText::_('Seat'); ?>
					</div>
					<?php echo JHtmlSelect::integerlist(1, 10, 1, 'adult','class="inputbox input-mini" id="search_adult"',$this->lists['adult'])?>
				</div>
				<div class="input-prepend input-append">

					<div class="add-on">
						<?php echo JText::_('Depart') ?>
					</div>
					<?php 
					echo JHtml::calendar($this->lists['start'], 'start', 'start','%Y-%m-%d',array('class'=>'input-small'));
					?>
				</div>

					<div class="input-prepend input-append" id="returnDate">
						<div class="add-on">
							<?php echo JText::_('Return') ?>
						</div>
						<?php echo JHtml::calendar($this->lists['end'], 'end', 'end','%Y-%m-%d',array('class'=>'input-small')); ?>
					</div>
					
					<button class="btn btn-primary" type="submit">
					<?php echo JText::_('COM_BOOKPRO_SEARCH') ?>
				</button>
				</div>
				
			</div>

			<input type="hidden" name="option" value="com_bookpro" /> <input
				type="hidden" name="controller" value="bussearch" /> <input
				type="hidden" name="task" value="search" />
		</form>
	<?php 

	if ($this->lists['bustrip-from'] && $this->lists['bustrip-to'] && $this->lists['adult'] && ($this->lists['start'] || $this->lists['end'])) {


		?>
	<div class="bus-filter">
		<form name="frontForm" id="bustrip_form" method="post"
			action='index.php' onsubmit="return submitForm()">
				<div class="lead">
						<?php echo $from_title .' - '.$to_title ?> 
				</div>
				<?php echo $this->loadTemplate('oneway'); ?>

				<?php if($this->lists['roundtrip'] == 1){ ?>
				<div class="bpblock" style="padding: 10px 0;">
					<h2>
						<span><?php echo JText::sprintf('COM_BOOKPRO_BUS_FROM_TO',$to_title,$from_title) ?>
						</span>
					</h2>
				</div>
				<?php echo $this->loadTemplate('return'); ?>

				<?php } ?>
			</div>
			<div class="passenger">
				<?php 
				
			$layout = new JLayoutFile('passenger_form', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
			$html = $layout->render(BusHelper::convertArrToObj($this->lists));
			echo $html;
			 ?>
			</div>
			
			<div class="lead">
					<h2 class="block_head">
						<span><?php echo JText::_('COM_BOOKPRO_CUSTOMER')?> </span>
					</h2>
				</div>
				<?php 
					echo $this->loadTemplate('customer');
				?>
		
			
				<input type="submit" name="btnSubmit"
					value="<?php echo JText::_('COM_BOOKPRO_CONTINUE') ?>"
					class="btn btn-primary" />
					
			<input type="hidden" name="start"
				value="<?php echo $this->lists['start']?>"> <input type="hidden"
				name="end" value="<?php echo $this->lists['end']?>">
			<?php echo FormHelper::bookproHiddenField(array('controller'=>'bussearch','task'=>'confirm','listseat'=>'','returnlistseat'=>'','adult'=>$this->lists['adult'],'roundtrip'=>$this->lists['roundtrip']))?>
		</form>
	<?php } ?>

</div>
<script type="text/javascript">
    		
function submitForm(){


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
				alert('<?php echo JText::_('please select seat') ?>');
				stop=1;
				tr_viewseat.find('.bodybuyt').focus();
				return false;
				
			}
		}
	});
	if(stop==1)
	{
		return false;
	}	
	form.submit();
}


</script>

