<?php 
	
	defined( '_JEXEC' ) or die( 'Restricted access' );
	jimport( 'joomla.html.html' );
	AImporter::model('customer');
	AImporter::css('bookpro');
	AImporter::helper('currency','date','hotel');

	$model=new BookProModelCustomer();
	$model->setId($this->order->user_id);
	$this->customer=$model->getObject();
	$hotel = HotelHelper::getObjectHotelByOrder($this->order->id);
	$hcmodel = new BookProModelCustomer();
	$hcmodel->setId($hotel->userid);
	$hotelCustomer = $hcmodel->getObject();
	$infos = HotelHelper::getRooms($this->order->id);
	//ob_start();
	
	?>

<?php echo $this->loadTemplate('header')?>
<div class="row-fluid">
	<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_ADDRESS',$this->order->customer->address); ?>
</div>
<div class="row-fluid">
	<div class="pull-left">
		<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_REGISTER_NUMBER',$hotel->code); ?>
	</div>
	<div class="pull-right">
	<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_BOOKING_ID',$this->order->id); ?>
	</div>
</div>
<div class="row-fluid">
	<div class="span2 pull-left">
		<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_PHONE',$hotelCustomer->phone); ?>
	</div>
	<div class="span2 pull-left">
			
		<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_FAX',$hotelCustomer->fax); ?>
	</div>
	<div class="span2 pull-right text-right">
		<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_DATE',JFactory::getDate($this->order->created)->format('F d, Y',true)); ?>
	</div>
</div>
<div class="row-fluid">
	<label><b><?php echo JText::_('TO:') ?></b></label>
	<label><?php echo $this->order->firstname. ' '.$this->order->lastname; ?></label>
	<label><?php echo $this->customer->address; ?></label>
	<label><?php echo $this->customer->email; ?></label>
	<label><?php echo $this->customer->phone; ?></label>
	<label><?php echo JText::sprintf('COM_BOOKPRO_PANID',$hotel->pan_no); ?></label>
</div>

<div style="padding:10px 0">
	<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_DEAR_CUSTOMER_NAME',$this->customer->fullname); ?>
</div>

<div style="padding:10px 0">
	<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_BOOKING_NOTE',$this->order->id); ?>
</div>
<div style="padding:10px 0">
	<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_BOOKING_RESERVED',JFactory::getDate($this->order->created)->format('d/m/Y',true),$hotel->title); ?>
</div>
<div>
	<?php 
	$layout = new JLayoutFile('rooms', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
	$html = $layout->render($infos);
	echo $html;
	?>
</div>
<div class="row-fluid">
	<div class="pull-left">
		<b><?php echo JText::_('Amount Paid:') ?></b>
	</div>
	<div class="pull-left">
		<label>
		<?php echo CurrencyHelper::formatprice($this->order->total); ?>
		</label>
	</div>
</div>
<div class="row-fluid">
	<div class="pull-left">
		<b><?php echo JText::_('Method of Payment:') ?></b>
	</div>
	<div class="pull-left">
		<label>
		<?php echo $this->order->pay_method; ?>
		</label>
	</div>
</div>

<div style="padding:10px 0">
	<?php echo JText::sprintf('COM_BOOKPRO_INVOICE_CONTENT',CurrencyHelper::formatprice($this->order->total)) ?>
</div>

<?php //echo $this->loadTemplate(strtolower($this->order->type))?>
<?php echo $this->loadTemplate('footer')?>
<?php //$output = ob_get_contents();ob_end_clean();?>