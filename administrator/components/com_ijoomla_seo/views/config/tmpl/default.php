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
JHTML::_('behavior.modal');
JHtml::_('behavior.formvalidation');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

require_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."reader.php");
include(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."left.php");

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/stats.js");
$params = $this->params;

$task2 = JRequest::getVar("task2", "general");

?>

<?php
	if($task2 == "google_ping"){
?>
		<script type="text/javascript" language="javascript">
			Joomla.submitbutton = function (task) {
				if(document.adminForm.ijseo_check_ext.value == ""){
					alert("<?php echo JText::_("COM_IJOOMLA_SEO_ADD_CHECK"); ?>");
					return false;
				}
				submitform(task);
			}
		</script>
<?php
	}
	elseif($task2 == "manage_meta"){
?>
		<script type="text/javascript" language="javascript">
			function isInt(n){
				return n != "" && !isNaN(n) && Math.round(n) == n && n > 0 && n % 1 == 0;
			}
			
			Joomla.submitbutton = function (task) {
				var er = /^[0-9]+$/;
				
				allow_no2 = document.adminForm.allow_no2.value;
				allow_no = document.adminForm.allow_no.value;
				allow_no_desc = document.adminForm.allow_no_desc.value;

				if(!isInt(allow_no2)){
					alert("<?php echo JText::_("COM_IJOOMLA_SEO_METAT_TITLE_INVALID"); ?>");
					return false;
				}
				
				if(!isInt(allow_no)){
					alert("<?php echo JText::_("COM_IJOOMLA_SEO_METAT_KEYS_INVALID"); ?>");
					return false;
				}
				
				if(!isInt(allow_no_desc)){
					alert("<?php echo JText::_("COM_IJOOMLA_SEO_METAT_DESC_INVALID"); ?>");
					return false;
				}
				
				submitform(task);
			}
		</script>
<?php
	}
	elseif($task2 == "track_keywords"){
?>
		<script type="text/javascript" language="javascript">
			Joomla.submitbutton = function (task) {
				if(document.adminForm.delimiters.value == ""){
					alert("<?php echo JText::_("COM_IJOOMLA_SEO_DELIOMITERS_MANDATORY"); ?>");
					return false;
				}
				submitform(task);
			}
		</script>
<?php
	}
?>

<style type="text/css">
	.control-label{
		display: inline !important;
	}
	
	.chzn-container .chzn-results{
		max-height:none !important;
	}
</style>

<form action="index.php" method="post" name="adminForm" id="adminForm">

