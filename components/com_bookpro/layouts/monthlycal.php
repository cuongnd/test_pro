<?php


defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.tooltip');

$config = AFactory::getConfig();

//$userCanReserve = $this->customer->id || $config->unRegisteregCanReserve;
$userCanReserve = $this->userCanReserve;
$this->setting = new BookProCalendarSetting();

?>

<table id="top" class="monthlyCalendar">
	<!-- Days names header -->
	<?php
		ob_start(); ?>
	
	<thead>
		<tr>
			<th><?php echo JText::_('WEEK'); ?></th>
			<?php if ($config->firstDaySunday) { ?>
				<th width="14%"><?php echo JText::_('Sun'); ?></th>
			<?php } ?>
			<th width="14%"><?php echo JText::_('MON'); ?></th>
			<th width="14%"><?php echo JText::_('Tue'); ?></th>
			<th width="14%"><?php echo JText::_('Wed'); ?></th>
			<th width="14%"><?php echo JText::_('Thu'); ?></th>
			<th width="14%"><?php echo JText::_('Fri'); ?></th>
			<th width="14%"><?php echo JText::_('Sat'); ?></th>
			<?php if (! $config->firstDaySunday) { ?>
				<th width="14%"><?php echo JText::_('Sun'); ?></th>
			<?php } ?>
		</tr>
	</thead>
	<?php 
		
		$head = ob_get_clean(); // save for next using
		echo $head;
	?>
	<!-- Body with months days -->
	<tbody>
	<tr>
			<td class="week">
				<span class="week"><?php echo $this->setting->week ++; ?></span>
				
			</td>
		<?php 
			$pcount = count($this->days->calendar);
			$break = 0;
			$month = 1;
			for ($i = 0; $i < $pcount; $i++) {
				$day = $this->days->calendar[$i];
				/* @var $day BookingDay */
				
				$firstBox = reset($day->boxes);
				$closedClassName = is_object($firstBox) && $firstBox->closed ? ' closed hasTip' : '';
				$title = is_object($firstBox) && $firstBox->closed ? $this->escape($firstBox->closingDayTitle).'::'.$this->escape($firstBox->closignDayText) : '';
		?>
				<td class="day<?php echo $closedClassName; ?><?php if ($day->engaged) { ?> reserved<?php if($config->colorCalendarFieldReserved){ echo '"style="background-color:'.$config->colorCalendarFieldReserved; }}else if($config->colorCalendarFieldFree){ echo '" style="background-color:'.$config->colorCalendarFieldFree; } ?>" title="<?php echo $title; ?>">
						<?php if ($config->enableResponsive) { ?>
							<span class="date"><?php echo AHtmlFrontEnd::date($day->date, ADATE_FORMAT_NICE_SHORT_RESPONSIVE, 0); ?></span>
						<?php } elseif ($this->subject->night_booking && $config->nightsStyle) { ?>
							<span class="date" ><?php echo JText::sprintf('NIGHT_BOOKING_DATE', AHtmlFrontEnd::date($day->date, ADATE_FORMAT_NICE_SHORT, 0), AHtmlFrontEnd::date($day->nextDate, ADATE_FORMAT_NICE_SHORT, 0)); ?></span>
					<?php } else { ?>
						<span class="date" ><?php echo AHtmlFrontEnd::date($day->date, ADATE_FORMAT_NICE_SHORT, 0); ?></span>
					<?php } ?>
		<?php
					if (! ($break && !$isLastWeek)) {
						foreach ($day->boxes as $box) {
							/* @var $box BookingTimeBox */
							if (!$box->closed) {
								foreach ($box->services as $service) {
									/* @var $service BookingService */
										if ($service->allowFixLimit || ($service->rtype == RESERVATION_TYPE_DAILY && (($config->bookCurrentDay && $day->Uts >= strtotime($this->setting->currentDate)) || ($this->isAdmin || $day->Uts > $this->setting->currentDayUTS)) && $service->canReserve && in_array($service->rtypeId, $this->lists['rids']))) {
										if ($userCanReserve)
											$commands = ADocument::setBoxParams($service, $service->i, $pcount, $i);
		?>		
											<span class="price price<?php echo $service->notBeginsFixLimit ? 'Transparent' : $service->priceIndex; ?>" id="<?php echo $service->idShort; ?>">
		<?php 
										if ($this->subject->display_capacity && $this->subject->total_capacity>1)
											echo ($this->subject->total_capacity - $service->alreadyReserved);
		?>
										</span>
        <?php 
									}
								}
							}
						}							
											
						
					}
		?> 
				</td>
		<?php		
				if (date('m', strtotime($day->date)) != date('m', strtotime($day->nextDate)) && $i > 6) {
					$lastMonthDay = $i % 7;
					$break++; // last day in month
				}
		 
				if ($i % 7 == 6 && $pcount > $i + 1) { // end of week and next week is coming
					$isLastWeek = $pcount - $i == 8;
		?>
					</tr>
		<?php
					if ($break == 1) { // last of month hapened
						if ($lastMonthDay != 6) { // not last week day, week continues in next month
							$i -= 7; // repeat last week with start of next month
							$this->setting->week --; // repeat last week number
							$break = -1; // set as -1 to ignore next step (repeating of end of this month in next month)
						} else
							$break = 0;
						$nextMonth = JFactory::getDate($this->setting->selected . '-01' . ' + ' . ($month++) . ' month');
						/* @var $nextMonth JDate */
		?>
						</table>
						</div>
						<div<?php if ($monthNumber++ < $this->setting->monthNumber) { ?> class="monthlyCalendarHidden"<?php } ?>>
						<h2 class="subjectSubtitle calendarTitle"><?php echo ($nextMonth->format('F') . ' ' . $nextMonth->format('Y')); ?></h2>
						<table class="monthlyCalendar">
		<?php
							echo $head;				
					}
		?>
					<tr>
							<td class="week">
								<span class="week"><?php echo $this->setting->week ++; ?></span>
								<?php if ($config->enableResponsive) { ?>
									<span class="month"><?php //echo AHtmlFrontEnd::date($day->date, ADATE_FORMAT_NICE_SHORT2, 0);; ?></span>
								<?php } ?>
							</td>
						
		<?php 
				}
			} 
		?>
	</tr>
	</tbody>
