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

/* @var $this BookingViewReservations */

JHTML::_('behavior.tooltip');

BookProHelper::setSubmenu(21);

AImporter::model('agents');

$model = new BookProModelAgents();
$model->init($this->lists);
$this->items = $model->getAgentTicketReport();
$this->pagination = $model->getPaginTicketAgent();


$colspan = $this->selectable ? 9 : 10;

echo $this->selectable;

$editCustomer = JText::_('Edit Bus');
$titleEditAcount = JText::_('Edit Bus');


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
//$start = $this->lists['start'];
$itemsCount = count($this->items);

$pagination = &$this->pagination;

$config =& JFactory::getConfig();
$tzoffset = $config->getValue('config.offset');
$today = JFactory::getDate($this->lists['start'],$tzoffset);
//$today->add(new DateInterval('P1D'));
$start= $today->format('d-m-Y',true);



AImporter::jquery();
AImporter::jqueryui();
AImporter::js('jquery.ui.datepicker-vi');

AImporter::css('customui');

?>
<script>
	jQuery(document).ready(function($) {
     $( "input#start" ).datepicker();
     $( "#start" ).datepicker( "option", $.datepicker.regional['vi']);
     $( "#start" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
     $("input#start").datepicker("setDate","<?php echo $start ?>" );
    
    
     
  });
  </script>
  <div>
  	<div>
		<?php echo $this->loadTemplate('menu')?>
	</div>
	<div>
		<form action="index.php" name="adminForm" id="adminForm">
			<div>
				<div class="report-filter-left">
					<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_DATE') ?></label>
					<input type="text" class="inputbox input width249" name="start" id="start"	value="" size="13" maxlength="10" />
					
				</div>
				<div class="report-filter-left">
					<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_FILTER_FROM') ?></label>
					<?php echo $this->from; ?>
				</div>
				<div class="report-filter-left">
					<label><?php echo JText::_('COM_BOOKPRO_BUSREPORT_FILTER_TO'); ?></label>
					<?php echo $this->to; ?>
				</div>
				<div class="report-filter-left">
					<input type="button" onclick="document.adminForm.submit();" name="button" value="<?php echo JText::_('COM_BOOKPRO_BUSREPORT_BUTTON') ?>" />
				</div>
				<div class="clear"></div>
				
			</div>
			<div class="clear"></div>
			
		
			<table class="adminlist" cellspacing="1" width="100%">
				<thead>
					<tr>
						<th width="25%">
							<?php echo JText::_('COM_BOOKPRO_BUSREPORT_TICKET_NUMBER') ?>
						</th>
						<th width="25%" align="center">
					        <?php echo JText::_('COM_BOOKPRO_BUSREPORT_PASSANGER'); ?>
						</th>
						<th class="title">
					        <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BUSREPORT_TICKET_BUSTRIP'), 'title', $orderDir, $order); ?>
						</th>
						<th width="25%">
					        <?php echo JText::_('COM_BOOKPRO_BUSREPORT_DATE'); ?>
						</th>
						
						
						
						
											
					</tr>
				</thead>
				<tfoot>
	    			<tr>
	    				<td colspan="<?php echo $colspan; ?>">
	    				    <div class="pagin">
	    				    	<?php echo $pagination->getPagesLinks (); ?>
	    				    </div>
	    				</td>
	    			</tr>
				</tfoot>
				<tbody>
					<?php if (! is_array($this->items) || ! $itemsCount) { ?>
						<tr><td colspan="<?php echo $colspan; ?>"><?php echo JText::_('No items found.'); ?></td></tr>
					<?php } else { ?>
					    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
					    
					    	<?php $subject = &$this->items[$i];
								$link = JRoute::_(ARoute::edit(CONTROLLER_BUSTRIP, $subject->id));
					    	?>
					    	     
					    	<tr class="row<?php echo ($i % 2); ?>">
					    		<td>
					    			<?php echo $subject->order_number; ?>
					    		</td>
					    		<td>
					    			
					    			<div class="report-seat"><?php echo JText::sprintf('COM_BOOKPRO_AGENT_REPORT_SEAT',$subject->sumbus); ?></div>
					    		</td>
					    		<td><a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a></td>
					    		<td><?php echo $subject->date; ?> </td>
					    		
					    		
					    		
					    		
					    	</tr>
					    <?php } ?>
					<?php } ?>
				</tbody>
			</table>
			
			
			<input type="hidden" name="option" value="com_bookpro" />
			<input type="hidden" name="controller" value="busreport" />
			<input type="hidden" name="layout" value="ticket" />
		</form>
	</div>
  </div>