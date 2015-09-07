<?php 
/** 
 * @package JREALTIMEANALYTICS::CPANEL::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage cpanel
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<div id="cpanel">
	<?php echo $this->icons; ?>
</div> 
<form id="adminForm" name="adminForm" action="index.php">
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>"/>
	<input type="hidden" name="task" value=""/>
</form>