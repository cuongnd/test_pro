<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper("currency","date",'form');
AImporter::css('customer');
AImporter::js('customer');
JHtmlBehavior::formvalidation();

$action='index.php?option=com_bookpro&controller=hotel&Itemid='.JRequest::getVar("Itemid");
?>
<form name="frontForm" method="post" action="<?php echo $action ?>" class="form-validate">

	<div class="row-fluid">
		
		<div class="span6">
        
			<?php 
			$layout = new JLayoutFile('hotel_short', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
			$html = $layout->render($this->hotel);
			echo $html;
			?>
			<?php echo $this->loadTemplate("room"); ?>
		</div>
		<div class="span6">
			<?php $this->addTemplatePath( JPATH_COMPONENT_FRONT_END_SITE.DS.'views' . DS . 'mypage' . DS . 'tmpl' );
 	 			echo $this->loadTemplate( 'customer' ); ?>
 	 		<div style="text-align: center;">

				<input type="submit" name="btnSubmit"
					value="<?php echo JText::_('COM_BOOKPRO_CONTINUE')?>"
					class="btn btn-primary" />
			</div>	
		</div>
		
		<?php /* ?>
		<div class="customer_form">
			

			<h2>
				<?php echo JText::_('COM_BOOKPRO_GUESTS') ?>
			</h2>
			
			<?php foreach($this->rooms as $room):
			
			?>
			
			<?php for ($j=0;$j<$room->no_room;$j++):?>
			<table class="bookstep2">
				<tbody>
					<tr>
						<td colspan="3" class="roomname"><b><?php echo JText::_('COM_BOOKPRO_ROOM')?>:</b>
							<?php echo $room->title ?>
						</td>
					</tr>
					<tr class="guest">
						<th class="first"><b><?php echo JText::_('COM_BOOKPRO_GUEST_NAME')?>
						</b></th>
						<th style="white-space: nowrap"><?php echo JText::_('COM_BOOKPRO_ROOM_MAX_PERSON')?>
						</th>

					</tr>
					<tr>
						<td width="200" class="name"><input type="hidden" name="room_id[]"
							value="<?php echo $room->id ?>" /> <input type="text"
							name="pfirstname[]" size="28" maxlength="60" value=""
							onblur="if(this.value=='') this.value='First name, last name';"
							onfocus="if(this.value=='First name, last name') this.value='';" />
						</td>
						<td width="50"><?php echo $room->max_person.' '. JText::_('COM_BOOKPRO_GUESTS') ?>
						</td>
					</tr>
					<tr>
						<td colspan="2"></td>
						<td></td>
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
					<textarea name="notes" cols="50" rows="4"></textarea>
				</dd>
			</dl>

			<div class="center-button">

				<input type="submit" name="btnSubmit"
					value="<?php echo JText::_('COM_BOOKPRO_CONTINUE')?>"
					class="button" />
			</div>
			
		</div>
		<?php */ ?>
		
		<?php 

		$hidden=array('controller'=>'hotel','task'=>'step2','id'=> $this->hotel->id,'customer_id'=>$this->customer->id);
		echo FormHelper::bookproHiddenField($hidden);
		echo JHtml::_('form.token');
		?>
	</div>
</form>
<style type="text/css">
#t3-header,#t3-mainnav
{
    display: none;
}
</style>



