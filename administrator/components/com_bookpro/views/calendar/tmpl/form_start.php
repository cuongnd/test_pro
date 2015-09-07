<?php 
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: form_image.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

$modallink= JURI::base().'index.php?option=com_bookpro&view=calendar&tmpl=component&layout=default&start='.$this->start;
$config=AFactory::getConfig();
?>
<script type="text/javascript">
function setDepart(date){
     document.getElementById('start_date').value=date;
}
</script>

<table>
 
  <tr>
    <td><div class="button2-left">
<div class="blank">
<a class="modal btn" href="<?php echo $modallink ?>" rel="{handler: 'iframe', size: {x: 600, y: 550}}"><?php echo JText::_("Edit Departure date")?></a>
</div>
</div></td>
</tr>
<tr>
    <td><?php $datearr=explode(';', $this->start);
						for ($i = 0; $i < count($datearr); $i++) {
							echo JHTML::_('date', $datearr[$i]).',';
						}?></td>
  </tr>
</table>

				
				<input type="hidden" id="start_date" name ="start" value="<?php echo $this->escape($this->start) ?>">
