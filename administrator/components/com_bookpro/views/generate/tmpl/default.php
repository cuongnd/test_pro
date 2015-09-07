<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JToolBarHelper::title(JText::_('Genarate route tool'), 'stack');
JToolBarHelper::apply('save');
JToolBarHelper::cancel();

?>
<script type="text/javascript">
window.addEvent('domready', function() {
	document.formvalidator.setHandler('select', function (value) { return (value != 0); } );

}); 
</script>

<script type="text/javascript">

function addDesination(){
	
	var index = parseInt(document.getElementById("index").value);
	var table = document.getElementById("bustrip");
	var row = table.insertRow(index);
	var cell1 = row.insertCell(0);
	var cell2 = row.insertCell(1);
	var number = parseInt(index) + 1;
	cell1.innerHTML = '<label class="control-label"><?php echo JText::_('Destination'); ?>'+number+':</label>';
	cell2.innerHTML = table.rows[0].cells[1].innerHTML;
	document.getElementById("index").value = index + 1;	
	
}

</script>
<form action="index.php" method="post" name="adminForm" id="adminForm"
	class="form-validate">

	<div class="form-horizontal">
		
		<table width="100%">
			
			<tr>
				<td><label class="control-label" for="pickup"><?php echo JText::_('Code'); ?>:
				</label>
				</td>
				<td><input type="text" name="code" value="" />
				</td>
			</tr>

			<tr>
				<td><label class="control-label" for="pickup"><?php echo JText::_('Agent'); ?>:
				</label>
				</td>
				<td><?php echo $this->getAgentSelectBox(); ?>
				</td>
			</tr>
			<tr>
				<td width="10%"><label class="control-label" for="bus_id"><?php echo JText::_('Select Bus'); ?>:
				</label>
				</td>
				<td><?php echo $this->bus; ?>
				</td>
			</tr>
		</table>
		<table id="bustrip" width="100%">

			<tr>
				<td width="10%"><label class="control-label" for="pickup"><?php echo JText::_('Destination 1'); ?>:
				</label>
				</td>
				<td><?php echo $this->dfrom; ?>
				</td>
			</tr>
			<tr>
				<td width="10%"><label class="control-label" for="pickup"><?php echo JText::_('Destination 2'); ?>:
				</label>
				</td>
				<td><?php echo $this->dfrom; ?>
				</td>
			</tr>
		</table>
		<div class="control-group">
			<div class="controls">
				<button type="button" onclick="addDesination()"
					class="btn btn-primary">
					<?php echo JText::_('COM_BOOKPRO_GENERATE_ADD_DESTINATION') ?>
				</button>
			</div>
		</div>

	</div>
	<input type="hidden" name="index" id="index" value="2" /> <input
		type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_GERNERATE; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

	<?php echo JHtml::_('form.token'); ?>
</form>

