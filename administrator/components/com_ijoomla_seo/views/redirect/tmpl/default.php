<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined('_JEXEC') or die('Restricted Access');
//JHtml::_('bootstrap.tooltip');
JHTML::_('behavior.modal');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");
$lang_target = array("_blank"=>JText::_("COM_IJOOMLA_SEO_TARGET_BLANK"), 
					"_self"=>JText::_("COM_IJOOMLA_SEO_TARGET_SELF"), 
					"_parent"=>JText::_("COM_IJOOMLA_SEO_TARGET_PARENT"), 
					"_top"=>JText::_("COM_IJOOMLA_SEO_TARGET_TOP"));
$search = JRequest::getVar("search", "");
?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="seo-head-line-title">
		<div class="row-fluid">
        	<div class="span6">
            	<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SEO_REDIRECT"); ?></span>
            </div>
            <div class="span6" style="float:right; text-align: right;">
            	<a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155666">                
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_REDIRECT_VID"); ?>
                </a>
            </div>
        </div>
	</div>
    
    <div class="seo-head-line">
		<div class="row-fluid">
        	<div class="span6">
            	<?php echo JText::_("COM_IJOOMLA_SEO_REDIRECTS_DESCRIPTION"); ?>
            </div>
            <div class="span6" style="float:right;">
    			<table width="100%">					
					<tr>
						<td style="float:right;">							
							<?php 
								echo JText::_("COM_IJOOMLA_SEO_FILTER"); 
							?>
							&nbsp;				
							<input type="text" style="margin-bottom: 0px;" value="<?php echo $search; ?>" name="search" onchange="document.adminForm.submit();" />
							<input type="submit" class="btn" value="Go" />
							<?php 
								echo $this->selectAllCategories(); 
							?>
						</td>
					</tr>					
				</table>
			</div>
		</div>
	</div>
    
	<table class="adminlist" cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr> 
			<th width="20">
                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
            </th>           
        	<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_NAME"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_URL"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_LINKS_TO"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_TARGET"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_HITS"); ?></th>
			<th align="left" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_RESET"); ?></th>
			<th align="center" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_ID"); ?></th>
			<th align="center" nowrap><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY_COLUMN"); ?></th>
		</tr>	
        <?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.redirect'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.redirect'.'.list.limit', 'limit');
		$row_switch = 0;			
		for($i=0; $i<count($this->items); $i++){
			$item=$this->items[$i];
			$url = 'index.php?option=com_ijoomla_seo&id='.$item->id;
			$path = JURI::root();
			$test_url = $url."&nbsp;&nbsp;(".'<a href="'.$path.('index.php?option=com_ijoomla_seo&controller=redirect&task=testredirect&id='.$item->id).'" target="_blank">'.JText::_("COM_IJOOMLA_SEO_TEST_URL").')</a>';
			$links_to = strlen($item->links_to) > 30 ? substr($item->links_to, 0, 30)."..." : $item->links_to;		
		?>
			<tr class="row<?php echo $row_switch; ?>">
				<td><?php echo  $checked = JHTML::_('grid.id', $i, $item->id);  ?></td>
				<td>
					<a href="index.php?option=com_ijoomla_seo&controller=newredirect&task=edit&id=<?php echo $item->id; ?>"><?php echo $item->name; ?></a>
				</td>
				<td><?php echo $test_url; ?></td>
				<td><?php echo $links_to; ?></td>
				<td><?php echo $lang_target[$item->target]; ?></td>
				<td align="center"><?php echo $item->hits; ?></td>
				<td><?php echo $item->last_hit_reset; ?></td>
				<td align="center">{ijseo_redirect id=<?php echo $item->id; ?>}</td>
				<td><?php echo $item->cat_name; ?></td>
			</tr>
		<?php
			$row_switch = 1 - $row_switch;
		}
		?>
		<tfoot>
            <tr>
                <td colspan="9">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
		</tfoot>		       
	</table>	
	
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="redirect" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>