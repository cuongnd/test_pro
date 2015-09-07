<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
$bar = &JToolBar::getInstance('toolbar');
JHtml::_('jquery.framework');
JHtml::_('behavior.modal');
//JHtml::_('behavior.mootools');

JToolbarHelper::title('Rate calendar');
AImporter::css('calendar');
BookProHelper::setSubmenu(1);
require_once JPATH_COMPONENT_BACK_END.'/classes/calendar.php';

?>
<script type="text/javascript">

var ajaxurl = "<?php echo JUri::base().'index.php?option=com_bookpro&controller=flight&task=calendar&flight_id='.$this->flight->id ?>";
var pn_appointments_calendar = null;
jQuery(function() {
    pn_appointments_calendar = new PN_CALENDAR();
    pn_appointments_calendar.init();
});

</script>
<script type="text/javascript">
function deleteRate(id,month,year){
	
	var ajaxurl = "<?php echo JUri::base().'index.php?option=com_bookpro&controller=flight&task=deleteRateDate&flight_id='.$this->flight->id ?>";
	 var data = {
             action: "pn_get_month_cal",
             month: month,
             year: year,
             id:id
         };
         jQuery.post(ajaxurl, data, function(response) {
        	
             jQuery('#pn_calendar').html(response);
         });
}
</script>
<div class="span10">
<div class="lead">
<?php echo $this->flight->from_name .' - '.$this->flight->to_name ;

?>
</div>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
       
       $calendar = new PN_Calendar();
      
       echo $calendar->draw();
      
      
 ?>

<input type="hidden" name="room_id" value="<?php echo JFactory::getApplication()->input->get('flight_id')  ?> " />
 </form>	

</div>

