<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/user/tmpl/viewprofile.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	View user profile (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.modal');
 
 $app  	=& JFactory::getApplication();
 $model = $this->getModel();
 $user = JFactory::getUser();
 $userid = $app->input->get('id', 0, 'int');
 if(empty($userid)){		// get the current userid if not passed
	$userid = $user->id;
 }
 
 $isMine = ($user->id == $userid);
 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 $userInfo = $jbuser->getUserGroupInfo($userid, null);
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycod = $config->currencyCode;
 $enableReporting = $config->enableReporting;
 $enableAddThis = $config->enableAddThis;
 $addThisPubid = $config->addThisPubid;
 
 $uri 	= JFactory::getURI();
 
 $link_sendpm = JRoute::_('index.php?option=com_jblance&view=message&layout=compose&username='.$this->userInfo->username);
 $link_report = JRoute::_('index.php?option=com_jblance&view=message&layout=report&id='.$userid.'&report=profile&link='.base64_encode($uri)/* .'&tmpl=component' */);
 $link_edit_profile = JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile');
 $link_edit_picture = JRoute::_('index.php?option=com_jblance&view=user&layout=editpicture');
?>
<form action="index.php" method="post" name="viewProfile" >
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PROFILE').' - '.$this->userInfo->name; ?></div>
	
	<!-- Do not show send message & edit link to the profile owner -->
	<div class="page-actions">
	<?php if($enableAddThis & !$isMine) : ?>
		<div id="social-bookmark" class="page-action fl">
			<!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_default_style ">
			<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
			<a class="addthis_button_tweet"></a>
			<a class="addthis_button_google_plusone" g:plusone:size="medium"></a> 
			<a class="addthis_counter addthis_pill_style"></a>
			</div>
			<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo $addThisPubid; ?>"></script>
			<!-- AddThis Button END -->
		</div>
	<?php endif; ?>
	<?php if($isMine) : ?>
		<div id="edit-profile" class="page-action">
		 	<a href="<?php echo $link_edit_profile; ?>" class="jbicon-edit"><?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?></a>
		</div>
	<?php else : ?>
		<?php if($enableReporting) : ?>
		<div id="report-this" class="page-action">
		    <a href="<?php echo $link_report; ?>" class="jbicon-report"><?php echo JText::_('COM_JBLANCE_REPORT_USER'); ?></a>
		</div>
		<?php endif; ?>
		<div id="send-message" class="page-action">
		    <a href="<?php echo $link_sendpm; ?>" class="jbicon-message"><?php echo JText::_('COM_JBLANCE_SEND_MESSAGE'); ?></a>
		</div>
	<?php endif; ?>
	</div>
	
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<table class="jbltable" width="100%">
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
				</td>
				<td>
					<?php echo  $this->userInfo->username; ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_NAME'); ?>:</label>
				</td>
				<td>
					<?php echo  $this->userInfo->name; ?>
				</td>
			</tr>
			<!-- Company Name should be visible only to users who can post project -->
			<?php if($userInfo->allowPostProjects) : ?>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?>:</label>
				</td>
				<td>
					<?php echo $this->userInfo->biz_name; ?>
				</td>
			</tr>
			<?php endif; ?>
			<!-- Skills and hourly rate should be visible only to users who can work/bid -->
			<?php if($userInfo->allowBidProjects) : ?>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_HOURLY_RATE'); ?>:</label>
				</td>
				<td>
					<?php echo $currencysym.$this->userInfo->rate.' ' .$currencycod.' / '.JText::_('COM_JBLANCE_HOUR'); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</label>
				</td>
				<td>
					<?php echo JblanceHelper::getCategoryNames($this->userInfo->id_category); ?>					
				</td>
			</tr>
		<?php endif; ?>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_AVERAGE_RATING'); ?>:</label>
				</td>
				<td>
					<?php
					$rate = JblanceHelper::getAvarageRate($this->userInfo->user_id, true);
					?>
				</td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_STATUS'); ?>:</label>
				</td>
				<td>
					<?php
					//get user online status
					$status = $jbuser->isOnline($this->userInfo->user_id);
					?>
					<?php if($status) : ?>
						<span class="greenfont"><?php echo JText::_('COM_JBLANCE_ONLINE'); ?></span>
					<?php else : ?>
						<span class="redfont"><?php echo JText::_('COM_JBLANCE_OFFLINE'); ?></span>
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="key"></td>
				<td>
					<?php
					$att = "style='border: solid 5px grey;'";
					$avatar = JblanceHelper::getLogo($userid, $att);
					echo $avatar;
					?><br>
					<?php if($isMine) : ?>
					<a href="<?php echo $link_edit_picture; ?>" class="jbicon-edit"><?php echo JText::_('COM_JBLANCE_EDIT_PICTURE'); ?></a>
					<?php endif; ?>
				</td>
			</tr>
		</table>
	</fieldset>
	<?php 
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class FieldsHelper
		$parents = array();$children = array();
	//isolate parent and childr
	foreach($this->fields as $ct){
		if($ct->parent == 0)
			$parents[] = $ct;
		else
			$children[] = $ct;
	}
		
	if(count($parents)){
		foreach($parents as $pt){ ?>
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_($pt->field_title); ?></legend>
		<table class="jbltable" width="100%">
			<?php
			foreach($children as $ct){
				if($ct->parent == $pt->id){ ?>
			<tr>
				<td class="key">
					<?php
					$labelsuffix = '';
					if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
					?>
					<label for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?>:</label>
				</td>
				<td>
					<?php $fields->getFieldHTMLValues($ct, $userid, 'profile'); ?>
				</td>
			</tr>
			<?php
				}
			} ?>
		</table>
	</fieldset>
			<?php
		}
	}
	?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PORTFOLIO') ?></div>
	<?php 
	if(count($this->portfolios)) :
	?>
	<div id="PortfolioGallery">
		<div id="ib-main-wrapper" class="ib-main-wrapper kinetic-active" tabindex="0" style="cursor: move;">
			<div class="ib-main">
			<?php
			$k = 0;
			for($i=0, $n=count($this->portfolios); $i < $n; $i++){
				$portfolio 		= $this->portfolios[$i];
				$link_view_portfolio 	= JRoute::_('index.php?option=com_jblance&view=user&layout=viewportfolio&id='.$portfolio->id);
				
				//get the portfolio image info
				if($portfolio->picture) {
					$attachment = explode(";", $portfolio->picture);
					$showName = $attachment[0];
					$fileName = $attachment[1];
					$imgLoc = JBPORTFOLIO_URL.$fileName;
				}
				else 
					$imgLoc = 'components/com_jblance/images/cpanel/portfolio.png';
			?>
				<a class="ib-image" href="<?php echo $link_view_portfolio; ?>">
                    <img src="<?php echo $imgLoc; ?>" alt="" title="">
                    <span><?php echo $portfolio->title; ?></span>
                </a>
			<?php 
			}
			?>
			<div style="clear:both;"></div>
			</div>
		</div>
	</div>
	<?php 
	else : 
		echo JText::_('COM_JBLANCE_NO_PORTFOLIO_FOUND');
	endif; ?> <!-- end of portfolio count -->
	<div class="lineseparator"></div>
	<?php 
	echo JHtml::_('tabs.start', 'panel-tabs', array('useCookie'=>'0'));
	echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_FREELANCER'), 'freelancer'); ?>
	<!-- project history -->
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PROJECTS_HISTORY'); ?></div>
	<?php 
	if(count($this->fprojects)) : ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<tr class="jbl_rowhead">
			<th>#</th>
			<th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_RATED_BY'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_RATING_FROM_PUBLISHER'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_COMMENTS'); ?></th>
		</tr>			
		<?php
		$k = 0;
		for($i=0, $n=count($this->fprojects); $i < $n; $i++){
			$fproject 		= $this->fprojects[$i];
			$link_project 	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$fproject->id);
			$buyer = JFactory::getUser($fproject->publisher_userid);
		?>
		<tr class="jbl_row<?php echo $k; ?>">
			<td>
				<?php echo $i+1;?>
			</td>
			<td>
				<a href="<?php echo $link_project;?>"><?php echo $fproject->project_title; ?></a>
			</td>
			<td>
				<?php echo LinkHelper::GetProfileLink($fproject->publisher_userid, $buyer->username); ?>
			</td>
			<td>
				<?php
				$rate = JblanceHelper::getUserRateProject($fproject->assigned_userid, $fproject->id);
				JblanceHelper::getRatingHTML($rate);
				?>
			</td>
			<td>
				<?php 
				if($rate > 0)
					echo $fproject->comments;
				else 
					echo '<i>'.JText::_('COM_JBLANCE_NOT_YET_RATED').'</i>';
				?>
			</td>
		</tr>
		<?php 
			$k = 1 - $k;
		} ?>
	</table>
	<?php 
		else : 
			echo JText::_('COM_JBLANCE_NO_PROJECTS_FOUND');
		endif;	
	?>
	
	<div class="sp20">&nbsp;</div>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_RATING'); ?></div>
	<?php 
	if(!empty($this->frating->quality_clarity)) : ?>
	<table class="jbltable">
		<tr>
			<td class="key" style="width:200px;">
				<label><?php echo JText::_('COM_JBLANCE_QUALITY_OF_WORK'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->frating->quality_clarity); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_COMMUNICATION'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->frating->communicate); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_EXPERTISE'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->frating->expertise_payment); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_PROFESSIONALISM'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->frating->professional); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_HIRE_AGAIN'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->frating->hire_work_again); ?>
			</td>
		</tr>
	</table>	
	<?php 
	else : 
		echo JText::_('COM_JBLANCE_RATING_NOT_FOUND');
	endif; ?>
	
	<?php echo JHtml::_('tabs.panel', JText::_('COM_JBLANCE_BUYER'), 'buyer'); ?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PROJECTS_HISTORY'); ?></div>
	<?php 
	if(count($this->bprojects)) : ?>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<tr class="jbl_rowhead">
			<th>#</th>
			<th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_RATED_BY'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_RATING_FROM_FREELANCER'); ?></th>
			<th><?php echo JText::_('COM_JBLANCE_COMMENTS'); ?></th>
		</tr>			
		<?php
		$k = 0;
		for($i=0, $n=count($this->bprojects); $i < $n; $i++){
			$bproject 		= $this->bprojects[$i];
			$link_project 	= JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id='.$bproject->id);
			$freelancer = JFactory::getUser($bproject->assigned_userid);
		?>
		<tr class="jbl_row<?php echo $k; ?>">
			<td>
				<?php echo $i+1;?>
			</td>
			<td>
				<a href="<?php echo $link_project;?>"><?php echo $bproject->project_title; ?></a>
			</td>
			<td>
				<?php echo LinkHelper::GetProfileLink($bproject->assigned_userid, $freelancer->username); ?>
			</td>
			<td>
				<?php
				$rate = JblanceHelper::getUserRateProject($bproject->publisher_userid, $bproject->id);
				?>
				<div class="rating_bar fl">
					<div style="width:<?php echo $rate*10*2; ?>%" class=""><!-- convert the rating into percent --></div>
				</div>
				<div class="fl"><?php echo "(".$rate.")"; ?></div>
			</td>
			<td>
				<?php 
				if($rate > 0)
					echo $bproject->comments;
				else 
					echo '<i>'.JText::_('COM_JBLANCE_NOT_YET_RATED').'</i>';
				?>
			</td>
		</tr>
		<?php 
			$k = 1 - $k;
		} ?>
	</table>
	<?php 
		else : 
			echo JText::_('COM_JBLANCE_NO_PROJECTS_FOUND');
		endif;	
	?>
	<div class="sp20">&nbsp;</div>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_RATING'); ?></div>
	<?php 
	if(!empty($this->brating->quality_clarity)) : ?>
	<table class="jbltable">
		<tr>
			<td class="key" style="width:200px;">
				<label><?php echo JText::_('COM_JBLANCE_CLARITY_SPECIFICATION'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->brating->quality_clarity); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_COMMUNICATION'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->brating->communicate); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_PAYMENT_PROMPTNESS'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->brating->expertise_payment); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_PROFESSIONALISM'); ?>:<label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->brating->professional); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_WORK_AGAIN'); ?>:</label>
			</td>
			<td>
				<?php JblanceHelper::getRatingHTML($this->brating->hire_work_again); ?>
			</td>
		</tr>
	</table>	
	<?php 
	else : 
		echo JText::_('COM_JBLANCE_RATING_NOT_FOUND');
	endif; ?>
	<?php echo JHtml::_('tabs.end'); ?>
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="Itemid" id="Itemid" value="<?php echo $Itemid; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>	
