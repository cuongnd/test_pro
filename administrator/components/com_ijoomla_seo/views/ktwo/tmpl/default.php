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

include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");
include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."meta.php");

$item=$this->items;
$type = "";

if (count($item)) {
	$type = JRequest::getInt('ktwo', 0);
}

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

?>

<script type="text/javascript">
window.addEvent('domready', function () {
	if (document.getElementById('ktwo').value == 0) {
	  document.getElementById('ktwo').value = 1;
	  document.adminForm.submit();
	}
});
Joomla.submitbutton = function (task) {
    var selItems = document.getElementsByName('cid[]'), num = selItems.length;
	if (task == "apply" || task == "save") {
		var form=document.adminForm;
		var i = 0;
		while(eval(document.getElementById("cb"+i))){
			document.getElementById("cb"+i).checked = true;
			i++;
		}
		form.task.value=task;
	} else if (task == 'copy_title_key') {
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
                    //console.log(itemName);
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
<?php if (isset($_GET['choosemain'])) { ?>
function chose_main() {
    var i, len = document.getElementById('menu_types').options.length, found = false;
    for (i=0; i <= len-1; i++) {
        if ((typeof(document.getElementById('menu_types').options[i]) != 'undefined') && 
            (document.getElementById('menu_types').options[i].value == "mainmenu")) {
            
            found = true;
            document.getElementById('menu_types').options[i].selected = true;
        }
    }
    if (!found) {
        for (i=0; i <= len-1; i++) {
            if ((typeof(document.getElementById('menu_types').options[i]) != 'undefined') && 
                (document.getElementById('menu_types').options[i].value)) {

                if (document.getElementById('menu_types').options[i].value) {
                    document.getElementById('menu_types').options[i].selected = true;
                    found = true;
                }
            }
        }
    }
    if (found) { document.adminForm.submit(); }
}
window.addEvent('domready', chose_main);
<?php } ?>
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
								if (JRequest::getVar('ktwo') == '1') {
									echo "<select name='itemcats_k2' onchange='document.adminForm.submit();'>
										<option value='0'>--- " . JText::_('COM_IJOOMLA_SEO_MTREE_SELECT_CATEGORY') . " ---</option>
									";
									foreach ($this->categs as $categ) {
										if (JRequest::getVar('itemcats_k2') == $categ->id) { $selected = " selected='selected' "; }
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
			<th align="center"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" /></th>			
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_TITLE") ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_VIEW'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_TITLE_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_KEYWORDS_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_DESCRIPTIONS_METATAG'); ?></th>			
		</tr>
		<?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.ktwo'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.ktwo'.'.list.limit', 'limit');
		$k = $limistart+1;
		$session_titletag = @$_SESSION["session_titletag"];
		$session_metakey = @$_SESSION["session_metakey"];
		$session_description = @$_SESSION["session_description"];
		
		for($i=0; $i<count($this->items); $i++){
			$item = $this->items[$i];
			
			if($type == "1"){ // items
				$page_title["page_title"] = $item->titletag;
				$page_title["menu-meta_keywords"] = $item->metakey;
				$page_title["menu-meta_description"] = $item->metadesc;
			}
			else{ // categories
				$page_title["page_title"] = $item->titletag;
				$params = $item->params;
				$params = json_decode($params);
				$page_title["menu-meta_keywords"] = $params->catMetaKey;
				$page_title["menu-meta_description"] = $params->catMetaDesc;
			}
			
			if(isset($session_titletag[$item->id])){
				$page_title["page_title"] = $session_titletag[$item->id];
			}
			
			if(isset($session_description[$item->id])){
				$page_title["menu-meta_description"] = $session_description[$item->id];
				unset($_SESSION["session_description"]);
			}
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>				
				<td id="name<?php echo $i; ?>">
					<?php echo $item->title; ?>
				</td>
				<td>
                	<?php
						$part_link = "";
                    	if($type == 1){
							$part_link = JURI::root()."index.php?option=com_k2&view=item&id=".$item->id;
						}
						elseif($type == 2){
							$part_link = JURI::root()."index.php?option=com_k2&view=itemlist&task=category&id=".$item->id;
						}
					?>
					<a href="<?php echo $part_link; ?>&tmpl=component" class="modal" rel="{handler: 'iframe', size: {x: 640, y: 480}}"><?php echo "view"; ?></a>
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_title == "Words"){
						$var = explode(' ', trim($page_title["page_title"]));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_no2 - $num;
					} else {
						if(isset($page_title["page_title"])){
							$var = strlen(utf8_decode($page_title["page_title"]));
						}
						else{
							$var = 0;
						}
						if(isset($ijseo_allow_no2)) {
							$do = $ijseo_allow_no2 - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metatitle".$item->id; ?>" name="page_title[<?php echo $item->id; ?>]" onkeyup="javascript: countTitle(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_title; ?>', '<?php echo $ijseo_allow_no2; ?>');" rows="4"><?php if(isset($page_title["page_title"])){ echo $page_title["page_title"];} ?></textarea>
					<span id="go_<?php echo $item->id; ?>" name="go_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "#666666") </script>';
					}	
					if(!empty($page_title["page_title"])){
						echo '<script type="text/javascript">
								unColor("metatitle['.$item->id.']");
							</script>';
					}				
					?>				
				</td>
				<td valign="top" align="center">
					<?php
					if(isset($session_metakey[$item->id])){
						$page_title["menu-meta_keywords"] = $session_metakey[$item->id];
					}
					
					if ($ijseo_type_key=="Words"){
						$var = explode(' ', trim($page_title["menu-meta_keywords"]));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$no = $ijseo_allow_no - $num;
					}
					else{
						if(isset($page_title["menu-meta_keywords"])){
							$var = strlen(utf8_decode($page_title["menu-meta_keywords"]));
						}
						else{
							$var = 0;
						}
						if(isset($ijseo_allow_no)){
							$no = $ijseo_allow_no - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metakey".$item->id; ?>" name="metakey[<?php echo $item->id; ?>]" onkeyup="javascript: countKey(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_key; ?>', '<?php echo $ijseo_allow_no; ?>');" rows="4"><?php if(isset($page_title["menu-meta_keywords"])){echo $page_title["menu-meta_keywords"];} ?></textarea>
					<span id="no_<?php echo $item->id; ?>" name="no_<?php echo $item->id; ?>"><?php if(isset($no)) echo $no; ?></span>
					<?php
					if(isset($no) &&($no < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "#666666") </script>';
					}	
					if(!empty($page_title["menu-meta_keywords"])){
						echo '<script type="text/javascript">
								unColor("metakey['.$item->id.']");
							</script>';
					}				
					?>
				</td>
				<td valign="top" align="center">
					<?php					
					if ($ijseo_type_desc=="Words"){
						$var = explode(' ', trim($page_title["menu-meta_description"]));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_desc - $num;
					}
					else{
						if(isset($page_title["menu-meta_description"])){
							$var = strlen(utf8_decode($page_title["menu-meta_description"]));
						}
						else{
							$var = 0;
						}
						if(isset($ijseo_allow_desc)){
							$do = $ijseo_allow_desc - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metadesc".$item->id; ?>" name="metadesc[<?php echo $item->id; ?>]" onkeyup="javascript: countDesc(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_desc; ?>', '<?php echo $ijseo_allow_desc; ?>');" rows="4"><?php if(isset($page_title["menu-meta_description"])){echo $page_title["menu-meta_description"];} ?></textarea>
					<span id="do_<?php echo $item->id; ?>" name="do_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "#666666") </script>';
					}	
					if(!empty($page_title["menu-meta_description"])){
						echo '<script type="text/javascript">
								unColor("metadesc['.$item->id.']");
							</script>';
					}				
					?>					
				</td>
			</tr>			
		<?php
			$k++;
		}
		unset($_SESSION["session_titletag"]);
		unset($_SESSION["session_metakey"]);
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
	<input type="hidden" name="controller" value="ktwo" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>