<?php 
defined('_JEXEC') or die('Restricted access');
AImporter::helper('report','date','busreport');
AImporter::model('agents');$model = new BookProModelAgents();$model->init($this->lists);$this->items = $model->getAgentReportDriver();
$this->pagination = $model->getPaginDriver();
JToolBarHelper::custom('exportdriver','export','','Export',false);
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(21);$colspan = $this->selectable ? 10 : 11;

$itemsCount=count($this->items);$pagination = &$this->pagination;$start = $this->lists['start'];
$end = $this->lists['end'];
$dstart = new DateTime(date($start));
$dend = new DateTime(date($end));

$days = $dend->diff($dstart)->days;
//$start= $today->format('d-m-Y',true);
$config =& JFactory::getConfig();
$tzoffset = $config->getValue('config.offset');
$today = JFactory::getDate('now',$tzoffset);


$date_arr = array();
for ($i =0;$i <= $days;$i++){
	$stdate = JFactory::getDate($start,$tzoffset);


	$stdate->add(new DateInterval('P'.$i.'D'));

	$date_arr[] = $stdate->format('d-m-Y',true);

}



AImporter::jquery();
AImporter::jqueryui();
AImporter::js('jquery.ui.datepicker-vi');

AImporter::css('customui');
$start = JFactory::getDate($start)->format('d-m-Y');
$end = JFactory::getDate($end)->format('d-m-Y');
?><script>	jQuery(document).ready(function($) {     $( "input#start" ).datepicker();          $( "#start" ).datepicker( "option", $.datepicker.regional['vi']);     $( "#start" ).datepicker( "option", "dateFormat", "dd-mm-yy" );     $("input#start").datepicker("setDate","<?php echo $start ?>" );              $( "input#end" ).datepicker();     $( "#end" ).datepicker( "option", $.datepicker.regional['vi']);     $( "#end" ).datepicker( "option", "dateFormat", "dd-mm-yy" );     $("input#end").datepicker("setDate","<?php echo $end ?>" );      });	  </script><div>	<div>		<?php echo $this->loadTemplate('menu')?>	</div>	<div><form action="index.php" name="adminForm" id="adminForm" method="POST">		<h2 class="titlePage">			<?php echo JText::_('Booking List'); ?>		</h2>		<div>		<div class="report-filter-left">			<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_START_DATE') ?></label>			<input type="text" class="inputbox input width249" name="start" id="start"	value="" size="13" maxlength="10" />					</div>		<div class="report-filter-left">			<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_END_DATE') ?></label>			<input type="text" class="inputbox input width249" name="end" id="end"	value="" size="13" maxlength="10" />					</div>		<div class="report-filter-left">			<label><?php echo JText::_('Agent') ?></label>			<?php echo $this->getAgenSelectBox($this->lists['agent_id']);?>		</div>		<div class="report-filter-left">			<input type="button" onclick="document.adminForm.submit();" class="" name="button" value="<?php echo JText::_('COM_BOOKPRO_BUSREPORT_BUTTON'); ?>" />		</div>		<div class="clear"></div>			</div>		<table class="adminlist" cellspacing="1" width="100%">					<thead>								<th class="title" width="30%">			        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BUSREPORT_TITLE'), 'title', $orderDir, $order); ?>				</th>								<?php for ($i = 0;$i < count($date_arr);$i++){ ?>				<th>					<?php echo JFactory::getDate($date_arr[$i])->format('d-m',true); ?>				</th>				<?php } ?>								</thead>			<tfoot>    			<tr>    				<td colspan="<?php echo $colspan; ?>">    					<div class="pagin">    						<?php echo $pagination->getListFooter(); ?>    					</div>    				        				</td>    			</tr>			</tfoot>			<tbody>				<?php if (! is_array($this->items) || ! $itemsCount) { ?>					<tr><td colspan="<?php echo $colspan; ?>"><?php echo JText::_('No items found.'); ?></td></tr>				<?php } else { ?>				    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>				    				    	<?php $subject = &$this->items[$i];							$link = JRoute::_(ARoute::edit(CONTROLLER_BUSTRIP, $subject->id));				    	?>				    	     				    	<tr class="row<?php echo ($i % 2); ?>">				    						    		<td><a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a></td>				    						    						    		<?php for ($j = 0;$j < count($date_arr);$j++){ ?>							<td>								<?php $report = BusReportHelper::getAgentDriverReport($subject->id,$date_arr[$j]); ?>								<?php 									if ($report->subprice == NULL) {										$report->subprice = 0;									}																	?>								<?php echo CurrencyHelper::formatprice($report->subprice*$report->qty) ; ?>				    										</td>							<?php } ?>				    						    						    	</tr>				    <?php } ?>				<?php } ?>			</tbody>		</table>		<input type="hidden" name="option" value="com_bookpro" />		<input type="hidden" name="controller" value="busreport" />		<input type="hidden" name="layout" value="driversale" /></form>	</div></div>