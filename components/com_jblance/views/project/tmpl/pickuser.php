<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/project/tmpl/pickuser.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Pick user from the bidders (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/LightFace.js");
 $doc->addScript("components/com_jblance/js/light.js");

 $model 		= $this->getModel();
 $user 			=& JFactory::getUser();
 $config 		=& JblanceHelper::getConfig();
 
 $currencysym 	= $config->currencySymbol;
 $currencycode 	= $config->currencyCode;
 $dformat 		= $config->dateFormat;
 $checkFund 	= $config->checkFund;
 
 $curr_balance = JblanceHelper::getTotalFund($user->id);
 
 JText::script('COM_JBLANCE_INSUFFICIENT_FUND');
 JText::script('COM_JBLANCE_DEPOSIT_FUNDS');
 JText::script('COM_JBLANCE_CLOSE');
?>
<script>
<!--
	function checkBalance(){

		if(!$$('input[name=assigned_userid]:checked')[0]){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_PICK_AN_USER_FROM_THE_LIST'); ?>');
			return false;
		}
		
		var checkFund = parseInt('<?php echo $checkFund; ?>');

		if(checkFund){
			var balance = parseFloat('<?php echo $curr_balance; ?>');
			var assigned = $$('input[name=assigned_userid]:checked')[0].get('value');
			var bidamt = $('bidamount_'+assigned).get('value');

			if(balance < bidamt){
				insufficientFund('<?php echo JText::_('COM_JBLANCE_INSUFFICIENT_BALANCE_PICK_USER'); ?>');
				return false;
			}
		}
		return true;	
	}

	var link_deposit = "<?php echo JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);?>";
//-->
</script>
<form action="index.php" method="post" name="userForm">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PICK_USER').' : '.$this->project->project_title; ?></div>
	<p class="font14 jb-alignright"><b><?php echo JText::_('COM_JBLANCE_CURRENT_BALANCE'); ?> : <?php echo $currencysym.' '.$curr_balance; ?></b></p>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<thead>
			<tr class="jbl_rowhead">
				<th><?php echo JText::_('COM_JBLANCE_PICK'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_FREELANCERS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_BIDS').' ('.$currencycode.')'; ?></th>
				<th><?php echo JText::_('COM_JBLANCE_DELIVERY_DAYS'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_TIME_OF_BID'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_RATING'); ?></th>	
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$k = 0;
			for($i=0, $n=count($this->rows); $i < $n; $i++){
				$row = $this->rows[$i];
			?>
			<tr class="jbl_row<?php echo $k; ?>">
				<td rowspan="2" class="jb-aligncenter">
					<?php if($row->status == '') : ?>
					<input type="radio" name="assigned_userid" id="assigned_userid_<?php echo $row->id; ?>" value="<?php echo $row->user_id; ?>"/>
					<?php endif; ?>
				</td>
				<td>
					<?php echo LinkHelper::GetProfileLink(intval($row->user_id), $this->escape($row->username)); ?>
				</td>
				<td>
					<?php echo $currencysym.' '.$row->amount; ?>
					<input type="hidden" id="bidamount_<?php echo $row->user_id; ?>" value="<?php echo  $row->amount; ?>" />
				</td>
				<td>
					<?php echo $row->delivery; ?>
				</td> 
				<td>
					<?php echo JHTML::_('date', $row->bid_date, $dformat); ?>
				</td>
				<td>
					<?php
					$rate = JblanceHelper::getAvarageRate($row->user_id);
					?>
				</td>
				<td><?php echo JText::_($row->status); ?></td>
			</tr>
			<tr class="jbl_row<?php echo $k; ?>">
				<td colspan="5" class=""><b><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?></b> : <br /><em><?php echo ($row->details) ? $row->details : JText::_('COM_JBLANCE_DETAILS_NOT_PROVIDED'); ?></em></td>
				<td class="jb-aligncenter">
					<!-- Show attachment if found -->
						<?php
						if(!empty($row->attachment)) : ?>
							<div style="display: inline;">
						<?php 
						$attachment = explode(";", $row->attachment);
						$showName = $attachment[0];
						$fileName = $attachment[1];
						?>	
								<a href="<?php echo JBBIDNDA_URL.$fileName; ?>" target="_blank"><img src="components/com_jblance/images/nda.png" width="20px" title="<?php echo JText::_('COM_JBLANCE_NDA_SIGNED'); ?>"/></a>
							</div>
						<?php	
						endif;
						?>
				</td>
			</tr>
			<?php 
			$k = 1 - $k;
			} ?>
		</tbody>
	</table>
	<div class="fr"><input type="submit" value="<?php echo JText::_('COM_JBLANCE_PICK_USER'); ?>" class="button" onclick="return checkBalance();"/></div>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.savepickuser" />	
	<input type="hidden" name="id" value="<?php echo $row->project_id; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>