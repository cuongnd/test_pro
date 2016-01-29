<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/user/tmpl/dashboard.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Displays the user Dashboard (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.framework');
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 $doc->addScript("components/com_jblance/js/LightFace.js");
 $doc->addScript("components/com_jblance/js/light.js");
 
 $model 				= $this->getModel();
 $user					=& JFactory::getUser();
 $config 				=& JblanceHelper::getConfig();
 $showFeedsDashboard 	= $config->showFeedsDashboard;
 $enableEscrowPayment 	= $config->enableEscrowPayment;
 $enableWithdrawFund 	= $config->enableWithdrawFund;

 JText::script('COM_JBLANCE_CLOSE');
 
 $link_portfolio	= JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio');
 $link_messages		= JRoute::_('index.php?option=com_jblance&view=message&layout=inbox');
 $link_post_project = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject');
 $link_list_project = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
 $link_search_proj  = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
 $link_my_project 	= JRoute::_('index.php?option=com_jblance&view=project&layout=showmyproject');
 $link_my_bid 		= JRoute::_('index.php?option=com_jblance&view=project&layout=showmybid');
 $link_deposit		= JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund');
 $link_withdraw		= JRoute::_('index.php?option=com_jblance&view=membership&layout=withdrawfund');
 $link_escrow		= JRoute::_('index.php?option=com_jblance&view=membership&layout=escrow');
 $link_transaction	= JRoute::_('index.php?option=com_jblance&view=membership&layout=transaction');
 $link_managepay	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay');
 $link_subscr_hist	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory');
 $link_buy_subscr	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planadd');
 
	if(!JBLANCE_FREE_MODE){
		if(!$user->guest){
			$planStatus = JblanceHelper::planStatus($user->id);
			
			if($planStatus == '1'){ ?>
				<div class="sp10">&nbsp;</div>
				<div class="expiredplan">
					<?php echo JText::sprintf('COM_JBLANCE_USER_SUBSCRIPTION_EXPIRED', $link_buy_subscr); ?>
				</div>
			<?php }
			elseif($planStatus == '2'){ ?>
			<div class="sp10">&nbsp;</div>
			<div class="noactiveplan">
					<?php echo JText::sprintf('COM_JBLANCE_USER_DONT_HAVE_ACTIVE_PLAN', $link_subscr_hist); ?>
				</div>
			<?php }
		}
	} ?>
<div class="jbl_h3title"><?php echo JText::_($this->userInfo->name).' '.JText::_('COM_JBLANCE_DASHBOARD'); ?></div>
	
<div style="clear:both;"></div>

<div class="border">
	<div id="cpanel">
		<table width="100%" border="0" cellpadding="0" cellspacing="1" >
			<tr class="jbl_rowhead"><th colspan="6"><div class="shade"><div class="arrow"></div><?php echo JText::_('COM_JBLANCE_PROFILE').' & '.JText::_('COM_JBLANCE_PROJECTS'); ?></div></th></tr>
			<tr align="center">
				<td>
					<div class="jbicon-container">
						<div class="jbicon">
						<!-- Get the profile edit link -->
						<?php 
						$profileInteg = JblanceHelper::getProfile();
						//$link_edit_profile = $profileInteg->getEditURL();
						$link_edit_profile = JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile');
						?>
						<a href="<?php echo $link_edit_profile; ?>"><img src="components/com_jblance/images/cpanel/edit_profile.png" border="0" width="48" alt="Edit Profile"/><br/>
						<?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?> </a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<!-- Get the avatar edit link -->
						<?php 
						$avatars = JblanceHelper::getAvatarIntegration();
						$link_edit_picture = $avatars->getEditURL();
						?>
						<a href="<?php echo $link_edit_picture; ?>"><img src="components/com_jblance/images/cpanel/picture.png" border="0" width="48" alt="Edit Picture"/><br/>
						<?php echo JText::_('COM_JBLANCE_EDIT_PICTURE'); ?> </a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<!-- Get the avatar edit link -->
						<?php 
						$avatars = JblanceHelper::getAvatarIntegration();
						$link_edit_picture = $avatars->getEditURL();
						?>
						<a href="<?php echo $link_portfolio; ?>"><img src="components/com_jblance/images/cpanel/portfolio.png" border="0" width="48" alt="Portfolio"/><br/>
						<?php echo JText::_('COM_JBLANCE_PORTFOLIO'); ?> </a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_messages; ?>"><img src="components/com_jblance/images/cpanel/messages.png" border="0" width="48" alt="Messages"/><br/>
						<?php echo JText::_('COM_JBLANCE_PRIVATE_MESSAGES'); ?> </a>
						</div>
					</div>
				
				<?php if($this->dbElements['allowPostProjects']) : ?>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_post_project; ?>"><img src="components/com_jblance/images/cpanel/new_project.png" border="0" width="48" alt="Post New Project"/><br/>
						<?php echo JText::_('COM_JBLANCE_POST_NEW_PROJECT'); ?> </a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_my_project; ?>"><img src="components/com_jblance/images/cpanel/my_projects.png" border="0" width="48" alt="My Job Listing"/> <br/>
						<?php echo JText::_('COM_JBLANCE_MY_PROJECTS'); ?></a>
						</div>
					</div>
				<?php endif; ?>
				<?php if($this->dbElements['allowBidProjects']) : ?>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_list_project; ?>"><img src="components/com_jblance/images/cpanel/latest_proj.png" border="0" width="48" alt="Latest Projects"/>
						<span><?php echo JText::_('COM_JBLANCE_LATEST_PROJECTS'); ?></span></a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_search_proj; ?>"><img src="components/com_jblance/images/cpanel/search.png" border="0" width="48" alt="Search Projects"/>
						<span><?php echo JText::_('COM_JBLANCE_SEARCH_PROJECTS'); ?></span></a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_my_bid; ?>"><img src="components/com_jblance/images/cpanel/my_bids.png" border="0" width="48" alt="My Bids"/>
						<span><?php echo JText::_('COM_JBLANCE_MY_BIDS'); ?></span></a>
						</div>
					</div>
				<?php endif; ?>
				</td>
			</tr>
			
			<?php 
				if(!JBLANCE_FREE_MODE) :
			?>
			<tr class="jbl_rowhead"><th colspan="6"><div class="shade"><div class="arrow"></div><?php echo JText::_('COM_JBLANCE_BILLING_AND_FINANCE'); ?></div></th></tr>
			<tr align="center">
				<td>
					<div>
						<div class="jbicon">
						<a href="<?php echo $link_deposit; ?>"><img src="components/com_jblance/images/cpanel/deposit.png" border="0" width="48" alt="Deposit Funds"/> <br/>
						<?php echo JText::_('COM_JBLANCE_DEPOSIT_FUNDS'); ?> </a>
						</div>
					</div>
					<!-- check if withdraw fund is enabled -->
					<?php if($enableWithdrawFund) : ?>
					<div>
						<div class="jbicon">
						<a href="<?php echo $link_withdraw; ?>"><img src="components/com_jblance/images/cpanel/withdraw.png" border="0" width="48" alt="Withdraw Funds"/> <br/>
						<?php echo JText::_('COM_JBLANCE_WITHDRAW_FUNDS'); ?> </a>
						</div>
					</div>
					<?php endif; ?>
					<!-- check if escrow payment is enabled -->
					<?php if($enableEscrowPayment) : ?>
					<div>
						<div class="jbicon">
						<a href="<?php echo $link_escrow; ?>"><img src="components/com_jblance/images/cpanel/escrow.png" border="0" width="48" alt="Escrow"/> <br/>
						<?php echo JText::_('COM_JBLANCE_ESCROW_PAYMENT'); ?> </a>
						</div>
					</div>
					<?php endif; ?>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_transaction; ?>"><img src="components/com_jblance/images/cpanel/transaction.png" border="0" width="48" alt="Transaction History"/><br/>
						<?php echo JText::_('COM_JBLANCE_TRANSACTION_HISTORY'); ?> </a>
						</div>
					</div>
					<div class="jbicon-container">
						<div class="jbicon">
						<a href="<?php echo $link_managepay; ?>"><img src="components/com_jblance/images/cpanel/managepay.png" border="0" width="48" alt="managepay"/><br/>
						<?php echo JText::_('COM_JBLANCE_MANAGE_PAYMENTS'); ?> </a>
						</div>
					</div>
					
					<div>
						<div class="jbicon">
						<a href="<?php echo $link_subscr_hist; ?>"><img src="components/com_jblance/images/cpanel/my_subscr.png" border="0" width="48" alt="My Subscription"/><br/>
						<?php echo JText::_('COM_JBLANCE_MY_SUBSCRS'); ?> </a>
						</div>
					</div>
				</td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
</div>
<div class="lineseparator"></div>

<!-- pending tasks section -->
<div>
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_TASKS_PENDING'); ?></div>
<?php 
if(!empty($this->pendings)){
	foreach($this->pendings as $pending){
?>
<div class="jbl_feed_item">
	<div class="feed_avatar" style="width:20px;">
		<img src="components/com_jblance/images/actions/report.png" border="0" width="16" alt=""/>
	</div>
	<div class="">
	<?php echo $pending; ?>
	</div>
</div>
<?php		
	}
}
else {
	echo JText::_('COM_JBLANCE_NO_TASK_PENDING_YOUR_ACTION');
}
?>
<div class="lineseparator"></div>
</div>

<!-- news feed section -->
<?php if($showFeedsDashboard) : ?>
<div class="app-box" id="recent-activities">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_NEWS_FEED'); ?></div>
	<?php
	for ($i=0, $n=count($this->feeds); $i < $n; $i++) {
		$feed = $this->feeds[$i]; ?>
		<div id="jbl_feed_item_<?php echo $feed->id; ?>" class="jbl_feed_item">
			<div class="feed_avatar">
					<?php echo $feed->logo; ?>
			</div>
	    	<div class="feed_content">
				<div class="feed_content_top">
					<?php echo $feed->title; ?>
				</div>
				<?php if(!empty($feed->content)) : ?>
					<div class="feed_content_hidden" style="display: block">
						<?php echo $feed->content; ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="feed_date small"><?php echo $feed->daysago; ?></div>
			<div id="feed_hide_<?php echo $feed->id; ?>" class="feed_remove">
				<?php if($feed->isMine) : ?>
				<a class="remFeed" onclick="processFeed('<?php echo $user->id; ?>' , '<?php echo $feed->id; ?>', 'remove');" href="javascript:void(0);">
					<img alt="" src="components/com_jblance/images/remove.gif" title="<?php echo JText::_('COM_JBLANCE_REMOVE'); ?>">
				</a>
				<?php endif; ?>
				<a class="hideFeed" onclick="processFeed('<?php echo $user->id; ?>' , '<?php echo $feed->id; ?>', 'hide');" href="javascript:void(0);">
					<img alt="" src="components/com_jblance/images/hide.gif" title="<?php echo JText::_('COM_JBLANCE_HIDE'); ?>">
				</a>
			</div>
		</div>
		<?php
	}
	?>
</div>
<?php endif; ?>