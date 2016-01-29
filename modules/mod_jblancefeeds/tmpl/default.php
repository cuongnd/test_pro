<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 August, 2012
 * @file name	:	modules/mod_jblancefeeds/tmpl/default.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */

 defined('_JEXEC') or die('Restricted access');

 $config =& JblanceHelper::getConfig();
 $dformat = $config->dateFormat;

 $document = & JFactory::getDocument(); 
 $document->addStyleSheet("components/com_jbjobs/css/$config->theme"); 
 $document->addStyleSheet("modules/mod_jblancefeeds/css/style.css"); 
?>
<?php
if($show_type == 'feed'){
	for ($i=0, $n=count($rows); $i < $n; $i++){
		$row = $rows[$i];
		if (isset($row->user_id))
			$link_detail = JRoute::_('index.php?option=com_jbjobs&view=employer&layout=detailjobseeker&id='.$row->user_id);	
		
?>
	<div class="jbj_newsfeed_item">
		<div class="newsfeed_avatar">
		<?php echo $row->logo; ?>
		</div>
    	<div class="newsfeed_content">
			<div class="newsfeed_content_top">
				<?php echo $row->title; ?>
			</div>
		</div>
		<div class="newsfeed_date small"><?php echo $row->daysago; ?></div>
	</div>
	<?php
	}
}
elseif($show_type == 'message'){
	for ($i=0, $n=count($rows); $i < $n; $i++){
		$row = $rows[$i];
		$userDtl = JFactory::getUser($row->idFrom);
	?>
	<div class="jbj_newsfeed_item">
		<div class="newsfeed_avatar">
		<?php
			$attrib = 'width=40 height=40';
			$avatar = JblanceHelper::getThumbnail($row->idFrom, $attrib);
			echo !empty($avatar) ? LinkHelper::GetProfileLink($row->idFrom, $avatar) : '&nbsp;' ?>
		</div>
    	<div class="newsfeed_content">
			<div class="newsfeed_content_top">
			<?php echo LinkHelper::GetProfileLink($row->idFrom, $userDtl->username); ?><br>
				<?php echo $row->message; ?>
			</div>
		</div>
		<div class="newsfeed_date small"><?php echo JHTML::_('date', $row->date_sent, $dformat, true); ?></div>
	</div>
	<?php
	}
	

}
?>