<?php
if($task2 == "general"){
?>
	<div class="panel panel-success">
        <div class="panel-heading">
        	<h3 class="panel-title"><?php echo JText::_("COM_IJOOMLA_SEO_GENERAL"); ?></h3>
        </div>
        <table align="center" width="100%" cellspacing="5" cellpadding="5">
            <tr>
                <th width="15%"></th><th></th><th width="45%"></th>
            </tr>
            <tr>
                <td colspan="3" class="stitle">
                    <table width="100%">
                        <tr>
                            <td>								
                                <?php echo JText::_("COM_IJOOMLA_SEO_ADD_ALT_AUTO"); ?>
                            </td>
                            <td align="right">                    
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155423">
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                                    <?php echo JText::_("COM_IJOOMLA_SEO_ADD_ALT_AUTO_VIDEO"); ?>                  
                                </a>
                            </td>
                            <td>&nbsp;
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                	<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_USE"); ?>
                   	</label>
                    <select name="Image_what">
                        <option value="up to" <?php if(isset($params->ijseo_Image_what) && $params->ijseo_Image_what=="up to") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_UPTO"); ?></option>
                        <option value="only" <?php if(isset($params->ijseo_Image_what)&& $params->ijseo_Image_what=="only") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_ONLY"); ?></option>
                    </select>
                    <select name="Image_number">
                        <option value="1" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="1") echo "selected"; ?>>1'st</option>
                        <option value="2" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="2") echo "selected"; ?>>2'nd</option>
                        <option value="3" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="3") echo "selected"; ?>>3'rd</option>
                        <option value="4" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="4") echo "selected"; ?>>4'th</option>
                        <option value="5" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="5") echo "selected"; ?>>5'th</option>
                        <option value="6" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="6") echo "selected"; ?>>6'th</option>
                        <option value="7" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="7") echo "selected"; ?>>7'th</option>
                        <option value="8" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="8") echo "selected"; ?>>8'th</option>
                        <option value="9" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="9") echo "selected"; ?>>9'th</option>
                        <option value="10" <?php if(isset($params->ijseo_Image_number) && $params->ijseo_Image_number=="10") echo "selected"; ?>>10'th</option>
                    </select>
                    <select name="Image_where">
                        <option value="keyword" <?php if(isset($params->ijseo_Image_where) && $params->ijseo_Image_where=="keyword") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORD"); ?></option>
                        <option value="phrase" <?php if(isset($params->ijseo_Image_where) && $params->ijseo_Image_where=="phrase") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_PHRASE"); ?></option>
                    </select>
                    &nbsp; <?php echo JText::_("COM_IJOOMLA_SEO_AS"); ?> &#39;alt&#39; <?php echo JText::_("COM_IJOOMLA_SEO_TAG"); ?> &nbsp;
                    <select name="Image_when">
                        <option value="Always" <?php 
                            if (isset($params->ijseo_Image_when) && $params->ijseo_Image_when=="Always") { 
                                echo 'selected="selected"';
                            } 
                        ?>><?php echo JText::_("COM_IJOOMLA_SEO_ALWAYS"); ?></option>
                        <option value="NotSpecified" <?php 
                            if (isset($params->ijseo_Image_when) && $params->ijseo_Image_when=="NotSpecified") {
                                echo 'selected="selected"'; 
                            }
                        ?>><?php echo JText::_("COM_IJOOMLA_SEO_ONLYWHEN"); ?></option>
                        <option value="Never" <?php 
                            if (isset($params->ijseo_Image_when) && $params->ijseo_Image_when=="Never") {
                                echo 'selected="selected"';
                            }
                        ?>><?php echo JText::_("COM_IJOOMLA_SEO_NEVER"); ?></option>
                    </select>
                    <?php 
                        echo JHTML::tooltip(
                            '', 
                            JText::_("COM_IJOOMLA_SEO_THISWILLUSEAKWONIMAGE"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>            
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td class="stitle" colspan="3">
                    <table width="100%"  cellpadding="2" cellspacing="2">
                        <tr>
                            <td align="left">								
                                <?php 
                                    echo JText::_('COM_IJOOMLA_SEO_STYLIZE') . ' ' . JText::_("COM_IJOOMLA_SEO_KEYWORDS"); 
                                ?>
                            </td>
                            <td align="right">
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155772">                            
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                                    <?php echo JText::_("COM_IJOOMLA_SEO_STYLIZE_KEYWORDS_VIDEO"); ?>
                                </a>
                            </td>
                            <td>&nbsp;
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="20%">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_WRAP_KEYWORDS_PHRASES_WITH"); ?>:
                    </label>
				</td>
                <td>
                     <select name="wrap_key" id="wrap_key">
                        <option value="nowrap" <?php if($params->ijseo_wrap_key=="nowrap") echo "selected"; ?>>
                            <?php echo JText::_('COM_IJOOMLA_SEO_DO_NOT_WRAP'); ?>
                        </option>
                        <option value="strong" <?php if($params->ijseo_wrap_key=="strong") echo "selected"; ?>>&lt;strong&gt; keyword / phrase &lt;/strong&gt;</option>
                        <option value="b" <?php if($params->ijseo_wrap_key=="b") echo "selected"; ?>>&lt;b&gt; keyword / phrase &lt;/b&gt;</option>
                        <option value="u" <?php if($params->ijseo_wrap_key=="u") echo "selected"; ?>>&lt;u&gt; keyword / phrase &lt;/u&gt;</option>
                     </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_WRAP_KEY"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        )
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <!-- Wrap partial words -->
            <tr>
                <td>
                	<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_WRAP_PARTIAL_WORDS"); ?>:
					</label>
				</td>
                <td>
                     <select name="wrap_partial" id="wrap_partial">
                        <option value="1" <?php if(isset($params->ijseo_wrap_partial) && $params->ijseo_wrap_partial=="1"){ echo "selected";} ?>><?php echo JText::_("JYES"); ?></option>
                        <option value="0" <?php if(isset($params->ijseo_wrap_partial) && $params->ijseo_wrap_partial=="0"){ echo "selected";} ?>><?php echo JText::_("JNO"); ?></option>
                     </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",

                            JText::_("COM_IJOOMLA_SEO_WRAP_INFO"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        )
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td class="stitle" colspan="3">
                    <table width="100%">
                        <tr>
                            <td>								
                                <?php echo JText::_("COM_IJOOMLA_SEO_REPLACE_JOMCLASSES"); ?>
                            </td>
                            <td align="right">
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155680">
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />                            
                                    <?php echo JText::_("COM_IJOOMLA_SEO_REPLACE_JOMCLASSES_VIDEO"); ?>
                                </a>
                            </td>
                            <td>&nbsp;
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="5">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_REPLACE"); ?>
                    </label>
				</td>
                <td align="left">
                    <input type="text" name="Replace1" class="input-medium" id="Replace1" value="<?php echo $params->ijseo_Replace1; ?>" />&nbsp;<?php echo JText::_("COM_IJOOMLA_SEO_WITH"); ?>&nbsp;
                    <select name="Replace1_with">
                        <option value="H1" <?php if($params->ijseo_Replace1_with == "H1") echo "selected"; ?>>H1</option>
                        <option value="H2" <?php if($params->ijseo_Replace1_with == "H2") echo "selected"; ?>>H2</option>
                        <option value="H3" <?php if($params->ijseo_Replace1_with == "H3") echo "selected"; ?>>H3</option>
                        <option value="H4" <?php if($params->ijseo_Replace1_with == "H4") echo "selected"; ?>>H4</option>
                        <option value="H5" <?php if($params->ijseo_Replace1_with == "H5") echo "selected"; ?>>H5</option>
                        <option value="H6" <?php if($params->ijseo_Replace1_with == "H6") echo "selected"; ?>>H6</option>
                        <option value="noreplace" <?php if($params->ijseo_Replace1_with=="noreplace") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_DONT_REPLACE"); ?></option>
                    </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_THISWILLREPLACESPECCLASS"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>        
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td width="5">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_REPLACE"); ?>
                   	</label>
				</td>
                <td>
                    <input type="text" name="Replace2" class="input-medium" id="Replace2" value="<?php echo $params->ijseo_Replace2; ?>" />&nbsp;with&nbsp;
                    <select name="Replace2_with">
                        <option value="H1" <?php if($params->ijseo_Replace2_with == "H1") echo "selected"; ?>>H1</option>
                        <option value="H2" <?php if($params->ijseo_Replace2_with == "H2") echo "selected"; ?>>H2</option>
                        <option value="H3" <?php if($params->ijseo_Replace2_with == "H3") echo "selected"; ?>>H3</option>
                        <option value="H4" <?php if($params->ijseo_Replace2_with == "H4") echo "selected"; ?>>H4</option>
                        <option value="H5" <?php if($params->ijseo_Replace2_with == "H5") echo "selected"; ?>>H5</option>
                        <option value="H6" <?php if($params->ijseo_Replace2_with == "H6") echo "selected"; ?>>H6</option>
                        <option value="noreplace" <?php if($params->ijseo_Replace2_with == "noreplace") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_DONT_REPLACE"); ?></option>
                    </select>
                <?php 
                    echo JHTML::tooltip(
                        "",
                        JText::_("COM_IJOOMLA_SEO_THISWILLREPLACESPECCLASS"), 
                        JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                    ); 
                ?>        
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td width="5">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_REPLACE"); ?>
                    </label>
				</td>
                <td>
                     <input type="text" name="Replace3" class="input-medium" id="Replace3" value="<?php echo $params->ijseo_Replace3; ?>" />&nbsp;with&nbsp;
                     <select name="Replace3_with">
                        <option value="H1" <?php if($params->ijseo_Replace3_with == "H1") echo "selected"; ?>>H1</option>
                        <option value="H2" <?php if($params->ijseo_Replace3_with == "H2") echo "selected"; ?>>H2</option>
                        <option value="H3" <?php if($params->ijseo_Replace3_with == "H3") echo "selected"; ?>>H3</option>
                        <option value="H4" <?php if($params->ijseo_Replace3_with == "H4") echo "selected"; ?>>H4</option>
                        <option value="H5" <?php if($params->ijseo_Replace3_with == "H5") echo "selected"; ?>>H5</option>
                        <option value="H6" <?php if($params->ijseo_Replace3_with == "H6") echo "selected"; ?>>H6</option>
                        <option value="noreplace" <?php if($params->ijseo_Replace3_with == "noreplace") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_DONT_REPLACE"); ?></option>
                    </select>
                <?php 
                    echo JHTML::tooltip(
                        "",
                        JText::_("COM_IJOOMLA_SEO_THISWILLREPLACESPECCLASS"), 
                        JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                    ); 
                ?>        
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td width="5">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_REPLACE"); ?>
					</label>
				</td>
                <td>
                     <input type="text" name="Replace4" class="input-medium" id="Replace4" value="<?php echo $params->ijseo_Replace4; ?>" />&nbsp;with&nbsp;
                     <select name="Replace4_with">
                        <option value="H1" <?php if($params->ijseo_Replace4_with == "H1") echo "selected"; ?>>H1</option>
                        <option value="H2" <?php if($params->ijseo_Replace4_with == "H2") echo "selected"; ?>>H2</option>
                        <option value="H3" <?php if($params->ijseo_Replace4_with == "H3") echo "selected"; ?>>H3</option>
                        <option value="H4" <?php if($params->ijseo_Replace4_with == "H4") echo "selected"; ?>>H4</option>
                        <option value="H5" <?php if($params->ijseo_Replace4_with == "H5") echo "selected"; ?>>H5</option>
                        <option value="H6" <?php if($params->ijseo_Replace4_with == "H6") echo "selected"; ?>>H6</option>
                        <option value="noreplace" <?php if($params->ijseo_Replace4_with == "noreplace") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_DONT_REPLACE"); ?></option>
                    </select>
                <?php 
                    echo JHTML::tooltip(
                        "",
                        JText::_("COM_IJOOMLA_SEO_THISWILLREPLACESPECCLASS"), 
                        JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                    ); 
                ?>        
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td  width="5">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_REPLACE"); ?>
					</label>
				</td>
                <td>
                     <input type="text" name="Replace5" class="input-medium" id="Replace5" value="<?php echo $params->ijseo_Replace5; ?>" />&nbsp;with&nbsp;
                     <select name="Replace5_with">
                            <option value="H1" <?php if($params->ijseo_Replace5_with == "H1") echo "selected"; ?>>H1</option>
                            <option value="H2" <?php if($params->ijseo_Replace5_with == "H2") echo "selected"; ?>>H2</option>
                            <option value="H3" <?php if($params->ijseo_Replace5_with == "H3") echo "selected"; ?>>H3</option>
                            <option value="H4" <?php if($params->ijseo_Replace5_with == "H4") echo "selected"; ?>>H4</option>
                            <option value="H5" <?php if($params->ijseo_Replace5_with == "H5") echo "selected"; ?>>H5</option>
                            <option value="H6" <?php if($params->ijseo_Replace5_with == "H6") echo "selected"; ?>>H6</option>
                            <option value="noreplace" <?php if($params->ijseo_Replace5_with == "noreplace") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_DONT_REPLACE"); ?></option>
                    </select>
                <?php 
                    echo JHTML::tooltip(
                        "",
                        JText::_("COM_IJOOMLA_SEO_THISWILLREPLACESPECCLASS"), 
                        JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                    ); 
                ?>        
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
        </table>
    </div>
<?php
}
elseif($task2 == "track_keywords"){
?>
	<div class="panel panel-success">
        <div class="panel-heading">
        	<h3 class="panel-title"><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_KEY"); ?></h3>
        </div>
        <table width="100%"  cellpadding="2" cellspacing="2">
            <tr>
                <td colspan="3" class="stitle">
                    <table width="100%">
                        <tr>
                            <td>								
                                <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_MANAGER"); ?>
                            </td>
                            <td align="right">
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155493">
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                                    <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_MANAGER_VIDEO"); ?>
                                </a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>								
                </td>
            </tr>
            <!-- Keyword/phrases source -->          
            <tr>
                <td valign="top" width="17%">
                    <label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_KEYWORD_PHRASES_SOURCE"); ?>
                    </label>
                </td>
                <td valign="top" align="left">
                 <?php
                    $opts = array();
                    $opts[] = JHTML::_('select.option', '1', JText::_("COM_IJOOMLA_SEO_TITLE_MTAGS"));
                    $opts[] = JHTML::_('select.option', '0', JText::_("COM_IJOOMLA_SEO_KEYWORDS_MTAGS"));
                    echo $lists['keysource'] = JHTML::_('select.genericlist', $opts, 'keysource', ' size="1"', 'value', 'text', $params->ijseo_keysource);
                 ?>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_KEYWORDS_TITLE_METATAG"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td>
                    <label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_DELIMITERS"); ?><span style="color:#FF0000">&nbsp;*</span>
                    </label>
                </td>
                <td align="left">
                    <input type="text" class="input-medium" name="delimiters" value="<?php echo $params->delimiters; ?>"/>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_TOOL_TIP"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>                        
        </table>
	</div>
<?php
}
elseif($task2 == "manage_meta"){
?>
	<div class="panel panel-success">
        <div class="panel-heading">
        	<h3 class="panel-title"><?php echo JText::_("COM_IJOOMLA_SEO_METATAGS"); ?></h3>
        </div>
        <table  cellpadding="2" cellspacing="2" width="100%">
            <tr>
                <td class="stitle" colspan="3">
                    <table width="100%">
                        <tr>
                            <td>								
                                <?php echo JText::_("COM_IJOOMLA_SEO_SEO_METATAGS"); ?>
                            </td>
                            <td align="right">
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155382">
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                                    <?php echo JText::_("COM_IJOOMLA_SEO_METATAGS_VIDEO"); ?>
                                </a>
                            </td>
                            <td>&nbsp;
                                
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="17%">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_TITLE_COUNTER"); ?>
                    </label>
				</td>
                <td>
                     <input type="text" class="input-mini" name = "allow_no2" value ="<?php if ($params->ijseo_allow_no2) {
                        echo $params->ijseo_allow_no2; 
                     } else {
                        echo "76";
                     }
                     ?>">
                     <select name="type_title" id="type_title">
                        <option value="Characters" <?php if($params->ijseo_type_title=="Characters") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_CHARACTERS"); ?></option>
                        <option value="Words" <?php if($params->ijseo_type_title=="Words") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_WORDS"); ?></option>
                     </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_COUNTERDESCRIPTION"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td width="17%">
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_KEYWORDS_COUNTER"); ?>
                    </label>
				</td>
                <td>
                     <input type="text" class="input-mini" name = "allow_no" value ="<?php echo $params->ijseo_allow_no; ?>">
                     <select name="type_key" id="type_key">
                        <option value="Characters" <?php if($params->ijseo_type_key=="Characters") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_CHARACTERS"); ?></option>
                        <option value="Words" <?php if($params->ijseo_type_key=="Words") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_WORDS"); ?></option>
                     </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_COUNTERDESCRIPTION"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td>
					<label class="control-label">
						<?php echo JText::_("COM_IJOMLA_SEO_DESCRIPTIONLENGHT"); ?>
					</label>
				</td>
                <td>
                    <!-- Length -->
                    <input type="text" class="input-mini" name = "allow_no_desc" value ="<?php echo $params->ijseo_allow_no_desc; ?>">
                    <select name="type_desc" id="type_desc">
                        <option value="Characters" <?php if($params->ijseo_type_desc=="Characters") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_CHARACTERS"); ?></option>
                        <option value="Words" <?php if($params->ijseo_type_desc=="Words") echo "selected"; ?>><?php echo JText::_("COM_IJOOMLA_SEO_WORDS"); ?></option>
                    </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_DESCRIPTIONLENGHTTOUSE"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>                         
            <tr>
                <td>
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_GETDESCRIPTIONFROM"); ?>
                   	</label>
				</td>
                <td>
                    <select name = 'gdesc'>
                        <option value = 'intro' <?php  if ($params->ijseo_gdesc == 'intro') echo "selected";?> ><?php echo JText::_("COM_IJOOMLA_SEO_INTROTEXT"); ?></option>
                        <option value = 'full' <?php  if ($params->ijseo_gdesc == 'full') echo "selected";?> ><?php echo JText::_("COM_IJOOMLA_SEO_MAINTEXT"); ?></option>
                    </select>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_INTROTEXTDESCRIPTION"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>            
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
				<td>
				 	<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_OMIT_KEYWORDS_TAGS"); ?>:
                   	</label>
				</td>
                 <td><textarea cols="40" rows="5" name="exclude_key"><?php
                        $text = '';										
                        if(!empty($params->exclude_key)){
                            foreach($params->exclude_key as $value)
                                $text.= trim($value).",";
                                $text = substr($text, 0, -1);
                            }
                            echo $text;
                        ?></textarea>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_OMITKT"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>
            </tr>                        
        </table>
	</div>
<?php 
}
elseif($task2 == "google_ping"){
?>
	<div class="panel panel-success">
        <div class="panel-heading">
        	<h3 class="panel-title"><?php echo JText::_("COM_IJOOMLA_SEO_GOOGLE_PING"); ?></h3>
        </div>
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr>
                <td colspan="3" class="stitle">
                    <table width="100%">
                        <tr>
                            <td width="11%">								
                                <?php echo JText::_("COM_IJOOMLA_SEO_GOOGLE_PING"); ?>
                            </td>
                            <td align="right">
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155395">
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                                    <?php echo JText::_("COM_IJOOMLA_SEO_GOOGLE_PING_VIDEO"); ?>
                                </a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>								
                </td>
            </tr>	
            <tr>
                <td valign="top" width="22%">
                    <label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_POSITION_CHECK_METHOD"); ?>
                    </label>
                </td>
                <td valign="top">
                	<div class="control-group">
                    	<div class="controls">
                        	<fieldset class="radio btn-group" id="ijseo_gposition">
								<?php
                                    $no_checked = "";
                                    $yes_cheched = "";
                                    $display = "block";
                                    
                                    if($params->ijseo_gposition == 0){
                                        $no_checked = 'checked="checked"';
                                        $display = "none";
                                    }
                                    else{
                                        $yes_cheched = 'checked="checked"';
                                    }
                                ?>
                                <input type="radio" value="0" <?php echo $no_checked; ?> name="gposition" id="jform_gposition0">
                                <label for="jform_gposition0" class="btn"><?php echo JText::_("COM_IJOOMLA_SEO_MANUALLY"); ?></label>
                                
                                <input type="radio" value="1" <?php echo $yes_cheched; ?> name="gposition" id="jform_gposition1">
                                <label for="jform_gposition1" class="btn"><?php echo JText::_("COM_IJOOMLA_SEO_AUTOMATICALLY"); ?></label>
                            </fieldset>
                            <?php
								echo JHTML::tooltip(
									"",
									JText::_("COM_IJOOMLA_SEO_POSITION_RANK_HELP"), 
									JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
								); 
							?>
                        </div>
                    </div>
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>						
            <tr>
                <td valign="top">
                    <label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_CHECK_GOOGLE_RANK"); ?>
                    </label>
                </td>
                <td valign="top">
                    <?php
                        $cgr = array();
                        $cgr[] = JHTML::_('select.option', '1', JText::_("COM_IJOOMLA_SEO_ONCE_A_DAY"));
                        $cgr[] = JHTML::_('select.option', '2', JText::_("COM_IJOOMLA_SEO_ONCE_EVERY_2_DAYS"));
                        $cgr[] = JHTML::_('select.option', '6', JText::_("COM_IJOOMLA_SEO_ONCE_A_WEEK"));
                        echo $lists['check_gr'] = JHTML::_('select.genericlist', $cgr, 'check_gr', ' size="1"', 'value', 'text', $params->ijseo_check_grank);
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_CHANGE_GR"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                 </td>
                 <td>&nbsp;
                    
                </td>
            </tr>      
            <tr>
                <td>
					<label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_CHECK_SITES"); ?><span style="color:#FF0000;">&nbsp;*</span>
                    </label>
				</td>
                <td>
                    <div class="input-prepend" style="margin-right:5px;">
						<span class="add-on">google.</span>
						<input type="text" name="ijseo_check_ext" value="<?php echo @$params->ijseo_check_ext; ?>" style="width:68px" />
					</div>
                    <?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_EXTENSION"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>
                </td>   
                <td>&nbsp;
                    
                </td>
            </tr>
            <tr>
                <td>
                    <label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_CHEKFOR_FIRST"); ?>
                   	</label>
                </td>
                <td>
                    <select id="check_nr" name="check_nr">
                    <?php 
                        for($i=1; $i<11; $i++){
                            $selected = "";
                            if(@$params->check_nr == $i*5){
                                $selected = "selected";												
                            }
                            echo "<option value=\"".($i*5)."\" ".$selected." >".($i*5)."</option>";
                        }																														
                     ?>
                     </select >
                     &nbsp;<?php echo JText::_('COM_IJOOMLA_SEO_SEARCH_RESULTS'); ?>
                        <?php 
                            echo JHTML::tooltip(
                                "",
                                JText::_("COM_IJOOMLA_SEO_CHECK_FIRST_RESULTS"), 
                                JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                            ); 
                        ?>                 
                </td>
                <td>&nbsp;
                    
                </td>
            </tr>
        </table>
	</div>
<?php
}
elseif($task2 == "keyword_linking"){
?>
	<div class="panel panel-success">
        <div class="panel-heading">
        	<h3 class="panel-title"><?php echo JText::_("COM_IJOOMLA_SEO_KEYWORD_LINKING"); ?></h3>
        </div>
        <table width="100%" cellpadding="2" cellspacing="2">
            <tr>
                <td colspan="3" class="stitle">
                    <table width="100%">
                        <tr>
                            <td>								
                                <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORD_LINKING"); ?>
                            </td>
                            <td align="right">
                                <a class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}" href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=28827842">
                                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                                    <?php echo JText::_("COM_IJOOMLA_SEO_KEYWORD_LINKING_VIDEO"); ?>
                                </a>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>								
                </td>
            </tr>	
            <tr>
                 <td valign="top" width="15%">
                    <label class="control-label">
						<?php echo JText::_("COM_IJOOMLA_SEO_CASE_SENSITIVE"); ?>:
                   	</label>
                 </td>
                 <td valign="top" align="left">
					<div class="control-group">
                    	<div class="controls">
                        	<fieldset class="radio btn-group" id="jform_case_sensitive">
								<?php
                                    $no_checked = "";
                                    $yes_cheched = "";
                                    $display = "block";
                                    
                                    if(@$params->case_sensitive == 0){
                                        $no_checked = 'checked="checked"';
                                        $display = "none";
                                    }
                                    else{
                                        $yes_cheched = 'checked="checked"';
                                    }
                                ?>
                                <input type="radio" value="0" <?php echo $no_checked; ?> name="case_sensitive" id="jform_case_sensitive0">
                                <label for="jform_case_sensitive0" class="btn"><?php echo JText::_("JNO"); ?></label>
                                
                                <input type="radio" value="1" <?php echo $yes_cheched; ?> name="case_sensitive" id="jform_case_sensitive1">
                                <label for="jform_case_sensitive1" class="btn"><?php echo JText::_("JYES"); ?></label>
                            </fieldset>
                            <?php 
								echo JHTML::tooltip(
									"",
									JText::_("COM_IJOOMLA_SEO_CASE_SENSITIVE_TIP"), 
									JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
								); 
							?>
                        </div>
                    </div>
                 </td>
                 <td>&nbsp;
                    
                </td>
             </tr>
             <tr>
                <td valign="top" width="15%">
                    <label class="control-label">
						<?php echo JText::_('COM_IJOOMLA_SEO_SEARCH_BETWEEN'); ?>:
                    </label>
                </td>
                <td colspan="2" align="left">
                    <div class="input-prepend">
						<span class="add-on">(<?php echo JText::_('COM_IJOOMLA_SEO_START'); ?>)</span>
						<input class="input-small" type="text" name="sb_start" value="<?php if (isset($params->sb_start)){ echo $params->sb_start; } ?>" />
					</div>
                    
                    <div style="float:left; margin-left:10px; margin-right:10px;">
                    	<label class="control-label">
							<?php echo JText::_('COM_IJOOMLA_SEO_AND'); ?>
                        </label>
                    </div>
                    
                    <div class="input-prepend" style="margin-right:5px;">
						<span class="add-on">(<?php echo JText::_('COM_IJOOMLA_SEO_END'); ?>)</span>
						<input class="input-small" type="text" name="sb_end" value="<?php if (isset($params->sb_end)) { echo $params->sb_end; }	?>" />
					</div>
                    
					<?php 
                        echo JHTML::tooltip(
                            "",
                            JText::_("COM_IJOOMLA_SEO_SEARCH_BETWEEN_TIP"), 
                            JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"
                        ); 
                    ?>			
                </td>
            </tr>
            <tr>
                <td colspan="3" align="left">
                <?php 
                    $link = "http://www.ijoomla.com/seo_find_div.txt";
                    $content = read_from_ijoomla($link);
                    echo "<br /><div id='ijoomla_message'>" . $content . "</div>";
                ?>        
                </td>
            </tr>
        </table>
	</div>
<?php
}
?>        
	<input type = "hidden" name = "action" value = 'save_config'>
	<input type = "hidden" name = "task" value = "config">
	<input type = "hidden" name = "option" value = "com_ijoomla_seo">
    <input type = "hidden" name = "controller" value = "config">
    <input type = "hidden" name = "task2" value = "<?php echo $task2; ?>">
</form>