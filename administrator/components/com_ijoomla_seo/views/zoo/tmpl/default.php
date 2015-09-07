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
JHtml::_('behavior.modal');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");
include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."meta.php");

$item=$this->items;

$ijseo_type_key = $this->params->ijseo_type_key;
$ijseo_allow_no = $this->params->ijseo_allow_no;

$ijseo_type_title = $this->params->ijseo_type_title;
$ijseo_allow_no2 = $this->params->ijseo_allow_no2;

$ijseo_type_desc = $this->params->ijseo_type_desc;
$ijseo_allow_desc = $this->params->ijseo_allow_no_desc;

$meta = new Meta();

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$app = JFactory::getApplication('administrator');
$limistart = $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
$limit = $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');

?>

<script type="text/javascript">
window.addEvent('domready', function () {
	if (document.getElementById('zoo').value == 0) {
	  document.getElementById('zoo').value = 1;
	  document.adminForm.submit();
	}
});
Joomla.submitbutton = function (task){
	var selItems = document.getElementsByName('cid[]'), num = selItems.length;
	if(task == "apply" || task == "save"){
		var form=document.adminForm;
		var i = 0;
		while(eval(document.getElementById("cb"+i))){
			document.getElementById("cb"+i).checked = true;
			i++;
		}
		form.task.value=task;
	}else if (task == 'copy_title_key') {
        if(num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true){
                    itemName = "metatitle"+selItems[i].value;
                    itemName1 = "metakey"+selItems[i].value;
                    source = document.getElementById(itemName).value;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countKey(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }
        }	
        return false;
    } else if (task == 'copy_key_title') {
        if (num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {
                    itemName = "metakey"+selItems[i].value;
                    itemName1 = "metatitle"+selItems[i].value;
                    source = document.getElementById(itemName).value;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countTitle(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }        
        }
        return false;
    } else if (task == 'copy_article_key') {
        if (num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {
                    itemName = "name" + i;
                    itemName1 = "metakey" + selItems[i].value;
                    source = document.getElementById(itemName).innerHTML;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countKey(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }
        }
        return false;
    } else if (task == 'copy_article_title') {
        if (num) {
            for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {
                    itemName = "name"+i;
                    itemName1 = "metatitle"+selItems[i].value;
                    source = document.getElementById(itemName).innerHTML;
                    source=source.replace("&amp;","&");
                    dest = document.getElementById(itemName1);
                    //replaces all matches(spaces,blank lines) with the string ''
                    dest.value = source.replace(/^\s*|\s*$/g,'');
                    countTitle(dest, dest.value, selItems[i].value, 'Characters', '200');
                }
            }
        }
        return false;
    }
	submitform(task);
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="seo-head-line-title">
		<div class="row-fluid">
        	<div class="span6">
            	<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SEO_METATAGS"); ?></span>
            </div>
            <div class="span6" style="float:right; text-align: right;">
            	<a class="modal seo_video_meta"  rel="{handler: 'iframe', size: {x: 740, y: 425}}" style="font-size: 16px;" target="_blank"
                        href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=28774680">
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_WHY_NOT_SHOW"); ?>
                </a>
				&nbsp;&nbsp;
				<span style="color:#FF0000; font-size:16px;"><?php echo JText::_('COM_IJOOMLA_SEO_MUST_WATCH'); ?></span>
            </div>
        </div>
	</div>

    <div class="seo-head-line">
		<div class="row-fluid">
        	<div class="span6">
            	<?php echo JText::_("COM_IJOOMLA_SEO_METATAGS_DESCRIPTION"); ?>
            </div>
            <div class="span6" style="float:right;">
            	<table width="100%">
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td style="float:right;">
										<div style="float:left;">
                                        	<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SELECTITEMS_TO_EDIT"); ?></span>
											<img alt="arrow" src="components/com_ijoomla_seo/images/redarrow.png" style="vertical-align:middle;">
                                        </div>
										<?php echo $meta->getList(); ?>
										<?php echo $meta->createOptions(); ?>
									</td>
								</tr>
							</table>		
						</td>
					</tr>
					<tr>
						<td style="float:right;">
							<?php
							echo $this->createCriterias();
							?>
						</td>
					</tr>
					<tr>
						<td  style="float:right;" colspan="2">
							<?php 
								if (JRequest::getVar('zoo') == '1') {
									echo "<select name='itemcats_zoo' onchange='document.adminForm.submit();'>
										<option value='0'>--- " . JText::_('COM_IJOOMLA_SEO_MTREE_SELECT_CATEGORY') . " ---</option>
									";
									foreach ($this->categs as $categ) {
										if (JRequest::getVar('itemcats_zoo') == $categ->id) { $selected = " selected='selected' "; }
										else { $selected = NULL; }
										echo "<option value='{$categ->id}' {$selected}>{$categ->name}</option>";
									}
									echo "</select>";
								} 
							?>
                            &nbsp;
							<?php
								 $search = JRequest::getVar("search", "");
							?>
							<?php echo JText::_("COM_IJOOMLA_SEO_FILTER"); ?>: <input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" style="margin-bottom:0px;" />&nbsp;<input type="submit" class="btn" name="submit_search" value="<?php echo JText::_("COM_IJOOMLA_SEO_GO"); ?>" />
						</td>
					</tr>
				</table>
            </div>
        </div>
	</div>
    
    <a href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155551"
        class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}">
        <img src="components/com_ijoomla_seo/images/icon_video.gif">
        <?php echo JText::_('COM_IJOOMLA_SEO_HOWTO_METATAGS_VIDEO'); ?>
    </a>
    </p>
			
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="adminlist">
		<tr>
			<th align="center"><?php echo JText::_('#'); ?></th>
			<th align="center"><input type="checkbox" name="toggle" id="toggle" value="" onclick="Joomla.checkAll(this);"/></th>
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_ITEM_TITLE") ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_VIEW'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_TITLE_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_KEYWORDS_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_DESCRIPTIONS_METATAG'); ?></th>			
		</tr>
		<?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');
		$k = $limistart+1;
		$zoo_type = JRequest::getVar("zoo", "0");
				
		for($i=0;$i<count($this->items);$i++){			
			$item=$this->items[$i];
			
			$element_name = "";
			$element_id = "";
			$metakey = "";
			$metadesc = "";
			
			if($zoo_type == "1"){
				$element_name = $item->name;
				$element_id = $item->id;
				
				$params = $item->params;
				$params = json_decode($params, true);
				
				$page_title = $params["metadata.title"];
				$metakey = $params["metadata.keywords"];
				$metadesc = $params["metadata.description"];
			}
			elseif($zoo_type == "2"){
				$element_name = $item->name;
				$element_id = $item->id;
				
				$params = $item->params;
				$params = json_decode($params, true);
				
				$page_title = $params["metadata.title"];
				$metakey = $params["metadata.keywords"];
				$metadesc = $params["metadata.description"];
			}
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $element_id); ?>
				</td>				
				<td id="name<?php echo $k-1; ?>">
					<?php echo $element_name; ?>
				</td>
				<td>
                	<?php
						$link = "";
                    	if($zoo_type == "1"){
							$link = JURI::root()."index.php?option=com_zoo&task=item&item_id=".$element_id;
						}
						else{
							$link = JURI::root()."index.php?option=com_zoo&view=category&layout=category&item_id=".$element_id;
						}
					?>
					<a href="<?php echo $link; ?>&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 640, y: 480}}"><?php echo "view"; ?></a>
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_title == "Words"){
						$var = explode(' ', trim(@$page_title));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_no2 - $num;
					}
					else{
						$var = strlen($page_title);
						if(isset($ijseo_allow_no2)){
							$do = $ijseo_allow_no2 - $var;
						}	
				 	}
					?>					
					<textarea id="<?php echo "metatitle".$element_id; ?>" name="page_title[<?php echo $element_id; ?>]" onkeyup="javascript: countTitle(this, this.value, '<?php echo $element_id; ?>', '<?php echo $ijseo_type_title; ?>', '<?php echo $ijseo_allow_no2; ?>');" rows="4"><?php echo $page_title; ?></textarea>
					<span id="go_<?php echo $element_id; ?>" name="go_<?php echo $element_id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$element_id.', "go", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$element_id.', "go", "#666666") </script>';
					}	
					if(!empty($page_title)){
						echo '<script type="text/javascript">
								unColor("metatitle['.$element_id.']");
							</script>';
					}				
					?>				
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_key=="Words"){
						$var = explode(' ', trim($metakey));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$no = $ijseo_allow_no - $num;
					}
					else{
						$var = strlen(trim($metakey));						
						if(isset($ijseo_allow_no)){
							$no = $ijseo_allow_no - $var;
						}
				 	}
					?>
					<textarea id="<?php echo "metakey".$element_id; ?>" name="metakey[<?php echo $element_id; ?>]" onkeyup="javascript: countKey(this, this.value, '<?php echo $element_id; ?>', '<?php echo $ijseo_type_key; ?>', '<?php echo $ijseo_allow_no; ?>');" rows="4"><?php echo $metakey; ?></textarea>
					<span id="no_<?php echo $element_id; ?>" name="no_<?php echo $element_id; ?>"><?php if(isset($no)) echo $no; ?></span>
					<?php
					if(isset($no) &&($no < 0)){
						echo '<script type="text/javascript"> changeColor('.$element_id.', "no", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$element_id.', "no", "#666666") </script>';
					}	
					if(!empty($metakey)){
						echo '<script type="text/javascript">
								unColor("metakey['.$element_id.']");
							</script>';
					}				
					?>
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_desc=="Words"){
						$var = explode(' ', trim($metadesc));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_desc - $num;
					}
					else{
						$var = strlen($metadesc);
						if(isset($ijseo_allow_desc)){
							$do = $ijseo_allow_desc - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metadesc".$element_id; ?>" name="metadesc[<?php echo $element_id; ?>]" onkeyup="javascript: countDesc(this, this.value, '<?php echo $element_id; ?>', '<?php echo $ijseo_type_desc; ?>', '<?php echo $ijseo_allow_desc; ?>');" rows="4"><?php echo $metadesc; ?></textarea>
					<span id="do_<?php echo $element_id; ?>" name="do_<?php echo $element_id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$element_id.', "do", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$element_id.', "do", "#666666") </script>';
					}	
					if(!empty($metadesc)){
						echo '<script type="text/javascript">
								unColor("metadesc['.$element_id.']");
							</script>';
					}				
					?>					
				</td>
			</tr>			
		<?php
			$k++;
		}
		?>
		<tfoot>
            <tr>
                <td colspan="7">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
		</tfoot>
	</table>
	
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="zoo" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>