<?php 
/** 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage serverstats
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );  ?>
 
<form action="index.php" method="post" id="adminForm" name="adminForm"> 
	<div id="filters">
		<?php echo JText::_('FILTER_BY_DATE');?>
			 				
		<?php echo JText::_('FILTER_BY_DATE_FROM');?>
		<input maxlength="10" size="10" type="text" id="fromperiod" name="fromperiod"  value="<?php echo $this->dates['start'];?>"/> 
		<img src="templates/system/images/calendar.png" alt="Calendar" class="calendar" id="jform_created_img_from">
		
		<?php echo JText::_('FILTER_BY_DATE_TO');?>
		<input maxlength="10" size="10" type="text" id="toperiod" name="toperiod" value="<?php echo $this->dates['to'];?>" /> 
		<img src="templates/system/images/calendar.png" alt="Calendar" class="calendar" id="jform_created_img_to">
		
		<button onclick="document.adminForm.task.value='serverstats.display';document.adminForm.submit();"><?php echo JText::_( 'Go' ); ?></button>  
		
		<script type="text/javascript">
			window.addEvent('domready', function() {
				Calendar.setup({
					// Id of the input field
					inputField: "fromperiod",
					// Format of the input field
					ifFormat: "%Y-%m-%d",
					// Trigger for the calendar (button ID)
					button: "jform_created_img_from",
					// Alignment (defaults to "Bl")
					align: "Tl",
					singleClick: true,
					firstDay: 0
				});
				Calendar.setup({
					// Id of the input field
					inputField: "toperiod",
					// Format of the input field
					ifFormat: "%Y-%m-%d",
					// Trigger for the calendar (button ID)
					button: "jform_created_img_to",
					// Alignment (defaults to "Bl")
					align: "Tl",
					singleClick: true,
					firstDay: 0
				});
			});
		</script> 
	 </div>
	 
	<div class="statsdatarow left" id="text_details">
		<?php echo $this->loadTemplate('details');?>
	</div>
	
	<div class="statsdatarow right" id="geolocation">
		<?php echo $this->loadTemplate('geolocation');?>
	</div>
	
	<div class="statsdatarow left" id="os">
		<?php echo $this->loadTemplate('os');?>
	</div>
	
	<div class="statsdatarow right" id="browser">
		<?php echo $this->loadTemplate('browser');?>
	</div>
	
	<div class="statsdatarow right" id="landing">
		<?php echo $this->loadTemplate('landing');?>
	</div>
	
	<div class="statsdatarow left" id="leaved">
		<?php echo $this->loadTemplate('leaved');?>
	</div>
		
	<div class="statsdatarow clear" id="pages">
		<?php echo $this->loadTemplate('pages');?>
	</div>
	
	<div class="statsdatarow" id="visitors">
		<?php echo $this->loadTemplate('visitors');?>
	</div>
  
	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="task" value="serverstats.display" />   
</form>


 