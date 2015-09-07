<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.2.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><div id="acy_content">
<?php include(dirname(__FILE__).DS.'form_menu.php'); ?>
<div id="acymailing_edit">
<form action="<?php echo JRoute::_('index.php?option=com_acymailing&ctrl=newsletter'); ?>" method="post" name="adminForm"  id="adminForm" autocomplete="off" enctype="multipart/form-data">

	<?php include(ACYMAILING_BACK.'views'.DS.'newsletter'.DS.'tmpl'.DS.'info.form.php'); ?>
	<?php include(ACYMAILING_BACK.'views'.DS.'newsletter'.DS.'tmpl'.DS.'param.form.php'); ?>
	<fieldset class="adminform" width="100%" id="htmlfieldset">
		<legend><?php echo JText::_( 'HTML_VERSION' ); ?></legend>
		<div style="clear:both"><?php echo $this->editor->display(); ?></div>
	</fieldset>
	<fieldset class="adminform" id="textfieldset">
		<legend><?php echo JText::_( 'TEXT_VERSION' ); ?></legend>
		<textarea style="width:98%" rows="20" name="data[mail][altbody]" id="altbody" ><?php echo @$this->mail->altbody; ?></textarea>
	</fieldset>


	<div class="clr"></div>
	<input type="hidden" name="cid[]" value="<?php echo @$this->mail->mailid; ?>" />
	<input type="hidden" id="tempid" name="data[mail][tempid]" value="<?php echo @$this->mail->tempid; ?>" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="data[mail][type]" value="news" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="newsletter" />
	<input type="hidden" name="listid" value="<?php echo JRequest::getInt('listid'); ?>"/>
	<?php if(!empty($this->Itemid)) echo '<input type="hidden" name="Itemid" value="'.$this->Itemid.'" />';
	echo JHTML::_( 'form.token' ); ?>
</form>
</div>
</div>
