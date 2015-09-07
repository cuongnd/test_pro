<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('date','currency');
AImporter::css('customer');
?>
<form name="frontForm" method="post"
	action='<?php echo JRoute::_("index.php") ?>'>

	<div id="hotelbook1">

		<?php echo $this->loadTemplate("hotel")?>

		<?php echo $this->loadTemplate("customer")?>

		<?php foreach($this->rooms as $room):?>
		<?php for ($j=0;$j<count($room->guest_name);$j++):?>
		<table class="bookstep2">
			<tbody>
				<tr>
					<td colspan="3" class="roomname"><b><?php echo JText::_('COM_BOOKPRO_ROOM')?>:</b>
						<?php echo $room->room_type ?>
					</td>
				</tr>
				<tr class="guest">
					<th class="first"><b><?php echo JText::_('COM_BOOKPRO_GUEST_NAME')?>
					</b>
					</th>
					<th style="white-space: nowrap"><?php echo JText::_('COM_BOOKPRO_ROOM_MAX_PERSON')?>
					</th>

				</tr>
				<tr>
					<td width="200" class="name"><input type="hidden" name="room_id[]"
						value="<?php echo $room->id ?>" /> <?php echo $room->guest_name[$j] ?>
					</td>
					<td width="50"><?php echo $room->max_person.' '. JText::_('COM_BOOKPRO_GUESTS') ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php endfor;?>
		<?php endforeach;?>

		<dl>
			<dt>
				<?php echo JText::_('COM_BOOKPRO_ORDER_NOTE') ?>
			</dt>
			<dd>
				<?php echo $this->cart->notes ?>
			</dd>
		</dl>
		<div class="center-button">
			<input type="submit" name="btnSubmit"
				value="<?php echo JText::_('COM_BOOKPRO_CONTINUE')?>" class="button" />
		</div>
		<div class="clear"></div>
	</div>

	<input type="hidden" name="option"		value="<?php echo JRequest::getVar('option') ?>" /> <input
		type="hidden" name="task" value="step3" />  <input type="hidden"
		name="controller" value="hotel" /> 
		<input type="hidden" name="Itemid"	value="<?php echo JRequest::getVar('Itemid') ?>" />
		<?php echo JHtml::_('form.token')?>
	
</form>

<script type="text/javascript">


function onCheck(){

		
	var form= document.frontForm;
	var firstname = document.getElementsByName('firstname[]');
	
	for(i=0 ; i < firstname.length; i++){
		
		if(firstname[i].value =="" ){
			alert('<?php echo JText::_('ENTER_GUEST_INFO')?>');
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
		alert('<?php echo JText::_('TELEPHONE_WARN')?>');
		form.telephone.focus();
		return;
	}
	  
	if(form.email.value==""){
		alert('<?php echo JText::_('INPUT_EMAIL_WARN')?>');
		form.email.focus();
		return;
	}
}

</script>


