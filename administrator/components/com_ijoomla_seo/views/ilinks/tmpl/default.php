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
            	<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINKS_MANAGER"); ?></span>
            </div>
            <div class="span6" style="float:right; text-align: right;">
            	<a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155445">                
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_VID"); ?>
                </a>
            </div>
        </div>
	</div>
    
    <div class="seo-head-line">
		<div class="row-fluid">
        	<div class="span6">
            	<?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_DESCRIPTION"); ?>
            </div>
            <div class="span6" style="float:right;">
    			<table width="100%">					
					<tr>
						<td style="float:right;">							
							<?php 
								echo JText::_("COM_IJOOMLA_SEO_FILTER"); 
							?>
							&nbsp;				
							<input style="margin-bottom: 0px !important;" type="text" value="<?php echo $search; ?>" name="search" onchange="document.adminForm.submit();" />
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
			<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" /></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_INTERNAL_LINK"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_PUBLISHED"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_TYPE"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_LOCATIN"); ?></th>
            <th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_OPEN_IN"); ?></th>
			<th align="left"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY_COLUMN"); ?></th>		
		</tr>	
        <?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.ilinks'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.ilinks'.'.list.limit', 'limit');
		$row_switch = 0;
		$host = JURI::root();		
		for($i=0; $i<count($this->items); $i++){
			$item=$this->items[$i];
			$type = "";
			
			switch ($item->type){
            	case 1:
                	$type = JText::_("COM_IJOOMLA_SEO_ARTICLE");
					$link_location = $host."index.php?option=com_content&view=article&id=".$item->articleId;
                    $location = $item->location;
					break;
                case 2:
                    $type = JText::_("COM_IJOOMLA_SEO_MENU");					
					$link_location = $this->getLocation($item->loc_id);
					if(strpos($link_location, "http://")== false && strpos($link_location, "www.") == false){			 	
						$link_location = $host.$link_location;
					}	
					$location = $item->location;					
                    break;
                case 3:
                   	$type = JText::_("COM_IJOOMLA_SEO_EXTERNAL_URL");
					$link_location = $item->location2;
                    $location = $item->location2;
                    break;
				case 4:
                   	$type = "#(no link)";
					$link_location = "";
                    $location = "";
                    break;
            }
			$url = '<a href="'.$link_location.'" target="_blank">'.$location.'</a>';			
		?>
			<tr class="row<?php echo $row_switch; ?>">
                <td><?php echo  $checked = JHTML::_('grid.id', $i, $item->id);  ?></td>
                <td><a href="index.php?option=com_ijoomla_seo&controller=newilinks&id=<?php echo $item->id; ?>"><?php echo $item->name; ?></a></td>
                <td><?php echo $published = JHTML::_('grid.published', $item->published, $i); ?></td>
                <td><?php echo $type ?></td>
                <td><?php echo $url ?></td>
                <td><?php echo ($item->target==1) ? JText::_("COM_IJOOMLA_SEO_TARGET_SAME") : JText::_("COM_IJOOMLA_SEO_TARGET_BLANK"); ?></td>
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
	<input type="hidden" name="controller" value="ilinks" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
</form>