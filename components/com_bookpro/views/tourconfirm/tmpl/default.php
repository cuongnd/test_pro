<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$session=&JFactory::getSession();
AImporter::helper('date','currency','form');
AImporter::css('customer');
AImporter::model('category');
$model=new BookProModelCategory();
$model->setId($this->cart->orderinfo['location']);
$location=$model->getObject();

?>
<form name="frontForm" method="post" action=<?php echo JURI::base().'index.php?option=com_bookpro&view=formpayment' ?>>

	<?php //$this->addTemplatePath( JPATH_COMPONENT_FRONT_END_SITE.DS.'views' . DS . 'tourbook' . DS . 'tmpl' );
 	 //echo $this->loadTemplate('tour' ); ?>
			
			<div class="bpblock">
			<div id="booking_detail">
			
			 <h2><?php echo JText::_('COM_BOOKPRO_BOOKING_INFORMATION')?></h2>				
			
				<table>
					
					<tr>
						<th><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE') ?></th>
						<td><b><?php echo $this->tour->title.' ('.$this->package->title.')' ?></b></td>
						<th><?php echo JText::_('COM_BOOKPRO_TOUR_PRICE') ?></th>
						<td><?php echo CurrencyHelper::formatprice($this->price->price) ?></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_BOOKPRO_TOUR_DEPART_DATE') ?></th>
						<td><?php echo DateHelper::formatDate($this->cart->orderinfo['start']) ?></td>
						<th><?php echo JText::_('COM_BOOKPRO_TOUR_START_TIME') ?></th>
						<td><?php echo $this->tour->start_time ?></td>
					</tr>
					
					<tr>
						<th><?php echo JText::_('COM_BOOKPRO_ADULT') ?></th>
						<td><?php echo $this->cart->orderinfo['adult'] ?></td>
						<th><?php echo JText::_('COM_BOOKPRO_CHILDREN') ?></th>
						<td><?php echo $this->cart->orderinfo['child'] ?></td>
					</tr>
					<tr>
						<th><?php echo JText::_('COM_BOOKPRO_TOUR_PICKUP_LOCATION') ?></th>
						<td><?php 	
							echo $location->title;
						 ?></td>
						 <th ><?php echo JText::_('COM_BOOKPRO_ORDER_NOTE')?></th>
						 <td><?php echo $this->cart->notes ?>		</td>
						
					</tr>
					
					<tr>
						<th>
						<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL')?>
						</th>
						<td>
							<span class="total"><?php echo CurrencyHelper::formatprice($this->cart->total) ?></span> 
						</td>
						<th>
						<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL')?>
						</th>
						<td>
						 <?php echo JText::_('Vat included')?>
						</td>
					</tr>
					
					
					</table>
				</div>
			</div>
		
		<div class="bpblock">
		
		<?php $this->addTemplatePath( JPATH_COMPONENT_FRONT_END_SITE.DS.'views' . DS . 'tourconfirm' . DS . 'tmpl' );
		 echo $this->loadTemplate("customer") ?>
		
		</div>
		<div class="center-button">
				<input type="submit" name="btnSubmit"
					value="<?php echo JText::_('COM_BOOKPRO_CONFIRM')?>" class="button" />
			</div>
			
			<?php 
	$hidden=array('controller'=>'tour','task'=>'step3',
			'id'=> $this->tour->id,
			"Itemid"=>JRequest::getVar('Itemid'));
			echo FormHelper::bookproHiddenField($hidden);
	?>

		<input type="hidden" name="<?php echo $this->token?>" value="1" />
</form>



