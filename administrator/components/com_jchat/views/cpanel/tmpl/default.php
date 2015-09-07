<?php 
/** 
 * @package JCHAT::CPANEL::administrator::components::com_jchat
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
	<?php echo JHtml::_('sliders.start', 'jchatsliders', array('useCookie' => 1)); ?> 
	<?php echo JHtml::_('sliders.panel', JText::_('JCHAT_STATUS'), 'infostatus-pane'); ?>
	<div class="jchat_logo"></div>
	<div class="slidercontents">
		<div class="container">  
			<!-- COMPONENT STATUS INDICATOR -->
			<div class="codeinfo">
				<div class="single_container">
			 		<div class="box">
						<span class="infousers"></span>
					</div>
					<label class="infotitle"><?php echo JText::_('TOTAL_USERS');?></label>
			 		<label class="infotitle stat"><?php echo $this->totalusers;?></label>
				</div>
				<div class="single_container">
			 		<div class="box">
						<span class="infousers"></span>
					</div>
					<label class="infotitle"><?php echo JText::_('TOTAL_LOGGED_USERS');?></label>
			 		<label class="infotitle stat"><?php echo $this->totallogged;?></label>
				</div>
			</div>
		</div>  
	</div>
	
	<div class="seperator"></div>
 	<?php echo JHtml::_('sliders.panel', JText::_('JCHAT_ABOUT'), 'about-pane'); ?>
 	<div class="jchat_logo"></div>
 	
	<div class="slidercontents"> 
		<div class="codeinfo">
			<div class="single_container">
		 		<div class="box">
					<span class="infostat"></span>
				</div>
				<label class="infotitle"><?php echo JText::_('VERSION_COMPONENT');?></label>
	 		</div>
	 		
	 		<div class="single_container">
		 		<div class="box">
					<span class="infostat"></span>
				</div>
				<label class="infotitle"><?php echo JText::_('AUTHOR_COMPONENT');?></label>
	 		</div>
	 		
	 		<div class="single_container">
		 		<div class="box">
					<span class="infostat"></span>
				</div>
				<label class="infotitle"><?php echo JText::_('SUPPORTLINK');?></label>
	 		</div>
	 		
	 		<div class="single_container">
		 		<div class="box">
					<span class="infostat"></span>
				</div>
				<label class="infotitle"><?php echo JText::_('DEMOLINK');?></label>
	 		</div>
	 		
	 		<div class="single_container">
		 		<div class="box">
					<span class="infostat"></span>
				</div>
				<label class="infotitle"><?php echo JText::_('JOOMLAEXTENSIONSLINK');?></label>
	 		</div>
		</div>
	</div>    
	<? echo JHtml::_('sliders.end'); ?>

<form name="adminForm" id="adminForm" action="index.php">
	<input type="hidden" name="option" value="<?php echo JRequest::getCmd('option');?>"/>
	<input type="hidden" name="task" value=""/>
</form>