<?php 
defined('_JEXEC') or die('Restricted access');
$layout = JRequest::getVar('layout','');

$datefrom=JRequest::getVar('filter_from',null);
$dateto=JRequest::getVar('filter_to','');

if(!$datefrom){
	$datefrom=JFactory::getDate(DateHelper::dateBeginMonth(time()))->toFormat();
}
if(!$dateto){
	$dateto=JFactory::getDate(DateHelper::dateEndMonth(time()))->toFormat();
}
?><script type="text/javascript">
function getFilter(start,end,layout){
	document.getElementById('filter_from').value = start;
	document.getElementById('filter_to').value = end;
	document.getElementById('layout').value = layout;
	document.adminForm.submit();
}	
</script><fieldset>	<legend>		<?php echo JText::_('Filter')?>	</legend>	<form action="index.php" method="get" name="adminForm" id="adminForm">		<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input			type="hidden" name="controller" value="salereport" /> <input			type="hidden" name="layout" id='layout'			value="<?php echo JRequest::getVar('layout','default')?>" /> <input			type="hidden" name="task"			value="<?php echo JRequest::getCmd('task'); ?>" />		<div class="width-70" style="float: left;">			<fieldset>				<legend>					<?php echo JText::_('Date filter')?>				</legend>				<div class="filter-saleport">					<ul>						<li><?php 
		
						$start_day = DateHelper::dateBeginDay(time() + (2*24 * 60 * 60));
							
						$end_day = DateHelper::dateEndDay(time() + (2*24 * 60 * 60));

						?> <a href="#"							onclick="getFilter('<?php echo date('Y-m-d H:i:s',$start_day); ?>','<?php echo date('Y-m-d H:i:s',$end_day) ?>','<?php echo $layout; ?>');"><?php echo JText::_('Tomorrow') ?>						</a>						</li>						<li><?php 
						$start_day = DateHelper::dateBeginDay(time());
						$end_day = DateHelper::dateEndDay(time());

						?> <a href="#"							onclick="getFilter('<?php echo date('Y-m-d H:i:s',$start_day); ?>','<?php echo date('Y-m-d H:i:s',$end_day) ?>','<?php echo $layout; ?>');"><?php echo JText::_('Today') ?>						</a>						</li>						<li><?php 
						$start_yesterday = DateHelper::dateBeginDay(strtotime('yesterday'));
						$end_yesterday = DateHelper::dateEndDay(strtotime('yesterday'));
						?> <a href="#"							onclick="getFilter('<?php echo date('Y-m-d H:i:s',$start_yesterday) ?>','<?php echo date('Y-m-d H:i:s',$end_yesterday) ?>','<?php echo $layout; ?>')"><?php echo JText::_('Yesterday') ?>						</a>						</li>						<li><?php 
						$start_week = DateHelper::dateBeginWeek(time());
						$end_week = DateHelper::dateEndWeek(time());

						?> <a href="#"							onclick="getFilter('<?php echo date('Y-m-d H:i:s',$start_week); ?>','<?php echo date('Y-m-d H:i:s',$end_week); ?>','<?php echo $layout; ?>');"><?php echo JText::_('This Week') ?>						</a>						</li>						<li><?php 
		
					$start_month = DateHelper::dateBeginMonth(time());
		
		
					$end_month = DateHelper::dateEndMonth(time());
		
					?> <a href="#"							onclick="getFilter('<?php echo date('Y-m-d H:i:s',$start_month); ?>','<?php echo date('Y-m-d H:i:s',$end_month); ?>','<?php echo $layout; ?>')"><?php echo JText::_('This Month') ?>						</a>						</li>						<li><a href="#"							onclick="document.getElementById('filter-date').style.display = 'block'"><?php echo JText::_('Date range') ?>						</a>						</li>					</ul>					<div class="clr"></div>					<table id="filter-date" style="display: none;">						<tr>							<td><label for="order_number" style="float: left;"><?php echo JText::_('From date'); ?>:							</label> <?php echo JHtml::calendar($datefrom, 'filter_from', 'filter_from') ?>							</td>							<td><label for="pay_status" style="float: left;"><?php echo JText::_('To date'); ?>:							</label> <?php echo JHtml::calendar($dateto, 'filter_to', 'filter_to') ?>							</td>							<td>								<button									onclick="getFilter(document.getElementById('from').value,document.getElementById('to').value,'<?php echo $layout ?>')">									<?php echo JText::_('SUBMIT'); ?>								</button>							</td>						</tr>					</table>				</div>			</fieldset>		</div>		<div class="width-30" style="float: right;">			<?php if ($layout == 'tourreport'){ 
					?>			<fieldset>				<legend>					<?php echo JText::_('Tour filter')?>				</legend>				<?php 
					echo $this->loadTemplate('tourlist');
					?>			</fieldset>			<?php } ?>		</div>		<input type="hidden" name="reset" value="0" />		<?php echo JHTML::_('form.token'); ?>	</form></fieldset>