
<?php 
defined('_JEXEC') or die('Restricted access');
$app		= JFactory::getApplication();
$tzoffset = $app->getCfg('offset');
JHtml::_('behavior.tooltip');

AImporter::css('bookpro','flight');
AImporter::helper('date','currency','form');

JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
JHtml::_('behavior.modal','a.cmodal');
$doc=JFactory::getDocument();

$doc->addStyleSheet('https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/flick/jquery-ui.css');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.ui.slider.js');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.ui.widget.js');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.ui.mouse.js');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery.datepick.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/flight.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery.ui.slider.css');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery.ui.datepicker.css');
$action='index.php?Itemid='.JRequest::getVar("Itemid");
?>
<form name="frontForm" id="flight_form" method="post" action='<?php echo $action ?>' onsubmit="return submitForm()">
<div class="flight_content">
	<div class="row-fluid">
		<div class="span8">
			<div class="row-fluid">
				<div class="span12">
				
					<?php echo $this->loadTemplate('special'); ?>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<?php echo $this->loadTemplate('flight'); ?>	
				</div>
			
			</div>
			
		</div>
		<div class="span4">
			<?php echo $this->loadTemplate('right'); ?>
		</div>
	</div>
</div>
<?php 

	$hidden=array('controller'=>'flight','task'=>'reserve','customer_id'=>$this->customer->id,
			'package'=>'','return_package'=>'');
	echo FormHelper::bookproHiddenField($hidden);	?>
	 <input type="hidden"
		name="<?php echo $this->token?>" value="1" />
</form>
