<?php 

defined('_JEXEC') or die('Restricted access');
 $option = JRequest::getVar('option','','request','string');
 
 ?>

<script language="javascript" type="text/javascript">

Joomla.submitbutton=function submitform(pressbutton)
{
var form = document.adminForm;
   if (pressbutton)
    {form.task.value=pressbutton;
	}
   if ((pressbutton=='add')||(pressbutton=='edit')||(pressbutton=='publish')||(pressbutton=='unpublish')
	 ||(pressbutton=='remove')|| (pressbutton=='copy') )
	 {		 
	  form.view.value="state_detail";
	 }
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
}
function submitform(pressbutton)
{
var form = document.adminForm;

if (pressbutton)
    {
		form.task.value=pressbutton;
		
	}
	
	if ((pressbutton=='publish')||(pressbutton=='unpublish'))
	{		 
	  form.view.value="state_detail";
	  
	}
	try {
		form.onsubmit();
		}
	catch(e){}
	
	form.submit();
	
}

</script>
<div class="span10">
<form action="<?php echo 'index.php?option='.$option; ?>" method="post" name="adminForm" >
<div id="editcell">
	<table class="adminlist" border="0">
	<label style="font-weight:bold"></label>	
	<thead>
	
		<tr>
			<th width="5%">
				<?php echo JText::_( 'NUM' ); ?>
			</th>
			<th width="10%">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->test ); ?>);" />
			</th>
			<th class="title">
			<?php echo JText::_('STATE_NAME'); ?>
			</th>
			<th class="title">
				<?php echo JText::_('COUNTRY_NAME'); ?>
			</th>
			<th width="10%" class="title">
			<?php echo JText::_('PUBLISHED'); ?>
			</th>
		</tr>
	</thead>
	<?php 
	$k = 0;	
	for ($i=0;$i<count($this->test);$i++){
		$row = & $this->test[$i];
		$link 	= JRoute::_( 'index.php?option='.$option.'&view=state_detail&task=edit&cid[]='. $row->state_id );
		$published 	= JHTML::_('grid.published', $row, $i );		
		?>		
		<tr class="<?php echo "row$k"; ?>">
			<td class="order">
			<?php echo $row->state_id; ?> 
			</td>
			<td class="order">
			<?php echo JHTML::_('grid.id', $i, $row->state_id); ?>
			</td>
			<td width="30%" class="order">
			<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'EDIT_STATE_DETAIL' ); ?>"><?php echo $row->state_name; ?></a>
			</td>
			<td width="30%" class="order">
			<?php echo $row->country_name; ?>
			</td>
			<td width="30%" class="order">
			<?php echo $published; ?>
			</td>
			</tr>
		<?php
		$k = 1 - $k;
	}
	?>	
		<tfoot>
		<td colspan="6" rowspan="2">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	 
	</table>
</div>

<input type="hidden" name="view" value="state" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
</div>
 