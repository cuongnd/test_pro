<?php defined('_JEXEC') or die('Restricted access'); 

?>
<div class='header'><?php echo JText::_('COM_BOOKPRO_REPORT_MENU')?></div>
		<ul>
		<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=manage', JText::_('COM_BOOKPRO_REPORT_ADMIN'),'class="mypage_link"') ?>
			</span></li>
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=driver', JText::_('COM_BOOKPRO_REPORT_DRIVER'),'class="mypage_link"') ?>
			</span></li>
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=default', JText::_('COM_BOOKPRO_REPORT_TOUR'),'class="mypage_link"') ?>
			</span></li>
			
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=customer', JText::_('COM_BOOKPRO_REPORT_CUSTOMER'),'class="mypage_link"') ?>
			</span></li>
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=referral', JText::_('COM_BOOKPRO_REPORT_REFERRAL'),'class="mypage_link"') ?>
			</span></li>
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=tourreport', JText::_('COM_BOOKPRO_REPORT_TOURREPORT'),'class="mypage_link"') ?>
			</span></li>
			<li><span> <?php echo JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=salereport&layout=category', JText::_('COM_BOOKPRO_REPORT_CATEGORY'),'class="mypage_link"') ?>
			</span></li>
	</ul>
<div class="summary">
<?php echo $this->loadTemplate('summary') ?>
</div>