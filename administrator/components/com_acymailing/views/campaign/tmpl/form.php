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
<div id="iframedoc"></div>
<form action="index.php?option=<?php echo ACYMAILING_COMPONENT ?>" method="post" name="adminForm" autocomplete="off" id="adminForm" >
	<table class="adminform" cellspacing="1" width="100%">
		<tr>
			<td>
				<label for="name">
					<?php echo JText::_( 'ACY_TITLE' ); ?>
				</label>
			</td>
			<td>
				<input type="text" name="data[list][name]" id="name" class="inputbox" style="width:200px" value="<?php echo $this->escape(@$this->list->name); ?>" />
			</td>
			<td>
				<label for="activated">
					<?php echo JText::_( 'ENABLED' ); ?>
				</label>
			</td>
			<td>
				<?php echo JHTML::_('acyselect.booleanlist', "data[list][published]" , '',$this->list->published); ?>
			</td>
		</tr>
	</table>

	<table width="100%">
		<tr>
			<td width = "60%" valign="top">
				<fieldset class="adminform">
					<legend><?php echo JText::_( 'ACY_DESCRIPTION' ); ?></legend>
					<?php echo $this->editor->display();?>
				</fieldset>
			</td>
			<td valign="top">
				<fieldset class="adminform">
					<legend><?php echo JText::_( 'LISTS' ); ?></legend>
					<?php echo JText::_('CAMPAIGN_START')?>
					<table class="adminlist table table-striped" cellpadding="1">
						<thead>
							<tr>
								<th class="title">
									<?php echo JText::_('LIST_NAME'); ?>
								</th>
								<th class="title">
									<?php echo JText::_('AFFECTED'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
					<?php
							$k = 0;

							for($i = 0,$a = count($this->lists);$i<$a;$i++){
								$row =& $this->lists[$i];
					?>
							<tr class="<?php echo "row$k"; ?>">
								<td>
									<?php echo '<div class="roundsubscrib rounddisp" style="background-color:'.$row->color.'"></div>'; ?>
									<?php
									$text = '<b>'.JText::_('ACY_ID').' : </b>'.$row->listid;
									$text .= '<br/>'.$row->description;
									echo acymailing_tooltip($text, $row->name, 'tooltip.png', $row->name);
									?>
								</td>
								<td align="center">
									<?php echo JHTML::_('acyselect.booleanlist', "data[listcampaign][".$row->listid."]" , '',(bool) $row->campaignid,JText::_('JOOMEXT_YES'),JText::_('JOOMEXT_NO'),$row->listid.'listcampaign'); ?>
								</td>
							</tr>
					<?php
								$k = 1-$k;
							}
						?>
						</tbody>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
	<div class="clr"></div>

	<input type="hidden" name="cid[]" value="<?php echo @$this->list->listid; ?>" />
	<input type="hidden" name="data[list][type]" value="campaign" />
	<input type="hidden" name="option" value="<?php echo ACYMAILING_COMPONENT; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="ctrl" value="campaign" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
