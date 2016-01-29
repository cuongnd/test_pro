<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 June 2012
 * @file name	:	views/user/tmpl/userlist.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	User list page (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $app  =& JFactory::getApplication();
 $letter = $app->input->get('letter', '', 'string');
 $actionLetter = (!empty($letter)) ? '&letter='.$letter : '';
 
 $action	= JRoute::_('index.php?option=com_jblance&view=user&layout=userlist'.$actionLetter);
 $actionAll	= JRoute::_('index.php?option=com_jblance&view=user&layout=userlist');
 
 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class fieldsHelper
 
 ?>
<form action="<?php echo $action; ?>" method="post" name="userFormJob" enctype="multipart/form-data">
	<div class="jbl_h3title"><?php echo $this->escape($this->params->get('page_heading', JText::_('COM_JBLANCE_USERLIST'))); ?></div>
		<div class="alpha-index">
		<?php
			echo JHTML::_('link', $actionAll, '#', array('title'=>JText::_('COM_JBLANCE_ALL'))); 
			foreach (range('A', 'Z') as $i) :
				$link_comp_index = JRoute::_('index.php?option=com_jblance&view=user&layout=userlist&letter='.strtolower($i), false);
				if(strcasecmp($letter, $i) == 0)
					echo JHTML::_('link', $link_comp_index, $i, array('title'=>$i, 'class'=>'boldfont'));
				else
					echo JHTML::_('link', $link_comp_index, $i, array('title'=>$i));	
			endforeach; ?>	
    </div>
	<table width="100%" cellpadding="0" cellspacing="0" class="border">
		<thead>
			<tr class="jbl_rowhead">
				<th>#</th>
				<th colspan="2"><?php echo JText::_('COM_JBLANCE_NAME'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_USERNAME'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_USERGROUP'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9" class="jbl_row3">
					<?php echo $this->pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
		$k = 0;
		for ($i=0, $x=count($this->rows); $i < $x; $i++){
			$row = $this->rows[$i];
			//get user online status
			$status = $jbuser->isOnline($row->user_id);
			?>
			<tr class="jbl_row<?php echo $k; ?>">
				<td><?php echo $this->pageNav->getRowOffset($i); ?></td>
				<td align="left">
					<?php
					$attrib = 'width=25 height=25';
					$avatar = JblanceHelper::getThumbnail($row->user_id, $attrib);
					echo !empty($avatar) ? LinkHelper::GetProfileLink($row->user_id, $avatar) : '&nbsp;' ?>
				</td>
				<td><?php echo LinkHelper::GetProfileLink($row->user_id, $this->escape($row->name)); ?></td>
				<td><?php echo LinkHelper::GetProfileLink($row->user_id, $this->escape($row->username)); ?></td>
				<td><?php echo $row->grpname; ?></td>
				<td>
				<?php if($status) : ?>
					<span class="greenfont"><?php echo JText::_('COM_JBLANCE_ONLINE'); ?></span>
				<?php else : ?>
					<span class="redfont"><?php echo JText::_('COM_JBLANCE_OFFLINE'); ?></span>
				<?php endif; ?>
		  </tr>
			<?php 
			$k = 1 - $k;
		}
		?>
		</tbody>
	</table>
</form> 