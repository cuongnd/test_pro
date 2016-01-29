<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/membership/tmpl/planadd.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows list of available Plans (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 JHTML::_('behavior.framework', true);
 JHTML::_('behavior.tooltip');
 $doc =& JFactory::getDocument();
 //$doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/LightFace.js");
 $doc->addScript("components/com_jblance/js/light.js");
 
 $model = $this->getModel();
 $user	=& JFactory::getUser();
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $taxname	 = $config->taxName;
 $taxpercent = $config->taxPercent;
 
 $hasJBProfile = JblanceHelper::hasJBProfile($user->id);
 
 JText::script('COM_JBLANCE_CLOSE');
 
 /*// if the user is not registered, direct him to registration page else to profile page.
 if($user->id == 0)
 	$link_register = JRoute::_('index.php?option=com_jblance&view=guest&layout=register&step=3', false);
 else
 	$link_register = JRoute::_('index.php?option=com_jblance&view=guest&layout=usergroupfield', false);*/
 
 $link_usergroup = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
 $link_subscr_history = JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory');

?>
<script language="javascript" type="text/javascript">
<!--
	function valButton(btn) {
		var cnt = -1;
		for (var i=btn.length-1; i > -1; i--) {
		   if (btn[i].checked) {cnt = i; i = -1;}
		   }
		if (cnt > -1) 
			return btn[cnt].value;
		else 
			return null;
	}
	function gotoRegistration() {
		var form = document.userFormJob;
		form.task.value = 'guest.grabplaninfo';
		
		if(validateForm()){
			form.submit();
		}
	}		
	function addSubscr() {
		var form = document.userFormJob;
		form.task.value = 'membership.upgradesubscription';
		if(validateForm()){
			form.submit();
		}
	}
	function validateForm() {			
		var form = document.userFormJob;
		var btn = valButton(document.getElementsByName('plan_id'));
		
		if(btn == null){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_CHOOSE_YOUR_PLAN'); ?>');
			form.plan_id.focus();
			return false;
		}
		else{
			return true;				
		}
	}
	function checkZeroPlan(planAmt){
		if(planAmt == 0)
			$('div-gateway').hide();
		else
			$('div-gateway').show();
	}
