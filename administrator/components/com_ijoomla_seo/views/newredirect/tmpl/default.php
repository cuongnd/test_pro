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
JHtml::_('behavior.formvalidation');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$id = JRequest::getVar("id", "0", "get");
$name = "";
$catid = "";
$links_to = "";
$rel_nofollow = "1";
$target = "";
$link_text = "";
$image = "";

if($id != "0"){
	$values = $this->getValues();	
	$name = $values["0"]->name;
	$name = str_replace('"', "&quot;", $name);
	$catid = $values["0"]->catid;
	$links_to = $values["0"]->links_to;
	$rel_nofollow = $values["0"]->rel_nofollow;
	$target = $values["0"]->target;
	$link_text = $values["0"]->link_text;
	$image = $values["0"]->image; 	
}

?>

<style type="text/css">
	#published0, #published1{
		float:left !important;
	}
	
	label {
		float:left !important;
		margin:2px;
	}
	
	#published1{
		margin-left:10px !important;
	}
</style>

<script type="text/javascript">
	function changeDisplayImage() {            
		if(document.adminForm.image.value != ''){
			document.adminForm.imagelib.src='../images/ijseo_redirects/' + document.adminForm.image.value;
		} 
		else{
			document.adminForm.imagelib.src='../images/blank.png';
		}
	}
	
	Joomla.submitbutton = function(pressbutton){
		var form = document.adminForm;		
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
		}		
		else if(pressbutton == 'save' || pressbutton == 'apply') {
			if (form.name.value == ""){
				alert("<?php  echo JText::_("COM_IJOOMLA_SEO_NAME"); ?>: <?php echo " ".JText::_("COM_IJOOMLA_SEO_IS_REQUIRED")."."; ?>");
			} 
			else if (form.links_to.value == ""){
				alert("<?php echo JText::_("COM_IJOOMLA_SEO_LINKS_TO"); ?>: <?php echo " ".JText::_("COM_IJOOMLA_SEO_IS_REQUIRED")."."; ?>");
			}
			else{
				links_to = form.links_to.value;
				var exist = links_to.indexOf("http");
				if(exist == -1){
					host_local = form.host.value;
					
					var parser = document.createElement('a');
					parser.href = "http://"+links_to;
					host_added = parser.host;
					
					if(host_local != host_added){
						alert("<?php echo addslashes(JText::_("COM_IJOOMLA_SEO_ADD_HTTP")) ?>");
						return false;
					}
				}
				submitform( pressbutton );		
			}	
		}
		else{
			submitform( pressbutton );
		}
		
	}
</script>

<form class="form-horizontal" action="index.php" method="post" name="adminForm" id="adminForm">
	<?php
		if($id == "0"){
			echo "<h2>".JText::_("COM_IJOOMLA_SEO_REDIRCET_NEW")."</h2>";
		}
		else{
			echo "<h2>".JText::_("COM_IJOOMLA_SEO_REDIRCET_EDIT")."</h2>";
		}
		echo "<br/>";
	?>
    <div class="control-group">
        <label class="control-label"><?php  echo JText::_("COM_IJOOMLA_SEO_NAME"); ?>:<span style="color:#FF0000;">*</span></label>
        <div class="controls">
        	<input name="name" type="text" class="inputbox" id="name" value="<?php echo $name; ?>" size="50" maxlength="50" />
			<?php 
                echo JHTML::tooltip(
                    JText::_("COM_IJOOMLA_SEO_TOOLTIP_EDIT_NAME"), 
                    "", 
                    JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                ); 
            ?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_CATEGORY"); ?>:</label>
        <div class="controls">
        	<?php echo $this->getAllCategories($catid); ?>
			<?php 
                echo JHTML::tooltip(
                    JText::_("COM_IJOOMLA_SEO_TOOLTIP_CATEGORY"), 
                    "", 
                    JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                ); 
            ?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_LINKS_TO"); ?>:<span style="color:#FF0000;">*</span></label>
        <div class="controls">
        	<input name="links_to" type="text" class="inputbox" id="links_to" value="<?php echo $links_to; ?>" size="50" />
			<?php 
                echo JHTML::tooltip(
                    JText::_("COM_IJOOMLA_SEO_SEO_TOOLTIP_EDIT_LINKS_TO"), 
                    "",
                    JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                ); 
            ?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_LINK_REL_NO_FOLLOW"); ?>?</label>
        <div class="controls">
        	<?php
				$categories = array();
				$categories[] = JHTML::_('select.option', "1", JText::_("JYES"), 'id', 'name');
				$categories[] = JHTML::_('select.option', "0", JText::_("JNO"), 'id', 'name');
				echo JHTML::_('select.genericlist', $categories, "rel_nofollow", "", 'id', 'name', $rel_nofollow);
				echo JHTML::tooltip(
					JText::_("COM_IJOOMLA_SEO_TOOLTIP_LINK_REL_NO_FOLLOW"), 
					"",
					JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
				);
			?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_TARGET"); ?>:</label>
        <div class="controls">
        	<?php
				$arr_targets = array();
				$arr_targets[] = JHTML::_('select.option', '_blank', JText::_("COM_IJOOMLA_SEO_TARGET_BLANK"), 'id', 'name');
				$arr_targets[] = JHTML::_('select.option', '_self', JText::_("COM_IJOOMLA_SEO_TARGET_SELF"), 'id', 'name');
				$arr_targets[] = JHTML::_('select.option', '_parent', JText::_("COM_IJOOMLA_SEO_TARGET_PARENT"), 'id', 'name');
				$arr_targets[] = JHTML::_('select.option', '_top', JText::_("COM_IJOOMLA_SEO_TARGET_TOP"), 'id', 'name');
				echo JHTML::_('select.genericlist', $arr_targets, 'target', '', 'id', 'name', $target);
				echo JHTML::tooltip(
					JText::_("COM_IJOOMLA_SEO_TOOLTIP_TARGET"), 
					"",
					JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
				);
			?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_LINK_TEXT"); ?>:</label>
        <div class="controls">
        	<input name="link_text" type="text" class="inputbox" id="link_text" value="<?php echo $link_text; ?>" size="50" />
			<?php 
                echo JHTML::tooltip(
                    str_replace("''", '"', JText::_("COM_IJOOMLA_SEO_TOOLTIP_LINK_TEXT")), 
                    "",
                    JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                );
			?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_IMAGE"); ?>:</label>
        <div class="controls">
        	<?php
				$javascript = 'onchange="changeDisplayImage();"';
				$directory = '/images/ijseo_redirects';
				echo JHTML::_('list.images',  'image', $image, $javascript, $directory, "bmp|gif|jpg|png|swf");
				echo JHTML::tooltip(
					str_replace("''", '"', JText::_("COM_IJOOMLA_SEO_TOOLTIP_IMAGE")), 
					"",
					JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
				);
			?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
			if ($image != ""){						
				if (@eregi("swf", $redirect_row->image)){
			?>
					<img src="../images/blank.png" name="imagelib">
			<?php
				}
				elseif (@eregi("gif|jpg|png", $image)){
			?>
					<img src="../images/ijseo_redirects/<?php echo $image; ?>" name="imagelib" id="imagelib"/>
			<?php
				}
				else{
			?>
					<img src="images/blank.png" name="imagelib" id="imagelib"/>
			<?php
				}
			}
			else{
				echo '<img src="'.JUri::root()."/components/com_ijoomla_seo/images/blank.png".'" name="imagelib" id="imagelib"/>';
			}	
			?>
        </div>
    </div>

	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="newredirect" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="host" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>