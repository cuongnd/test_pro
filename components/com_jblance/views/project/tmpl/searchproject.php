<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 March 2012
 * @file name	:	views/project/tmpl/searchproject.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Search projects (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.tooltip');
 
 $app  	=& JFactory::getApplication();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 
 $keyword	  = $app->input->get('keyword', '', 'string');
 $phrase	  = $app->input->get('phrase', 'any', 'string');
 $id_categ	  = $app->input->get('id_categ', array(), 'array');
 $min_budget  = $app->input->get('min_bud', '', 'string');
 $max_budget  = $app->input->get('max_bud', '', 'string');
 $status	  = $app->input->get('status', '', 'string');
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycode = $config->currencyCode;
 $dformat = $config->dateFormat;
 $action = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
?>
<script type="text/javascript">
<!--
	function checkUncheck(obj, type){
		$$('.'+type+'-parent-'+obj.alt).each(function(el){
			el.set('checked', obj.checked);
			checkUncheck(el, type);
		});
	}
//-->
</script>
<form action="<?php echo $action; ?>" method="get" name="userFormJob" enctype="multipart/form-data">
	<div>
	<table width="100%">
		<tr>
			<td colspan="2" align="center"><span class="fontt14"><?php echo JText::_('COM_JBLANCE_KEYWORDS'); ?></span>&nbsp;&nbsp;
				<?php $tipMsg = JText::_('COM_JBLANCE_KEYWORDS').'::'.JText::_('COM_JBLANCE_SEARCH_KEYWORD_TIPS'); ?>
				<input type="text" name="keyword" id="keyword" value="<?php echo $keyword; ?>" class="inputbox hasTip searchbox" title="<?php echo $tipMsg; ?>"/>&nbsp;&nbsp;
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SEARCH'); ?>" class="button" />
				<div class="sp10">&nbsp;</div>
				<?php $list_phrase = $select->getRadioSearchPhrase('phrase', $phrase);	   					   		
				 echo $list_phrase; ?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="lineseparator"></div></td>
		</tr>
		<tr>
			<td class="sidesearch" valign="top">
				<div class="searchcontainer shadow_top jb-aligncenter">
					<dt class="font14"><?php echo JText::_('COM_JBLANCE_PROJECT_STATUS'); ?></dt>
					<?php $list_status = $select->getSelectProjectStatus('status', $status, 'COM_JBLANCE_ANY', '', '');	   					   		
					 echo $list_status; ?>
				</div>
				<div class="lineseparator"></div>
				<div class="searchcontainer shadow_top">
					<dt class="font14"><?php echo JText::_('COM_JBLANCE_CATEGORIES'); ?></dt>
					<?php $list_categ = $select->getCheckCategory($id_categ);	   					   		
					 echo $list_categ; ?>
				</div>
				<div class="lineseparator"></div>
				<div class="searchcontainer shadow_top">
						<dt class="font14"><?php echo JText::_('COM_JBLANCE_BUDGET'); ?></dt>
						<?php echo JText::_('COM_JBLANCE_MINIMUM'); ?>:
						<?php echo $currencysym;?> <input type="text" name="min_bud" id="min_bud" size="10" value="<?php echo $min_budget; ?>" class="inputbox"/><div class="sp10">&nbsp;</div>
						<?php echo JText::_('COM_JBLANCE_MAXIMUM'); ?>:
						<?php echo $currencysym;?> <input type="text" name="max_bud" id="max_bud" size="10" value="<?php echo $max_budget; ?>" class="inputbox"/><div class="sp10">&nbsp;</div>
					</div>
					<div class="lineseparator"></div>
					<div class="jb-aligncenter shadow_btm">
						<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SEARCH'); ?>" class="button" />
						<div class="sp20">&nbsp;</div>
					</div>
			</td>
			<td class="searchcontent" valign="top">
				<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_SEARCH_RESULTS'); ?></div>
				<table width="100%" cellpadding="0" cellspacing="0" class="border">
					<thead>
						<tr class="jbl_rowhead">
							<th>#</th>
							<th ><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_BIDS'); ?></th>
							<th nowrap><?php echo JText::sprintf('COM_JBLANCE_AVG_CCY', $currencycode); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
							<th><?php echo JText::_('COM_JBLANCE_STARTED'); ?></th>
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
						$buyer = JFactory::getUser($row->publisher_userid);
						$daydiff = $row->daydiff;
						
						if($daydiff == -1){
							$startdate = JText::_('COM_JBLANCE_YESTERDAY');
						}
						elseif($daydiff == 0){
							$startdate = JText::_('COM_JBLANCE_TODAY');
						}
						else {
							$startdate =  JHTML::_('date', $row->start_date, $dformat, true);
						}
						
						
						$link_proj_detail	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id);
						$bidsCount = $model->countBids($row->id);
						?>
						<tr class="jbl_row<?php echo $k; ?>">
							<td><?php echo $this->pageNav->getRowOffset($i); ?></td>
					  		<td>
					  			<a href="<?php echo $link_proj_detail; ?>"><strong><?php echo $row->project_title;?></strong></a>
					  			<div class="fr">
						  			<?php if($row->is_featured) : ?>
						  			<img src="components/com_jblance/images/featured.png" alt="Featured" width="24" class="" title="<?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT'); ?>" />
						  			<?php endif; ?>
						  			<?php if($row->is_urgent) : ?>
						  			<img src="components/com_jblance/images/urgent.png" alt="Urgent" width="24" class="" title="<?php echo JText::_('COM_JBLANCE_URGENT_PROJECT'); ?>" />
						  			<?php endif; ?>
						  			<?php if($row->is_private) : ?>
						  			<img src="components/com_jblance/images/private.png" alt="Private" width="24" class="" title="<?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT'); ?>" />
						  			<?php endif; ?>
						  			<?php if($row->is_nda) : ?>
						  			<img src="components/com_jblance/images/nda.png" alt="NDA" width="24" class="" title="<?php echo JText::_('COM_JBLANCE_NDA_PROJECT'); ?>" />
						  			<?php endif; ?>
					  			</div>
					  		</td>
							<td class="jb-aligncenter">
							<?php if($row->is_sealed) : ?>
				  				<img src="components/com_jblance/images/sealed.png" alt="Sealed" width="24" class="" title="<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>" />
				  			<?php else : ?>
				  				<?php echo $bidsCount; ?>
				  			<?php endif; ?>
							</td>
							<td class="jb-aligncenter">
								<?php
								$projHelper = JblanceHelper::get('helper.project');		// create an instance of the class ProjectHelper
								$avg = $projHelper->averageBidAmt($row->id);
								$avg = round($avg, 0); ?>
							<?php if($row->is_sealed) : ?>
				  				-
				  			<?php else : ?>
				  				<?php echo $currencysym.$avg; ?>
				  			<?php endif; ?>
							</td>
							<td><?php echo JText::_($row->status);?></td>
							<td nowrap class="jb-aligncenter"><?php echo $startdate;?></td>
					  </tr>
						<?php 
						$k = 1 - $k;
					}
					?>
					</tbody>
				</table>
				<?php 
				$link_rss = JRoute::_('index.php?option=com_jblance&view=project&format=feed');
				$rssvisible = (!$config->showRss) ? 'style=display:none' : '';
				?>
				<div class="jbrss" <?php echo $rssvisible; ?>>
					<div id="showrss" class="fr">
						<a href="<?php echo $link_rss; ?>" target="_blank">
							<img src="components/com_jblance/images/rss.png" alt="RSS" title="<?php echo JText::_('COM_JBLANCE_RSS_IMG_ALT'); ?>">
						</a>
					</div>
				</div>
			</td>
		</tr>
	</table>
	</div>
	<input type="hidden" name="option" value="com_jblance" />
	<input type="hidden" name="view" value="project" />
	<input type="hidden" name="layout" value="searchproject" />
	<input type="hidden" name="task" value="" />
</form>