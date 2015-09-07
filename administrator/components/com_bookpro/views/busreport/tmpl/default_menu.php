<?php defined('_JEXEC') or die('Restricted access'); 
AImporter::css('report');
?><div class='report-menu'>
<div><?php echo JText::_('COM_BOOKPRO_REPORT_MENU')?></div>
		<ul>
		
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=busreport&layout=default', JText::_('COM_BOOKPRO_REPORT_DRIVER'),'class="mypage_link"') ?>
			</span></li>
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=busreport&layout=driversale', JText::_('COM_BOOKPRO_BUSREPORT_DRIVER_SALE'),'class="mypage_link"') ?>
			</span></li>
			
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=busreport&layout=ticket', JText::_('COM_BOOKPRO_BUSREPORT_TICKET'),'class="mypage_link"') ?>
			</span></li>
			
	</ul></div>
<div class="summary">
<?php echo $this->loadTemplate('summary') ?>
</div><div class="clr"></div>