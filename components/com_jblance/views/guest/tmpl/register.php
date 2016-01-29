<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/tmpl/register.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User Groups (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');
 JHTML::_('behavior.modal');
 JHTML::_('behavior.tooltip');

 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js"); 

 //JblanceHelper::getTooltip();

 $app =& JFactory::getApplication();
 $user	=& JFactory::getUser();
 $model = $this->getModel();
 $config =& JblanceHelper::getConfig();
 $taxpercent = $config->taxPercent;
 $taxname = $config->taxName;
 $currencysym = $config->currencySymbol;

 $session =& JFactory::getSession();
 $ugid = $session->get('ugid', 0, 'register');
 $planChosen = $session->get('planChosen', 0, 'register');

 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 
 if(empty($planChosen)){	//this is to check if the user has selected plan and entered this page
	$link = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
	$app->redirect($link);
 }
?>
<script language="javascript" type="text/javascript">
<!--
	function validateForm(f){
		if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>';
	    	if($('password2').hasClass('invalid')){
		    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_VERIFY_PASSWORD_INVALID'); ?>';
		    }
			alert(msg);
			return false;
	    }
		return true;
	}
	window.addEvent('domready', function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); 
		});
	});
//-->
</script>

<form action="index.php" method="post" name="regNewUser" class="form-validate" onsubmit="return validateForm(this);" enctype="multipart/form-data">
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_ACCOUNT_INFO'); ?></div>
<?php echo JText::_('COM_JBLANCE_FIELDS_COMPULSORY'); ?>
	
	<fieldset class="jblfieldset">
	<legend><?php echo JText::_('COM_JBLANCE_MEMBERSHIP_CHOSEN'); ?></legend>
		<table class="jbltable">
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>:<br /></label>
				</td>
				<td class="font16">
					<?php $sub_id = $planChosen['plan_id'];
					echo $planChosen['planname'.$sub_id]; ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_PLAN_DURATION'); ?>:</label>
				</td>
				<td>
					<?php echo $planChosen['planperiod'.$sub_id]; ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_BONUS_FUND'); ?>:</label>
				</td>
				<td>
					<?php echo $currencysym.' '.number_format($planChosen['plancredit'.$sub_id], 2, '.', '' ); ?>
				</td>
			</tr>
			<?php 
			$totalamt = $planChosen['price'.$sub_id];
			if($totalamt > 0) :
			?>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_PAY_MODE'); ?>:</label>
				</td>
				<td>
					<?php echo JblanceHelper::getGwayName($planChosen['gateway']); ?>
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_TOTAL_AMOUNT'); ?>:</label>
				</td>
				<td>
					<?php
						$totalamt = $planChosen['price'.$sub_id];
						if($taxpercent > 0){
							$taxamt = $totalamt * ($taxpercent/100);
							$totalamt = $taxamt + $totalamt;
						}
						echo $currencysym.' '.number_format($totalamt, 2, '.', '' );
						if($taxpercent > 0){
							echo ' ( '.$planChosen['price'.$sub_id].' + '.$taxamt.' )';
						}
					?>
				</td>
			</tr>
	    </table>
	</fieldset>
	
	<fieldset class="jblfieldset">
	<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<table class="jbltable">
			<tr>
				<td class="key"><label for="firstname"><?php echo JText::_('COM_JBLANCE_FIRST_NAME'); ?> <span class="redfont">*</span>:</label>
				</td>
				<td>
					<input class="inputbox required" type="text" name="firstname" id="firstname" size="40" maxlength="100" value="" />
				</td>
			</tr>
			<tr>
				<td class="key"><label for="lastname"><?php echo JText::_('COM_JBLANCE_LAST_NAME'); ?>:</label>
				</td>
				<td>
					<input class="inputbox" type="text" name="lastname" id="lastname" size="40" maxlength="100" value="" />
				</td>
			</tr>
			<tr>
				<td class="key"><label for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?> <span class="redfont">*</span>:</label>
				</td>
				<td>
					<input type="text" size="40" maxlength="100" name="username" id="username" class="inputbox hasTip required" onchange="checkAvailable(this);" title="<?php echo JText::_('COM_JBLANCE_TT_USERNAME'); ?>"> 
					<div id="status_username" class="dis-inl-blk"></div>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="email"><?php echo JText::_('COM_JBLANCE_EMAIL'); ?> <span class="redfont">*</span>:</label>
				</td>
				<td>
					<input type="text" size="40" maxlength="100" name="email" id="email" class="inputbox hasTip required validate-email" onchange="checkAvailable(this);" title="<?php echo JText::_('COM_JBLANCE_TT_EMAIL'); ?>">
					<div id="status_email" class="dis-inl-blk"></div>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="password"><?php echo JText::_('COM_JBLANCE_PASSWORD'); ?> <span class="redfont">*</span>:</label>
				</td>
				<td>
					<input type="password" size="40" maxlength="100" name="password" id="password" class="inputbox hasTip required validate-password" title="<?php echo JText::_('COM_JBLANCE_TT_PASSWORD'); ?>">
				</td>
			</tr>
			<tr>
				<td class="key"><label for="password2"><?php echo JText::_('COM_JBLANCE_CONFIRM_PASSWORD'); ?> <span class="redfont">*</span>:</label>
				</td>
				<td>
					<input type="password" size="40" maxlength="100" name="password2" id="password2" class="inputbox hasTip required validate-passverify" title="<?php echo JText::_('COM_JBLANCE_TT_REPASSWORD'); ?>">
				</td>
			</tr>
	    </table>
	</fieldset>
	
	<?php //$model->showCustom($this->custom); ?>

	<!-- <?php if($config->showCaptcha): ?>
		<div class="border">
			<div class="shade"><div class="arrow"></div><?php echo JText::_('COM_JBLANCE_CAPTCHA_VERIFICATION'); ?></div>
			<!-- Insert the captcha here-->
			<?php
			//set the argument below to true if you need to show vertically (3 cells one below the other)
			$app->triggerEvent('onShowOSOLCaptcha', array(true));
			?>
		</div>
	<?php endif; ?> -->
	
	<?php
	$termid = $config->termArticleId;
	$link = JRoute::_("index.php?option=com_content&view=article&id=".$termid.'&tmpl=component');
	?>
	<p><?php echo JText::sprintf('COM_JBLANCE_BY_CLICKING_YOU_AGREE', $link); ?></p>
	
	<input type="submit" value="<?php echo JText::_( 'COM_JBLANCE_I_ACCEPT_CREATE_MY_ACCOUNT' ); ?>" class="button"  />
		
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="guest.grabuseraccountinfo" />
	<?php echo JHTML::_('form.token'); ?>
</form>