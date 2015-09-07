<?php
	defined( '_JEXEC' ) or die( 'Restricted access' );
	jimport( 'joomla.html.html' );
	AImporter::model('customer');
	AImporter::css('bookpro','customer');
	AImporter::helper('currency','date');

	$model=new BookProModelCustomer();
	$model->setId($this->order->user_id);
	$this->customer=$model->getObject();
	?>



<?php echo $this->loadTemplate('header')?>
<?php echo $this->loadTemplate('hotel')?>
<?php //echo $this->loadTemplate(strtolower('order'))?>
<?php echo $this->loadTemplate('footer')?>
