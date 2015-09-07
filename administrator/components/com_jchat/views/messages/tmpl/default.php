<?php 
/** 
 * @package JCHAT::AGENTS::administrator::components::com_jchat
 * @subpackage views
 * @subpackage agents
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
JHTML::_('behavior.tooltip'); ?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">  
		<div class="filter-search fltlft">
			<label class="label filter-search-lbl" for="filter_search"><?php echo JText::_( 'Filter' ); ?>:</label>
			<div class="input-append">
				<input type="text" name="search" id="search" value="<?php echo $this->searchword;?>" class="text_area" onchange="document.adminForm.submit();" />
			</div>
			<button class="label label-info" onclick="document.adminForm.task.value='messages.display';this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button class="label label-info" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</div>
		<div class="filter-search fltlft last"> 
			<label class="label filter-search-lbl" for="filter_search"><?php echo JText::_( 'FILTER_BY_DATE' ); ?></label>
			<label class="label filter-hide-lbl" for="filter_begin"><?php echo JText::_('FILTER_BY_DATE_FROM'); ?></label>
			<?php echo JHtml::_('calendar', $this->dates['start'], 'fromperiod', 'fromperiod', '%Y-%m-%d' , array('size'=>10));?>
					      	  	
			<label class="label filter-hide-lbl" for="filter_end"><?php echo JText::_('FILTER_BY_DATE_TO'); ?></label>
			<?php echo JHtml::_('calendar', $this->dates['to'], 'toperiod', 'toperiod', '%Y-%m-%d' , array('size'=>10));?>
			<button class="label label-info" onclick="document.adminForm.task.value='messages.display';this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
			<button class="label label-info" onclick="document.getElementById('fromperiod').value='';document.getElementById('toperiod').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
		</div>
		<div style="float: right">
			<?php echo $this->lists['type'];?> 
			<?php echo $this->lists['status'];?> 
		</div>
	</fieldset>
	<div class="clr"> </div>

	<table class="adminlist table table-striped" cellpadding="1">
		<thead>
			<tr>
				<th width="1%" class="title">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th width="1%" class="title">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
				</th>
				<th width="8%"class="title">
					<?php echo JHTML::_('grid.sort', 'Sender_name', 'a.actualfrom', @$this->orders['order_Dir'], @$this->orders['order'], 'messages.display' ); ?>
				</th>
				<th width="8%" class="title" >
					<?php echo JHTML::_('grid.sort', 'Receiver_name', 'a.actualto', @$this->orders['order_Dir'], @$this->orders['order'], 'messages.display' ); ?>
				</th>  
				<th width="25%" class="title" nowrap="nowrap">
					<?php echo JText::_('Message'); ?>
				</th>
				<th width="5%" class="title">
					<?php echo JHTML::_('grid.sort', 'Sent', 'a.sent', @$this->orders['order_Dir'], @$this->orders['order'], 'messages.display' ); ?>
				</th>
				<th width="2%" class="title">
					<?php echo JHTML::_('grid.sort', 'Read', 'a.read', @$this->orders['order_Dir'], @$this->orders['order'], 'messages.display' ); ?>
				</th>
				<th width="5%" class="title">
					<?php echo JHTML::_('grid.sort', 'Type', 'a.type', @$this->orders['order_Dir'], @$this->orders['order'], 'messages.display' ); ?>
				</th>
				<th width="1%" class="title" nowrap="nowrap">
					<?php echo JHTML::_('grid.sort', 'ID', 'a.id', @$this->orders['order_Dir'], @$this->orders['order'], 'messages.display' ); ?>
				</th> 
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
				$row = $this->items[$i]; 
				
				// Read status
				$imgRead 	= $row->read ? 'icon-16-tick.png' : 'icon-16-publish_x.png'; 
				$altRead 	= $row->read ? JText::_( 'Read' ) : JText::_( 'Unread' );
 
				$imgFileDownloaded 	= $row->status ? 'icon-16-download-tick.png' : 'icon-16-download-notick.png';
				$altFileDownloaded 	= $row->status ? JText::_( 'Downloaded' ) : JText::_( 'Not downloaded' );
				 
				// Sent datetime formatting
				$sentDateTime = JHTML::_('date', $row->sent, 'Y-m-d H:i:s');
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center">
					<a class="badge badge-info" href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','messages.showEntity')">
						<?php echo $i+1+$this->pagination->limitstart;?>
					</a>
				</td>
				<td align="center">
					<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
				</td>
				<td> 
					<?php echo $row->actualfrom; ?> 
				</td>
				<td>
					<?php echo $row->actualto ? $row->actualto : JText::_('MULTIPLE_RECEIVER_USERS'); ?>
				</td> 
				<td>
					<div style="height:40px; overflow:auto"><?php echo $row->message; ?></div>
				</td>    
				<td>
					<?php echo $sentDateTime; ?>
				</td>
				<td align="center"> 
					<?php if($row->actualto):?>
						<img src="components/com_jchat/images/<?php echo $imgRead;?>" width="16" height="16" border="0" alt="<?php echo $altRead; ?>" />  
					<?php else:
						echo JText::_('JCHAT_ND');
						endif;
					?>
				</td>
				<td> 
					<?php echo $row->type == 'message' ? JText::_('TIPO_TEXT') : JText::_('TIPO_FILE') . "<img class='inner_spia' src='components/com_jchat/images/$imgFileDownloaded' title='$altFileDownloaded' width='16' height='16' border='0' alt='$altFileDownloaded'/>";?>
				</td> 
				<td>
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>

	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="messages.display" /> 
	<input type="hidden" name="boxchecked" value="0" /> 
	<input type="hidden" name="filter_order" value="<?php echo $this->orders['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->orders['order_Dir']; ?>" /> 
</form>