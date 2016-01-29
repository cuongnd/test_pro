<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 April 2012
 * @file name	:	modules/mod_jblanceusers/tmpl/default.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$config = JblanceHelper::getConfig();

$document = & JFactory::getDocument(); 
$document->addStyleSheet("components/com_jblance/css/$config->theme"); 
$document->addStyleSheet("modules/mod_jblanceusers/css/style.css"); 

$show_logo = intval($params->get('show_logo', 1));
$set_Itemid	= intval($params->get('set_itemid', 0));
$Itemid = ($set_Itemid > 0) ? '&Itemid='.$set_Itemid : '';
$show_rating = intval($params->get('show_usertype', 0));
?>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbj_tbl">
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
			$row = $rows[$i];
			$link_detail = JRoute::_('index.php?option=com_jblance&view=user&layout=viewprofile&id='.$row->user_id.$Itemid);				
			?>
			<tr><td>
				<table class="latestcv" width="100%">
					<tr>
						<?php if($show_logo){ ?>
						<td width="20%">
							<?php echo JblanceHelper::getThumbnail($row->user_id, 'width=25 height=25'); ?>
						</td>
						<?php } ?>			
						<td width="80%">
							<?php echo LinkHelper::GetProfileLink($row->user_id, $row->username); ?>
							<!-- <a class="jobcriteria jbj_bold" href="<?php echo $link_detail;?>"><?php echo $row->name; ?></a> -->
						</td>	
					</tr>
					<?php if($show_rating == 1){ ?>		
					<tr>
						<td></td>
						<td>
							<?php JblanceHelper::getAvarageRate($row->user_id, 1); ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</td></tr>
			<?php
			$k = 1 - $k;
		}
		?>
	</table>
	<?php 
	$user = JFactory :: getUser();
	if($user->guest)
		$link_findresume = JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront'.$Itemid); 
	else
		$link_findresume = JRoute::_('index.php?option=com_jblance&view=user&layout=searchuser'.$Itemid); ?>
	<!-- <div align="center">
		<input type="button" onclick="location.href='<?php echo $link_findresume; ?>'" class="button" value="<?php echo JText::_('MOD_JBLANCE_VIEW_ALL'); ?>"/>
	</div> -->