<?php 
/** 
 * @package JREALTIMEANALYTICS::REALSTATS::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage realstats
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );  ?>
  
<div id="placeholder_textstats"></div>
<div id="placeholder_chartpie"></div>
<div id="placeholder_chartbar"></div> 
<div id="placeholder_text"></div>
<div id="placeholder_perpage"></div>
  
<form action="index.php" method="post" id="adminForm" name="adminForm"> 
	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="realstats.display" />   
</form>