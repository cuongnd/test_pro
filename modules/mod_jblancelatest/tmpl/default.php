<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	29 March 2012
 * @file name	:	modules/mod_jblancelatest/tmpl/default.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 // no direct access
 defined('_JEXEC') or die('Restricted access');
 $show_logo = intval($params->get('show_logo', 1));
 $set_Itemid	= intval($params->get('set_itemid', 0));
 $Itemid = ($set_Itemid > 0) ? '&Itemid='.$set_Itemid : '';

 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $currencycod = $config->currencyCode;
 $dformat = $config->dateFormat;

 $document = & JFactory::getDocument();
 $document->addStyleSheet("components/com_jblance/css/$config->theme");
 $document->addStyleSheet("components/com_jblance/css/style.css");
 $document->addStyleSheet("modules/mod_jblancecategory/css/style.css");

 $link_listproject = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject'.$Itemid); 

 $lang =& JFactory::getLanguage();
 $lang->load('com_jblance', JPATH_SITE);
?>
<table width="100%" cellpadding="0" cellspacing="0" class="border">
	<thead>
		<tr class="jbl_rowhead">
			<th><?php echo JText::_('MOD_JBLANCE_PROJECT_NAME'); ?></th>
			<?php if($show_categ == 1){?><th><?php echo JText::_('MOD_JBLANCE_SKILLS_REQUIRED'); ?></td><?php } ?> 
			<?php if($show_bid == 1){?><th><?php echo JText::_('MOD_JBLANCE_BIDS'); ?></th><?php } ?>
			<?php if($show_avgbid == 1){?><th><?php echo JText::sprintf('MOD_JBLANCE_AVG_BIDS', $currencycod); ?></th><?php } ?>
			<?php if($show_startdate == 1){?><th><?php echo JText::_('MOD_JBLANCE_STARTED'); ?></th><?php } ?>
			<?php if($show_enddate ==1){?><th><?php echo JText::_('MOD_JBLANCE_ENDS'); ?></th><?php } ?>
			<?php if($show_budget == 1){?><th nowrap="nowrap"><?php echo JText::_('MOD_JBLANCE_BUDGET').' ('.$currencycod.')'; ?></th><?php } ?>
			<?php if($show_publisher == 1){?><th align="center" colspan="2" ><?php echo JText::_('MOD_JBLANCE_PUBLISHER'); ?></th><?php } ?>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	for ($i=0, $x=count($rows); $i < $x; $i++){
		$row = $rows[$i];
		$buyer =& JFactory::getUser($row->publisher_userid);
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
		
		$expiredate = JFactory::getDate($row->start_date);
		$expiredate->modify("+$row->expires days");
		
		$link_proj_detail	= JRoute::_( 'index.php?option=com_jblance&view=project&layout=detailproject&id='.$row->id.$Itemid); 
		?>
		<tr class="jbl_row<?php echo $k; ?>">
	  		<td>
	  			<a href="<?php echo $link_proj_detail; ?>"><strong><?php echo $row->project_title; ?></strong></a>
	  			<div class="fr">
		  			<?php if($row->is_featured) : ?>
		  			<img src="components/com_jblance/images/featured.png" alt="Featured" width="20" class="" title="<?php echo JText::_('MOD_JBLANCE_FEATURED_PROJECT'); ?>" />
		  			<?php endif; ?>
		  			<?php if($row->is_urgent) : ?>
		  			<img src="components/com_jblance/images/urgent.png" alt="Urgent" width="20" class="" title="<?php echo JText::_('MOD_JBLANCE_URGENT_PROJECT'); ?>" />
		  			<?php endif; ?>
		  			<?php if($row->is_private) : ?>
		  			<img src="components/com_jblance/images/private.png" alt="Private" width="20" class="" title="<?php echo JText::_('MOD_JBLANCE_PRIVATE_PROJECT'); ?>" />
		  			<?php endif; ?>
		  			<?php if($row->is_nda) : ?>
		  			<img src="components/com_jblance/images/nda.png" alt="NDA" width="20" class="" title="<?php echo JText::_('MOD_JBLANCE_NDA_PROJECT'); ?>" />
		  			<?php endif; ?>
	  			</div>
	  		</td>
			<?php if($show_categ == 1){?><td><?php echo $row->categories; ?></td><?php } ?>
			<?php if($show_bid == 1){?>
			<td class="jb-aligncenter">
			<?php if($row->is_sealed) : ?>
  				<img src="components/com_jblance/images/sealed.png" alt="Sealed" width="20" class="" title="<?php echo JText::_('MOD_JBLANCE_SEALED_PROJECT'); ?>" />
  			<?php else : ?>
  				<?php echo $row->bids; ?>
  			<?php endif; ?>
			</td>
			<?php } ?>
			<?php if($show_avgbid == 1){?>
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
			<?php } ?>
			<?php if($show_startdate == 1){?><td class="jb-aligncenter"><?php echo $startdate; ?></td><?php } ?>
			<?php if($show_enddate == 1){?><td class="jb-aligncenter"><?php echo JblanceHelper::showRemainingDHM($expiredate, 'SHORT'); ?></td><?php } ?>
	 		<?php if($show_budget == 1){?><td class="jb-aligncenter"><?php echo $currencysym.number_format($row->budgetmin); ?> - <?php echo $currencysym.number_format($row->budgetmax); ?></td><?php } ?>
			<?php if($show_publisher == 1){?>
			<td>
				<?php
				$attrib = 'width=25 height=25';
				echo JblanceHelper::getThumbnail($row->publisher_userid, $attrib); ?>
			</td>
			<td>
				<?php echo LinkHelper::GetProfileLink($row->publisher_userid, $buyer->username); ?>
			 </td>
			 <?php } ?>
	  </tr>
		<?php 
		$k = 1 - $k;
	}
	?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="9" class="jbl_row3" align="center">
				<a href="<?php echo $link_listproject; ?>" class=""><?php echo JText::_('MOD_JBLANCE_MORE_PROJECTS'); ?></a><br>
			</td>
		</tr>
	</tfoot>
</table>
