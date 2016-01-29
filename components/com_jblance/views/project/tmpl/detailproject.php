<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	26 March 2012
 * @file name	:	views/project/tmpl/detailproject.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows details of the project (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');

 $row = $this->row;
 $model = $this->getModel();
 $user =& JFactory::getUser();
 $uri 	= JFactory::getURI();
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycode = $config->currencyCode;
 $dformat = $config->dateFormat;
 $enableReporting = $config->enableReporting;
 $guestReporting = $config->enableGuestReporting;
 $enableAddThis = $config->enableAddThis;
 $addThisPubid = $config->addThisPubid;
 
 $projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
 $hasJBProfile = JblanceHelper::hasJBProfile($user->id);
 
 if($hasJBProfile){
 	$jbuser = JblanceHelper::get('helper.user');
 	$userGroup = $jbuser->getUserGroupInfo($user->id, null);
 }
 
 $isMine = ($row->publisher_userid == $user->id);
 
 $link_report 		= JRoute::_('index.php?option=com_jblance&view=message&layout=report&id='.$row->id.'&report=project&link='.base64_encode($uri)/* .'&tmpl=component' */);
 $link_edit_project = JRoute::_('index.php?option=com_jblance&view=project&layout=editproject&id='.$row->id); 
 $link_pick_user	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=pickuser&id='.$row->id);
 JText::script('COM_JBLANCE_CLOSE');
?>
<script type="text/javascript">
	window.addEvent('domready',function() {
		new Fx.SmoothScroll({
			duration: 500
			}, window);
	});

	window.addEvent('domready', function(){
		$('commentForm').addEvent('submit', function(e){
		e.stop();
		var req = new Request.HTML({
			url: 'index.php?option=com_jblance&task=project.submitforum',
			data: $('commentForm'),
			onRequest: function(){ $('btnSendMessage').set({'disabled': true, 'value': '<?php echo JText::_('COM_JBLANCE_SENDING'); ?>'}); },
			onSuccess: function(tree, response){
				
				var li = new Element('li');
				var span = new Element('span', {'text': response[1].get('text')}).inject(li);
				var span1 = new Element('span', {'text': '<?php echo JText::_('COM_JBLANCE_RECENTLY'); ?>', 'class':'fr'}).inject(span);
				var p = new Element('p', {'text': response[2].get('text')}).inject(li);
				li.inject($('commentList')).highlight('#EEE');
				$('commentForm').reset();
				$('btnSendMessage').set('value', '<?php echo JText::_('COM_JBLANCE_SENT'); ?>');
				
				//Scrolls the window to the bottom
				var myFx = new Fx.Scroll('commentList').toBottom();
			}
		}).send();
		});
	});
</script>
<!-- <form action="index.php" method="post" name="userForm"> -->
	<div class="jbl_h3title">
		<?php echo $row->project_title.' - '.JText::_('COM_JBLANCE_PROJECT_DETAILS'); ?>
		<?php if($row->is_featured) : ?>
  		<span class="featured" title="<?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT'); ?>"></span>
  		<?php endif; ?>
		<?php if($row->is_urgent) : ?>
  		<span class="urgent" title="<?php echo JText::_('COM_JBLANCE_URGENT_PROJECT'); ?>"></span>
  		<?php endif; ?>
		<?php if($row->is_private) : ?>
  		<span class="private" title="<?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT'); ?>"></span>
  		<?php endif; ?>
		<?php if($row->is_sealed) : ?>
  		<span class="sealed" title="<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>"></span>
  		<?php endif; ?>
		<?php if($row->is_nda) : ?>
  		<span class="nda" title="<?php echo JText::_('COM_JBLANCE_NDA_PROJECT'); ?>"></span>
  		<?php endif; ?>
	</div>
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td colspan="3">
				<div class="page-actions">
					<?php if($enableAddThis) : ?>
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
					<!-- show Edit Project and Pick User only to publisher -->
					<?php if($isMine) : ?>
						<div id="edit-project" class="page-action">
						    <a href="<?php echo $link_edit_project; ?>" class="jbicon-edit"><?php echo JText::_('COM_JBLANCE_EDIT_PROJECT'); ?></a>
						</div>
						<!-- show Pick User if bids>0 and status=open -->
						<?php if($row->status != 'COM_JBLANCE_CLOSED' && count($this->bids) > 0) :?>
							<div id="pick-user" class="page-action">
							    <a href="<?php echo $link_pick_user; ?>" class="jbicon-pick"><?php echo JText::_('COM_JBLANCE_PICK_USER').' ('.count($this->bids).')'; ?></a>
							</div>
						<?php endif; ?>
					<?php else : ?>
						<?php if($enableReporting && ($user->id !=0 || $guestReporting )) : ?>
						<div id="report-this" class="page-action">
						    <a href="<?php echo $link_report; ?>" class="jbicon-report"><?php echo JText::_('COM_JBLANCE_REPORT_PROJECT'); ?></a>
						</div>
						<?php endif; ?>
					<?php endif; ?>
					<!-- <div id="send-message" class="page-action">
					    <a href="<?php //echo $link_sendpm; ?>" class="jbicon-message"><?php echo JText::_('COM_JBLANCE_SEND_MESSAGE'); ?></a>
					</div> -->
				</div>
				<div class="fr">
				<!-- show the bid button only if the status is OPEN -->
				<?php if($row->status == 'COM_JBLANCE_OPEN') : ?>
					<?php $link_place_bid = JRoute::_( 'index.php?option=com_jblance&view=project&layout=placebid&id='.$row->id); ?>
					<a href="<?php echo $link_place_bid; ?>" class="jbbutton"><span><?php echo JText::_('COM_JBLANCE_BID_ON_THIS_PROJECT'); ?></span></a>
				<?php endif; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td width="220px" valign="top">
				<div class="projsummary border">
				<div class="projsummary_ttl"><?php echo JText::_('COM_JBLANCE_PROJECT_SUMMARY'); ?></div>
					<ul>
						<li>
							<b><?php echo JText::_('COM_JBLANCE_PROJECTID'); ?> : <?php echo $row->id; ?></b>
						</li>
						<li>
							<b><?php echo JText::_('COM_JBLANCE_POSTED_BY'); ?> :</b>
							<?php 
							$avatar = JblanceHelper::getThumbnail($row->publisher_userid);
							echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar) : '&nbsp;';	?><br>
							<?php 
							$publisher =& JFactory::getUser($row->publisher_userid); 
							echo LinkHelper::GetProfileLink($row->publisher_userid, $this->escape($publisher->username)); ?>
							<div style="clear:both;"></div>  
							<?php JblanceHelper::getAvarageRate($row->publisher_userid); ?><div style="clear:both;"></div>  
						</li>
						<li>
							<b><?php echo JText::_('COM_JBLANCE_BUDGET'); ?> :</b>
							<?php echo $currencysym.' '.number_format($row->budgetmin); ?> - <?php echo $currencysym.' '.number_format($row->budgetmax).' '.$currencycode; ?>
						</li>
						<li>
							<b><?php echo JText::_('COM_JBLANCE_STARTS_ON'); ?> :</b>
								<?php echo JHTML::_('date', $row->start_date, $dformat, true); ?>
						</li>
						<li>
							<b><?php echo JText::_('COM_JBLANCE_EXPIRES_ON'); ?> :</b>
								<?php 
								$expiredate = JFactory::getDate($row->start_date);
								$expiredate->modify("+$row->expires days");
								echo JblanceHelper::showRemainingDHM($expiredate);
								?>
						</li>
						<li>
							<b><?php echo JText::_('COM_JBLANCE_STATUS'); ?> : <?php echo JText::_($row->status); ?></b>
						</li>
						<li>
							<b>
							<?php echo JText::_('COM_JBLANCE_BIDS'); ?> : 
							<?php if($row->is_sealed) : ?>
				  				<img src="components/com_jblance/images/sealed.png" alt="Sealed" width="20" class="" title="<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>" />
				  			<?php else : ?>
				  				<?php echo count($this->bids); ?>
				  			<?php endif; ?>
				  			</b>
						</li>
						<li>
							<b>
							<?php echo JText::_('COM_JBLANCE_AVG_BID'); ?> : 
							<?php
							$projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
							$avg = $projHelper->averageBidAmt($row->id);
							$avg = round($avg, 0);
							 ?>
							<?php if($row->is_sealed) : ?>
				  				<?php echo JText::_('COM_JBLANCE_NA'); ?>
				  			<?php else : ?>
				  				<?php echo $currencysym.$avg.' '.$currencycode; ?>
				  			<?php endif; ?>
				  			</b>
						</li>
					</ul>
				</div>
			</td>
			<td valign="top">
				<table>
					<tr id="con_long_desc">
						<td colspan="2">
							<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_PROJECT_DESCRIPTION'); ?>:</span><br>
							<div class="border_bg"><?php echo $row->description; ?></div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_SKILLS_REQUIRED'); ?>:</span> 
							<?php echo JblanceHelper::getCategoryNames($row->id_category); ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<?php
						if(count($this->projfiles) > 0) : ?>
							<span class="font16 uline boldfont"><?php echo JText::_('COM_JBLANCE_ADDITIONAL_FILES'); ?>:</span> <img src="components/com_jblance/images/attachment.png" /></a>
						<?php
							foreach($this->projfiles as $projfile){ 
								if($user->guest){
									echo $projfile->show_name.', ';
								} 
								else {
								?>
								<a href="<?php echo JBPROJECT_URL.$projfile->file_name; ?>" target="_blank"><?php echo $projfile->show_name; ?></a>, 
							<?php
								}	
							}
						endif;
						?>
						</td>
					</tr>
					<!-- display custom fields -->
					
					<tr>
						<td colspan="2">
						<?php if($this->fields) : ?>
						<?php 
						$fields = JblanceHelper::get('helper.fields');		// create an instance of the class FieldsHelper
						$parents = $children = array();
						//isolate parent and childr
						foreach($this->fields as $ct){
							if($ct->parent == 0)
								$parents[] = $ct;
							else
								$children[] = $ct;
						}
						
						if(count($parents)){
							foreach($parents as $pt){ ?>
							<span class="font16 uline boldfont"><?php echo JText::_($pt->field_title); ?>:</span>
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
										<?php $fields->getFieldHTMLValues($ct, $row->id, 'project'); ?>
									</td>
								</tr>
								<?php
									}
								} ?>
							</table>
						<?php
							}
						}
						?>
						<?php endif; ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div class="lineseparator"></div>
				<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PUBLIC_CLARIFICATION_BOARD'); ?></div>
				<span style="font-style:italic;"><?php echo JText::sprintf('COM_JBLANCE_X_MESSAGES', count($this->forums)); ?></span>
				<div class="fr"><a href="#addmessage_bm" class="jbbutton"><span><?php echo JText::_('COM_JBLANCE_ADD_MESSAGE'); ?></span></a></div>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="comments">
					<ul id="commentList" style="max-height: 400px; overflow: auto;">
					<?php 
					for($i=0, $x=count($this->forums); $i < $x; $i++){
						$forum = $this->forums[$i];
						$poster = JFactory::getUser($forum->user_id)->username;
						$postDate = JFactory::getDate($forum->date_post);
					?>
						<li>
			        		<span><?php echo LinkHelper::GetProfileLink($forum->user_id, $poster); ?>
				        		<span class="fr">
				        		<?php echo JblanceHelper::showTimePastDHM($postDate, 'SHORT'); ?>
								</span>
							</span>
			        		<p><?php echo $forum->message; ?></p>
			      		</li>
			      	<?php 
					}
			      	?>
			    	</ul>
			    	<form id="commentForm" method="post" action="index.php">
			    		<a name="addmessage_bm" id="addmessage_bm"></a>
			    		<!-- show the forum add message only for bidder and publisher -->
						<?php 
						$hasBid = $projHelper->hasBid($row->id, $user->id);
						if(($user->id == $row->publisher_userid) || $hasBid) :
						?>
				    	<div class="addMessage">
					        <textarea id="message" name="message" rows="3" style="width:100%"></textarea><br>
					        <input type="submit" value="<?php echo JText::_('COM_JBLANCE_POST_MESSAGE'); ?>" id="btnSendMessage" class="fr" />
					        <div><?php echo JText::_('COM_JBLANCE_SHARING_CONTACT_PROHIBITED'); ?></div>
					        <input type="hidden" name="project_id" value="<?php echo $row->id; ?>" />
					        <input type="hidden" name="user_id" value="<?php echo $user->id; ?>" />
						</div>
						<?php 
						else : ?>
						<span class="redfont"><?php echo JText::_('COM_JBLANCE_MUST_BID_TO_POST_MESSAGES'); ?><span>
						<?php	
						endif;
						?>
					</form>
				</div>
				<div class="lineseparator"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_ALL_BIDS'); ?></div></td>
		</tr>
		<tr>
			<td colspan="3">
				<!-- if the project is sealed and the user is not the publisher, then hide the bid details of the project -->
				<?php 
				//check if the user has bid
				$hasBid = $projHelper->hasBid($row->id, $user->id);
				?>
				<?php if($row->is_sealed && ($user->id != $row->publisher_userid) && !$hasBid) : ?>
					<div class="jb-aligncenter redfont"><?php echo JText::_('COM_JBLANCE_SEALED_PROJECT_PULBISHER_AND_BIDDERS_SEE_DETAILS'); ?></div>
				<?php else : ?>
				<table width="100%" cellpadding="0" cellspacing="0" class="border">
					<thead>
						<tr class="jbl_rowhead">
							<th colspan="2"><?php echo JText::_('COM_JBLANCE_FREELANCERS'); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_BIDS').' ('.$currencycode.')'; ?></th>
							<th><?php echo JText::_('COM_JBLANCE_DELIVERY_DAYS'); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_TIME_OF_BID'); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_RATING'); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$k = 0;
						for($i=0, $n=count($this->bids); $i < $n; $i++){
							$bid = $this->bids[$i];
						?>
						
						<tr class="jbl_row<?php echo $k; ?>">
							<td rowspan="2" class="">
								<?php
								$attrib = 'width=52 height=52';
								$avatar = JblanceHelper::getThumbnail($bid->user_id, $attrib);
								echo !empty($avatar) ? LinkHelper::GetProfileLink($bid->user_id, $avatar) : '&nbsp;'; ?>
							</td>
							<td><?php echo LinkHelper::GetProfileLink(intval($bid->user_id), $this->escape($bid->username)); ?></td>
							<td><?php echo $currencysym.' '.$bid->amount?></td>
							<td><?php echo $bid->delivery?></td>
							<td><?php echo JHTML::_('date', $bid->bid_date, $dformat) ?></td>
							<td>
								<?php
								$rate = JblanceHelper::getAvarageRate($bid->user_id, true);
								?>
							</td>
							<td><?php echo JText::_($bid->status); ?></td>
						</tr>
						<tr class="jbl_row<?php echo $k; ?>">
							<td colspan="7" class=""><b><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?></b> : <br /><em><?php echo ($bid->details) ? $bid->details : JText::_('COM_JBLANCE_DETAILS_NOT_PROVIDED'); ?></em></td>
						</tr>
						<?php 
						$k = 1 - $k;
						} ?>
					</tbody>
				</table>
				<?php endif; ?>
			</td>
		</tr>
	</table>
<!-- </form> -->