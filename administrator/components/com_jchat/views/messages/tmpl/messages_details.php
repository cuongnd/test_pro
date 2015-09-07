<?php 
/** 
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage views
 * @subpackage messages
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
JHTML::_('behavior.tooltip'); ?>
 
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div class="col100">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'Details' ); ?></legend> 
			<table class="admintable table table-striped">
			<tbody>
				<tr>
					<td width="20%" class="left_title"> 
						<?php echo JText::_( 'ID' ); ?>: 
					</td>
					<td width="80%" class="right_details">
						<?php echo $this->record->id;?> 
					</td>
				</tr>
				<tr>
					<td width="20%" class="left_title">
						<?php echo JText::_( 'SENDER_NAME' ); ?>:
					</td>
					<td width="80%" class="right_details">
						<?php echo $this->record->actualfrom;?>  
					</td>
				</tr>
				<tr>
					<td width="20%" class="left_title">
						<?php echo JText::_( 'RECEIVER_NAME' ); ?>:
					</td>
					<td width="80%" class="right_details">
						<?php echo $this->record->actualto ? $this->record->actualto : JText::_('MULTIPLE_RECEIVER_USERS'); ?>
					</td>
				</tr> 
				<tr>
					<td width="20%" class="left_title">
						<?php echo JText::_( 'MESSAGE_CONTENTS' ); ?>:
					</td>
					<td width="80%" class="right_details">
						<?php echo $this->record->message;?>  
					</td>
				</tr>
				<tr>
					<td width="20%" class="left_title">
						<?php echo JText::_( 'SENT' ); ?>:
					</td>
					<td width="80%" class="right_details">
						<?php echo date('Y-m-d H:i:s', $this->record->sent);?>  
					</td>
				</tr>
				<tr>
					<td width="20%" class="left_title">
						<?php echo JText::_( 'READ' ); ?>:
					</td>
					<td width="80%" class="right_details">
						<?php 
							if($this->record->actualto):
							$imgRead 	= $this->record->read ? 'icon-16-tick.png' : 'icon-16-publish_x.png';
							$altRead 	= $this->record->read ? JText::_( 'Read' ) : JText::_( 'Unread' ); 
						?>  
						<img src="components/com_jchat/images/<?php echo $imgRead;?>" width="16" height="16" border="0" title="<?php echo $altRead; ?>" alt="<?php echo $altRead; ?>" /> 
						<?php 
							else:
							echo JText::_('JCHAT_ND');
							endif;
						?>
					</td>
				</tr>
				<tr>
					<td width="20%" class="left_title">
						<?php echo JText::_( 'TYPE' ); ?>:
					</td>
					<td width="80%" class="right_details">
						<?php 
							$imgFileDownloaded 	= $this->record->status ? 'icon-16-download-tick.png' : 'icon-16-download-notick.png';
							$altFileDownloaded 	= $this->record->status ? JText::_( 'Downloaded' ) : JText::_( 'Not downloaded' ); 
							echo $this->record->type == 'message' ? JText::_('TIPO_TEXT') : JText::_('TIPO_FILE_DETAILS') . "<img class='inner_spia' src='components/com_jchat/images/$imgFileDownloaded' title='$altFileDownloaded' width='16' height='16' border='0' alt='$altFileDownloaded'/>";
						?>
				 	</td>
				</tr>
			</tbody>
			</table>
		</fieldset>
	</div>
	<div class="clr"></div>
 
	<input type="hidden" name="option" value="<?php echo $this->option;?>" /> 
	<input type="hidden" name="task" value="" /> 
</form>