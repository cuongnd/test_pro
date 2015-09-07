<?php 
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.modal');
AImporter::helper('contants','date','currency','busreport');AImporter::model('agents');$model = new BookProModelAgents();$model->init($this->lists);$this->items = $model->getAgentReportDriver();$this->pagination = $model->getPaginDriver();
$bar = &JToolBar::getInstance('toolbar');
BookProHelper::setSubmenu(21);
$colspan = $this->selectable ? 9 : 10;echo $this->selectable;$editCustomer = JText::_('Edit Bus');$titleEditAcount = JText::_('Edit Bus');$orderDir = $this->lists['order_Dir'];$order = $this->lists['order'];$itemsCount = count($this->items);$pagination = &$this->pagination;$start = $this->lists['start'];$end = $this->lists['end'];
$dstart = new DateTime(date($start));$dend = new DateTime(date($end));$days = $dend->diff($dstart)->days;//$start= $today->format('d-m-Y',true);$config =& JFactory::getConfig();$tzoffset = $config->getValue('config.offset');$today = JFactory::getDate('now',$tzoffset);$date_arr = array();for ($i =0;$i <= $days;$i++){	$stdate = JFactory::getDate($start,$tzoffset);			$stdate->add(new DateInterval('P'.$i.'D'));		$date_arr[] = $stdate->format('d-m-Y',true);	}AImporter::jquery();AImporter::jqueryui();AImporter::js('jquery.ui.datepicker-vi');AImporter::css('customui');$start = JFactory::getDate($start)->format('d-m-Y');$end = JFactory::getDate($end)->format('d-m-Y');
?>
<script type="text/javascript">	jQuery(document).ready(function($) {     $( "input#start" ).datepicker();          $( "#start" ).datepicker( "option", $.datepicker.regional['vi']);     $( "#start" ).datepicker( "option", "dateFormat", "dd-mm-yy" );     $("input#start").datepicker("setDate","<?php echo $start ?>" );              $( "input#end" ).datepicker();     $( "#end" ).datepicker( "option", $.datepicker.regional['vi']);     $( "#end" ).datepicker( "option", "dateFormat", "dd-mm-yy" );     $("input#end").datepicker("setDate","<?php echo $end ?>" );      });	  </script>
<div>
	<div>
		<?php echo $this->loadTemplate('menu'); ?>
	</div>
	<div>
		<form action="index.php" name="adminForm" id="adminForm" method="POST">

			<div class="width-100 fltrt">
				<fieldset class="adminform">
					<legend>
						<?php echo JText::_('New Order') ?>
					</legend>
					<h2 class="titlePage">
						<?php echo JText::_('Order List'); ?>
					</h2>
					<div class="clr"></div>
					<div>
						<div class="report-filter-left">
							<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_START_DATE') ?>
							</label> <input type="text" class="inputbox input width249"
								name="start" id="start" value="" size="13" maxlength="10" />
						</div>
						<div class="report-filter-left">
							<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_END_DATE') ?> </label>
							<input type="text" class="inputbox input width249" name="end"
								id="end" value="" size="13" maxlength="10" />
						</div>
						<div class="report-filter-left">
							<label><?php echo JText::_('Agent') ?> </label>
							<?php				echo $this->getAgenSelectBox($this->lists['agent_id']);?>
						</div>
						<div class="report-filter-left">
							<input type="button" onclick="document.adminForm.submit();"
								class="" name="button"
								value="&lt;?php echo JText::_('COM_BOOKPRO_BUSREPORT_BUTTON'); ?&gt;" />
						</div>
						<div class="clear"></div>
					</div>

					<table class="adminlist" style="width: 100%">
						<thead>
							<tr>
								<th class="title" width="30%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BUSREPORT_TITLE'), 'title', $orderDir, $order); ?>
								</th>
								<?php				 for ($i = 0;$i < count($date_arr);$i++){ 		?>
								<th><?php echo JFactory::getDate($date_arr[$i])->format('d-m',true); ?>
								</th>
								<?php } ?>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<td colspan="13"><?php echo $pagination->getListFooter(); ?></td>
							</tr>
						</tfoot>
						<tbody>
							<?php		if (! is_array($this->items) || ! $itemsCount) { ?>
							<tr>
								<td colspan="&lt;?php echo $colspan; ?&gt;"><?php echo JText::_('No items found.'); ?>
								</td>
							</tr>
							<?php } else { ?>
							<?php for ($i = 0; $i < $itemsCount; $i++) { ?>
							<?php $subject = &$this->items[$i];							$link = JRoute::_(ARoute::edit(CONTROLLER_BUSTRIP, $subject->id));				    	?>
							<tr class="row&lt;?php echo ($i % 2); ?&gt;">
								<td><a href="&lt;?php echo $link; ?&gt;"><?php echo $subject->title; ?>
								</a>
									<div>
										<?php 				    								    					echo JText::sprintf('COM_BOOKPRO_BUSREPORT_DEPART',BusReportHelper::getAgentBusStop($subject->id));				    				?>
									</div></td>
								<?php for ($j = 0;$j < count($date_arr);$j++){ ?>
								<td align="center"><?php $report = BusReportHelper::getAgentDriver($subject->id,$date_arr[$j]); 																?>
									<?php 									if ($report->seat == NULL) {										//$report->seat = 0;									}																	?>
									<div class="report-ticket">
										<?php 									if ($report->ticket) {									?>
										<a
											href="index.php?option=com_bookpro&amp;view=ticketreport&amp;bustrip_id=&lt;?php echo $subject-&gt;id ?&gt;&amp;start=&lt;?php echo JFactory::getDate($date_arr[$j])-&gt;format('d-m-Y',true); ?&gt;&amp;Itemid=&lt;?php echo JRequest::getVar('Itemid',0); ?&gt;">
											<?php 										echo JText::sprintf('COM_BOOKPRO_BUSREPORT_TICKET_SEAT',$report->ticket,$report->seat);									?>
										</a>
										<?php 	 										}else{										echo 0;									
										}									?>
									</div></td>
								<?php } ?>
							</tr>
							<?php } ?>
							<?php } ?>
						</tbody>
					</table>
				</fieldset>
			</div>
			<input type="hidden" name="option" value="com_bookpro" /> <input
				type="hidden" name="view" value="busreport" />
		</form>

	</div>
</div>
