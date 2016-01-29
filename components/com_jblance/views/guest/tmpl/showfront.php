<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	views/guest/tmpl/showfront.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User Groups (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');

 $app  	=& JFactory::getApplication();
 $user	=& JFactory::getUser();
 $model =& $this->getModel();

 $config =& JblanceHelper::getConfig();
 $link_dashboard = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard');
 
 $Itemid = $app->input->get('Itemid', 0, 'int');
?>
	
<script language="javascript" type="text/javascript">
<!--
	window.addEvent('domready',function() {
		new Fx.SmoothScroll({
			duration: 500
			}, window);
	});

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
	function validateForm(f){
		var btn = valButton(document.getElementsByName('ugid'));
		
		if(btn == null){
			alert('<?php echo JText::_('COM_JBLANCE_PLEASE_CHOOSE_YOUR_ROLE'); ?>');
			return false;
		}
		else {
			return true;				
		}
	}
//-->
</script>

<div id="signuplogin" class="plan-choose">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tbody><tr>
			<td valign="top">
				<div class="introduction">
					<h1><?php echo JText::_($config->welcomeTitle); ?></h1>
						<ul id="featurelist">
							<li><?php echo JText::_('COM_JBLANCE_HIRE_ONLINE_FRACTION_COST'); ?></li>
							<li><?php echo JText::_('COM_JBLANCE_OUTSOURCE_ANYTHING_YOU_CAN_THINK'); ?></li>
				            <li><?php echo JText::_('COM_JBLANCE_PROGRAMMERS_DESIGNERS_CONTENT_WRITERS_READY'); ?></li>
				            <li><?php echo JText::_('COM_JBLANCE_PAY_FREELANCERS_ONCE_HAPPY_WITH_WORK'); ?></li>
						</ul>
					<div class="joinbutton">
						<a href="#ugselect" id="signup" class="jbj_regbutton" style="text-decoration:none;color:#ffffff;" >
							<?php echo JText::_('COM_JBLANCE_SIGN_UP_NOW');?>
						</a>
					</div>
				</div>
	        </td>
	        <td width="200">
	        <?php if($user->guest) : ?>
			    <div class="loginform">
			    	<form action="index.php" method="post" name="login" id="form-login">
			        <h2><?php echo JText::_('COM_JBLANCE_MEMBERS_LOGIN'); ?></h2>
			            <div>
							<?php echo JText::_('COM_JBLANCE_USERNAME'); ?><br>
			                <input type="text" class="inputbox frontlogin" name="username" id="username">
			            </div>

			            <div>
							<?php echo JText::_('COM_JBLANCE_PASSWORD'); ?><br>
			                <input type="password" class="inputbox frontlogin" name="password" id="password">
			            </div>

                        <div for="remember">
							<input type="checkbox" alt="Remember me" value="yes" id="remember" name="remember">
							<?php echo JText::_('COM_JBLANCE_REMEMBER_ME'); ?>
						</div>
						
						<div style="text-align: center; padding: 10px 0 5px;">
						    <input type="submit" value="<?php echo JText::_('COM_JBLANCE_LOGIN');?>" name="submit" id="submit" class="button" />
							<input type="hidden" name="option" value="com_users" />
							<input type="hidden" name="task" value="user.login" />
							<input type="hidden" name="return" value="<?php echo base64_encode($link_dashboard); ?>" />
							<?php echo JHTML::_('form.token'); ?>
						</div>
						
						<span>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>" class="login-forgot-password"><span><?php echo JText::_('COM_JBLANCE_FORGOT_YOUR_PASSWORD').'?'; ?></span></a><br />
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>" class="login-forgot-username"><span><?php echo JText::_('COM_JBLANCE_FORGOT_YOUR_USERNAME').'?'; ?></span></a>
						</span>
						<br>
						
			        </form>
				</div>
			<?php endif; ?>
	        </td></tr>
		</tbody>
	</table>
	<div class="sp10">&nbsp;</div>
</div>
<div style="clear:both;"></div>
<div class="sp20">&nbsp;</div>
<a name="ugselect" id="ugselect"></a>
<form action="index.php" method="post" name="userGroup" class="form-validate" onsubmit="return validateForm(this);">
<input type="hidden" name="check" value="post"/>
	<?php
		foreach($this->userGroups as $userGroup){
	?>
	<div class="plan-choose plan-choose-shadow">
		<div class="fl" style="width: 30%;">
			<h4>
		 		<input type="radio" name="ugid" value="<?php echo $userGroup->id; ?>" class="required validate-radio"/> <?php echo $userGroup->name; ?>
			</h4>
			<?php if($userGroup->approval == 1) : ?>
			<small><div class="jb-aligncenter">(<?php echo JText::_('COM_JBLANCE_REQUIRE_APPROVAL'); ?>)</div></small>
			<?php endif; ?>
		</div>
		<div class="fl shadow_rgt" style="width: 65%; padding-left: 20px;">
			<?php echo stripslashes($userGroup->description); ?>
		</div>
	</div>
	 
	<div class="sp20">&nbsp;</div>
	<?php 
		}
	?>
	<?php echo JText::_('COM_JBLANCE_REQUIRE_APPROVAL_NOTE'); ?>
	
	<div style="clear:both;"></div>
	
	<input type="submit" value="<?php echo JText::_('COM_JBLANCE_CHOOSE_AND_CONTINUE'); ?>" class="bigbutton">
	
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="guest.grabusergroupinfo">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>" />
</form>	
