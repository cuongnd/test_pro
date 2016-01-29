<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/membership/tmpl/planhistory.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows plan history subscribed by user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 JHTML::_('behavior.framework');
 JHTML::_('behavior.modal');
 
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/LightFace.js");
 $doc->addScript("components/com_jblance/js/light.js");

 JText::script('COM_JBLANCE_CLOSE');
 JText::script('COM_JBLANCE_CONFIRM_DELETE');
 JText::script('COM_JBLANCE_YES');

 $config =& JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $currencysym = $config->currencySymbol;
 
 $link_plan_add  = JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd');
 $action = JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory');
?>
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_SUBSCR_HISTORY'); ?></div>
<?php
	$onclick = ''; 
	if(isset($this->rows[0])) 
	if($this->rows[0]->approved == 0){	//the recent subscr is pending approval
		$ttl = JText::_('COM_JBLANCE_CANCEL_SUBSCR');
		$msg = JText::_('COM_JBLANCE_PENDING_SUBSCR_CANCEL_FIRST');
		$onclick="onclick='jbLightAlert(\"$ttl\", \"$msg\");return false;'";
	}
?>
 <P><a href="<?php echo $link_plan_add; ?>" <?php echo $onclick; ?> ><?php echo JText::_('COM_JBLANCE_GET_NEW_SUBSCR'); ?></a></P>

<form action="<?php echo $action; ?>" method="post" name="userFormJob" enctype="multipart/form-data">
	<?php 
	if($this->finish) echo "<p>$this->finish</p>";
	 ?>
      <?php
      if(count($this->rows) > 0){ ?>
	  	<div class="border">
          <table border="0" width="100%" cellpadding="0" cellspacing="0">
		  <thead>
            <tr class="jbl_rowhead">
              <th width="10"><?php echo JText::_('ID'); ?></th>
              <th width="22%"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?></th>
              <th width="3%"><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			  <th width="15%"><?php echo JText::_('COM_JBLANCE_DATE_BUY'); ?></th>
              <th width="10%"><?php echo JText::_('COM_JBLANCE_DAYS_LEFT'); ?></th>
              <th width="15%"><?php echo JText::_('COM_JBLANCE_START'); ?></th>
              <th width="15%"><?php echo JText::_('COM_JBLANCE_END'); ?></th>
              <th width="10%"><?php echo JText::_('COM_JBLANCE_PRICE'); ?></th>
			  <th width="12%"><?php echo JText::_('COM_JBLANCE_ACTION'); ?></th>
            </tr>
		</thead>
		<tbody>
            <?php
            $k = 0;
            foreach ($this->rows AS $row){
				if($row->gateway == 'banktransfer')
					$link_checkout  = JRoute::_('index.php?option=com_jblance&view=membership&layout=bank_transfer&id='.$row->id.'&type=plan');
				elseif($row->gateway != 'banktransfer')
					$link_checkout  = JRoute::_('index.php?option=com_jblance&view=membership&layout=check_out&id='.$row->id.'&type=plan&repeat=1');

				$link_plandetail	= JRoute::_('index.php?option=com_jblance&view=membership&layout=plandetail&id='.$row->id);
				$link_cancelsubscr  = JRoute::_('index.php?option=com_jblance&task=membership.cancelsubscr&id='.$row->id.'&'.JSession::getFormToken().'=1');
				$link_invoice 		=  JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=plan');
	            ?>
				<tr class="jbl_<?php echo "row$k"; ?>">
					<td><?php echo $row->id;?></td>
					<td><a href="<?php echo $link_plandetail; ?>" class="jobcriteria"><?php echo $row->name; ?></a></td>
					<td>
						<?php if($row->daysleft < 0): ?>
							<img src="components/com_jblance/images/s3.png" alt="">
						<?php else: ?>
							<img src="components/com_jblance/images/s<?php echo $row->approved;?>.png" alt="">
						<?php endif; ?>
					</td>
					
					<td><?php echo JHTML::_('date', $row->date_buy, $dformat, true); ?></td>
                    <td><?php if($row->daysleft >= 0)echo $row->daysleft; else echo '0';?></td>
                   	<td><?php echo $row->date_approval != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_approval, $dformat, true) :  "&nbsp;"; ?></td>
                   	<td><?php echo $row->date_expire != "0000-00-00 00:00:00" ?  JHTML::_('date', $row->date_expire, $dformat, true) :  "&nbsp;"; ?></td>
					<td class="jb-alignright"><?php echo $currencysym; ?> <?php echo number_format($row->price, 2, '.', ','); ?></td>
					<td style="text-align:center;"><?php if(!$row->approved): ?>
						<img src="components/com_jblance/images/checkout.png" alt="CO" style="cursor: pointer" title="<?php echo JText::_('COM_JBLANCE_CHECKOUT'); ?>" width="18" onclick="javascript:location.href = '<?php echo $link_checkout; ?>';" />
						<img src="components/com_jblance/images/delete.png" alt="Cancel" style="cursor: pointer" title="<?php echo JText::_('COM_JBLANCE_CANCEL_SUBSCR'); ?>" width="16" onclick="javascript:confirmAction('<?php echo JText::_('COM_JBLANCE_CANCEL_SUBSCR'); ?>', '<?php echo JText::_('COM_JBLANCE_CONFIRM_CANCEL_SUBSCR'); ?>', '<?php echo $link_cancelsubscr; ?>');" />
						<?php endif; ?>
						<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $link_invoice; ?>" class="modal"><img src="components/com_jblance/images/print.png" title="<?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?>" width="18" alt="Print"/></a>
					</td>
                </tr>
                <?php
            $k = 1 - $k;
            }
                ?>
			</tbody>
            </TABLE>
		</div>
		<div class="sp20">&nbsp;</div>
		<table>
		<tr>
			<th colspan="2"><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
		</tr>
			<tr>
				<td><img src="components/com_jblance/images/s0.png" alt=""></td>
				<td><?php echo JText::_('COM_JBLANCE_APPROVAL_PENDING'); ?></td>
			</tr>
			<tr>
				<td><img src="components/com_jblance/images/s1.png" alt=""></td>
				<td><?php echo JText::_('COM_JBLANCE_APPROVED'); ?></td>
			</tr>
			<tr>
				<td><img src="components/com_jblance/images/s2.png" alt=""></td>
				<td><?php echo JText::_('COM_JBLANCE_CANCELLED'); ?></td>
			</tr>
			<tr>
				<td><img src="components/com_jblance/images/s3.png" alt=""></td>
				<td><?php echo JText::_('COM_JBLANCE_EXPIRED'); ?></td>
			</tr>
		</table>
		<?php 
      }
      else 
      {
      	echo JText::_('COM_JBLANCE_NO_SUBSCR');
      }
		?>
</form>