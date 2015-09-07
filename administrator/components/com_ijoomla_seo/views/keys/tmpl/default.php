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
include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."meta.php");

$items = $this->items;

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$ijseo_gposition = $this->params->ijseo_gposition;
$ijseo_keysource = $this->params->ijseo_keysource;
$ijseo_check_ext = $this->params->ijseo_check_ext;

if(!isset($ijseo_check_ext) || trim($ijseo_check_ext) == ""){
	$ijseo_check_ext = "com";
}

$sort1 = JRequest::getVar('sort1', '');
$sort2 = JRequest::getVar('sort2', '');
$sort3 = JRequest::getVar('sort3', '');
$sort4 = JRequest::getVar('sort4', '');
$sort5 = JRequest::getVar('sort5', '');

$meta = new Meta();
$configs = $meta->getParams();
$check_nr = @$configs->check_nr;
?>

<script language="javascript" type="text/javascript">

function checkKeysValue(){
	task = document.adminForm.task.value;
	if(task == "save"){
		keys = document.adminForm.keys_title.value;
		if(keys.length == 0){
			alert("<?php echo addslashes(JText::_("COM_IJOOMLA_SEO_ADD_KEYS")); ?>");
			return false;
		}
	}
}

function submitbutton(pressbutton) {
	/// change sticky status: on/off 
    var selItems = document.getElementsByName('cid[]'), num = selItems.length;

	if (pressbutton == 'sticky') {
		if (num) {
			for (var i = 0; i< num; i++) {
				 if(selItems[i].checked == true) {							 		
					sticky = $("sticky"+i);
					alt = sticky.getProperty('alt');							 		
					sticky.alt = 'sticky_on';
					sticky.src = '<?php echo JUri::root(); ?>components/com_ijoomla_seo/images/sticky_on.gif';
					id = parseInt(selItems[i].value);	
					changeStickyDB(id, 1, '<?php echo JURI::base(); ?>');							 		
				 }
             }
        }
	} else if (pressbutton == 'unsticky'){
		if(num) {
			for(var i = 0; i< num; i++) {
                if(selItems[i].checked == true) {							 		
					sticky = $("sticky"+i);
					alt = sticky.getProperty('alt');							 		
					sticky.alt = 'sticky_off';
					sticky.src = '<?php echo JUri::root(); ?>components/com_ijoomla_seo/images/sticky_off.gif';
					id = parseInt(selItems[i].value);	
					changeStickyDB(id, 0, '<?php echo JURI::base(); ?>');									 		
                }
            }
        }
	}
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

<style type="text/css">	
	.chzn-container {
    	display: inline-table !important;
	}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm" onsubmit="return checkKeysValue();">
	<div class="seo-head-line-title">
		<div class="row-fluid">
        	<div class="span6">
            	<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_MANAGER"); ?></span>
            </div>
            <div class="span6" style="float:right; text-align: right;">
            	<a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155476">
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_('COM_IJOOMLA_SEO_KEYWORD_VID'); ?>
                </a>
            </div>
        </div>
	</div>

    <div class="seo-head-line">
		<div class="row-fluid">
        	<div class="span5">
            	<?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_DESCRIPTION"); ?>
            </div>
            <div class="span7" style="float:left;">
            	<table width="100%" style="margin-left:20px;">
					<tr>
						<td width="100%">
							<span class="add-key-header"><?php echo JText::_("COM_IJOOMLA_SEO_ADD_KEY_PHRASE"); ?></span>
                            <br/>
                            <span class="add-key-desc"><?php echo JText::_("COM_IJOOMLA_SEO_ADD_KEY_PHRASE_DESC"); ?></span>
						</td>
					</tr>
                    <tr>
                    	<td>
                        	<input class="input-xxlarge" type="text" name="keys_title" style="margin:0px !important;" />
                            <input type="submit" name="save" value="<?php echo JText::_("COM_IJOOMLA_SEO_ADD"); ?>" class="btn btn-primary" onclick="document.adminForm.task.value='save';" />
                        </td>
                    </tr>
				</table>
            </div>
        </div>
	</div>
    
    <table width="100%" style="margin-bottom:5px;">
        <tr>
            <td  style="float:right;" colspan="2">
                <?php
                     $search = JRequest::getVar("search", "");
                ?>
                <?php echo JText::_("COM_IJOOMLA_SEO_FILTER"); ?>: <input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" style="margin-bottom:0px;" />&nbsp;<input type="submit" class="btn" name="submit_search" value="<?php echo JText::_("COM_IJOOMLA_SEO_GO"); ?>" />
                <?php
                	echo $this->createCriterias();
				?>
            </td>
        </tr>
    </table>
    
	<?php
		$colnum = JRequest::getVar("colnum", "0");
		$col = JRequest::getVar('col', '');
		$var1 = "sort".$colnum;
		$var2 = JRequest::getVar($var1);				
		$img = ($var2 == "asc")? JURI::base().'components/com_ijoomla_seo/images/sort_up.gif' : JURI::base().'components/com_ijoomla_seo/images/sort_down.gif';				
	?>		
	<table width="100%" class="table table-striped">
		<tr>
			<th width="10px">#</th>				
			<th width="10px" align="center"><input type="checkbox" name="toggle" value="" onClick="Joomla.checkAll(this);" /></th>							
			<th width="35%" class="sortable" align="center">
				<div onclick="fieldSort('title', document.adminForm.sort1, 1); submitform()"><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORD_PHRASES")?>&nbsp;
					<?php if($col == 'title'){echo '<img src="'.$img.'" alt="sort" />';}?>
				</div>
			</th>			
			<th class="sortable" align="center">
				<div onclick="fieldSort('rank', document.adminForm.sort2, 2); submitform()"><?php echo JText::_("COM_IJOOMLA_SEO_GOOGLE_RANK"); ?>
					<?php if($col == 'rank'){echo '<img src="'.$img.'"  alt="sort" />';}?>
				</div>
			</th>			
			<th class="sortable" align="center">
				<div onclick="fieldSort('rchange', document.adminForm.sort3, 3); submitform()"><?php echo JText::_("COM_IJOOMLA_SEO_CHANGE");?>
					<?php if($col == 'rchange'){echo '<img src="'.$img.'"  alt="sort" />';} ?>
				</div>
			</th>			
			<th class="sortable" align="center">
				<div onclick="fieldSort('checkdate', document.adminForm.sort4, 4); submitform()"><?php echo JText::_("COM_IJOOMLA_SEO_CHANGE_SINCE");?>
					<?php if($col == 'checkdate'){echo '<img src="'.$img.'"  alt="sort" />';}?>
				</div>
			</th>			
			<th class="sortable" align="center">
				<div onclick="fieldSort('sticky', document.adminForm.sort5, 5); submitform()"><?php echo JText::_("COM_IJOOMLA_SEO_STICKY");?>
					<?php if($col == 'sticky'){echo '<img src="'.$img.'"  alt="sort" />';}?>
				</div>
			</th>			
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_VIEW_ON_GOOGLE");?></th>					
		</tr>
		<?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.keysarticles'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.keysarticles'.'.list.limit', 'limit');
		$k = $limistart+1;
		for($i=0; $i<count($this->items); $i++){			
			$item = $this->items[$i];					
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $item["id"]); ?>
				</td>				
				<td id="name<?php echo $k-1; ?>">
					<?php echo $item["title"]; ?>
				</td>
				<td  align="center">
				<?php						
					// get Google Rank - automatically
					if(intval($ijseo_gposition)){									
						$crank = ($item["rank"])? $item["rank"] : '-';							
						echo '<span id="rank'.$i.'">'.$crank.'</span>';				
					}					
					// get Google Rank - manually
					else { 
						echo '<input name="googlerank" id="googlerank" onclick="getRank(\''.$item["title"].'\','.$i.', \''.JURI::root().'administrator/components/com_ijoomla_seo/\', '.$check_nr.'); " type="button" value="'.JText::_("COM_IJOOMLA_SEO_CHECK_POSITION").'" />&nbsp;&nbsp;&nbsp;';
						if($item["rank"] > 0){
							echo '<span id="rank'.$i.'">'.$item["rank"].'</span>';
						}	
						else{
							echo '<span id="rank'.$i.'">-</span>';
						}	
					}
				?>
				</td>
				<td align="center">
					<?php
						if(isset($newRank)){
							$path = JURI::base().'components/com_ijoomla_seo/';							
							$mode = 0;							
							// Down arrow
							if(($newRank > $item["rank"] || $newRank == 0) && $item["rank"] && $change){
								echo '<span id="change'.$i.'"><span style="color:red">'.$change.'</span>&nbsp;&nbsp;&nbsp;';
								echo '<img src="'.$path.'images/down.gif" border="0" alt="down" align="absmiddle"/></span>';
								$mode = 0;
							}
							// Up arrow
							else if(($newRank < $item["rank"] || $item["rank"] == 0) && $newRank && $change) {
								echo '<span id="change'.$i.'"><span style="color:green">'.$change.'</span>&nbsp;&nbsp;&nbsp;';
								echo '<img src="'.$path.'images/up.gif" border="0" alt="up" align="absmiddle"/></span>';
								$mode = 1;
							}															
							else{
								echo '<span id="change'.$i.'">-</span>';
							}	
																												
						}						
						else{
							if($item["rchange"]){
								/// 1 - up, 0 - down								
								$updown = ($item["mode"])? "up.gif" : "down.gif";
								$color	=($item["mode"]) ? "green.gif" : "red.gif";
								echo "<span id='change".$i."'><span style='color:".$color."'>{".$item["rchange"]."}</span>&nbsp;&nbsp;&nbsp;";
								echo "<img src='components/com_ijoomla_seo/images/".$updown."' border='0' alt='' align='absmiddle'/></span>";
							}
							else{
								echo "<span id='change".$i."' >-</span>";
							}	
						}
					?>
				</td>
				<td>
					<?php
						if(isset($newRank)){
							echo $currentDate;
						}																	
						else{
							echo $item["checkdate"];
						}	
						unset($newRank);
					?>
				</td>
				<td align="center">
					<?php 						
						$url = JURI::base()."components/com_ijoomla_seo/images/";
						$url .= ($item["sticky"] > 0) ? "sticky_on.gif" : "sticky_off.gif";
						$alt = ($item["sticky"] > 0) ? "sticky_on.gif" : "sticky_off.gif";
					 ?>	
					<img src="<?php echo $url; ?>" alt="<?php echo $alt; ?>" border="0" onclick="changeSticky(this, '<?php echo JURI::base(); ?>') " class="sticky" id="sticky<?php echo $i;?>"/>
				</td>
				<td align="center">
					<a href="http://www.google.<?php echo $ijseo_check_ext; ?>/search?q=<?php echo urlencode($item["title"])?>&amp;num=<?php echo $check_nr; ?>&amp;start=0" target="_blank" title="view" class="viewG<?php echo $i; ?>" ><?php echo JText::_("COM_IJOOMLA_SEO_VIEW"); ?></a>
				</td>
			</tr>			
		<?php
			$k++;
		}
		?>
		<tfoot>
            <tr>
                <td colspan="8">
					<?php
                    	echo $this->pagination->getLimitBox();
						echo $this->pagination->getListFooter();
					?>
                </td>
            </tr>
		</tfoot>
	</table>
	
	<input type="hidden" name="sort1" value="<?php echo $sort1; ?>" />
	<input type="hidden" name="sort2" value="<?php echo $sort2; ?>" />
	<input type="hidden" name="sort3" value="<?php echo $sort3; ?>" />
	<input type="hidden" name="sort4" value="<?php echo $sort4; ?>" />
	<input type="hidden" name="sort5" value="<?php echo $sort5; ?>" />
	<input type="hidden" name="col" value="<?php echo $col; ?>" />
	<input type="hidden" name="colnum" value="<?php echo $colnum; ?>" />
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="keys" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
    
</form>