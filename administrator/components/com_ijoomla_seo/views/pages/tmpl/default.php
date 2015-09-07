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

function removetrim(&$val){		
	$val = trim($val);	
	return ($val != "");	
}	

?>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="seo-head-line-title">
		<div class="row-fluid">
        	<div class="span6">
            	<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SEO_PAGES"); ?></span>
            </div>
            <div class="span6" style="float:right; text-align: right;">
            	<a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155651">                
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_PAGES_VID"); ?>
                </a>
            </div>
        </div>
	</div>
    
    <div class="seo-head-line">
		<div class="row-fluid">
        	<div class="span5">
            	<?php echo JText::_("COM_IJOOMLA_SEO_PAGES_DESCRIPTION"); ?>
            </div>
            <div class="span7" style="float:right;">
    			<table width="100%">					
					<tr>
						<td style="float:right;">							
							<?php
                            	$filter_catid = JRequest::getVar("filter_catid", "");
							?>
                            <select name="filter_catid" class="inputbox" onchange="this.form.submit()">
								<option value="">-- <?php echo JText::_('COM_IJOOMLA_SEO_SELECT_CATEGORY');?> --</option>
								<?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_content'), 'value', 'text', $filter_catid);?>
							</select>
							<?php
							echo $this->createCriterias();
							?>
						</td>
					</tr>
					<tr>
						<td style="float:right;" colspan="2">
							<?php
                                 $search = JRequest::getVar("search", "");
                            ?>
                            <?php echo JText::_("COM_IJOOMLA_SEO_FILTER"); ?>: <input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" style="margin-bottom:0px;" />&nbsp;<input type="submit" class="btn" value="<?php echo JText::_("COM_IJOOMLA_SEO_GO"); ?>" />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	      
    <table class="adminlist" cellpadding="0" cellspacing="0" border="0" width="100%">            
        <tr class="row1">
            <th width="15">#</th>
            <th><?php echo JText::_("COM_IJOOMLA_SEO_ARTICLES"); ?></th>
            <th width="30px"><?php echo JText::_("COM_IJOOMLA_SEO_EDITMTAGS"); ?></th>			
            <th width="120px"><?php echo JText::_("COM_IJOOMLA_SEO_OUTGOING_LINKS"); ?></th>
            <th><?php echo JText::_("COM_IJOOMLA_SEO_METATAGS_META"); ?></th>
            <th width="50%"><?php echo JText::_("COM_IJOOMLA_SEO_VALUE"); ?></th>
        </tr>
        <?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.pages'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.pages'.'.list.limit', 'limit');
		$k = $limistart+1;
		$z = 0;
		for($i=0; $i<count($this->items); $i++){
			$item=$this->items[$i];
			$attribs = json_decode($item->attribs);							
		?>
        <tr class="<?php echo "row0"; ?>">
        	<td>
            	<?php echo $k;?>
            </td>
            <td>
            	<a href="index.php?option=com_content&task=article.edit&id=<?php echo $item->id ?>" target="_blank">
                <?php echo $item->title ?></a>
            </td>
            <td  align="center">
			<?php					
				$obj = new stdClass();
				$obj->id = $item->id;
				$attribs = json_decode($item->attribs);
								
				$obj->metakey = trim($item->metakey);
				$obj->metadesc = trim($item->metadesc);
				if(isset($attribs->page_title) && trim($attribs->page_title) != ""){
					$obj->titletag = trim($attribs->page_title);
				}
				else{
					$obj->titletag = trim($item->titletag);
				}
				$titletag = $obj->titletag;
				$content = $item->introtext.$item->fulltext;
				
				$obj->metakey = str_replace("&", '*ij*', $obj->metakey);
				$obj->metadesc = str_replace("&", '*ij*', $obj->metadesc);
				$obj->titletag = str_replace("&", '*ij*', $obj->titletag);
            ?>
            <a rel="{handler: 'iframe', size: {x: 600, y: 550}}" href="index.php?option=com_ijoomla_seo&amp;task=edit_page&amp;tmpl=component&amp;controller=pages&amp;id=<?php echo $item->id; ?>&amp;dataOBJ=<?php echo htmlspecialchars(json_encode($obj)); ?>" class="modal" ><?php echo JText::_("COM_IJOMLA_SEO_EDIT"); ?></a>
            </td>
            <td align="center">
			<?php                
                $sitehost = preg_quote($_SERVER['HTTP_HOST']);
                $regx = '#http:[^<\s>]+[.]#i';
                preg_match_all($regx, $content, $links, PREG_PATTERN_ORDER);
                $oLinks = count($links[0]);															
                
                if($oLinks){
                    echo '<a  rel="{handler: \'iframe\', size: {x: 500, y: 250}}" href="index.php?option=com_ijoomla_seo&controller=pages&amp;tmpl=component&amp;task=outLinks&amp;data='.$item->id.'" class="modal">'.$oLinks.'</a>';
                }
                else{
                    echo $oLinks;
                }	
            ?>
            </td>
            <?php
				$keywords = array();
				$attribs = json_decode($item->attribs);				
				$kws1 = explode(",", @$attribs->page_title);				
				$kws2 = explode(",", $item->metakey);					 	
				$kws = array_merge($kws1, $kws2);					
				$keywords = array_keys(array_count_values(array_filter($kws, "removetrim")));								
				$num = count($keywords);
				if($num){
					if($keywords[$num-1] == ''){
						unset($keywords[$num-1]);
						$num--;
					}	
				}
				$out = array();
				if(!empty($keywords)){
					@preg_match_all("/\b$keywords[0]\b/iU", $content, $out, PREG_PATTERN_ORDER);
				}
			?>
            <td width="10%" valign="top">
				<?php echo JText::_("COM_IJOOMLA_SEO_TITLE").":"; ?>
            </td>
            <td>
            	<?php
                	echo '<span style="color:#316200;">'.$titletag.'</span>';
				?>
            </td>
		</tr>
        
        <tr>
        	<td colspan="4"></td>
            <td width="10%" valign="top"><?php echo JText::_("COM_IJOOMLA_SEO_DESCRIPTION").":"; ?></td>
            <td>
            	<?php
                	echo '<span style="color:#351B00;">'.$item->metadesc.'</span>';
				?>
            </td>
        </tr>
        
        <tr>
        	<td colspan="4" style="border-bottom:1px solid #EEEEEE;"></td>
            <td width="10%" style="border-bottom:1px solid #EEEEEE;" valign="top"><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_KEY").":"; ?></td>
            <td style="border-bottom:1px solid #EEEEEE;">
            	<?php
					//show the keyword on new line
				 	$nums = count($keywords);
				 	if($nums > 0){
						for($j=0; $j <= $nums; $j++){
							if(isset($keywords[$j]) && trim($keywords[$j]) != ""){
								preg_match_all("/\b".$keywords[$j]."\b/iU", strip_tags($content), $out, PREG_PATTERN_ORDER);
								
								echo '<span style="color:#6633CC;">'.$keywords[$j].'</span>&nbsp;&nbsp;';
								$total = count($out["0"]);
								if(intval($total) == 0){
									echo '<span style="color:#FF4F24;">'.intval($total).'</span>';
								}
								else{
									echo '<span style="color:#000000;">'.intval($total).'</span>';
								}
								echo '<br/>';
							}
						}//for
					}//if
				?>            
            </td>
        </tr>
		<?php
			$k++;
			$z = 1 - $z;
        }
		?>      
		<tfoot>
            <tr>
                <td colspan="6">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
		</tfoot>
	</table>
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="pages" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>