</table>
</div>
<!-- Calendar pagination -->
<div class="calendarPagination">
	<?php if (! $this->setting->onCurrentMonth || $this->isAdmin) { // admin can browse to the past, customer can browse to the future only ?>
		<span class="previousPage"> 
			<a href="javascript:Calendars.monthNavigation(<?php echo $this->setting->previousMonth; ?>,<?php echo $this->setting->previousYear; ?>)"><?php echo JText::_('PREVIOUS_MONTH'); ?></a>
		</span>
	<?php } ?>
	<span class="currentPage"> 
		<a href="javascript:Calendars.monthNavigation(<?php echo $this->setting->currentMonth; ?>,<?php echo $this->setting->currentYear; ?>)"><?php echo JText::_('CURRENT_MONTH'); ?></a>
	</span> 
	<?php if (! $this->setting->lastAllowPage) { ?>
		<span class="nextPage"> 
			<a href="javascript:Calendars.monthNavigation(<?php echo $this->setting->nextMonth; ?>,<?php echo $this->setting->nextYear; ?>)"><?php echo JText::_('NEXT_MONTH'); ?></a> 
		</span>
	<?php } ?>
</div>
<?php
	if ($config->buttonPosition == 1) echo $this->loadTemplate('bookitform');
?>
<?php if (JRequest::getInt('ajax')) { ?>
	<!-- Calendar HTML End -->
<?php } ?>
</div>
<?php if (JRequest::getInt('ajax')) { ?>
	<!--  Calendar JS Begin -->
		<?php echo implode(PHP_EOL, $commands); ?>
	<!--  Calendar JS End -->
<?php die(); 
} ?>