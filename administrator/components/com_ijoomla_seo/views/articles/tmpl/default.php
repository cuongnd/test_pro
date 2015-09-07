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

$item = $this->items;

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
Joomla.submitbutton = function (task){
	if(task == "apply" || task == "save"){
		var form=document.adminForm;
		form.task.value=task;
		var i = 0;
		while(eval(document.getElementById("cb"+i))){
			document.getElementById("cb"+i).checked = true;
			i++;
		}
	}	
	submitform(task);
}
<?php 
    $selected = JRequest::getVar('selected');
    if ($selected == 'menus') {
?>
setTimeout(function() {
    // Set "menus" as selected type when on metatags by default
    document.getElementById('types').options[1].selected = true;
    showMenu('menus');
}, 400);
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
        	<div class="span5">
            	<?php echo JText::_("COM_IJOOMLA_SEO_METATAGS_DESCRIPTION"); ?>
            </div>
            <div class="span7" style="float:right;">
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
						<td  style="float:right;" colspan="2">
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
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_ARTICLE_TITLE") ?></th>
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
		$session_titletag = "";
		$session_metakey = "";
		$session_description = "";
		if(isset($_SESSION["session_titletag"])){
			$session_titletag = $_SESSION["session_titletag"];
		}
		if(isset($_SESSION["session_metakey"])){
			$session_metakey = $_SESSION["session_metakey"];
		}
		if(isset($_SESSION["session_description"])){
			$session_description = $_SESSION["session_description"];
		}
		
		for($i=0;$i<count($this->items);$i++){
			$item=$this->items[$i];
			$metatitle = $item->titletag;
			if(isset($session_titletag[$item->id])){
				$metatitle = $session_titletag[$item->id];
			}
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>				
				<td>
					<?php echo $item->title; ?>
				</td>
				<td>
					<a href="index.php?option=com_ijoomla_seo&controller=preview&id=<?php echo $item->id; ?>&tmpl=component&task=article_preview" class="modal" rel="{handler: 'iframe', size: {x: 640, y: 480}}"><?php echo "view"; ?></a>
				</td>
				<td valign="top" align="center">
					<?php
                    // Metatitle
					if ($ijseo_type_title == "Words"){
						$var = explode(' ', trim($metatitle));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_no2 - $num;
					}
					else{
						$var = strlen(utf8_decode($metatitle));
						if(isset($ijseo_allow_no2)){
							$do = $ijseo_allow_no2 - $var;
						}	
				 	}
                    
					?>					
					<textarea id="<?php echo "metatitle".$item->id; ?>" name="page_title[<?php echo $item->id; ?>]" onkeyup="javascript: countTitle(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_title; ?>', '<?php echo $ijseo_allow_no2; ?>');" rows="4"><?php echo $metatitle; ?></textarea>
					<span id="go_<?php echo $item->id; ?>" name="go_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "go", "#666666") </script>';
					}	
					if(!empty($metatitle)){
						echo '<script type="text/javascript">
								unColor("metatitle['.$item->id.']");
							</script>';
					}				
					?>				
				</td>
				<td valign="top" align="center">
					<?php
					if(isset($session_metakey[$item->id])){
						$item->metakey = $session_metakey[$item->id];
					}
					
					if ($ijseo_type_key=="Words"){
						$var = explode(' ', trim($item->metakey));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$no = $ijseo_allow_no - $num;
					}
					else{
						$var = strlen(utf8_decode(trim($item->metakey)));
						if(isset($ijseo_allow_no)){
							$no = $ijseo_allow_no - $var;
						}	
				 	}
                    
					?>
					<textarea id="<?php echo "metakey".$item->id; ?>" name="metakey[<?php echo $item->id; ?>]" onkeyup="javascript: countKey(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_key; ?>', '<?php echo $ijseo_allow_no; ?>');" rows="4"><?php echo $item->metakey; ?></textarea>
					<span id="no_<?php echo $item->id; ?>" name="no_<?php echo $item->id; ?>"><?php if(isset($no)) echo $no; ?></span>
					<?php
					if(isset($no) &&($no < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "no", "#666666") </script>';
					}	
					if(!empty($item->metakey)){
						echo '<script type="text/javascript">
								unColor("metakey['.$item->id.']");
							</script>';
					}				
					?>
				</td>
				<td valign="top" align="center">
					<?php
					if(isset($session_description[$item->id])){
						$item->metadesc = $session_description[$item->id];
					}
					
					if ($ijseo_type_desc=="Words"){
						$var = explode(' ', trim($item->metadesc));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_desc - $num;
					}
					else{
						$var = strlen(utf8_decode($item->metadesc));
						if(isset($ijseo_allow_desc)){
							$do = $ijseo_allow_desc - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metadesc".$item->id; ?>" name="metadesc[<?php echo $item->id; ?>]" onkeyup="javascript: countDesc(this, this.value, '<?php echo $item->id; ?>', '<?php echo $ijseo_type_desc; ?>', '<?php echo $ijseo_allow_desc; ?>');" rows="4"><?php echo $item->metadesc; ?></textarea>
					<span id="do_<?php echo $item->id; ?>" name="do_<?php echo $item->id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$item->id.', "do", "#666666") </script>';
					}	
					if(!empty($item->metadesc)){
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
		unset($_SESSION["session_description"]);
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
	<input type="hidden" name="controller" value="articles" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter" value="<?php echo JRequest::getVar("filter", ""); ?>" />
	<input type="hidden" name="value" value="<?php echo JRequest::getVar("value", ""); ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>