//-->
</script>
<form action="index.php" method="post" name="userFormJob" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_BUY_SUBSCR'); ?></div>

	<?php 
	if($hasJBProfile){ ?>
	<p>
	  <a href="<?php echo $link_subscr_history; ?>"><?php echo JText::_('COM_JBLANCE_SUBSCR_HISTORY'); ?></a>
	</p>
	<?php 
	}
	?>
	<p><?php 
		if($hasJBProfile) 
			echo JText::_('COM_JBLANCE_CHOOSE_SUBSCR_PAYMENT'); 
		else 
			echo JText::_('COM_JBLANCE_SUBSCR_WELCOME');?>
	</p>
	<?php 
		if(!$hasJBProfile){
			$session =& JFactory::getSession();
			$ugid = $session->get('ugid', 0, 'register');
			$jbuser = JblanceHelper::get('helper.user');
			$groupName = $jbuser->getUserGroupInfo(null, $ugid)->name;
			echo JText::sprintf('COM_JBLANCE_USERGROUP_CHOSEN_CLICK_TO_CHANGE', $groupName, $link_usergroup);
 		}; ?>
	<div class="sp10">&nbsp;</div>
	<?php
	//get the array of plan ids, the user has subscribed to.
	$planArray = array();
	foreach($this->plans as $plan){
		$planArray[] = $plan->planid;
	}
	foreach($this->rows AS $row) {?>
	    <div class="plan-choose plan-choose-shadow fl" style="width:45%; max-width: 400px; margin: 0 0px 20px 20px;">
		    <div class="plan-content">
				<div class="plan-heading">
				    <div class="plan-dur-price">
						<?php
					    $nprice = '';
					    if(($row->discount > 0) && in_array($row->id, $planArray) && ($row->price > 0)){
					        $nprice = $row->price - (($row->price / 100) * $row->discount);
					        $nprice = number_format($nprice, 2, '.', '' );
					    }
					    ?>
					
							<?php echo $nprice ?  '<S style="color:red;text-decoration:line-through " >'.$currencysym.' '.$row->price.'</S><BR> '.$currencysym.' <span class="bigfont">'.$nprice.'</span>' : ''.$currencysym.'<span class="bigfont"> '.$row->price.'</span>'; ?><br/>
							<?php echo JText::_('COM_JBLANCE_FOR'); ?><br/>
							<?php if($row->days > 100 && $row->days_type == 'years')
					      		echo JText::_('COM_JBLANCE_LIFETIME');
					     	  else { ?>
						      	<span class="bigfont"><?php echo $row->days.' '; ?> </span>
						      	<?php echo getDaysType($row->days_type); 
						 		    }?>
					</div>
					<h4><input type="radio" name="plan_id" value="<?php echo $row->id; ?>"  onclick="checkZeroPlan('<?php echo $nprice ? $nprice : $row->price; ?>');" /> <?php echo $row->name; ?>
					<span><?php echo $row->description; ?></span>
					</h4>
				</div>
				<div class="sp20">&nbsp;</div> 
				<?php 
					$infos = $model->buildPlanInfo($row->id);
					$html = "<table width=\'100%\'>";
					foreach($infos as $info){
						$html .= "<tr>";
						$html .= "<td>".$info->key."</td>";
						$html .= "<td>".$info->value."</td>";
						$html .= "</tr>";
					}
					$html .= "</table>";
				?>
				<div class="ul">
					<div class="li fl"><?php echo JText::_('COM_JBLANCE_BONUS_FUND'); ?></div><div class="li fr"><?php echo $currencysym.' '.number_format($row->bonusFund, 2, '.', '' ); ?></div><br/><div style="clear:both;"></div>
				<?php 
				foreach($infos as $info){ ?>
					<!-- <div class="li fl"><?php echo $info->key; ?></div><div class="li fr"><?php echo $info->value; ?></div><br/><div style="clear:both;"></div> -->
				<?php	
				}
				?>
				</div>
				<div class="sp20">&nbsp;</div><div class="sp20">&nbsp;</div>  
				<div class="fr" style="position: relative; bottom: 30px;">
					<a href="javascript:void(0);" class="jbbutton" onclick="jbLightAlert('<?php echo $row->name; ?>', '<?php echo $html; ?>', true);">
						<span><?php echo JText::_('COM_JBLANCE_LEARN_MORE'); ?></span>
					</a>
				</div>
			</div>
			<!-- Disable the plans if the limit is exceeded -->
			<?php if($user->id > 0) : ?>
				<?php if($row->time_limit > 0 && in_array($row->id, $planArray) && $this->plans[$row->id]->plan_count >= $row->time_limit) : ?>
					<div style="" class="plan-limit-overlay"><div class="plan-limit-message"><?php echo JText::sprintf('COM_JBLANCE_PLAN_PURCHASE_LIMIT_MESSAGE', $row->time_limit); ?></div></div>
				<?php endif; ?>
			<?php endif; ?>
			
		</div>
		<input type="hidden" name="planname<?php echo $row->id; ?>"   id="planname<?php echo $row->id; ?>"   value="<?php echo  $row->name; ?>" />
		<input type="hidden" name="planperiod<?php echo $row->id; ?>" id="planperiod<?php echo $row->id; ?>" value="<?php echo  $row->days.' '.ucfirst($row->days_type); ?>" />
		<input type="hidden" name="plancredit<?php echo $row->id; ?>" id="plancredit<?php echo $row->id; ?>" value="<?php echo  $row->bonusFund; ?>" />
		<input type="hidden" name="price<?php echo $row->id; ?>" 	  id="price<?php echo $row->id; ?>" 	 value="<?php echo $nprice ? $nprice : $row->price;?>" />
		
	<?php
	}
	?>
	<div style="clear:both;"></div>    
	<div class="">
		<?php /*if($coupons && $user->id)
		{
		 ?>
		<P><?php echo JText::_('COM_JBLANCE_COUPONS'); ?></P>
		<input type="text" size="40" name="coupon" class="inputbox" value="<?php echo mosGetParam($_REQUEST, 'coupon')?>" />
		<?php 
		}*/
		?>
		
		<div class="sp10">&nbsp;</div> 
		<div id="div-gateway" class="plan-choose" style="width: 90%;padding: 15px;">
		<table cellpadding="10" cellspacing="1">
			<tr>
				<td class="key"><?php echo JText::_('COM_JBLANCE_PAYMENT'); ?>:</td>
				<td>
					<?php 
						$list_paymode = $model->getSelectPaymode('gateway', '', '');
						echo $list_paymode;
					?>						
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
						if($taxpercent > 0)
							echo JText::sprintf('COM_JBLANCE_TAX_APPLIES', $taxname, $taxpercent);
					?>
				</td>
			</tr>
		</table>
		</div>
		<div class="sp10">&nbsp;</div> 
		<div style="clear:both;"></div>
		<?php
			if($hasJBProfile){ ?>
				<input type="button" class="button" value="<?php echo JText::_('COM_JBLANCE_SUBSCRIBE') ?>" onclick="addSubscr();"/>
		<?php 
			}
			else {?>
				<input type="button" class="button" value="<?php echo JText::_('COM_JBLANCE_REGISTER'); ?>" onclick="gotoRegistration();" />
		<?php }?>
	</div>
	
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="">
	<?php echo JHTML::_('form.token'); ?>
</FORM>
<?php 
	function getDaysType($daysType){
		if($daysType == 'days')
			$lang = JText::_('COM_JBLANCE_DAYS');
		elseif($daysType == 'weeks')
			$lang = JText::_('COM_JBLANCE_WEEKS');
		elseif($daysType == 'months')
			$lang = JText::_('COM_JBLANCE_MONTHS');
		elseif($daysType == 'years')
			$lang = JText::_('COM_JBLANCE_YEARS');
		return $lang;
	}
?>