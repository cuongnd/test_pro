<?php 
/** 
 * @package JMAP::CPANEL::administrator::components::com_jmap
 * @subpackage views
 * @subpackage cpanel
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 

echo JHtml::_('sliders.start', 'jchatsliders_help', array('useCookie' => 1)); ?>
 
	<?php echo JHtml::_('sliders.panel', JText::_('JCHAT_MAIN_FUNCTIONALITIES'), 'functionalities-pane'); ?>
	<?php echo JText::_('JCHAT_MAIN_FUNCTIONALITIES_DESC');?>
	<div class="codeinfo">
		<img src="components/com_jchat/images/help/frontend1.jpg" alt="cpanel"/>
	</div>
	<div class="codeinfo">
		<?php echo JText::_('JCHAT_MAIN_FUNCTIONALITIES_DESC1');?>
	</div>
	<div class="codeinfo">
		<?php echo JText::_('JCHAT_MAIN_FUNCTIONALITIES_DESC2');?>
	</div>
	<div class="codeinfo">
		<img src="components/com_jchat/images/help/frontend2.jpg" alt="cpanel"/>
	</div>
	<?php echo JHtml::_('sliders.panel', JText::_('JCHAT_SECONDARY_FUNCTIONALITIES'), 'second-functionalities-pane'); ?> 
	<?php echo JText::_('JCHAT_SECONDARY_FUNCTIONALITIES_DESC');?>
	<div class="codeinfo">
		<img src="components/com_jchat/images/help/cpanel.jpg" alt="cpanel"/>
	</div>
	<div class="codeinfo">
 		 <?php echo JText::_('JCHAT_SECONDARY_FUNCTIONALITIES_DESC1');?>
	</div>
	<div class="codeinfo">
 		<img src="components/com_jchat/images/help/messages.jpg" alt="cpanel"/>
	</div>
		
<? echo JHtml::_('sliders.end'); ?>	

<form name="adminForm" id="adminForm" action="index.php">
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>"/>
	<input type="hidden" name="task" value=""/>
</form>