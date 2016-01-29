<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 November 2012
 * @file name	:	views/user/tmpl/viewportfolio.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Lets user to view porfolio (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $row = $this->row;
 $config =& JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
?>
<form action="index.php" method="post" name="userForm">
	<div class="jbl_h3title">
		<?php echo JText::_('COM_JBLANCE_PORTFOLIO_DETAILS').' - '.$row->title; ?>
	</div>
	
	<table width="100%" class="jbltable">
		<tr>
			<td colspan="2">
			<?php
			if($this->row->picture){
				$attachment = explode(";", $this->row->picture);
				$showName = $attachment[0];
				$fileName = $attachment[1];
				
				$imgLoc = JBPORTFOLIO_URL.$fileName;
				$fileAtr = getimagesize($imgLoc);
				$width = $fileAtr[0];
				$height = $fileAtr[1];
				
				//if image width is > 400px, then set it to 400px
				if($width > 400)
					$width = '400px';
			?>
			<p  class="jb-aligncenter"><img src='<?php echo $imgLoc; ?>' width="<?php echo $width; ?>" /></p>
			<?php 
			} ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?>:</span><br>
				<div class="border_bg"><?php echo $row->description; ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</span> 
				<?php echo JblanceHelper::getCategoryNames($row->id_category); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_WEB_ADDRESS'); ?>:</span> 
				<?php echo !empty($row->link) ? $row->link : '<span class="redfont">'.JText::_('COM_JBLANCE_NOT_MENTIONED').'</span>'; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_DURATION'); ?>:</span>
				<?php
				if( ($row->start_date != "0000-00-00 00:00:00" ) && ($row->finish_date!= "0000-00-00 00:00:00") ){
				?>
					<?php echo JHTML::_('date', $this->row->start_date, $dformat).' &harr; '.JHTML::_('date', $this->row->finish_date, $dformat); ?>
				<?php 
				}
				else
					echo '<span class="redfont">'.JText::_('COM_JBLANCE_NOT_MENTIONED').'</span>';
				?>
			</td>
		</tr>
		<?php
		if($row->attachment){
			$attachment = explode(";", $this->row->attachment);
			$showName = $attachment[0];
			$fileName = $attachment[1];
		?>
		<tr>
			<td colspan="2">
				<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_ATTACHMENT'); ?>:</span>
				<a href="<?php echo JBPORTFOLIO_URL.$fileName; ?>" target="_blank"><?php echo $showName; ?></a>
			</td>
		</tr>
		<?php } ?>
	</table>
</form>