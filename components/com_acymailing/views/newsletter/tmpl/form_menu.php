<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.2.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><fieldset id="acy_form_menu">
	<div class="toolbar" id="acytoolbar" style="float: right;">
		<table><tr>
		<?php if(acymailing_isAllowed($this->config->get('acl_templates_view','all'))){ ?><td id="acybuttontemplate"><a class="modal"  rel="{handler: 'iframe', size: {x: 750, y: 550}}" href="<?php echo acymailing_completeLink("fronttemplate&task=theme",true ); ?>"><span class="icon-32-acytemplate" title="<?php echo JText::_('ACY_TEMPLATE'); ?>"></span><?php echo JText::_('ACY_TEMPLATE'); ?></a></td><?php } ?>

		<?php if(acymailing_isAllowed($this->config->get('acl_tags_view','all'))){ ?>
			<td id="acybuttontag"><a onclick="try{IeCursorFix();}catch(e){};" class="modal" rel="{handler: 'iframe', size: {x: 750, y: 550}}" href="<?php echo acymailing_completeLink("fronttag&task=tag&type=".$this->type,true ); ?>"><span class="icon-32-tag" title="<?php echo JText::_('TAGS'); ?>"></span><?php echo JText::_('TAGS'); ?></a></td>
			<td id="acybuttonreplace"><a onclick="javascript:submitbutton('replacetags'); return false;" href="#" class="toolbar"><span class="icon-32-replacetag" title="<?php echo JText::_('REPLACE_TAGS'); ?>"></span><?php echo JText::_('REPLACE_TAGS'); ?></a></td>
		<?php } ?>
		<td id="acybuttondivider"><span class="divider"></span></td>
		<td id="acybuttonpreview"><a onclick="javascript:submitbutton('savepreview'); return false;" href="#" ><span class="icon-32-acypreview" title="<?php echo JText::_('ACY_PREVIEW').' / '.JText::_('SEND'); ?>"></span><?php echo JText::_('ACY_PREVIEW').' / '.JText::_('SEND'); ?></a></td>
		<td id="acybuttonsave"><a onclick="javascript:submitbutton('save'); return false;" href="#" ><span class="icon-32-save" title="<?php echo JText::_('ACY_SAVE'); ?>"></span><?php echo JText::_('ACY_SAVE'); ?></a></td>
		<td id="acybuttonapply"><a onclick="javascript:submitbutton('apply'); return false;" href="#" ><span class="icon-32-apply" title="<?php echo JText::_('ACY_APPLY'); ?>"></span><?php echo JText::_('ACY_APPLY'); ?></a></td>
		<td id="acybuttoncancel"><a onclick="javascript:submitbutton('cancel'); return false;" href="#" ><span class="icon-32-cancel" title="<?php echo JText::_('ACY_CANCEL'); ?>"></span><?php echo JText::_('ACY_CANCEL'); ?></a></td>
		</tr></table>
	</div>
	<div class="acyheader" style="float: left;"><h1><?php echo JText::_('NEWSLETTER').' : '.@$this->mail->subject; ?></h1></div>
</fieldset>
