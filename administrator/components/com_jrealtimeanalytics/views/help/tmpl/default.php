<?php 
/** 
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage help
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<div class="maincontainer">
	<div class="header padding"><?php echo JTEXT::_('HOWTOUSE');?></div>
	<p><?php echo JText::_('PREAMBLE');?></p>
	<img class="pretitle" src="components/com_jrealtimeanalytics/images/icon-48-realstats.png" alt="Realtime stats">
	<h2><?php echo JText::_('REALTIME STATS');?></h2>
	<p><?php echo JText::_('REALTIME_STATS_HELP1');?></p>
	<p><?php echo JText::_('REALTIME_STATS_HELP2');?></p> 
	<p><?php echo JText::_('REALTIME_STATS_HELP3');?></p>
	 
	<div class="clrhelp"></div>
	
	<img class="pretitle" src="components/com_jrealtimeanalytics/images/icon-48-stats.png" alt="Analytics stats">
	<h2><?php echo JText::_('ANALYTICS STATS_HELP');?></h2>
	<p><?php echo JText::_('ANALYTICS_STATS_HELP1');?></p>
	<p><?php echo JText::_('ANALYTICS_STATS_HELP2');?></p> 
	<p><?php echo JText::_('ANALYTICS_STATS_HELP3');?></p>
	<p><?php echo JText::_('ANALYTICS_STATS_HELP4');?></p>
	<p><?php echo JText::_('ANALYTICS_STATS_HELP5');?></p>
 
 	<div class="clrhelp"></div>
 	
	<form id="adminForm" name="adminForm" action="index.php">
		<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>"/>
		<input type="hidden" name="task" value=""/>
	</form>
</div>